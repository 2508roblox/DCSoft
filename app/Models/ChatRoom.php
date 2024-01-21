<?php

namespace App\Models;

use App\Models\User;
use Eloquent as Model;
use App\Models\ChatRoom;
use App\Models\ChatMission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class ChatRoom extends Model {
    public static $rules = [
    ];

    public $table = 'chat_room';

    public $fillable = [
        'user_id',
        'room_id',
        'room_name',
    ];
    public function getChatsInRoomModel  ($room_id) {

        $chats = ChatMission::where('room_id', $room_id)
            ->join('users', 'chat.sender_id', '=', 'users.id')
            ->get();

        $usersInRoom = [];
        //get room info
        $latestChat = DB::table('chat')
            ->where('room_id', $room_id)
            ->orderBy('created_at', 'desc')
            ->first();

        $usersInRoom = DB::table('users')
            ->join('chat_room', 'users.id', '=', 'chat_room.user_id')
            ->where('chat_room.room_id', $room_id)
            ->select('users.*', 'chat_room.room_name as room_name')
            ->get();

        $usersInRoom = [
            $room_id => [
                'latest_chat' => $latestChat,
                'users' => $usersInRoom
            ]
        ];


        //get all rooms
        // get all room id of this user
        $authUserId = Auth::user()->id;

        if (!isset($_GET['search'])) {
            $authUser = User::find($authUserId);
            $user_rooms_id = $authUser->getRoomsByParticipants($authUserId);
            // join all user id and room_name of each room

            $userRooms = [];


            foreach ($user_rooms_id as $roomId) {
                $latestChat = DB::table('chat')
                    ->where('room_id', $roomId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $userRoom = DB::table('users')
                    ->join('chat_room', 'users.id', '=', 'chat_room.user_id')
                    ->where('chat_room.room_id', $roomId)
                    ->select('users.*', 'chat_room.room_name as room_name')
                    ->get();

                $userRooms[$roomId] = [
                    'latest_chat' => $latestChat,
                    'users' => $userRoom
                ];
            }
            //room name of room_id
            $roomNameByRoomId = null;


            if (count($usersInRoom[$room_id]['users']) >= 3) {
                $roomNameByRoomId = $usersInRoom[$room_id]['users'][0]->room_name ?? null;
            } elseif (count($usersInRoom[$room_id]['users']) == 2) {
                foreach ($usersInRoom[$room_id]['users'] as $user) {
                    if ($user->id != Auth::user()->id) {
                        $roomNameByRoomId = $user->name;
                        break;
                    }
                }
            }

            // userRooms is list of rooms and members
            return [
                'chats' => $chats,
                'usersInRoom' => $usersInRoom,
                'room_id' => $room_id,
                'userRooms' => $userRooms,
                'roomNameByRoomId' => $roomNameByRoomId
            ];

            // $authUserId = Auth::user()->id;
            // if (isset($_GET['search'])) {
            //     $searchTerm = $_GET['search'];
            //     $users = User::where('id', '!=', $authUserId)->where('name', 'like', '%' . $searchTerm . '%')->get();
            // } else {
            //     $users = User::where('id', '!=', $authUserId)->get();
            // }
            // foreach ($users as $user) {
            //     $chatRoom = $user->getChatRoom($user->id, $authUserId);
            //     $user->chatRoom = $chatRoom;
            //     $latestChat = $user->getLatestChat($user->chatRoom->room_id ?? null, $user->id);
            //     $user->latestChat = $latestChat;
            // }
            // $sortedUsers = $users->sortByDesc(function ($user) {
            //     return optional($user->latestChat->id ?? null);
            // });

            // //get room and chats and other user
            // $select_room  = ChatRoom::where('room_id', intval($room_id))->get();
            // $otherUser  = User::find($_GET['other_id']);
            // // $room_id = mt_rand(); // Tạo một room_id ngẫu nhiên
            // if ($select_room->isEmpty()) {

            //     // Tạo chat room thứ nhất
            //     $newChatRoom1 = new ChatRoom();
            //     $newChatRoom1->user_id = $_GET['other_id'];
            //     $newChatRoom1->room_id = $room_id;
            //     $newChatRoom1->save();

            //     // Tạo chat room thứ hai
            //     $newChatRoom2 = new ChatRoom();
            //     $newChatRoom2->user_id = $authUserId;
            //     $newChatRoom2->room_id = $room_id;
            //     $newChatRoom2->save();
            // }
            // $chats = ChatMission::where('room_id', $room_id)->get();

            // return view('chat.index', compact('sortedUsers', 'chats', 'otherUser', 'room_id'));
        }
    }


}
