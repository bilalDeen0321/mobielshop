<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $sliderImages = SliderImage::orderBy('sort_order')->get();
        $themePrimary = Setting::get('theme_primary', '#00b4d8');
        $themeSecondary = Setting::get('theme_secondary', '#0f172a');
        $themeAccent = Setting::get('theme_accent', '#f59e0b');
        $whatsappNumber = Setting::get('whatsapp_number', '');
        $shopName = Setting::get('shop_name', config('app.name'));
        $shopLogo = Setting::get('shop_logo', '');
        $currency = Setting::get('currency', config('currencies.default', '£'));
        $taxPercentage = Setting::get('tax_percentage', '0');
        $receiptFormat = Setting::get('receipt_format', 'standard');
        $currencies = config('currencies.list', ['£' => 'British Pound (£)', '$' => 'US Dollar ($)', '€' => 'Euro (€)']);
        return view('admin.settings.index', compact(
            'sliderImages', 'themePrimary', 'themeSecondary', 'themeAccent', 'whatsappNumber',
            'shopName', 'shopLogo', 'currency', 'currencies', 'taxPercentage', 'receiptFormat'
        ));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_primary' => ['nullable', 'string', 'max:20'],
            'theme_secondary' => ['nullable', 'string', 'max:20'],
            'theme_accent' => ['nullable', 'string', 'max:20'],
        ]);
        Setting::set('theme_primary', $request->input('theme_primary', '#00b4d8'));
        Setting::set('theme_secondary', $request->input('theme_secondary', '#0f172a'));
        Setting::set('theme_accent', $request->input('theme_accent', '#f59e0b'));
        return redirect()->route('admin.settings.index')->with('success', 'Theme colors updated.');
    }

    public function updateWhatsApp(Request $request)
    {
        $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
        ]);
        $number = preg_replace('/[^0-9]/', '', $request->input('whatsapp_number', ''));
        Setting::set('whatsapp_number', $number);
        return redirect()->route('admin.settings.index')->with('success', 'WhatsApp number updated.');
    }

    public function uploadSlider(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);
        $file = $request->file('image');
        $path = $file->store('slider', 'public');
        $maxOrder = SliderImage::max('sort_order') ?? -1;
        SliderImage::create([
            'path' => $path,
            'caption' => $request->input('caption'),
            'sort_order' => $maxOrder + 1,
        ]);
        return redirect()->route('admin.settings.index')->with('success', 'Slider image added.');
    }

    public function deleteSlider(SliderImage $slider)
    {
        if ($slider->path && Storage::disk('public')->exists($slider->path)) {
            Storage::disk('public')->delete($slider->path);
        }
        $slider->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Slider image removed.');
    }

    public function reorderSlider(Request $request)
    {
        $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer', 'exists:slider_images,id']]);
        foreach ($request->input('order') as $position => $id) {
            SliderImage::where('id', $id)->update(['sort_order' => $position]);
        }
        return response()->json(['success' => true]);
    }

    public function updateShop(Request $request)
    {
        $request->validate([
            'shop_name' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:20'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'receipt_format' => ['nullable', 'string', 'in:standard,minimal'],
        ]);
        Setting::set('shop_name', $request->input('shop_name', ''));
        Setting::set('currency', $request->input('currency', config('currencies.default', '£')));
        Setting::set('tax_percentage', $request->input('tax_percentage', '0'));
        Setting::set('receipt_format', $request->input('receipt_format', 'standard'));
        if ($request->hasFile('shop_logo')) {
            $request->validate(['shop_logo' => ['image', 'max:2048']]);
            $path = $request->file('shop_logo')->store('settings', 'public');
            Setting::set('shop_logo', $path);
        }
        return redirect()->route('admin.settings.index')->with('success', 'Shop settings updated.');
    }
}
