<?php

namespace Elgg\Controllers;

use Elgg\Http\DownloadResponse;

/**
 * Helper class for JSON download actions
 *
 * @since 7.0
 */
abstract class JsonDownloadAction extends DownloadAction {

	/**
	 * {@inheritdoc}
	 */
	protected function getFilename(): string {
		return 'output.json';
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getMimeType(): string {
		return 'text/json';
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): DownloadResponse {
		$contents = $this->getContents();
		if (!is_string($contents)) {
			$contents = json_encode($contents);
		}
		
		return elgg_download_response($contents, $this->getFilename(), false, $this->getHeaders());
	}
}
