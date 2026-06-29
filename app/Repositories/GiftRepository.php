<?php

namespace App\Repositories;

use App\Models\Gift;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GiftRepository
{
    public function getBaseQuery(Request $request)
    {
        return Gift::with('giftType')
            ->withSum(['giftTransactions as purchase_count' => function ($query) {
                $query->where('type', 'purchase');
            }], 'count')
            ->withSum(['giftTransactions as sale_count' => function ($query) {
                $query->where('type', 'sale');
            }], 'count')
            ->withSum(['giftTransactions as waste_count' => function ($query) {
                $query->where('type', 'waste');
            }], 'count')
            ->withSum(['giftTransactions as use_count' => function ($query) {
                $query->where('type', 'use');
            }], 'count')
            ->when($request->gift, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->gift . '%');
            })
            ->when($request->gift_type_id, function ($query) use ($request) {
                $query->whereHas('giftType', function ($query) use ($request) {
                    $query->where('id', $request->gift_type_id);
                });
            })
            ->when($request->input('giftTransactions.type_purchase'), function ($query) use ($request) {
                $query->whereHas('giftTransactions', function ($query) use ($request) {
                    $query->where('type', 'purchase')
                        ->selectRaw('sum(count) as count')
                        ->having('count', $request->input('giftTransactions.type_purchase'));
                });
            })
            ->when($request->input('giftTransactions.type_sale'), function ($query) use ($request) {
                $query->whereHas('giftTransactions', function ($query) use ($request) {
                    $query->where('type', 'sale')
                        ->selectRaw('sum(count) as count')
                        ->having('count', $request->input('giftTransactions.type_sale'));
                });
            })
            ->when($request->input('giftTransactions.type_waste'), function ($query) use ($request) {
                $query->whereHas('giftTransactions', function ($query) use ($request) {
                    $query->where('type', 'waste')
                        ->selectRaw('sum(count) as count')
                        ->having('count', $request->input('giftTransactions.type_waste'));
                });
            })
            ->when($request->input('giftTransactions.type_use'), function ($query) use ($request) {
                $query->whereHas('giftTransactions', function ($query) use ($request) {
                    $query->where('type', 'use')
                        ->selectRaw('sum(count) as count')
                        ->having('count', $request->input('giftTransactions.type_use'));
                });
            });
    }

    public function getTrashedGifts()
    {
        return Gift::onlyTrashed()
            ->with('giftType')
            ->withSum(['giftTransactions as purchase_count' => function ($query) {
                $query->where('type', 'purchase');
            }], 'count')
            ->withSum(['giftTransactions as sale_count' => function ($query) {
                $query->where('type', 'sale');
            }], 'count')
            ->withSum(['giftTransactions as waste_count' => function ($query) {
                $query->where('type', 'waste');
            }], 'count')
            ->withSum(['giftTransactions as use_count' => function ($query) {
                $query->where('type', 'use');
            }], 'count')
            ->get();
    }

    public function store(array $data)
    {
        $gift = new Gift;
        $gift->fill($data);
        $gift->save();

        return $gift;
    }

    public function show(Gift $gift)
    {
        return $gift;
    }

    public function find($id)
    {
        return Gift::find($id);
    }

    public function getTransactionsQuery($giftId, Request $request)
    {
        return GiftTransaction::where('gift_id', $giftId)
            ->when($request->daterange, function ($query) use ($request) {
                $dates = explode(' - ', $request->daterange);
                $query->whereDate('created_at', '>=', Carbon::parse($dates[0]))
                    ->whereDate('created_at', '<=', Carbon::parse($dates[1]));
            })->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })->when($request->attachment_id, function ($query) use ($request) {
                $query->where('attachment_id', $request->attachment_id);
            });
    }

    public function update(Gift $gift, array $data)
    {
        $gift->fill($data);
        $gift->save();

        return $gift;
    }

    public function destroy(Gift $gift)
    {
        logActivity($gift, $gift->id, 'gift.index', 'SoftDelete', __('Gift deleted'), false);
        $gift->delete();
        return collect(['success' => __('pages.Gift deleted')]);
    }

    public function forceDelete($id)
    {
        $gift = Gift::withTrashed()->find($id);
        logActivity($gift, $gift->id, 'gift.index', 'Delete', __('Gift permanently deleted'));
        $gift->forceDelete();
        return collect(['success' => __('pages.Gift permanently deleted')]);
    }

    public function restore($id)
    {
        $gift = Gift::withTrashed()->find($id);
        logActivity($gift, $gift->id, 'gift.transaction', 'Restore', __('Gift restored'));
        $gift->restore();
        return collect(['success' => __('pages.Gift restored')]);
    }
}
