<?php

namespace App\Services;

use App\Repositories\GiftRepository;
use App\Models\Gift;
use App\Models\GiftType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class GiftService
{
    protected $repository;

    public function __construct(GiftRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $gifts = $this->repository->getBaseQuery($request)->get();
        $trashGifts = $this->repository->getTrashedGifts();
        $giftTypes = GiftType::all();

        return Inertia::render('Gift/Index', [
            'gifts' => $gifts,
            'trashGifts' => $trashGifts,
            'giftTypes' => $giftTypes,
            'filters' => $request->only(['gift', 'gift_type_id']),
        ]);
    }

    public function create()
    {
        // Handled in Vue Modal
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:gifts,name,Null,id,gift_type_id,' . $request->gift_type_id,
            'gift_type_id' => 'required',
        ], [
            'name.required' => 'জুতার গিফট ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই গিফট ইতিমধ্যে বসানো আছে।',
        ]);

        $gift = $this->repository->store($request->all());
        
        logActivity($gift, $gift->id, 'gift.transaction', 'Create', __('New gift created'));
        return Redirect::back()->with('success', __('pages.New gift created') ?? 'New gift created successfully');
    }

    public function show(Gift $gift)
    {
        //
    }

    public function transactionShow(Request $request, $id)
    {
        $gift = $this->repository->find($id);
        $giftTransactions = $this->repository->getTransactionsQuery($id, $request)->paginate(50)->withQueryString();
        
        $minDate = \DB::table('gift_transactions')->where('gift_id', $id)->min('created_at');
        $maxDate = \DB::table('gift_transactions')->where('gift_id', $id)->max('created_at');
        
        $dates = [];
        $dates[0] = $minDate ? Carbon::parse($minDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y');
        $dates[1] = $maxDate ? Carbon::parse($maxDate)->format('m/d/Y') : Carbon::now()->format('m/d/Y');
        
        return Inertia::render('Gift/Show', [
            'gift' => $gift,
            'giftTransactions' => $giftTransactions,
            'dates' => $dates,
            'filters' => $request->only(['daterange', 'type']),
        ]);
    }

    public function edit(Gift $gift)
    {
        // Handled in Vue Modal
    }

    public function update(Request $request, Gift $gift)
    {
        $request->validate([
            'name' => 'required|unique:gifts,name,' . $gift->id . ',id,gift_type_id,' . $request->gift_type_id,
            'gift_type_id' => 'required',
        ], [
            'name.required' => 'জুতার গিফট ফিল্ডটি প্রয়োজন।',
            'name.unique' => 'এই গিফট ইতিমধ্যে বসানো আছে।',
        ]);
        
        $updatedGift = $this->repository->update($gift, $request->all());
        logActivity($updatedGift, $updatedGift->id, 'gift.transaction', 'Update', __('Gift edited'));

        return Redirect::back()->with('success', __('pages.Gift edited') ?? 'Gift edited successfully');
    }

    public function destroy(Gift $gift)
    {
        $message = $this->repository->destroy($gift);
        return Redirect::back()->with('success', $message['success']);
    }

    public function forceDelete($id)
    {
        $message = $this->repository->forceDelete($id);
        return Redirect::back()->with('success', $message['success']);
    }

    public function restore($id)
    {
        $message = $this->repository->restore($id);
        return Redirect::back()->with('success', $message['success']);
    }
}
