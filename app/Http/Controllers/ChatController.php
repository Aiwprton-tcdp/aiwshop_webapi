<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        // $n = request('name');
        // $name = mb_strtolower(trim($n));
        $only_deleted = filter_var(request('only_deleted'), FILTER_VALIDATE_BOOLEAN);
        $limit = (int)request('limit');

        $chats = Chat::join('users', 'users.id', 'chats.user_id')
            ->when(in_array(Auth::user()->role_id, [2, 3]), function($q) {
                $q->withTrashed();
            })
            ->whereUserId(Auth::id())
            ->orWhere('admin_id', Auth::id())
            ->where(function($q) use($only_deleted) {
                $only_deleted
                    ? $q->whereNotNull('chats.deleted_at')
                    : $q->whereNull('chats.deleted_at');
            })
            // ->when(!empty($name), function($q) use($name) {
            //     $q->whereRaw('LOWER(users.name) LIKE ? ', ["%{$name}%"]);
            // })
            ->select('chats.*', 'users.name AS username')
            ->orderByDesc('chats.updated_at')
            ->paginate($limit < 1 ? 10 : $limit);
        
        return response()->json([
            "data" => $chats
        ]);
    }

    public function store(\App\Http\Requests\StoreChatRequest $r)
    {
        $chat = Chat::create($r->validated());

        return response()->json([
            "data" => $chat
        ]);
    }

    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        
        return response()->json([
            "data" => $chat
        ]);
    }

    public function update(\App\Http\Requests\UpdateChatRequest $r, $id)
    {
        $chat = Chat::findOrFail($id);
        $chat->fill($r->validated());
        $chat->save();

        Log::info("Chat #" + $id + " has been successfully updated");

        return response()->json([
            "data" => $chat
        ]);
    }

    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        // $chat->active = false;
        $result = $chat->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
