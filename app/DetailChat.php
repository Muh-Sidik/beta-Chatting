<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailChat extends Model
{
    protected $fillable =[
        "no_detail_chat", "id_user_from", "id_user_to", "chat", "status"
    ];

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'id_user_from');
        
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'id_user_to');
    }
}
