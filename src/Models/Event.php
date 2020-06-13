<?php


namespace tima2000\Groups\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['group_id', 'name', 'description', 'start', 'end', 'log'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function addEvent($data)
    {
        return $this->create($data);
    }

    /**
     * Updates Event.
     *
     * @param int   $eventId
     * @param array $data
     *
     * @return Event
     */
    public function updateEvent($eventId, $data)
    {
        $this->where('id', $eventId)->update($data);

        return $this;
    }
}