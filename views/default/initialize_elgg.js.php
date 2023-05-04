<?php
/**
 * Initialize Elgg's js lib with the uncacheable data
 */
?>

var elgg = <?php echo json_encode(_elgg_get_js_page_data($vars)); ?>;
<?php
