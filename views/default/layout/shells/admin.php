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
		<h1 class="elgg-site-title">
			<a href="<?php echo elgg_get_site_url(); ?>">
				<?php echo $vars['config']->sitename; echo " " . elgg_echo('admin'); ?>
			</a>
		</h1>
		<ul class="elgg-user-menu">
			<li><?php echo elgg_echo('admin:loggedin', array(get_loggedin_user()->name)); ?></li>
			<li><?php echo $view_site; ?></li>
			<li><?php echo $logout; ?></li>
		</ul>
	</div>
</div>

<div class="elgg-page-body">
	<div class="elgg-inner clearfix">
		<div class="elgg-sidebar clearfix">
			<?php
				echo elgg_view('layout/elements/sidebar', $vars);
			?>
		</div>
		<div class="elgg-main elgg-body">
			<?php
				if (isset($vars['title'])) {
					echo elgg_view_title($vars['title']);
				}

				if (isset($vars['content'])) {
					echo $vars['content'];
				}
			?>
		</div>
	</div>
</div>
<div class="elgg-page-footer"></div>