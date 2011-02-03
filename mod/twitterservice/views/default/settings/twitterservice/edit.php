<?php
/**
 * 
 */
$insert_view = elgg_view('twittersettings/extend');

$consumer_key_string = elgg_echo('twitterservice:consumer_key');
$consumer_key_view = elgg_view('input/text', array(
	'internalname' => 'params[consumer_key]',
	'value' => $vars['entity']->consumer_key,
	'class' => 'text_input',
));

$consumer_secret_string = elgg_echo('twitterservice:consumer_secret');
$consumer_secret_view = elgg_view('input/text', array(
	'internalname' => 'params[consumer_secret]',
	'value' => $vars['entity']->consumer_secret,
	'class' => 'text_input',
));

$sign_on_with_twitter_string = elgg_echo('twitterservice:login');
$sign_on_with_twitter_view = elgg_view('input/dropdown', array(
	'internalname' => 'params[sign_on]',
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no'),
	),
	'value' => $vars['entity']->sign_on ? $vars['entity']->sign_on : 'no',
));

$settings = <<<__HTML
<div id="twitterservice_site_settings">
	<div>$insert_view</div>
	<div>$consumer_key_string $consumer_key_view</div>
	<div>$consumer_secret_string $consumer_secret_view</div>
	<div>$sign_on_with_twitter_string $sign_on_with_twitter_view</div>
</div>
__HTML;

echo $settings;
