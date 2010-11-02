<?php
/**
 * 
 */

$page_type = get_input('page_type', 'frontsimple');
if (!$sitepage = sitepages_get_sitepage_object($page_type)) {
	$sitepage = sitepages_create_sitepage_object($page_type);
}

switch ($page_type) {
	case 'about':
	case 'terms':
	case 'privacy':
		$content = get_input('sitepages_content', '', FALSE);
		if (empty($content)) {
			register_error(elgg_echo('sitepages:blank'));
			forward(REFERER);
		}
		
		//$sitepage->title = $page_type;
		$sitepage->description = $content;
		$sitepage->tags = string_to_tag_array(get_input('sitepages_tags'));
		
		break;
	case 'seo':
		$sitepage->title = get_input('metatags', '', FALSE);;
		$sitepage->description = get_input('description', '', FALSE);
		
		break;
	case 'frontsimple':
	default:
		$params = get_input('params', array());
		set_plugin_setting('ownfrontpage', $params['ownfrontpage'], 'sitepages');
		
		$sitepage->welcometitle = get_input('welcometitle', '', FALSE);
		$sitepage->welcomemessage = get_input('welcomemessage', '', FALSE);
		$sitepage->sidebartitle = get_input('sidebartitle', '', FALSE);
		$sitepage->sidebarmessage = get_input('sidebarmessage', '', FALSE);
		
		break;
}

if ($sitepage->save()) {
	system_message(elgg_echo('sitepages:posted'));
} else {
	register_error(elgg_echo('sitepages:error'));
}

forward(REFERER);
