<?php

namespace App\Http\Controllers;

use App\Models\UserAdvertisement;
use App\Models\AdvertisementLike;
use App\Models\AdvertisementComment;
use App\Models\AdvertisementShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    // Vista crear PTC
    public function createPtc()
    {
        $userAd = auth()->user()->advertisements()->where('type', 'ptc')->where('is_active', true)->first();
        return view('advertisements.create-ptc', compact('userAd'));
    }

    // Vista crear Banner
    public function createBanner()
    {
        $userAd = auth()->user()->advertisements()->where('type', 'banner')->where('is_active', true)->first();
        return view('advertisements.create-banner', compact('userAd'));
    }

    // Guardar publicidad
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ptc,banner',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
            'redirect_url' => 'required|url',
            'description' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $fileType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        $path = $file->store('advertisements', 'public');

        UserAdvertisement::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => $fileType,
            'redirect_url' => $request->redirect_url,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Publicidad creada exitosamente');
    }

    // Previsualización
    public function preview(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ptc,banner',
            'title' => 'required|string',
            'file' => 'required|file',
            'redirect_url' => 'required|url',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $fileType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        $tempPath = $file->store('temp', 'public');

        return view('advertisements.preview', [
            'type' => $request->type,
            'title' => $request->title,
            'file_path' => $tempPath,
            'file_type' => $fileType,
            'redirect_url' => $request->redirect_url,
            'description' => $request->description,
        ]);
    }

    // Clonar anuncio
    public function clone($id)
    {
        $original = UserAdvertisement::findOrFail($id);
        
        UserAdvertisement::create([
            'user_id' => auth()->id(),
            'type' => $original->type,
            'title' => $original->title . ' (Clonado)',
            'file_path' => $original->file_path,
            'file_type' => $original->file_type,
            'redirect_url' => $original->redirect_url,
            'description' => $original->description,
            'is_cloned' => true,
            'cloned_from' => $original->id,
        ]);

        return redirect()->back()->with('success', 'Anuncio clonado exitosamente');
    }

    // Like
    public function like($id)
    {
        $ad = UserAdvertisement::findOrFail($id);
        $userId = auth()->id();

        $like = AdvertisementLike::where('user_id', $userId)
            ->where('advertisement_id', $id)
            ->first();

        if ($like) {
            $like->delete();
            $ad->decrement('likes_count');
            $liked = false;
        } else {
            AdvertisementLike::create([
                'user_id' => $userId,
                'advertisement_id' => $id,
            ]);
            $ad->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $ad->fresh()->likes_count,
        ]);
    }

    // Comentar
    public function comment(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        $ad = UserAdvertisement::findOrFail($id);
        
        AdvertisementComment::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $id,
            'comment' => $request->comment,
        ]);

        $ad->increment('comments_count');

        return response()->json([
            'success' => true,
            'comments_count' => $ad->fresh()->comments_count,
        ]);
    }

    // Compartir
    public function share($id)
    {
        $ad = UserAdvertisement::findOrFail($id);
        
        AdvertisementShare::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $id,
        ]);

        $ad->increment('shares_count');

        return response()->json([
            'success' => true,
            'shares_count' => $ad->fresh()->shares_count,
        ]);
    }

    // Obtener comentarios
    public function getComments($id)
    {
        $comments = AdvertisementComment::where('advertisement_id', $id)
            ->with('user')
            ->latest()
            ->get();

        return response()->json($comments);
    }
}
