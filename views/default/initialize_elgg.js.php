<?php
/**
 * Initialize Elgg's js lib with the uncacheable data
 */
?>

var elgg = <?php echo json_encode(_elgg_get_js_page_data()); ?>;
<?php
// note: elgg.session.user needs to be wrapped with elgg.ElggUser, but this class isn't
// defined yet. So this is delayed until after the classes are defined, in js/lib/session.js
