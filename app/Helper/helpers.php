<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Factory;
use App\Models\Employee;
use App\Models\WasteEntry;
use App\Models\RetailStore;
use Jenssegers\Agent\Agent;
use App\Models\GiftSupplier;
use App\Models\InvoiceEntry;
use App\Models\PurchaseEntry;
use App\Models\AdjustmentEntry;
use Illuminate\Support\HtmlString;
use App\Models\ReturnToFactoryEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\ReturnFromRetailEntry;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;

// HTTP request
function preventHttp()
{
    if (!request()->ajax()) {
        die('Not ajax request');
    }
    csrf_field();
}

// Form helpers
function label($id, $label)
{
    return new HtmlString("<label for=\"{$id}\">{$label}</label>");
}

function inputGroupBegin($id, $label)
{
    return new HtmlString("<div class=\"input-group\"><div class=\"input-group-prepend\"><label for=\"{$id}\" class=\"input-group-text bg-white\">{$label}</label></div>");
}

function inputGroupEnd()
{
    return new HtmlString("</div>");
}

function input($errors, $type, $name, $id, $value = '', $class = '', $attrs = [])
{
    if ($type === 'textarea') {
        $tag = 'textarea';
        $typestring = '';
    } else {
        $tag = 'input';
        $typestring = " type=\"{$type}\"";
    }

    $htmlname = htmlName($name);
    $namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

    $idstring = $id === '' ? '' : " id=\"{$id}\"";

    $fullvalue = old($name, $value);
    $valuestring = ($type === 'textarea' || $fullvalue === '') ? '' : " value=\"{$fullvalue}\"";



    if ($type === 'hidden') {
        $classstring = $class === '' ? '' : " class=\"{$class}\"";
    } else {
        $classstring = ' class="form-control' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';
    }
    $attrstring = '';
    foreach ($attrs as $attr => $val) {
        if ($val === false)
            continue;
        if ($val === true) {
            $attrstring .= " {$attr}";
        } else {
            $attrstring .= " {$attr}=\"{$val}\"";
        }
    }

    $inputstring = "<{$tag}{$typestring}{$namestring}{$idstring}{$valuestring}{$classstring}{$attrstring}>";
    if ($type === 'textarea') {
        $inputstring .= "{$fullvalue}</{$tag}>";
    }
    return new HtmlString($inputstring);
}

function disabledInput($errors, $type, $name, $id, $value = '', $class = '', $attrs = [])
{
    $attrs = array_merge($attrs, ['disabled' => true]);
    return input($errors, $type, $name, $id, $value, $class, $attrs);
}

function select($errors, $name, $id, $value, $options, $class = '', $attrs = [], $optionvalue = 'id', $optiontext = 'name')
{
    $htmlname = htmlName($name);
    $namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

    $idstring = $id === '' ? '' : " id=\"{$id}\"";

    $fullvalue = old($name, $value);

    $classstring = ' class="form-control' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

    $attrstring = '';
    foreach ($attrs as $attr => $val) {
        if ($val === false)
            continue;
        if ($val === true) {
            $attrstring .= " {$attr}";
        } else {
            $attrstring .= " {$attr}=\"{$val}\"";
        }
    }

    $inputstring = "<select{$namestring}{$idstring}{$classstring}{$attrstring}>";
    if ((array_key_exists('not_selected_label', $attrs) && $attrs['not_selected_label'] === true) || !array_key_exists('required', $attrs) || $attrs['required'] === false)
        $inputstring .= '<option value="">' . __('pages.Select') . '</option>';
    foreach ($options as $option) {
        $selectedstring = $option->$optionvalue == $value ? ' selected' : '';
        $inputstring .= "<option value=\"{$option->$optionvalue}\"{$selectedstring}>{$option->$optiontext}</option>";
    }
    $inputstring .= "</select>";

    return new HtmlString($inputstring);
}

function disabledSelect($errors, $name, $id, $value, $options, $class = '', $attrs = [], $optionvalue = 'id', $optiontext = 'name')
{
    $attrs = array_merge($attrs, ['disabled' => true]);
    return select($errors, $name, $id, $value, $options, $class, $attrs, $optionvalue, $optiontext);
}

function checkbox($errors, $name, $id, $checked = false, $class = '', $attrs = [])
{
    $htmlname = htmlName($name);
    $namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

    $idstring = $id === '' ? '' : " id=\"{$id}\"";

    $classstring = ' class="' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

    $attrstring = '';
    foreach ($attrs as $attr => $val) {
        if ($val === false)
            continue;
        if ($val === true) {
            $attrstring .= " {$attr}";
        } else {
            $attrstring .= " {$attr}=\"{$val}\"";
        }
    }

    $inputstring = "<input type=\"checkbox\"{$namestring}{$idstring}{$classstring}{$attrstring}>";
    return new HtmlString($inputstring);
}

function radio($errors, $name, $id, $checked = false, $class = '', $attrs = [])
{
    $htmlname = htmlName($name);
    $namestring = $name === '' ? '' : " name=\"{$htmlname}\"";

    $idstring = $id === '' ? '' : " id=\"{$id}\"";

    $classstring = ' class="' . ($errors->has($name) ? ' is-invalid' : '') . ($class === '' ? '' : ' ' . $class) . '"';

    $attrstring = '';
    foreach ($attrs as $attr => $val) {
        if ($val === false)
            continue;
        if ($val === true) {
            $attrstring .= " {$attr}";
        } else {
            $attrstring .= " {$attr}=\"{$val}\"";
        }
    }

    $inputstring = "<input type=\"radio\"{$namestring}{$idstring}{$classstring}{$attrstring}>";
    return new HtmlString($inputstring);
}

function error($errors, $name)
{
    if ($errors->has($name)) {
        return new HtmlString('<div class="invalid-feedback">' . $errors->first($name) . '</div>');
    }
}

function htmlName($laravelName)
{
    $parts = explode('.', $laravelName);
    $string = '';

    foreach ($parts as $i => $part) {
        if ($i == 0) {
            $string .= $part;
        } else {
            $string .= "[{$part}]";
        }
    }
    return $string;
}

function orderUrl($field, $orderby, $order)
{
    if ($field == $orderby) {
        $neworder = $order == 'asc' ? 'desc' : 'asc';
    } else {
        $neworder = 'asc';
    }
    return new HtmlString(request()->fullUrlWithQuery(['orderby' => $field, 'order' => $neworder]));
}

function orderArrow($field, $orderby, $order)
{
    if ($field != $orderby) {
        return '';
    }
    if ($order == 'asc') {
        return new HtmlString('&#x25B2;');
    } else {
        return new HtmlString('&#x25BC;');
    }
}

// Storage helpers
function imagePath($filename = '')
{
    if (empty($filename))
        return public_path('images/small-thumbnail/');
    return public_path('images/small-thumbnail/' . $filename);
}

function tempImagePath($filename = '')
{
    if (empty($filename))
        return public_path('images/small-thumbnail/');
    return public_path('images/small-thumbnail/' . $filename);
}

// Image helpers
function randomImageFileName()
{
    return date('YmdHis') . uniqid() . '.jpg';
}

function imageRoute($filename, $template = 'small')
{
    if (empty($filename)) {
        return asset('img/shoe.png');
    }
    return route('imagecache', compact('template', 'filename'));
}

// String format
function toFixed($number, $digits = 2)
{
    if ($number === '') {
        return $number;
    }
    return number_format((float) $number, $digits, '.', '');
}
function dateFormat($datestring, $format = 'd/m/Y', $inputFormat = 'Y-m-d H:i:s')
{
    return Carbon::parse($datestring)->format($format);
}


function onlyDateFormat($date, $format = 'd-m-Y')
{
    if (empty($date)) {
        return '';
    }

    return Carbon::parse($date)->format($format);
}

function dateToDb($date)
{
    if (!$date) {
        return null;
    }
    return Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
}


function dateTimeFormat($datestring, $format = 'd/m/Y h:i:s A', $inputFormat = 'Y-m-d H:i:s')
{
    return Carbon::parse($datestring)->format($format);
}

function formatToMySQLDateTime($datestring)
{
    return Carbon::parse($datestring)->format('Y-m-d H:i:s');
}

// Bangla helpers
function toBangla($string)
{
    if (App::getLocale() == 'en')
        return $string;
    return str_replace(
        ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
        $string
    );
}


// number Bangla show

if (!function_exists('englishToBanglaNumber')) {
    function englishToBanglaNumber($number)
    {
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        $englishDigits = range(0, 9);

        $convertedNumber = str_replace($englishDigits, $banglaDigits, $number);

        return $convertedNumber;
    }
}


// returns activityLog causer_id info
if (!function_exists('activityLogCauserInfo')) {
    function activityLogCauserInfo($causer_id)
    {
        return $causer = User::find($causer_id);
    }
}


// if (!function_exists('generateActivityLogRoute')) {
// 	function generateActivityLogRoute($properties)
// 	{
// 		if (!empty($properties['route']) && !empty($properties['id'])) {
// 			return route($properties['route'], $properties['id']);
// 		}
// 		return '#';
// 	}
// }


if (!function_exists('generateActivityLogRoute')) {
    function generateActivityLogRoute($properties)
    {
        $routeName = $properties['route'] ?? null;
        $id = $properties['id'] ?? null;

        // route নেই বা Laravel এ define নেই → '#'
        if ($routeName && $id && Route::has($routeName)) {
            return route($routeName, $id);
        }

        return '#';
    }
}


// returns bangladeshi date time format
if (!function_exists('bangladeshiDateTimeFormatter')) {
    function bangladeshiDateTimeFormatter($time)
    {
        return $formattedTime = Carbon::parse($time)->format('d F, Y g:i A');
    }
}

if (!function_exists('overWriteEnvFile')) {
    function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            $envContents = file_get_contents($path);
            if (strpos($envContents, $type) !== false) {
                $envContents = preg_replace(
                    "/^$type=.*/m",
                    "$type=$val",
                    $envContents
                );
            } else {
                $envContents .= "\r\n$type=$val";
            }
            file_put_contents($path, $envContents);
            Artisan::call('config:clear');
        }
    }
}

//logged in user data
function loggedInUser()
{
    return Auth::user();
}


function getSMSReceiverMobileNumbers($receiverType)
{
    switch ($receiverType) {
        case 'retail_stores':
            return RetailStore::where('mobile_no', '!=', '')
                ->whereNotNull('mobile_no')
                ->pluck('mobile_no');

        case 'factories':
            return Factory::where('mobile_no', '!=', '')
                ->whereNotNull('mobile_no')
                ->pluck('mobile_no');

        case 'gift_suppliers':
            return GiftSupplier::where('mobile_no', '!=', '')
                ->whereNotNull('mobile_no')
                ->pluck('mobile_no');

        case 'employees':
            return Employee::where('mobile_no', '!=', '')
                ->whereNotNull('mobile_no')
                ->pluck('mobile_no');

        case 'all':
            return RetailStore::where('mobile_no', '!=', '')
                ->whereNotNull('mobile_no')
                ->pluck('mobile_no')
                ->merge(Factory::where('mobile_no', '!=', '')
                    ->whereNotNull('mobile_no')
                    ->pluck('mobile_no'))
                ->merge(GiftSupplier::where('mobile_no', '!=', '')
                    ->whereNotNull('mobile_no')
                    ->pluck('mobile_no'))
                ->merge(Employee::where('mobile_no', '!=', '')
                    ->whereNotNull('mobile_no')
                    ->pluck('mobile_no'));

        default:
            return collect(); // Return an empty collection if the receiver type is invalid
    }
}

function getModelInfoById(string $modelName, int $id)
{
    $modelClass = "App\\Models\\" . $modelName;
    if (class_exists($modelClass)) {
        return $modelClass::find($id);
    }
    return null;
}



function replaceMessagePlaceholders($message, $name = null, $id = null, $totalAmount = null, $paidAmount = null, $dueAmount = null, $quantity = null)
{
    if ($name !== null) {
        $message = str_replace('@N', $name, $message);
    }
    if ($id !== null) {
        $message = str_replace(['@I', '@P'], $id, $message);
    }
    if ($totalAmount !== null) {
        $message = str_replace('$T', $totalAmount, $message);
    }
    if ($paidAmount !== null) {
        $message = str_replace('$P', $paidAmount, $message);
    }
    if ($dueAmount !== null) {
        $message = str_replace('$D', $dueAmount, $message);
    }
    if ($quantity) {
        $message = str_replace('@Q', $quantity, $message);
    }
    return $message;
}


function formatBangladeshiNumber($number)
{
    $number = preg_replace('/[^0-9+]/', '', $number);

    if (substr($number, 0, 4) === '+880') {
        $number = substr($number, 1);
    } elseif (substr($number, 0, 3) === '880') {
        // No change needed
    } elseif (substr($number, 0, 1) === '0') {
        $number = '88' . $number;
    }

    return $number;
}

function sendSms($formattedMessage, $formattedNumber)
{
    Http::post(env('SMS_PROVIDER_URL'), [
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID'),
        'msg' => $formattedMessage,
        'to' => $formattedNumber,
    ]);
}



function unViewedSoftDeleteActivityLogs()
{
    return $softDeleteLogs = Activity::where('properties->event', 'SoftDelete')->where('properties->viewed', false)->count();
}

// if (!function_exists('logActivity')) {
//     function logActivity($model, $modelId, $route, $event, $message ,$viewed = null)
//     {
// 		$agent = new Agent();
//         activity()
//             ->causedBy(Auth::user())
//             ->performedOn($model)
//             ->withProperties([
//                 'event' => $event,
//                 'id' => $modelId,
//                 'route' =>$route,
//                 'ipAddress' => request()->ip(),
//                 'deviceName' => $agent->device(),
//                 'operatingSystem' => $agent->platform(),
//                 'browser' => $agent->browser(),
// 				'viewed' => $viewed
//             ])
//             ->useLog($message)
//             ->log($message);
//     }
// }

if (!function_exists('logActivity')) {
    function logActivity($model, $modelId, $route, $event, $message, $viewed = null)
    {
        $agent = new Agent();
        if (is_string($model) && class_exists($model)) {
            // If a model class name is passed, fetch the instance by ID
            $modelInstance = $model::find($modelId);
        } elseif ($model instanceof  Illuminate\Database\Eloquent\Model) {
            $modelInstance = $model;
            $modelId = $model->getKey(); // Ensure ID is set correctly
        } else {
            $modelInstance = null;
        }

        $activity = activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'event' => $event,
                'id' => $modelId,
                'route' => $route,
                'ipAddress' => request()->ip(),
                'deviceName' => $agent->device(),
                'operatingSystem' => $agent->platform(),
                'browser' => $agent->browser(),
                'viewed' => $viewed,
            ])
            ->useLog($message);

        if ($modelInstance) {
            $activity->performedOn($modelInstance);
        }

        $activity->log($message);
    }
}


function findTransactionHistoryModelInfo($model, $id)
{
    if (empty($model) || empty($id) || !class_exists($model)) {
        return null;
    }

    $modelInstance = app($model);
    return $modelInstance::withTrashed()->find($id) ?: null;
}


if (!function_exists('localize_number')) {
    function localize_number($number)
    {
        $locale = app()->getLocale(); // 'en' or 'bn' etc.

        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        if ($locale === 'bn') {
            return str_replace($englishDigits, $banglaDigits, $number);
        } else {
            return str_replace($banglaDigits, $englishDigits, $number);
        }
    }
}






if (!function_exists('getBanglaMonth')) {
    function getBanglaMonth($monthNumber)
    {
        $banglaMonths = [
            '01' => 'জানুয়ারি',
            '02' => 'ফেব্রুয়ারি',
            '03' => 'মার্চ',
            '04' => 'এপ্রিল',
            '05' => 'মে',
            '06' => 'জুন',
            '07' => 'জুলাই',
            '08' => 'আগস্ট',
            '09' => 'সেপ্টেম্বর',
            '10' => 'অক্টোবর',
            '11' => 'নভেম্বর',
            '12' => 'ডিসেম্বর',
        ];

        $key = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);
        return $banglaMonths[$key] ?? '';
    }
}
if (!function_exists('getBanglaWeekday')) {
    function getBanglaWeekday($dayOfWeek)
    {
        $days = [
            0 => 'রবিবার',
            1 => 'সোমবার',
            2 => 'মঙ্গলবার',
            3 => 'বুধবার',
            4 => 'বৃহস্পতিবার',
            5 => 'শুক্রবার',
            6 => 'শনিবার',
        ];

        return $days[$dayOfWeek] ?? '';
    }
}



if (!function_exists('bn_number')) {
    function bn_number($number)
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($en, $bn, $number);
    }
}


if (!function_exists('bn_month_year')) {
    function bn_month_year($date)
    {
        $enMonths = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $bnMonths = [
            'জানুয়ারি',
            'ফেব্রুয়ারি',
            'মার্চ',
            'এপ্রিল',
            'মে',
            'জুন',
            'জুলাই',
            'আগস্ট',
            'সেপ্টেম্বর',
            'অক্টোবর',
            'নভেম্বর',
            'ডিসেম্বর'
        ];

        // Get the English formatted date string
        $formatted = $date->format('F Y'); // Example: "May 2025"

        // Replace the month with Bangla
        $banglaFormatted = str_replace($enMonths, $bnMonths, $formatted);

        // Convert English numerals to Bangla
        return bn_number($banglaFormatted);
    }
}












if (!function_exists('local_number')) {
    function local_number($number)
    {
        return app()->getLocale() === 'bn' ? bn_number($number) : $number;
    }
}

if (!function_exists('local_month_year')) {
    function local_month_year($date)
    {
        return app()->getLocale() === 'bn'
            ? bn_month_year($date)
            : $date->format('F Y');
    }
}

if (!function_exists('local_weekday')) {
    function local_weekday($dayOfWeek)
    {
        return app()->getLocale() === 'bn'
            ? getBanglaWeekday($dayOfWeek)
            : \Carbon\Carbon::create()->startOfWeek()->addDays($dayOfWeek)->format('l');
    }
}

if (!function_exists('local_month')) {
    function local_month($monthNumber)
    {
        return app()->getLocale() === 'bn'
            ? getBanglaMonth($monthNumber)
            : \Carbon\Carbon::createFromFormat('!m', $monthNumber)->format('F');
    }
}




if (!function_exists('calculateStockByDate')) {

    function calculateStockByDate($date)
    {
        $date = Carbon::parse($date);

        $purchase = PurchaseEntry::whereDate('created_at', '<=', $date)->sum('count');
        $returnToFactory = ReturnToFactoryEntry::join('shoes', 'shoes.id', '=', 'return_to_factory_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('return_to_factory_entries.status', 'accepted')
            ->where('return_to_factory_entries.retail_store_return_entry_id', 0)
            ->where('return_to_factory_entries.factory_return', 1)
            ->whereDate('return_to_factory_entries.created_at', '<=', $date)
            ->sum('return_to_factory_entries.count');

        $invoice = InvoiceEntry::whereDate('created_at', '<=', $date)->sum('count');
        $waste = WasteEntry::join('shoes', 'shoes.id', '=', 'waste_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->whereIn('waste_entries.entries_type', ['gift-supplier', 'other', 'employee', 'loan-payment', 'retail-store', 'factory'])
            ->where('waste_entries.inventory_count', 'instant_waste')
            ->whereDate('waste_entries.created_at', '<=', $date)
            ->sum('waste_entries.count');

        $adjustmentIn = AdjustmentEntry::where('type', 'in')->whereDate('created_at', '<=', $date)->sum(\Illuminate\Support\Facades\DB::raw('abs(count)'));
        $adjustmentOut = AdjustmentEntry::where('type', 'out')->whereDate('created_at', '<=', $date)->sum(\Illuminate\Support\Facades\DB::raw('abs(count)'));
        $returnFromRetail = ReturnFromRetailEntry::join('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->where('shoes.code', 'not like', 'X%')
            ->where('return_from_retail_entries.inventory', 1)
            ->whereDate('return_from_retail_entries.updated_at', '<=', $date)
            ->sum('return_from_retail_entries.count');

        return
            $purchase
            - $returnToFactory
            - $invoice
            - $waste
            + $adjustmentIn
            - $adjustmentOut
            + $returnFromRetail;
    }
}




// if (!function_exists('calculatePricekByDate')) {
// 	function calculatePricekByDate($date)
// 	{
// 		$date = Carbon::parse($date);
// 		$purchase = PurchaseEntry::with('shoe')->whereDate('created_at', '<=', $date)->sum('count');
// 		$returnToFactory = ReturnToFactoryEntry::with('shoe')->where('status', 'accepted')->where('retail_store_return_entry_id', 0)
// 			->where('factory_return', 1)->whereDate('created_at', '<=', $date)->sum('count');

// 		$invoice = InvoiceEntry::with('shoe')->whereDate('created_at', '<=', $date)->sum('count');
// 		$waste = WasteEntry::with('shoe')->whereIn('entries_type', ['gift-supplier', 'other', 'employee', 'loan-payment', 'retail-store', 'factory'])
// 			->where('inventory_count', 'instant_waste')
// 			->whereDate('created_at', '<=', $date)->sum('count');

// 		$adjustmentIn = AdjustmentEntry::with('shoe')->where('type', 'in')->whereDate('created_at', '<=', $date)->sum('count');
// 		$adjustmentOut = AdjustmentEntry::with('shoe')->where('type', 'out')->whereDate('created_at', '<=', $date)->sum('count');
// 		$returnFromRetail = ReturnFromRetailEntry::with('shoe')->where('inventory', 1)->whereDate('updated_at', $date)->sum('count');

// 		return
// 			$purchase
// 			- $returnToFactory
// 			- $invoice
// 			- $waste
// 			+ $adjustmentIn
// 			- $adjustmentOut
// 			+ $returnFromRetail;
// 	}
// }











