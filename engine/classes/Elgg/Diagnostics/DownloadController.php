<?php

namespace Elgg\Diagnostics;

/**
 * Elgg diagnostics download
 *
 * @since 7.0
 */
class DownloadController extends \Elgg\Controllers\DownloadAction {

	/**
	 * {@inheritdoc}
	 */
	protected function getFilename(): string {
		return 'elggdiagnostic.txt';
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getMimeType(): string {
		return 'text/plain';
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getContents(): string {
		// generating report could take some time
		set_time_limit(0);

		$result = elgg_echo('diagnostics:header', [date('r'), elgg_get_logged_in_user_entity()?->getDisplayName()]);
		return (string) elgg_trigger_event_results('diagnostics:report', 'system', [], $result);
	}
}
