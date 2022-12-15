<?php

namespace Elgg\SystemLog;

/**
 * Event callbacks for cron
 *
 * @since 4.0
 * @internal
 */
class Cron {

	/**
	 * Trigger the log rotation
	 *
	 * @param \Elgg\Event $event 'cron', 'all'
	 *
	 * @return void|string
	 */
	public static function rotateLogs(\Elgg\Event $event) {
		$resulttext = elgg_echo('logrotate:logrotated') . PHP_EOL;
	
		$period = elgg_get_plugin_setting('period', 'system_log');
		if ($period !== $event->getType()) {
			return;
		}
		
		$offset = self::getSecondsInPeriod($period);
	
		if (!self::archiveLog($offset)) {
			$resulttext = elgg_echo('logrotate:lognotrotated') . PHP_EOL;
		}
	
		return $event->getValue() . $resulttext;
	}
	
	/**
	 * Trigger the log deletion
	 *
	 * @param \Elgg\Event $event 'cron', 'daily'
	 *
	 * @return void|string
	 */
	public static function deleteLogs(\Elgg\Event $event) {
		$retention = (int) elgg_get_plugin_setting('retention', 'system_log');
		if ($retention < 1) {
			return;
		}
		
		$resulttext = elgg_echo('logrotate:logdeleted') . PHP_EOL;
		
		$offset = $retention * (24 * 60 * 60);
		if (!self::deleteLog($offset)) {
			$resulttext = elgg_echo('logrotate:lognotdeleted') . PHP_EOL;
		}
		
		return $event->getValue() . $resulttext;
	}
	
	/**
	 * Convert an interval to seconds
	 *
	 * @param string $period interval
	 *
	 * @return int
	 */
	protected static function getSecondsInPeriod($period) {
		$seconds_in_day = 86400;
	
		switch ($period) {
			case 'weekly':
				$offset = $seconds_in_day * 7;
				break;
	
			case 'yearly':
				$offset = $seconds_in_day * 365;
				break;
	
			case 'monthly':
			default:
				// assume 28 days even if a month is longer. Won't cause data loss.
				$offset = $seconds_in_day * 28;
		}
	
		return $offset;
	}
	
	/**
	 * This function creates an archive copy of the system log.
	 *
	 * @param int $offset An offset in seconds that has passed since the time of log entry
	 *
	 * @return bool
	 */
	protected static function archiveLog($offset = 0) {
	
		$log = SystemLog::instance();
	
		$time = $log->getCurrentTime()->getTimestamp();
	
		$cutoff = $time - (int) $offset;
	
		$created_before = new \DateTime();
		$created_before->setTimestamp($cutoff);
	
		return $log->archive($created_before);
	}
	
	/**
	 * This function deletes archived copies of the system logs that are older than specified offset
	 *
	 * @param int $offset An offset in seconds that has passed since the time of archival
	 *
	 * @return bool
	 */
	protected static function deleteLog($offset) {
	
		$log = SystemLog::instance();
	
		$time = $log->getCurrentTime()->getTimestamp();
	
		$cutoff = $time - (int) $offset;
	
		$archived_before = new \DateTime();
		$archived_before->setTimestamp($cutoff);
	
		return $log->deleteArchive($archived_before);
	}
}
