<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

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
    $chatRoom = DB::table('chat_room')
    ->whereIn('user_id', [$userId, $authUserId])
    ->groupBy('room_id')
    ->havingRaw('COUNT(DISTINCT user_id) = 2')
    ->first();

    return $chatRoom;
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
public function getRoomLatestChat($room_id)
{
    $latestChat = DB::table('chat')
        ->where('room_id', $room_id)
        ->orderBy('created_at', 'desc')
        ->first();

    return $latestChat;
}

}
