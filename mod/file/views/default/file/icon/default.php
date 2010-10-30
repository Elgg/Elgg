<?php

	if ($vars['size'] == 'large') {
		$ext = '_lrg';
	} else {
		$ext = '';
	}
	echo elgg_get_site_url()."mod/file/graphics/icons/general{$ext}.gif";

?>