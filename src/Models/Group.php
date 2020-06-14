<?php

namespace tima2000\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use tima2000\Groups\Groups;

class Group extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'description',
        'short_description',
        'image',
        'private',
        'extra_info',
        'settings',
        'conversation_id',
    ];

    public function users()
    {
        return $this->belongsToMany(Groups::userModel(), 'group_user')->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'group_post')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(Groups::userModel());
    }

    public function requests()
    {
        return $this->hasMany(GroupRequest::class, 'group_id')->with('sender');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'group_id');
    }

    /**
     * Creates a group.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return Group
     */
    public function make($userId, $data)
    {
        $data['user_id'] = $userId;

        return $this->create($data)->addMembers($userId);
    }

    /**
     * Creates a group join request.
     *
     * @param int $user_id
     */
    public function request($user_id)
    {
        $request = new GroupRequest(['user_id' => $user_id]);

        $this->requests()->save($request);
    }

    public function deleteRequest($user_id)
    {
        $this->requests()->where('user_id', $user_id)->delete();
    }

    /**
     * Accepts a group join request.
     *
     * @param int $userId
     *
     * @return Group
     */
    public function acceptRequest($userId)
    {
        $this->addMembers($userId);

        $this->deleteRequest($userId);

        return $this;
    }

    /**
     * Decline a group join request.
     *
     * @param int $userId
     *
     * @return Group
     */
    public function declineRequest($userId)
    {
        $this->deleteRequest($userId);

        return $this;
    }

    /**
     * Add members / join group.
     *
     * @param mixed $members integer user_id or an array of user ids
     *
     * @return Group
     */
    public function addMembers($members)
    {
        if (is_array($members)) {
            $this->users()->sync($members);
        } else {
            $this->users()->attach($members);
        }

        return $this;
    }

    /**
     * Removes user from group.
     *
     * @param mixed $members this can be user_id or an array of user ids
     *
     * @return Group
     */
    public function leave($members)
    {
        if (is_array($members)) {
            foreach ($members as $id) {
                $this->users()->detach($id);
            }
        } else {
            $this->users()->detach($members);
        }

        return $this;
    }

    /**
     * Attach a post to a group.
     *
     * @param int $postId
     *
     * @return Group
     */
    public function attachPost($postId)
    {
        if (is_array($postId)) {
            $this->posts()->sync($postId);
        } else {
            $this->posts()->attach($postId);
        }

        return $this;
    }

    public function detachPost($postId)
    {
        $this->posts()->detach($postId);

        return $this;
    }
}
