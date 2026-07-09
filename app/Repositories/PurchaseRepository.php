<?php

namespace App\Repositories;

use App\Models\GiftTransaction;
use App\Models\Inventory;
use App\Models\Message;
use App\Models\Purchase;
use App\Models\PurchaseEntry;
use App\Models\Factory;
use App\Models\Shoe;
use App\Models\Gift;
use App\Models\Transaction;
use App\Models\Cheque;
use App\Models\AccountBook;
use App\Models\BankAccount;
use App\Models\ShoeToSize;
use App\Models\TransactionHistory;
use App\Models\View\FactoryAccountEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PurchaseRepository
{
    public function store(array $data)
    {
        $factory = Factory::find($data['factory_id']);
        $accountBook = AccountBook::where('account_id', $factory->id)->where('account_type', 'factory')->where('open', true)->first();
        
        $purchase = new Purchase;
        $purchase->account_book_id = $accountBook->id;
        $purchase->save();

        $bankAccount = isset($data['payment_method']) ? BankAccount::find($data['payment_method']) : null;
        $createdAt = Carbon::now()->format('Y-m-d H:i:s');
        $count = 0;
        $total_purchase_price = 0;
        $total_retail_price = 0;

        foreach ($data['purchases'] ?? [] as $i => $row) {
            if (isset($row['category_id'])) {
                if (empty($row['purchase_price'])) {
                    $row['purchase_price'] = 0;
                }
                $shoe = new Shoe;
                $shoe->fill($row);
                $shoe->code = $row['shoe_id'];
                $factory->shoes()->save($shoe);
                $row['shoe_id'] = $shoe->id;
            } else {
                $shoe = Shoe::where('code', $row['shoe_id'])->first();
                if ($shoe) {
                    $row['shoe_id'] = $shoe->id;
                }
            }

            $purchaseEntry = new PurchaseEntry;
            $purchaseEntry->fill($row);
            $purchase->purchaseEntries()->save($purchaseEntry);

            if (isset($row['sizes'])) {
                foreach ($row['sizes'] as $size) {
                    if (isset($size['quantity']) && $size['quantity'] > 0) {
                        $shoeToSize = ShoeToSize::firstOrNew([
                            'shoe_id' => $row['shoe_id'], 
                            'size_id' => $size['size_id'], 
                            'type' => 'purchase', 
                            'operation' => '+', 
                            'reference_id' => $purchase->id
                        ]);
                        $shoeToSize->quantity = $size['quantity'];
                        $shoeToSize->sorts = json_encode($size['sorts'] ?? []);
                        $shoeToSize->save();
                    }
                }
            }

            /*--------inventory quantity------- */
            if (isset($row['category_id'])) {
                if (!empty($row['box_id'])) {
                    $giftInventory = new GiftTransaction();
                    $giftInventory->type = 'use';
                    $giftInventory->gift_id = $row['box_id'];
                    $giftInventory->count = $row['count'];
                    $giftInventory->attachment_id = $purchase->id;
                    $giftInventory->save();
                }
                if (!empty($row['bag_id'])) {
                    $giftInventory = new GiftTransaction();
                    $giftInventory->type = 'use';
                    $giftInventory->gift_id = $row['bag_id'];
                    $giftInventory->count = $row['count'];
                    $giftInventory->attachment_id = $purchase->id;
                    $giftInventory->save();
                }
            } else {
                $shoes = Shoe::find($row['shoe_id']);
                if ($shoes && $shoes->box_id) {
                    $giftInventory = new GiftTransaction();
                    $giftInventory->type = 'use';
                    $giftInventory->gift_id = $shoes->box_id;
                    $giftInventory->count = $row['count'];
                    $giftInventory->attachment_id = $purchase->id;
                    $giftInventory->save();
                }
                if ($shoes && $shoes->bag_id) {
                    $giftInventory = new GiftTransaction();
                    $giftInventory->type = 'use';
                    $giftInventory->gift_id = $shoes->bag_id;
                    $giftInventory->count = $row['count'];
                    $giftInventory->attachment_id = $purchase->id;
                    $giftInventory->save();
                }
            }

            /*--------inventory quantity------- */
            $inventory = Inventory::find($row['shoe_id']);
            if (isset($inventory)) {
                $inventory->increment('count', $row['count']);
            } else {
                $inventory = new Inventory;
                $inventory->shoe_id = $row['shoe_id'];
                $inventory->factory = $factory->name;
                $inventory->category = $row['category'] ?? '';
                $inventory->color = $row['color'] ?? '';
                $inventory->purchase_price = $row['purchase_price'] ?? 0;
                $inventory->retail_price = $row['retail_price'] ?? 0;
                $inventory->count = $row['count'];
                $inventory->image = $row['image'] ?? null;
                $inventory->purchase_id = $purchase->id;
                $inventory->save();
            }

            if (isset($row['shoe_id']) && !isset($row['category_id'])) {
                $shoes = Shoe::find($row['shoe_id']);
                $total_p_price = ($shoes?->purchase_price ?? 0) * $row['count'] / 12;
                $total_r_price = ($shoes?->retail_price ?? 0) * $row['count'];
            } else {
                $total_p_price = ($row['purchase_price'] ?? 0) * $row['count'] / 12;
                $total_r_price = ($row['retail_price'] ?? 0) * $row['count'];
            }
            $count += $row['count'];
            $total_purchase_price += $total_p_price;
            $total_retail_price += $total_r_price;
        }

        if (isset($data['payment_amount']) && $data['payment_amount'] > 0) {
            $purchase->payment_amount = $data['payment_amount'];
            $purchase->save();
            if (isset($data['payment_method']) && $data['payment_method'] === 'cheque') {
                Cheque::issue($data['cheque_no'] ?? null, $accountBook, $data['payment_amount'], $data['cheque_date'] ?? null, $purchase);
            } else if (isset($data['payment_method'])) {
                $description = isset($data['cheque_no']) ? 'চেক নং ' . $data['cheque_no'] : null;
                $transaction = Transaction::createTransaction('factory', $factory->id, 'expense', $data['payment_method'], $data['payment_amount'], $description, $createdAt, $purchase);
                $accountEntries = new FactoryAccountEntry;
                $accountEntries->account_id = $bankAccount->id;
                $accountEntries->account_name = $bankAccount->bank;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = 2; // Payment
                $accountEntries->entry_id = $transaction['transaction']->id;
                $accountEntries->total_amount = $data['payment_amount'];
                $accountEntries->save();
            }
        }

        $accountEntries = new FactoryAccountEntry;
        $accountEntries->account_book_id = $accountBook->id;
        $accountEntries->entry_type = 0; // Purchase
        $accountEntries->entry_id = $factory->id;
        $accountEntries->purchase_id = $purchase->id;
        $accountEntries->count = $count;
        $accountEntries->purchase_price = $total_purchase_price;
        $accountEntries->retail_price = $total_retail_price;
        $accountEntries->account_id = $bankAccount->id ?? null;
        $accountEntries->account_name = $bankAccount->bank ?? null;
        $accountEntries->total_amount = $total_purchase_price;
        $accountEntries->save();

        $purchase->load('accountBook.account', 'purchaseEntries.shoe', 'transaction', 'cheque');
        
        // SMS sending
        $message = Message::where('id', '1')->select('supplier_message', 'supplier_status')->first();

        if ($message && $message->supplier_status == '1') {
            $formattedMessage = replaceMessagePlaceholders($message->supplier_message, $factory->name, $purchase->id, $total_purchase_price, $data['payment_amount'] ?? 0, ($total_purchase_price - ($data['payment_amount'] ?? 0)), $count);
            $formattedNumber = formatBangladeshiNumber($factory->mobile_no);
            Http::post(env('SMS_PROVIDER_URL'), [
                'api_key' => env('SMS_API_KEY'),
                'sender_id' => env('SMS_SENDER_ID'),
                'msg' => $formattedMessage,
                'to' => $formattedNumber,
            ]);
        }

        return $purchase;
    }

    public function show($id)
    {
        return Purchase::with('accountBook.account', 'purchaseEntries.shoe')->findOrFail($id);
    }

    public function update(Purchase $purchase, array $data)
    {
        $purchase->load('accountBook.account');
        $survived = [];
        $factory = Factory::find($data['factory_id']);

        if ($purchase->accountBook->open && $purchase->accountBook->account_id != $data['factory_id']) {
            $accountBook = $factory->getCurrentAccountBook();
            $purchase->accountBook()->associate($accountBook);
            $purchase->save();
        }

        foreach ($data['purchases'] ?? [] as $i => $row) {
            if (isset($row['id'])) {
                // Update purchase entry
                $purchaseEntry = PurchaseEntry::find($row['id']);
                $oldCount = $purchaseEntry->count;
                $shoe = Shoe::where('code', $row['shoe_id'])->first();
                if ($shoe) {
                    $row['shoe_id'] = $shoe->id;
                }
                $purchaseEntry->fill($row);
                $purchaseEntry->save();

                // Update inventory
                $inventory = Inventory::find($row['shoe_id']);
                if ($inventory) {
                    $diff = $purchaseEntry->count - $oldCount;
                    $inventory->count = max(0, $inventory->count + $diff);
                    $inventory->purchase_price = isset($row['purchase_price']) ? (float) $row['purchase_price'] : $inventory->purchase_price;
                    $inventory->purchase_id = $purchaseEntry->purchase_id;
                    $inventory->save();
                }

                // Update shoe price
                $shoe = Shoe::find($row['shoe_id']);
                if ($shoe) {
                    $shoe->purchase_price = isset($row['purchase_price']) ? (float) $row['purchase_price'] : $shoe->purchase_price;
                    $shoe->save();
                }
            } else {
                if (isset($row['category_id'])) {
                    $shoe = new Shoe;
                    $shoe->fill($row);
                    $shoe->code = $row['shoe_id'];
                    $purchase->accountBook->account->shoes()->save($shoe);
                    $row['shoe_id'] = $shoe->id;
                } else {
                    $shoe = Shoe::where('code', $row['shoe_id'])->first();
                    if ($shoe) {
                        $row['shoe_id'] = $shoe->id;
                    }
                }
                $purchaseEntry = new PurchaseEntry();
                $purchaseEntry->fill($row);
                $purchase->purchaseEntries()->save($purchaseEntry);
                
                $inventory = Inventory::find($row['shoe_id']);
                if (isset($inventory)) {
                    $inventory->increment('count', $row['count']);
                } else {
                    $inventory = new Inventory;
                    $inventory->shoe_id = $row['shoe_id'];
                    $inventory->factory = $factory->name;
                    $inventory->category = $row['category'] ?? '';
                    $inventory->color = $row['color'] ?? '';
                    $inventory->purchase_price = $row['purchase_price'] ?? 0;
                    $inventory->retail_price = $row['retail_price'] ?? 0;
                    $inventory->count = $row['count'];
                    $inventory->image = $row['image'] ?? null;
                    $inventory->save();
                }
            }
            
            // Handle sizes for the updated row
            if (isset($row['sizes'])) {
                // Delete old ones first
                ShoeToSize::where([
                    'shoe_id' => $row['shoe_id'],
                    'type' => 'purchase',
                    'reference_id' => $purchase->id
                ])->delete();

                foreach ($row['sizes'] as $size) {
                    if (isset($size['quantity']) && $size['quantity'] > 0) {
                        $shoeToSize = ShoeToSize::firstOrNew([
                            'shoe_id' => $row['shoe_id'], 
                            'size_id' => $size['size_id'], 
                            'type' => 'purchase', 
                            'operation' => '+', 
                            'reference_id' => $purchase->id
                        ]);
                        $shoeToSize->quantity = $size['quantity'];
                        $shoeToSize->sorts = json_encode($size['sorts'] ?? []);
                        $shoeToSize->save();
                    }
                }
            }

            $survived[] = $purchaseEntry->id;
        }

        $purchaseEntries = $purchase->purchaseEntries()->get();
        foreach ($purchaseEntries as $purchaseEntry) {
            if (!in_array($purchaseEntry->id, $survived)) {
                $purchaseEntry->delete();
                $inventory = Inventory::find($purchaseEntry['shoe_id']);
                if ($inventory) {
                    $countElsewhere = PurchaseEntry::where('shoe_id', $purchaseEntry->shoe_id)
                        ->where('id', '!=', $purchaseEntry->id)
                        ->sum('count');

                    if ($countElsewhere > 0) {
                        $inventory->decrement('count', $purchaseEntry->count);
                    } else {
                        $inventory->delete();
                    }
                }
            }
        }

        $purchaseEntries = $purchase->purchaseEntries()->with('shoe')->get();
        $count = 0;
        $total_purchase_price = 0;
        $total_retail_price = 0;
        foreach ($purchaseEntries as $item) {
            $total_p_price = ($item->shoe->purchase_price ?? 0) * $item->count / 12;
            $total_r_price = ($item->shoe->retail_price ?? 0) * $item->count;
            $count += $item->count;
            $total_purchase_price += $total_p_price;
            $total_retail_price += $total_r_price;
        }

        $entry = FactoryAccountEntry::where('purchase_id', $purchase->id)->first();
        if ($entry) {
            $entry->count = $count;
            $entry->purchase_price = $total_purchase_price;
            $entry->retail_price = $total_retail_price;
            $entry->total_amount = $total_purchase_price;
            $entry->save();
        }

        $purchase->load('purchaseEntries.shoe');
        return $purchase;
    }

    public function destroy(Purchase $purchase)
    {
        $accountBook = $purchase->accountBook;
        logActivity($purchase, $purchase->id, 'purchase.show', 'SoftDelete', __('Shoe purchase deleted'));
        $purchase->delete();
        return collect(['success' => __('pages.Shoe purchase deleted'), 'accountBook' => $accountBook]);
    }

    public function forceDelete($id)
    {
        $purchase = Purchase::withTrashed()->find($id);
        if (!$purchase) {
            return collect(['error' => 'Purchase not found']);
        }
        $purchaseEntries = PurchaseEntry::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseEntries as $entry) {
            $countElsewhere = PurchaseEntry::where('shoe_id', $entry->shoe_id)
                ->where('purchase_id', '!=', $purchase->id)
                ->count();
            if ($countElsewhere > 0) {
                Inventory::where('shoe_id', $entry->shoe_id)->decrement('count', $entry->count);
            } else {
                if ($shoe = Shoe::find($entry->shoe_id)) {
                    $shoe->forceDelete();
                }
                if ($inventory = Inventory::find($entry->shoe_id)) {
                    $inventory->forceDelete();
                }
            }
            $entry->forceDelete();
        }
        $transaction = Transaction::where('attachment_id', $purchase->id)
            ->where('attachment_type', Purchase::class)
            ->first();
        if ($transaction) {
            TransactionHistory::where('model', Factory::class)
                ->where('transaction_id_two', $transaction->id)
                ->forceDelete();
            $transaction->forceDelete();
        }
        GiftTransaction::where('type', 'use')->where('attachment_id', $id)->forceDelete();
        FactoryAccountEntry::where('purchase_id', $purchase->id)->forceDelete();
        $accountBook = $purchase->accountBook;
        $purchase->forceDelete();
        
        logActivity($purchase, $purchase->id, '', 'Update', __('Shoe purchase permanently deleted'));
        
        return collect(['success' => __('pages.Shoe purchase permanently deleted'), 'accountBook' => $accountBook]);
    }

    public function restore($id)
    {
        $purchase = Purchase::withTrashed()->find($id);
        if ($purchase) {
            logActivity($purchase, $purchase->id, 'purchase.show', 'Restore', __('Purchase restored'));
            $purchase->restore();
            return $purchase;
        }
        return null;
    }
}
