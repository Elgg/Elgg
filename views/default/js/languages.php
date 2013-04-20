<?php
/**
 * @uses $vars['language']
 * @uses $vars['lc'] if present, client will be sent long expires headers
 */

$language = $vars['language'];
$lastcache = elgg_extract('lc', $vars, 0);

// @todo add server-side caching
if ($lastcache) {
	// we're relying on lastcache changes to predict language changes
	$etag = '"' . md5("$language|$lastcache") .  '"';

	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
	header("Pragma: public", true);
	header("Cache-Control: public", true);
	header("ETag: $etag");

	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
		header("HTTP/1.1 304 Not Modified");
		exit;
	}
}

$all_translations = elgg_get_config('translations');
$translations = $all_translations['en'];

if ($language != 'en') {
	$translations = array_merge($translations, $all_translations[$language]);
}
?>
define(<?php echo json_encode($translations); ?>);
