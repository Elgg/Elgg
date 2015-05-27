<?php

$elgg_init = elgg_view('js/initialize_elgg');
echo "<script>$elgg_init</script>";

// TODO(evan): "head" JS and "footer" JS distinction doesn't make sense anymore
// TODO(evan): Introduce new "async" location for scripts allowed in head?
$js = elgg_get_loaded_js('head');
foreach ($js as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

$js = elgg_get_loaded_js('footer');
foreach ($js as $url) {
	echo elgg_format_element('script', array('src' => $url));
}

$requires = json_encode(_elgg_services()->amdConfig->getDependencies());
echo "<script>require($requires)</script>";
