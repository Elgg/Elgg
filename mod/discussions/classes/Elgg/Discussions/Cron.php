<?php

namespace Elgg\Discussions;

/**
 * Handle cron events
 */
class Cron {
	
	/**
	 * Automatically close discussions after x days
	 *
	 * @param \Elgg\Event $event 'cron', 'daily'
	 *
	 * @return void
	 */
	public static function autoClose(\Elgg\Event $event): void {
		$days = (int) elgg_get_plugin_setting('auto_close_days', 'discussions');
		if ($days < 1) {
			return;
		}
		
		$dt = $event->getParam('dt');
		
		elgg_call(ELGG_IGNORE_ACCESS, function () use ($days, $dt) {
			/** @var \ElggBatch $discussions */
			$discussions = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'discussion',
				'last_action_before' => $dt->modify("-{$days} days")->getTimestamp(),
				'limit' => 250,
				'batch' => true,
				'batch_inc_offset' => false,
				'metadata_name_value_pairs' => [
					[
						'name' => 'status',
						'value' => 'open',
					],
				],
				'sort_by' => [
					'property' => 'last_action',
					'direction' => 'desc',
				],
			]);
			/** @var \ElggDiscussion $discussion */
			foreach ($discussions as $discussion) {
				$discussion->status = 'closed';
				$discussion->save();
				$discussion->invalidateCache();
			}
		});
	}
}
