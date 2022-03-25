<?php

echo elgg_format_element('script', [], elgg_view('initialize_elgg.js'));

$js = elgg_get_loaded_external_files('js', 'footer');
foreach ($js as $url) {
	echo elgg_format_element('script', ['src' => $url]);
}

$deps = _elgg_services()->amdConfig->getDependencies();
?>
<script>
require(<?= json_encode($deps, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>);
</script>
