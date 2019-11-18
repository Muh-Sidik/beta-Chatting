<?php

namespace App\Http\Controllers;

use App\DetailChat;
use App\User;
use App\Chat;
use App\Events\MessagePushed;
use Illuminate\Http\Request;

class MessageChatController extends Controller
{
    public function index($id)
    {

        $profil = User::find($id);
        $numchat = Chat::where('chats.id_user1', $id)
            // ->orwhere('chats.id_user2', $id)
            ->select('*', 'users.username AS lawan', 'users.photo AS avatar')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'chats.id_user2');
                // $join->orOn('users.id', '=', 'chats.id_user1');
            })
            ->count();
        $chat = Chat::where('chats.id_user1', $id)
            ->select('*', 'users.username AS lawan', 'users.photo AS avatar')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'chats.id_user2');
            })
            ->get();
        // if ($numchat <= 0) {
        //     $chat = Chat::where('chats.id_user2', $id)
        //         ->select('*', 'users.username AS lawan', 'users.photo AS avatar')
        //         ->join('users', function ($join) {
        //             $join->on('users.id', '=', 'chats.id_user1');
        //         })
        //         ->get();
        // } else {
        //     $chat = Chat::where('chats.id_user1', $id)
        //         ->select('*', 'users.username AS lawan', 'users.photo AS avatar')
        //         ->join('users', function ($join) {
        //             $join->on('users.id', '=', 'chats.id_user2');
        //         })
        //         ->get();
        // }

        return response()->json(compact('chat'), 200);
    }

    public function getDetail($no_detail_chat)
    {
        // event(new MessagePushed($message));
        $detail = \App\DetailChat::where('detail_chats.no_detail_chat', $no_detail_chat)
            ->join('users', 'users.id', '=', 'detail_chats.id_user_from')
            ->join('chats', 'chats.no_detail_chat', '=', 'detail_chats.no_detail_chat')
            ->orderBy('detail_chats.id', 'asc')
            ->get();

        return response()->json($detail, 200);
    }

    public function getChatHstory(Request $request)
    {
        $from = $request->input('id_user1');
        $to = $request->input('id_user2');
        $chat = Chat::where('id_user1', $from)
            ->where('id_user2', $to)
            ->count();
        $data_chat = Chat::where('id_user1', $from)
            ->where('id_user2', $to)
            ->first();

        if ($chat <= 0) {
            $chat = Chat::where('id_user2', $from)
                ->where('id_user1', $to)
                ->count();
            $data_chat = Chat::where('id_user2', $from)
                ->where('id_user1', $to)
                ->first();
            if ($chat <= 0) {
                return response()->json('no', 200);
            } else {
                $no_detail = $data_chat->no_detail_chat;
                $detail = DetailChat::where('detail_chats.no_detail_chat', $no_detail)
                    ->join('users', 'users.id', '=', 'detail_chats.id_user_from')
                    ->join('chats', 'chats.no_detail_chat', '=', 'detail_chats.no_detail_chat')
                    ->orderBy('detail_chats.id', 'asc')
                    ->get();
                return response()->json($detail, 200);
            }
        } else {
            $no_detail = $data_chat->no_detail_chat;
            $detail = DetailChat::where('detail_chats.no_detail_chat', $no_detail)
                ->join('users', 'users.id', '=', 'detail_chats.id_user_from')
                ->join('chats', 'chats.no_detail_chat', '=', 'detail_chats.no_detail_chat')
                ->orderBy('detail_chats.id', 'asc')
                ->get();
            return response()->json($detail, 200);
        }
    }

    public function search($id)
    {
        $search = \App\DetailChat::where('detail_chats.chat', 'like', $_GET['search'])
            ->where('detail_chats.no_detail_chat', $id)
            ->join('users', 'users.id', '=', 'detail_chats.id_user_from')
            ->get();

        return response()->json([$search], 200);
    }

    public function sendchat(Request $request)
    {
        $from = $request->input('id_user1');
        $to = $request->input('id_user2');
        $chat = Chat::where('id_user1', $from)
            ->where('id_user2', $to)->count();

        if ($chat <= 0) { //belum pernah chat maka jomblo :v
            $chat = Chat::count();
            $generate = $chat + 1;
            $generate = '00' . $generate;
            $insert = new Chat();
            $insert->no_detail_chat = $generate;
            $insert->id_user1 = $from;
            $insert->id_user2 = $to;
            $insert2 = new Chat();
            $insert2->no_detail_chat = $generate;
            $insert2->id_user1 = $to;
            $insert2->id_user2 = $from;
            $detailChat = new DetailChat();
            $detailChat->no_detail_chat = $generate;
            $detailChat->chat = $request->input('chat');
            $detailChat->id_user_from = $request->input('id_user_from');
            $detailChat->id_user_to = $request->input('id_user_to');
            if ($insert2->save() && $insert->save() && $detailChat->save()) {
                return response()->json(['status' => 'Pesan terkirim!'], 200);
            }
        } else {
            $room = Chat::where('id_user1', $from)
                ->where('id_user2', $to)->first();
            $detailChat = new DetailChat();
            $detailChat->no_detail_chat = $room->no_detail_chat;
            $detailChat->chat = $request->input('chat');
            $detailChat->id_user_from = $request->input('id_user_from');
            $detailChat->id_user_to = $request->input('id_user_to');
            if ($detailChat->save()) {
                return response()->json(['status' => 'Pesan terkirim mamank!'], 200);
            }
        }
    }

    public function edit_chat(Request $request)
    {

        $from = $request->input('id_user1');
        $to = $request->input('id_user2');
        $room = Chat::where('id_user1', $from)
            ->where('id_user2', $to)->count();

        $edit = DetailChat::find($request->input('id'));
        $edit->no_detail_chat = $room->no_detail_chat;
        $edit->chat = $request->input('chat');
        $edit->id_user1 = $from;
        $edit->id_user2 = $to;
        $edit->id_user_from = $request->input('id_user_from');
        $edit->id_user_to = $request->input('id_user_to');
        if ($edit->save()) {
            return response()->json(['status' => 'berhasil edit', 200]);
        }
    }

    public function delete_chat($chat)
    {
        $delete = DetailChat::where('chat', $chat);
        if ($delete->delete()) {
            return response()->json(['status' => 'success delete', 200]);
        }
    }
}
