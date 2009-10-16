<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} else if (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}
?>

<?php echo elgg_view('page_elements/header', $vars); ?>
<?php echo elgg_view('page_elements/elgg_topbar', $vars); ?>
<?php echo elgg_view('page_elements/header_contents', $vars); ?>

<!-- main contents -->

<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>


<!-- canvas -->
<div id="layout_canvas">

<?php echo $vars['body']; ?>

<div class="clearfloat"></div>
</div><!-- /#layout_canvas -->

<?php
if(isloggedin()){
?>
	<!-- spotlight -->
	<?php echo elgg_view('page_elements/spotlight', $vars); ?>
<?php
}
?>

<!-- footer -->
<?php echo elgg_view('page_elements/footer', $vars); ?>