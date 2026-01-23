<?php

namespace Elgg\Controllers;

use Elgg\Http\DownloadResponse;

/**
 * Helper class for CSV download actions
 *
 * @since 7.0
 */
abstract class CsvDownloadAction extends DownloadAction {

	/**
	 * {@inheritdoc}
	 */
	protected function getFilename(): string {
		return 'output.csv';
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getMimeType(): string {
		return 'text/csv';
	}

	/**
	 * Returns the separator
	 *
	 * @return string
	 */
	protected function getSeparator(): string {
		return ';';
	}

	/**
	 * Returns the enclosure
	 *
	 * @return string
	 */
	protected function getEnclosure(): string {
		return '"';
	}

	/**
	 * Returns the escape
	 *
	 * @return string
	 */
	protected function getEscape(): string {
		return '\\';
	}

	/**
	 * Returns the first row of headers for your file
	 *
	 * @return array
	 */
	protected function getContentHeaders(): array {
		return [];
	}

	/**
	 * Returns an array of rows with data for your file
	 *
	 * @return array
	 */
	abstract protected function getContentRows(): array;

	/**
	 * {@inheritdoc}
	 */
	final protected function getContents(): string {
		$fh_temp = new \ElggTempFile();
		$fh = $fh_temp->open('write');

		$headers = $this->getContentHeaders();
		if (!empty($headers)) {
			fputcsv($fh, $headers, $this->getSeparator(), $this->getEnclosure(), $this->getEscape());
		}
		
		foreach ($this->getContentRows() as $row) {
			fputcsv($fh, $row, $this->getSeparator(), $this->getEnclosure(), $this->getEscape());
		}

		$contents = $fh_temp->grabFile();
		$fh_temp->close();
		
		return $contents;
	}
}
