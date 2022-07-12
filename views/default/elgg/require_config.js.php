<?php

$amdConfig = _elgg_services()->amdConfig->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);

// Note we don't process the require() queue yet because it may require('elgg')
// and we have to load that synchronously a little later

?>
// <script>
require = <?php echo json_encode($amdConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>;
require.onNodeCreated = function(node, config, module, path) {
	// SRI is populated on first use and provided by a elgg.data hook callback
	module = module + '.js';
	if (elgg.data.sri && elgg.data.sri[module]) {
		node.setAttribute('integrity', elgg.data.sri[module]);
		node.setAttribute('crossorigin', 'anonymous');
	}
};
