<?php

namespace Elgg\Groups\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Moves group icons owned by user to directory owned by the groups itself.
 *
 * BEFORE: /dataroot/<bucket>/<owner_guid>/groups/<group_guid><size>.jpg
 * AFTER:  /dataroot/<bucket>/<group_guid>/icons/icon/<size>.jpg
 */
class GroupIconTransfer implements AsynchronousUpgrade {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::getVersion()
	 */
	public function getVersion() {
		return 2016101900;
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::needsIncrementOffset()
	 */
	public function needsIncrementOffset() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::shouldBeSkipped()
	 */
	public function shouldBeSkipped() {
		return empty($this->countItems());
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::countItems()
	 */
	public function countItems() {
		return elgg_get_entities([
			'types' => 'group',
			'count' => true,
		]);
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Upgrade\Batch::run()
	 */
	public function run(Result $result, $offset) {

		$groups = elgg_get_entities([
			'types' => 'group',
			'offset' => $offset,
			'limit' => 10,
		]);

		foreach ($groups as $group) {
			$this->transferIcons($group, $result);
		}
	}

	/**
	 * Transfer group icons to new filestore location
	 * Before 3.0, group icons where owned by the group owner
	 * and located in /groups/<guid><size>.jpg
	 * relative to group owner's filestore directory
	 * In 3.0, we are moving these to default filestore location
	 * relative to group's filestore directory
	 *
	 * @param \ElggGroup $group  Group entity
	 * @param Result     $result Upgrade result
	 * @return Result
	 */
	public function transferIcons(\ElggGroup $group, Result $result) {

		$sizes = elgg_get_icon_sizes('group', $group->getSubtype());
		$sizes['original'] = [];
		$dataroot = elgg_get_config('dataroot');
		$dir = (new \Elgg\EntityDirLocator($group->owner_guid))->getPath();
		$prefix = 'groups/';

		$error = false;
		foreach ($sizes as $size => $opts) {
			if ($size == 'original') {
				$filename = "{$group->guid}.jpg";
			} else {
				$filename = "{$group->guid}{$size}.jpg";
			}
			$filestorename = "{$dataroot}{$dir}{$prefix}{$filename}";
			if (!file_exists($filestorename)) {
				// nothing to move
				continue;
			}

			$icon = $group->getIcon($size);

			// before transferring the file, we need to make sure
			// the directory structure of the new filestore location exists
			$icon->open('write');
			$icon->close();

			if (!rename($filestorename, $icon->getFilenameOnFilestore())) {
				$result->addError("
					Failed to transfer file from '$filestorename'
					to {$icon->getFilenameOnFilestore()}
				");
				$error = true;
			}
		}

		if ($error) {
			$result->addFailures();
		} else {
			$result->addSuccesses();
		}
	}

}
