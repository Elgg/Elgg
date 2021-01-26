<?php

$amdConfig = _elgg_services()->amdConfig->getConfig();

// Deps are loaded in page/elements/foot with require([...])
unset($amdConfig['deps']);

// Note we don't process the require() queue yet because it may require('elgg')
// and we have to load that synchronously a little later

?>
// <script>
require = <?php echo json_encode($amdConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>;
