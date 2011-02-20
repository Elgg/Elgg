<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 * @uses $vars['title']   Title string
 */

$admin_title = elgg_get_site_entity()->name . ' ' . elgg_echo('admin');

$view_site = elgg_view('output/url', array(
	'href' => elgg_get_site_url(),
	'text' => elgg_echo('admin:view_site'),
));
$logout = elgg_view('output/url', array(
	'href' => 'action/logout',
	'text' => elgg_echo('logout'),
));
?>

<div class="elgg-page-header">
	<div class="elgg-inner clearfix">
		<h1 class="elgg-heading-site">
			<a href="<?php echo elgg_get_site_url(); ?>pg/admin/">
				<?php echo $admin_title; ?>
			</a>
		</h1>
		<ul class="elgg-menu-user">
			<li><?php echo elgg_echo('admin:loggedin', array(elgg_get_logged_in_user_entity()->name)); ?></li>
			<li><?php echo $view_site; ?></li>
			<li><?php echo $logout; ?></li>
		</ul>
	</div>
</div>

<?php
// @todo clean up system messages code
$messages = null;
if (count_messages()) {
	// get messages - try for errors first
	$messages = system_messages(NULL, "error");
	if (count($messages["error"]) == 0) {
		// no errors so grab rest of messages
		$messages = system_messages(null, "");
	} else {
		// we have errors - clear out remaining messages
		system_messages(null, "");
	}
}
echo elgg_view('page/elements/messages', array('object' => $messages));
?>

<div class="elgg-page-body">
	<div class="elgg-sidebar clearfix">
		<?php
			echo elgg_view('admin/sidebar/top', $vars);
			echo elgg_view('layout/shells/admin/menu', $vars);
			echo elgg_view('admin/sidebar/bottom', $vars);
		?>
	</div>
	<div class="elgg-main elgg-body">
		<div class="elgg-head">
		<?php
			if (isset($vars['title'])) {
				echo elgg_view_title($vars['title']);
			}
		?>
		</div>
		<?php
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
</div>
<div class="elgg-page-footer">
</div>
