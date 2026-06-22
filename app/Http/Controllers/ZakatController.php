<?php

namespace App\Http\Controllers;

use App\Models\Zakat;
use App\Models\ClosingPeriod;
use Illuminate\Http\Request;
use App\Services\CapitalService;
use Inertia\Inertia;

class ZakatController extends Controller
{
    protected $capitalService;

    public function __construct(CapitalService $capitalService)
    {
        $this->capitalService = $capitalService;
    }

    /**
     * Display the Zakat calculation worksheet page.
     */
    public function zakatpage(Request $request)
    {
        $closingPeriods = ClosingPeriod::yearlyPeriodsCached();
        $selectedYear = $request->input('year') ?? $closingPeriods->first()?->id;

        $data = [];
        if ($selectedYear) {
            $data = $this->capitalService->calculate($selectedYear);
        }

        if (!$data) {
            // If no data returned, default structure
            $data = [
                'netCapital' => 0,
                'totalAssets' => 0,
                'totalLiabilities' => 0,
            ];
        }

        $data['closingPeriods'] = $closingPeriods;
        $data['selectedYear'] = (int)$selectedYear;

        return Inertia::render('Zakat/Index', $data);
    }

    /**
     * Store Zakat calculation record.
     */
    public function zakatStore(Request $request)
    {
        $request->validate([
            'year' => 'nullable|exists:closing_periods,id',
            'netcapital_value' => 'required|numeric',
            'custom_label.*' => 'nullable|string',
            'custom_value.*' => 'nullable|numeric',
        ]);

        $systemAssets = $request->netcapital_value;
        $totalAssets = $systemAssets;
        $customAssetsData = [];

        if ($request->has('custom_label')) {
            foreach ($request->custom_label as $key => $label) {
                if (!empty($label)) {
                    $value = $request->custom_value[$key] ?? 0;
                    $totalAssets += $value;

                    $customAssetsData[] = [
                        'label' => $label,
                        'amount' => (float)$value
                    ];
                }
            }
        }

        $zakatAmount = $totalAssets * 0.025;

        $zakat = Zakat::create([
            'fiscal_year_id' => $request->year,
            'system_assets' => $systemAssets,
            'custom_assets' => $customAssetsData,
            'total_assets' => $totalAssets,
            'zakat_payable' => $zakatAmount,
        ]);

        logActivity($zakat, $zakat->id, 'zakat.calculate.list', 'Create', __('Zakat calculation created'));

        return redirect()->route('zakat.calculate.list')->with('success', 'Zakat calculation stored successfully');
    }

    /**
     * Display a listing of saved Zakat records.
     */
    public function zakatCalculateList()
    {
        $closingPeriods = ClosingPeriod::all();
        $zakatRecords = Zakat::with('fiscalYear')->latest()->get();
        $trashRecords = Zakat::onlyTrashed()->with('fiscalYear')->latest()->get();

        return Inertia::render('Zakat/List', [
            'closingPeriods' => $closingPeriods,
            'zakatRecords' => $zakatRecords,
            'trashRecords' => $trashRecords,
        ]);
    }

    /**
     * Soft delete a Zakat calculation record.
     */
    public function zakatCalculateDestroy($id)
    {
        $record = Zakat::findOrFail($id);
        logActivity($record, $record->id, 'zakat.calculate.list', 'SoftDelete', __('Zakat calculation deleted'), false);
        $record->delete();

        return redirect()->back()->with('success', 'রেকর্ডটি সফলভাবে ডিলিট করা হয়েছে।');
    }

    /**
     * Restore a soft deleted Zakat calculation record.
     */
    public function zakatCalculateRestore($id)
    {
        $record = Zakat::withTrashed()->findOrFail($id);
        $record->restore();
        logActivity($record, $record->id, 'zakat.calculate.list', 'Restore', __('Zakat calculation restored'));

        return redirect()->back()->with('success', 'Record restored successfully!');
    }

    /**
     * Permanently delete a Zakat calculation record.
     */
    public function zakatCalculateForceDelete($id)
    {
        $record = Zakat::withTrashed()->findOrFail($id);
        logActivity($record, $record->id, 'zakat.calculate.list', 'Delete', __('Zakat calculation permanently deleted'));
        $record->forceDelete();

        return redirect()->back()->with('success', 'Record permanently deleted.');
    }
}
