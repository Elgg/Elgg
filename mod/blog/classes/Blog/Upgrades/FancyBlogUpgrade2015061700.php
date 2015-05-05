<?php

namespace Blog\Upgrades;

use Elgg\BatchUpgrade;

class FancyBlogUpgrade2015061700 implements BatchUpgrade {

	private $offset;

	public function getNumRemaining() {
		$count = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'blog',
			'count' => true,
		));

		return $count - $this->offset;
	}

	public function isRequired() {
		return true;
	}

	public function setOffset($offset) {
		$this->offset = $offset;
	}

	public function getErrorMessages() {
		return array();
	}

	public function getSuccessCount() {
		return $this->offset;
	}

	public function run() {
		$blogs = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'blog',
		));

		foreach ($blogs as $blog) {

			$this->offset++;
		}
	}

	public function getNextOffset() {
		return 0;
	}
}
