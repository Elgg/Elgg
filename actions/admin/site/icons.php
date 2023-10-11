<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;

$site = elgg_get_site_entity();

if (get_input('icon_remove')) {
	$site->deleteIcon();
	$site->deleteIcon('favicon');
} else {
	$site->saveIconFromUploadedFile('icon');
	$site->saveIconFromUploadedFile('icon', 'favicon');
}

$remove_zip = function() {
	elgg_delete_directory(elgg_get_data_path() . 'fontawesome');
	elgg_remove_config('font_awesome_zip');
	
	// view locations and simplecache need to be updated
	elgg_invalidate_caches();
};

$zip = elgg_get_uploaded_file('font_awesome_zip');
if (get_input('remove_font_awesome_zip')) {
	$remove_zip();
} elseif ($zip instanceof UploadedFile && extension_loaded('zip')) {
	$archive = new \ZipArchive();
	if ($archive->open($zip->getPathname()) !== true) {
		return elgg_error_response(elgg_echo('admin:site_icons:font_awesome:zip:error'));
	}
	
	// remove existing upload
	$remove_zip();
	
	// extraction could take some time
	set_time_limit(0);
	
	$base_folder = elgg_get_data_path() . 'fontawesome';
	$archive->extractTo($base_folder);
	
	// rename folder for easier mapping in the future
	foreach (glob($base_folder . '/*', GLOB_ONLYDIR) as $item) {
		// rename the first folder we find
		rename($item, $base_folder . '/webfont');
		break;
	}
	
	elgg_save_config('font_awesome_zip', $zip->getClientOriginalName());
}
	
return elgg_ok_response('', elgg_echo('save:success'));
