<?php
/**
 * Link to download the file
 *
 * @uses $vars['entity']
 */

if (elgg_instanceof($vars['entity'], 'object', 'file')) {
	$download_url = elgg_get_download_url($vars['entity']);
	$size = $vars['entity']->getSize();
	$mime_type = $vars['entity']->getMimeType();
	echo <<<END

	<enclosure url="$download_url" length="$size" type="$mime_type" />
END;
}
