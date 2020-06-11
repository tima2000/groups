<?php


namespace tima2000\Groups\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}