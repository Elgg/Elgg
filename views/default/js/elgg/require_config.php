<?php

$amd = _elgg_services()->amdConfig;

try {
	$amd->applyDecorations();
} catch (RuntimeException $e) {
	echo "console.log(" . json_encode($e->getMessage()) . ");\n";
}

$amdConfig = $amd->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);
?>
// <script>
if (typeof require == "undefined") {
	var require = <?php echo json_encode($amdConfig); ?>;
}
