<?php

namespace Elgg\Controllers;

use Elgg\Http\DownloadResponse;

/**
 * Helper class for download actions
 *
 * @since 7.0
 */
abstract class DownloadAction extends GenericAction {
	
	/**
	 * File name for download
	 *
	 * @return string
	 */
	abstract protected function getFilename(): string;

	/**
	 * File contents
	 *
	 * @return mixed
	 */
	abstract protected function getContents(): mixed;

	/**
	 * Return the content mimetype for the Content-Type header
	 *
	 * @return string
	 */
	protected function getMimeType(): string {
		return 'application/octet-stream';
	}

	/**
	 * Returns headers for download response
	 *
	 * @return array
	 */
	protected function getHeaders(): array {
		return [
			'Content-Type' => $this->getMimeType() . '; charset=utf-8',
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function success(): DownloadResponse {
		return elgg_download_response((string) $this->getContents(), $this->getFilename(), false, $this->getHeaders());
	}
}
