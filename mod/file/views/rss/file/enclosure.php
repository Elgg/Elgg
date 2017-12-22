<?php
/**
 * Link to download the file
 *
 * @uses $vars['entity']
 */

if ($vars['entity'] instanceof ElggFile && $vars['entity']->canDownload()) {
	$download_url = $vars['entity']->getDownloadURL();
	$size = $vars['entity']->getSize();
	$mime_type = $vars['entity']->getMimeType();
	echo <<<END

	<enclosure url="$download_url" length="$size" type="$mime_type" />
END;
}
