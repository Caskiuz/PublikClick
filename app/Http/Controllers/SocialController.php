<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    // Feed principal
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->latest()
            ->paginate(10);
        
        $friendRequests = Friendship::where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->with('user')
            ->get();

        return view('social.feed', compact('posts', 'friendRequests'));
    }

    // Crear post
    public function storePost(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:5120'
        ]);

        $data = ['user_id' => auth()->id(), 'content' => $request->content];
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return back()->with('success', 'Publicación creada');
    }

    // Like post
    public function likePost($id)
    {
        $post = Post::findOrFail($id);
        $like = PostLike::where('user_id', auth()->id())->where('post_id', $id)->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            PostLike::create(['user_id' => auth()->id(), 'post_id' => $id]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json(['success' => true, 'liked' => $liked, 'likes_count' => $post->fresh()->likes_count]);
    }

    // Comentar post
    public function commentPost(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        $post = Post::findOrFail($id);
        PostComment::create([
            'user_id' => auth()->id(),
            'post_id' => $id,
            'comment' => $request->comment
        ]);

        $post->increment('comments_count');

        return back()->with('success', 'Comentario agregado');
    }

    // Buscar usuarios
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('name', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(10)
            ->get();

        return view('social.search', compact('users', 'query'));
    }

    // Enviar solicitud de amistad
    public function sendFriendRequest($userId)
    {
        $existing = Friendship::where('user_id', auth()->id())
            ->where('friend_id', $userId)
            ->first();

        if ($existing) {
            return back()->with('error', 'Ya enviaste solicitud');
        }

        Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $userId,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Solicitud enviada');
    }

    // Aceptar solicitud
    public function acceptFriendRequest($id)
    {
        $friendship = Friendship::findOrFail($id);
        
        if ($friendship->friend_id !== auth()->id()) {
            return back()->with('error', 'No autorizado');
        }

        $friendship->update(['status' => 'accepted']);

        // Crear amistad recíproca
        Friendship::create([
            'user_id' => $friendship->friend_id,
            'friend_id' => $friendship->user_id,
            'status' => 'accepted'
        ]);

        return back()->with('success', 'Solicitud aceptada');
    }

    // Rechazar solicitud
    public function rejectFriendRequest($id)
    {
        $friendship = Friendship::findOrFail($id);
        
        if ($friendship->friend_id !== auth()->id()) {
            return back()->with('error', 'No autorizado');
        }

        $friendship->delete();

        return back()->with('success', 'Solicitud rechazada');
    }

    // Lista de amigos
    public function friends()
    {
        $friends = Friendship::where('user_id', auth()->id())
            ->where('status', 'accepted')
            ->with('friend')
            ->get();

        return view('social.friends', compact('friends'));
    }

    // Chat con amigo
    public function chat($friendId)
    {
        $friend = User::findOrFail($friendId);
        
        $messages = Message::where(function($q) use ($friendId) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $friendId);
        })->orWhere(function($q) use ($friendId) {
            $q->where('sender_id', $friendId)->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Marcar como leídos
        Message::where('sender_id', $friendId)
            ->where('receiver_id', auth()->id())
            ->update(['is_read' => true]);

        return view('social.chat', compact('friend', 'messages'));
    }

    // Enviar mensaje
    public function sendMessage(Request $request, $friendId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friendId,
            'message' => $request->message
        ]);

        return back();
    }
}
