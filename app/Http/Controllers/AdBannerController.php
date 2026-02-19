<?php

namespace App\Http\Controllers;

use App\Models\AdBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdBannerController extends Controller
{
    public function index()
    {
        $banners = AdBanner::with('advertiser')->latest()->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'size' => 'required|in:728x90,300x250,1200x628',
            'image' => 'required|image|max:2048',
            'url' => 'nullable|url'
        ]);

        $path = $request->file('image')->store('banners', 'public');

        AdBanner::create([
            'title' => $request->title,
            'image_path' => $path,
            'size' => $request->size,
            'url' => $request->url,
            'advertiser_id' => auth()->id()
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner creado exitosamente');
    }

    public function toggle($id)
    {
        $banner = AdBanner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return back()->with('success', 'Estado actualizado');
    }

    public function destroy($id)
    {
        $banner = AdBanner::findOrFail($id);
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return back()->with('success', 'Banner eliminado');
    }

    public function trackView($id)
    {
        $banner = AdBanner::find($id);
        if ($banner) {
            $banner->incrementViews();
        }
        return response()->json(['success' => true]);
    }

    public function trackClick($id)
    {
        $banner = AdBanner::find($id);
        if ($banner) {
            $banner->incrementClicks();
            return redirect($banner->url);
        }
        return redirect('/');
    }
}
