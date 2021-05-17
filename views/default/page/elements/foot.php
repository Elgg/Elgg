<?php

$elgg_init = elgg_view('initialize_elgg.js');
echo "<script>$elgg_init</script>";

// TODO(evan): "head" JS and "footer" JS distinction doesn't make sense anymore
// TODO(evan): Introduce new "async" location for scripts allowed in head?
$js = elgg_get_loaded_external_files('js', 'head');
foreach ($js as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

$js = elgg_get_loaded_external_files('js', 'footer');
foreach ($js as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

$deps = _elgg_services()->amdConfig->getDependencies();

// Load modules with elgg_import().
$imports = [];
$deps = array_filter($deps, function ($dep) use (&$imports) {
	if (preg_match('~\\.mjs$~', $dep, $m)) {
		$imports[] = $dep;
		return false;
	}

	return true;
});

?>
<script>
require(<?= json_encode($deps, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>);
<?php foreach ($imports as $uri): ?>
elgg_import(<?= json_encode($uri, JSON_UNESCAPED_SLASHES) ?>);
<?php endforeach; ?>
</script>
