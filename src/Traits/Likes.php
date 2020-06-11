<?php

namespace tima2000\Groups\Traits;

use tima2000\Groups\Models\Like;

trait Likes
{
    public function like($user_id)
    {
        $like = new Like(['user_id' => $user_id]);

        return $this->likes()->save($like);
    }

    public function unlike($user_id)
    {
        return $this->likes()->where('user_id', $user_id)->delete();
    }

    public function toggleLike($user_id)
    {
        if ($this->isLiked($user_id)) {
            return $this->unlike($user_id);
        }

        $this->like($user_id);
    }

    public function isLiked($user_id)
    {
        return (bool) $this->likes()
            ->where('user_id', $user_id)
            ->count();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
