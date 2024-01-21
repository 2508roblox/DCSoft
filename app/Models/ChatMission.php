<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class ChatMission extends Model {
    public static $rules = [
    ];

    public $table = 'chat';

    public $fillable = [
        'sender_id',
        'room_id',
        'note'

    ];

}
