<?php namespace Parangi;

class PollOption extends BaseModel
{

	protected $appends = array(
		'width',
		'percent',
	);

	/**
	 * Poll
	 *
	 * @return Relation
	 */
	public function poll()
	{
		return $this->belongsTo('Parangi\Poll');
	}

	/**
	 * Get width of vote bar
	 *
	 * @return int
	 */
	public function getWidthAttribute()
	{
		$proportion = 100 / $this->poll->max_percent;

		return round($this->percent * $proportion);
	}

	/**
	 * Get percentage score of this option vs other options
	 *
	 * @return int
	 */
	public function getPercentAttribute()
	{
		if ($this->poll->total_picks > 0) {
			return round(100 * ($this->total_votes / $this->poll->total_picks));
		}

		return 0;
	}

}

