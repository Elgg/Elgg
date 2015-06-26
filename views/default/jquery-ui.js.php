<?php
$js = file_get_contents(elgg_get_root_path() . "/vendor/bower-asset/jquery-ui/jquery-ui.min.js");

// We must hide define() from jQuery UI in order to reliably run its factory
// function synchronously. If we let it define(), require() does not block and
// allows other scripts to run code before $.ui is available.
?>
//<script>
!function(define) {
	<?= $js ?>
}();
define('jquery-ui');
