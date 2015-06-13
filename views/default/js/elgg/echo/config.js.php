<?php
/**
 * Generates list of language "packs"
 */

$definition = [
	'packs' => _elgg_services()->languagePacks->buildConfig(),
];

?>
//<script>
define(<?php echo json_encode($definition); ?>);
