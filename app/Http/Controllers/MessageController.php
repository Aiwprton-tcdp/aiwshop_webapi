<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return  string
     */
    public function index()
    {
        $chat_id = (int)request('chat_id');
        $limit = (int)request('limit');

        $chat = \App\Models\Chat::findOrFail($chat_id);
        if (!in_array(Auth::id(), [$chat->user_id, $chat->admin_id])
            && !in_array(Auth::user()->role_id, [2, 3])) {
            return response()->json([
                "data" => null,
                "message" => 'This is a private chat',
            ], 401);
        }

        $messages = Message::whereChatId($chat_id)
            ->when(in_array(Auth::user()->role_id, [2, 3]), function($q) {
                $q->withTrashed();
            })
            ->orderByDesc('id')
            ->simplePaginate($limit < 1 ? 10 : $limit);
        
        return response()->json([
            "data" => $messages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return  string
     */
    public function store(\App\Http\Requests\StoreMessageRequest $r)
    {
        $validated = $r->validated();
        $validated['sender_id'] = Auth::id();

        $chat = \App\Models\Chat::findOrFail($validated['chat_id']);
        if (!in_array(Auth::id(), [$chat->user_id, $chat->admin_id])
            && !in_array(Auth::user()->role_id, [2, 3])) {
            return response()->json([
                "data" => null,
                "message" => 'This is a private chat',
            ], 401);
        }
        
        $validated['recipient_id'] = Auth::id() == $chat->user_id
            ? $chat->admin_id
            : $chat->user_id;
        event(new ChatEvent($validated));
        return response()->json([
            "data" => $validated
        ]);

        // send message to websocket
        $message = Message::create($validated);

        return response()->json([
            "data" => $message
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return  string
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        
        $chat = \App\Models\Chat::findOrFail($message->chat_id);
        if (!in_array(Auth::id(), [$chat->user_id, $chat->admin_id])
            && !in_array(Auth::user()->role_id, [2, 3])) {
            return response()->json([
                "data" => null,
                "message" => 'This is a private chat',
            ], 401);
        }

        return response()->json([
            "data" => $message
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return  string
     */
    public function update(Request $r, $id)
    {
        $message = Message::findOrFail($id);
        
        $chat = \App\Models\Chat::findOrFail($message->chat_id);
        if (!in_array(Auth::id(), [$chat->user_id, $chat->admin_id])
            && !in_array(Auth::user()->role_id, [2, 3])) {
            return response()->json([
                "data" => null,
                "message" => 'This is a private chat',
            ], 401);
        }

        $message->fill($r->validated());
        $message->save();

        return response()->json([
            "data" => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return  string
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        $chat = \App\Models\Chat::findOrFail($message->chat_id);
        if (!in_array(Auth::id(), [$chat->user_id, $chat->admin_id])
            && !in_array(Auth::user()->role_id, [2, 3])) {
            return response()->json([
                "data" => null,
                "message" => 'This is a private chat',
            ], 401);
        }

        $result = $message->delete();

        return response()->json([
            "data" => $result
        ]);
    }
}
