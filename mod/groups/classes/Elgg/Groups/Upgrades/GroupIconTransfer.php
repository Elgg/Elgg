<?php

namespace Elgg\Groups\Upgrades;

class GroupIconTransfer implements \Elgg\Upgrade\Batch {

	const INCREMENT_OFFSET = true;

	const VERSION = 2016101900;

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return elgg_get_entities([
			'types' => 'group',
			'count' => true,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(\Elgg\Upgrade\Result $result, $offset) {

		$groups = elgg_get_entities([
			'types' => 'group',
			'offset' => $offset,
			'limit' => 10,
		]);

		foreach ($groups as $group) {
			$result = $this->transferIcons($group, $result);
		}

		return $result;
	}

	/**
	 * Transfer group icons to new filestore location
	 * Before 3.0, group icons where owned by the group owner
	 * and located in /groups/<guid><size>.jpg
	 * relative to group owner's filestore directory
	 * In 3.0, we are moving these to default filestore location
	 * relative to group's filestore directory
	 *
	 * @param \ElggGroup           $group  Group entity
	 * @param \Elgg\Upgrade\Result $result Upgrade result
	 * @return \Elgg\Upgrade\Result
	 */
	public function transferIcons(\ElggGroup $group, \Elgg\Upgrade\Result $result) {

		$sizes = elgg_get_icon_sizes('group', $group->getSubtype());

		$dataroot = elgg_get_config('dataroot');
		$dir = (new \Elgg\EntityDirLocator($group->owner_guid))->getPath();
		$prefix = 'groups/';

		foreach ($sizes as $size => $opts) {
			$filename = "{$group->guid}{$size}.jpg";
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

		return $result;
	}

}
