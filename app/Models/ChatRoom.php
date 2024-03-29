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


class ChatRoom extends Model
{
    public static $rules = [];

    public $table = 'chat_room';

    public $fillable = [
        'user_id',
        'room_id',
        'room_name',
    ];
    public function getChatsInRoomModel($room_id)
    {

        $chats =  ChatMission::where('room_id', $room_id)
            ->join('users', 'chat.sender_id', '=', 'users.id')
            ->select('chat.*', 'users.name')
            ->get();
        foreach ($chats as $chat) {
            $user = User::find($chat->sender_id);
            $avatarUrl = $user->getAvatarUrl($user);
            // Thực hiện các thao tác khác với $avatarUrl
            $chat->avatar_url = $avatarUrl;
            // Thực hiện các thao tác khác với $avatarUrl
        }

        foreach ($chats as $chat) {
            if ($chat->getFirstMedia('chat_files') != null) {
                $media = $chat->getFirstMedia('chat_files')->getFullUrl();
                if ($media) {
                    $fileUrl = $media;
                    $chat->file_url = $fileUrl;
                } else {
                    $chat->file_url = null;
                }
            } else {

                $chat->file_url = null;
            }
        }

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
            //get avatar
            foreach ($usersInRoom as $user) {
                $user_model = User::find($user->id);
                $avatarUrl = $user_model->getAvatarUrl($user_model);
                // Thực hiện các thao tác khác với $avatarUrl
                $user->avatar_url = $avatarUrl;
                // Thực hiện các thao tác khác với $avatarUrl
            }
        $usersInRoom = [
            $room_id => [
                'latest_chat' => $latestChat,
                'users' => $usersInRoom
            ]
        ];


        //get all rooms
        // get all room id of this user
        $authUserId = Auth::user()->id;


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
    }
}
