<?php

namespace tima2000\Groups\Traits;

use tima2000\Groups\Models\Event;
use tima2000\Groups\Models\Group;

trait   HasEvents
{
    public function events()
    {
        return $this->hasManyThrough(Event::class, Group::class);
    }
}
