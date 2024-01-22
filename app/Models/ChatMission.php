<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

class ChatMission extends Model implements HasMedia
 {
    use HasFactory, InteractsWithMedia;
    public static $rules = [
    ];

    public $table = 'chat';

    public $fillable = [
        'sender_id',
        'room_id',
        'note'

    ];

}
