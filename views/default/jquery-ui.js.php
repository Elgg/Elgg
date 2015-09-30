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

// The datepicker language modules depend on "../datepicker", so to avoid RequireJS from
// trying to load that, we define it manually here. The lang modules have names like
// "jquery-ui/i18n/datepicker-LANG.min" and these views are mapped in /elgg-config/views.php
define('jquery-ui/datepicker', jQuery.datepicker);
