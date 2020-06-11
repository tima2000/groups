<?php

namespace tima2000\Groups\Traits;

use tima2000\Groups\Models\Post;

trait   HasPosts
{
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
}
