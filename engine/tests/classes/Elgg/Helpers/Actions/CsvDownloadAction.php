<?php

namespace Elgg\Helpers\Actions;

class CsvDownloadAction extends \Elgg\Controllers\CsvDownloadAction {

	protected function getContentHeaders(): array {
		return ['first', 'second'];
	}
	/**
	 * {@inheritdoc}
	 */
	protected function getContentRows(): array {
		return [
			[
				'row1col1',
				'row1col2',
			],
			[
				'row2col1',
				'row2col2',
			],
		];
	}
}
