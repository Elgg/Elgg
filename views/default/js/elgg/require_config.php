<?php

$amdConfig = _elgg_services()->amdConfig->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);

// require is currently an Elgg shim. Convert it to a RequireJS config
?>
// <script>
require = <?php echo json_encode($amdConfig); ?>;
require.callback = function() {
	// process queue
	if (!window._require_queue) {
		if (window.console) {
			console.log('Elgg\'s require() shim is not defined. Do not override the view "page/elements/head".');
		}
	} else {
		while (_require_queue.length) {
			require.apply(null, _require_queue.shift());
		}
		delete window._require_queue;
	}
};