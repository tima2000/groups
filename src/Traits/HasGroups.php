<?php

namespace tima2000\Groups\Traits;

use tima2000\Groups\Models\Group;

trait HasGroups
{
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }
}
