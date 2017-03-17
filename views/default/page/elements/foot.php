<?php

$elgg_init = elgg_view('initialize_elgg.js');
echo "<script>$elgg_init</script>";

// TODO(evan): "head" JS and "footer" JS distinction doesn't make sense anymore
// TODO(evan): Introduce new "async" location for scripts allowed in head?
$js = elgg_get_loaded_js('head');
foreach ($js as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

$js = elgg_get_loaded_js('footer');
foreach ($js as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

$deps = _elgg_services()->amdConfig->getDependencies();
?>
<script>
require(<?= json_encode($deps, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>);
</script>
