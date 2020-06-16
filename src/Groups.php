<?php

namespace tima2000\Groups;

use tima2000\Groups\Models\GroupUser;
use tima2000\Groups\Models\Comment;
use tima2000\Groups\Models\Group;
use tima2000\Groups\Models\Post;
use tima2000\Groups\Models\User;
use tima2000\Groups\Models\Event;

class Groups
{
    /**
     * @var Comment
     */
    protected $comment;
    /**
     * @var Group
     */
    protected $group;
    /**
     * @var Post
     */
    protected $post;

    /**
     * @var Comment
     */
    protected $event;

    /**
     * @var User
     */
    protected $user;

    public function __construct(Comment $comment, Group $group, Post $post, Event $event)
    {
        $this->comment = $comment;
        $this->group = $group;
        $this->post = $post;
        $this->event = $event;
        $this->user = app(self::userModel());
    }

    public static function userModel()
    {
        return config('tima2000_groups.user_model', User::class);
    }

    /**
     * Returns User instance with group relation.
     *
     * @param int $userId
     *
     * @return User
     */
    public function getUser($userId)
    {
        return $this->user->find($userId);
    }

    /**
     * Creates a group.
     *
     * @param int   $userId owner of group
     * @param array $data   group information
     *
     * @return Group
     */
    public function create($userId, $data)
    {
        return $this->group->make($userId, $data);
    }

    /**
     * Returns a group.
     *
     * @param int $groupId
     *
     * @return Group
     */
    public function group($groupId)
    {
        return $this->group->findOrFail($groupId);
    }

    /**
     * Creates a post.
     *
     * @param array $data
     *
     * @return Post
     */
    public function createPost($data)
    {
        return $this->post->make($data);
    }

    /**
     * Returns a post.
     *
     * @param int $postId
     *
     * @return Post
     */
    public function post($postId)
    {
        return $this->post->findOrFail($postId);
    }

    /**
     * Adds a comment.
     *
     * @param array $comment
     *
     * @return Comment
     */
    public function addComment($comment)
    {
        return $this->comment->add($comment);
    }

    /**
     * Returns a comment.
     *
     * @param int $commentId
     *
     * @return Comment
     */
    public function comment($commentId)
    {
        return $this->comment->findOrFail($commentId);
    }

    public function addEvent($data)
    {
        return $this->event->addEvent($data);
    }

    public function event($eventId)
    {
        return $this->event->findOrFail($eventId);
    }

    public function groupsOwnedBy($user_id)
    {
        return $this->group->where('user_id', $user_id)->get();
    }

    public function userEvents($user_id)
    {
        Event::whereIn('group_id', function ($query) use($user_id){
            $query->select('group_id')->from('group_user')->where('user_id',$user_id);
            })
            ->orderBy('id', 'desc')
            ->get();
    }

    public function shareSamePrivateGroup($host_id, $client_id)
    {
        return GroupUser::select('group_id')
            ->where('user_id', $client_id)
            ->whereIn('group_id', function ($query) use($host_id){
                $query->select('id')->from('groups')->where('user_id',$host_id)->where('private', 1);
            })
            ->orderBy('group_id', 'desc')
            ->firstOr(function (){return false;});
    }
}
