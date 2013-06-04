<?php
/**
 * Calendar interface for events.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Notable
 *
 * @deprecated 1.9
 */
interface Notable {
	/**
	 * Calendar functionality.
	 * This function sets the time of an object on a calendar listing.
	 *
	 * @param int $hour     If ommitted, now is assumed.
	 * @param int $minute   If ommitted, now is assumed.
	 * @param int $second   If ommitted, now is assumed.
	 * @param int $day      If ommitted, now is assumed.
	 * @param int $month    If ommitted, now is assumed.
	 * @param int $year     If ommitted, now is assumed.
	 * @param int $duration Duration of event, remainder of the day is assumed.
	 *
	 * @return bool
	 */
	public function setCalendarTimeAndDuration($hour = null, $minute = null, $second = null,
		$day = null, $month = null, $year = null, $duration = null);

	/**
	 * Return the start timestamp.
	 *
	 * @return int
	 */
	public function getCalendarStartTime();

	/**
	 * Return the end timestamp.
	 *
	 * @return int
	 */
	public function getCalendarEndTime();
}
