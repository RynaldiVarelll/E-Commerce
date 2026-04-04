<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display the chat list for the logged-in user.
     */
    public function index()
    {
        $userId = Auth::id();

        // Get unique list of people the user has chatted with
        $chats = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->select(DB::raw('IF(sender_id = ' . $userId . ', receiver_id, sender_id) as chat_user_id'), DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('chat_user_id')
            ->orderBy('last_activity', 'desc')
            ->get();

        $users = User::whereIn('id', $chats->pluck('chat_user_id'))->get();
        
        // Attach last message and unread count to each user
        $users->each(function($user) use ($userId) {
            $user->last_message = Message::where(function($q) use ($userId, $user) {
                    $q->where('sender_id', $userId)->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($userId, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $userId);
                })->latest()->first();

            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();
        });

        return view('frontend.chat.index', compact('users'));
    }

    /**
     * Show the chat room with a specific user.
     */
    public function show($receiverId)
    {
        $userId = Auth::id();
        $receiver = User::findOrFail($receiverId);

        // Mark messages as read
        Message::where('sender_id', $receiverId)
            ->where('receiver_id', $userId)
            ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })->orWhere(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')
            ->get();

        return view('frontend.chat.show', compact('receiver', 'messages'));
    }

    /**
     * Send a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'html' => view('frontend.chat.partial_message', ['message' => $message])->render()
            ]);
        }

        return back()->with('success', 'Pesan terkirim.');
    }

    /**
     * Get new messages for AJAX polling.
     */
    public function getMessages($receiverId)
    {
        $userId = Auth::id();

        // Mark messages as read
        Message::where('sender_id', $receiverId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })->orWhere(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $html = "";
        foreach($messages as $message) {
            $html .= view('frontend.chat.partial_message', compact('message'))->render();
        }

        return response()->json([
            'html' => $html,
            'count' => $messages->count()
        ]);
    }

    /**
     * Delete entire conversation with a user.
     */
    public function destroy($receiverId)
    {
        $userId = Auth::id();

        Message::where(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })->orWhere(function($q) use ($userId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })->delete();

        return redirect()->route('chat.index')->with('success', 'Percakapan berhasil dihapus.');
    }

    /**
     * Delete an individual message (within 12 hours).
     */
    public function destroyMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Keamanan: Hanya pengirim yang bisa hapus pesannya sendiri
        if ($message->sender_id !== Auth::id()) {
            abort(403, 'Aksi ilegal.');
        }

        // Batasan: Maksimal 12 jam sejak pesan dikirim
        $twelveHoursAgo = now()->subHours(12);
        if ($message->created_at->lt($twelveHoursAgo)) {
            return back()->with('error', 'Pesan sudah lebih dari 12 jam dan tidak dapat dihapus.');
        }

        $message->delete();

        return back()->with('success', 'Pesan berhasil ditarik.');
    }
}
