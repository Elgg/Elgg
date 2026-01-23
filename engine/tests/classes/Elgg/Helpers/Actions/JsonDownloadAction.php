<?php

namespace Elgg\Helpers\Actions;

class JsonDownloadAction extends \Elgg\Controllers\JsonDownloadAction {

	/**
	 * {@inheritdoc}
	 */
	protected function getContents(): mixed	{
		return ['key' => 'value'];
	}
}
