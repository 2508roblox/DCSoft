<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\ChatRoom;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia {
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'country',
        'facebook',
        'point_accumulated',
        'expertise_coefficient',
        'allowance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    const ADMIN = 'admin';
    const MANAGER = 'manager';

    public function userRole() {
        return $this->belongsTo(Roles::class, 'role_id');
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
    public function getChatRoom($userId, $authUserId)
    {
        $chatRooms = DB::table('chat_room')
            ->join('users', 'chat_room.user_id', '=', 'users.id')
            ->select('chat_room.room_id', 'chat_room.room_name') // Lấy trường room_name
            ->whereIn('chat_room.room_id', function ($query) use ($userId, $authUserId) {
                $query->select('room_id')
                    ->from('chat_room')
                    ->whereIn('user_id', [$userId, $authUserId])
                    ->groupBy('room_id')
                    ->havingRaw('COUNT(DISTINCT user_id) = 2');
            })
            ->groupBy('chat_room.room_id', 'chat_room.room_name') // Thêm room_name vào mệnh đề groupBy
            ->havingRaw('COUNT(DISTINCT chat_room.user_id) = 2')
            ->get()
            ->toArray();

        return $chatRooms;
    }
public function getRoomsByParticipants($authId)
{
    return DB::table('chat_room')
        ->whereIn('room_id', function ($query) use ($authId) {
            $query->select('room_id')
                ->from('chat_room')
                ->where('user_id', $authId)
                ->orWhere(function ($query) {
                    $query->select('room_id')
                        ->from('chat_room')
                        ->groupBy('room_id')
                        ->havingRaw('COUNT(DISTINCT user_id) >= 3');
                });
        })
        ->groupBy('room_id')
        ->havingRaw('COUNT(DISTINCT user_id) >= 2')
        ->pluck('room_id');
}
public function getRoomsBySearchParams($authId, $searchTerm)
{
    // lấy tất cả các row user join chat room group by room id và điều kiện là room id đó phải có 2 row chứa cả 2 user id: auth user id và user id
    // - lấy phòng có 2 người
    $users = User::where('name', 'like', '%' . $searchTerm . '%')
    ->leftJoin('chat_room AS cr1', function ($join) use ($authId) {
        $join->on('users.id', '=', 'cr1.user_id')
            ->where('cr1.room_id', 'IN', function ($query) use ($authId) {
                $query->select('room_id')
                    ->from('chat_room')
                    ->where('user_id', $authId);
            });
    })
    ->leftJoin('chat_room AS cr2', function ($join) use ($authId) {
        $join->on('users.id', '=', 'cr2.user_id')
            ->whereNull('cr2.room_id');
    })
    ->select('users.*')
    ->where(function ($query) {
        $query->whereNotNull('cr1.user_id')
            ->orWhereNull('cr2.user_id');
    })
    ->get();
    if ($users->isEmpty()) {
        $chatRooms = ChatRoom::where('room_name', 'like', '%' . $searchTerm . '%')
        ->leftJoin('users', 'chat_room.user_id', '=', 'users.id')
        ->select('chat_room.room_id', 'users.*')
        ->groupBy('chat_room.room_id')
        ->get();


        foreach ($chatRooms as $chatRoom) {
            $roomId = $chatRoom->room_id;
            $chatRoom['users'] = ChatRoom::where('room_id', $roomId)->get();
        }
        $chatRooms['room_type'] = 'group';
        return $chatRooms;

    // dd($chatRooms);
    }


    return $users;
}
public function getRoomLatestChat($room_id)
{
    $latestChat = DB::table('chat')
        ->where('room_id', $room_id)
        ->orderBy('created_at', 'desc')
        ->first();

    return $latestChat;
}


}
