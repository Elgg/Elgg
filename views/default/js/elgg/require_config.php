<?php

$amdConfig = _elgg_services()->amdConfig->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);
?>
// <script>
if (typeof require == "undefined") {
	var require = <?php echo json_encode($amdConfig); ?>;
}
