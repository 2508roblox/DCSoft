<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMission;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends BaseController
{
    public function index($room_id_param = false)
    {

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
            // get Chats in first room
            $userRoomValues = array_values($userRooms); // Get the array values

            $firstUserRoom = reset($userRoomValues); // Get the first element
            if ($room_id_param) {
                # code...
                $first_room_id =  $room_id_param;
            } else {
                $first_room_id =    $firstUserRoom['latest_chat']->room_id;
            }

            // chats
            // usersInRoom
            // room_id
            // userRooms
            // roomNameByRoomId
            $spec_room = ChatRoom::where('room_id', $first_room_id)->first();
            //if not exist => create new one
            if (!$spec_room) {
                $spec_room = $this->createRoomBy2UserId($_GET['otherId'], $authUserId, $first_room_id);
            }
            $chatsInRoom = $spec_room->getChatsInRoomModel(intval($first_room_id)) ?? null;
            $chats = $chatsInRoom['chats'];
            $usersInRoom = $chatsInRoom['usersInRoom'];
            $room_id = $chatsInRoom['room_id'];
            $roomNameByRoomId = $chatsInRoom['roomNameByRoomId'];
            // dd($chats);

            return view('chat.index', compact('chats', 'usersInRoom', 'room_id', 'userRooms', 'roomNameByRoomId'));
        } else {



            //search logic
            $authUser = User::find($authUserId);
            $searchTerm = $_GET['search'];
            // get all user match search param and number count <= 2, if not exist => get by room_name
            $users = $authUser->getRoomsBySearchParams($authUserId, $searchTerm);
            // trường hợp group chat
            if ( isset($users['room_type'] ) && $users['room_type'] == 'group') {
                //get latest chat

                foreach ($users as $key => $user) {
                    if ($key === 'room_type') {
                        continue; // Skip the room_type element
                    }

                    // Make sure $user is an object, not a string

                    $latestChat = $this->getLatestChat($user->room_id ?? null);
                    $user->latestChat = $latestChat->note ?? null;

                }
                $searchUsers =$users->sortByDesc(function ($user) {
                    return optional($user->latestChat->id ?? null);
                });

                return view('chat.index', compact('searchUsers'));


            }else {
                  // trường hợp private room
            foreach ($users as $user) {
                // get only 2 members room
                $chatRoom = $user->getChatRoom($user->id, $authUserId);
                $user->chatRoom = $chatRoom;
                $latestChat = $user->getLatestChat(isset($user->chatRoom[0]->room_id) ?$user->chatRoom[0]->room_id  : null);
                $user->latestChat = $latestChat;
            }
            $searchUsers = $users->sortByDesc(function ($user) {
                return optional($user->latestChat->id ?? null);
            });
            // dd($searchUsers);
            return view('chat.index', compact('searchUsers'));
            }

        }
    }
    public function getChatsInRoom($room_id)
    {
        return  $this->index($room_id);
    }
    public function store(Request $request)
    {
        $sender_id = Auth::user()->id;
        $newChatMission = ChatMission::create([
            'sender_id' => $sender_id,
            'room_id' =>  $request->input('room_id'),
            'note' =>  $request->input('note')
        ]);
        return redirect()->back();
    }
    public function createRoomBy2UserId($user_id, $auth_user_id, $room_id)
    {
        $roomName =  null;
        $createdAt = now();
        $updatedAt = now();

        $data = [
            [
                'user_id' => $user_id,
                'room_id' => $room_id,
                'room_name' => $roomName,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'user_id' => $auth_user_id,
                'room_id' => $room_id,
                'room_name' => $roomName,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]
        ];

        DB::table('chat_room')->insert($data);

        $latestChat = DB::table('chat')
            ->where('room_id', $room_id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $latestChat;
    }
    public function getLatestChat($roomId)
    {
        if ($roomId != null) {
            return ChatMission::where('room_id', $roomId)
            ->orderBy('id', 'desc')
            ->first();
        }
        return null;
    }
}
