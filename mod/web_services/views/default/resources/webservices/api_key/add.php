<?php
/**
 * Create a new API key object
 */

use Elgg\WebServices\ApiKeyForm;

$title = elgg_echo('add:object:api_key');

$form = new ApiKeyForm();

$content = elgg_view_form('webservices/api_key/edit', [], $form());

if (elgg_is_xhr()) {
	// in the lightbox
	echo elgg_view_module('info', $title, $content);
} else {
	echo elgg_view_page($title, [
		'content' => $content,
		'filter' => false,
	]);
}
