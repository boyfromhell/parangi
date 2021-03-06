<?php namespace Parangi;

use DB;

class Shout extends BaseModel
{
    use \Earlybird\Foundry;

	protected $table = 'shoutbox';
	protected $guarded = array('id');

	/**
	 * User who posted the message
	 *
	 * @return Relation
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

	/**
	 * Posts from X days ago
	 *
	 * @param  Query  $query
	 * @param  int  $days
	 * @return Query
	 */
	public function scopeDaysAgo($query, $days)
	{
		return $query->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$days.' DAY)'));
	}

}

