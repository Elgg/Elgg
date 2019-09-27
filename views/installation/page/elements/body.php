<?php
/**
 * Elgg install body
 *
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] Array of system status messages
 */

use Elgg\Filesystem\Directory as ElggDirectory;

$isElggAtRoot = Elgg\Application::elggDir()->getPath() === ElggDirectory\Local::projectRoot()->getPath();
$elggSubdir = $isElggAtRoot ? '' : 'vendor/elgg/elgg/';

$footer_menu_items = elgg_format_element('li', [], elgg_view('output/url', [
	'text' => elgg_echo('install:footer:instructions'),
	'href' => 'http://learn.elgg.org/en/stable/intro/install.html',
	'target' => '_blank',
]));
$footer_menu_items .= elgg_format_element('li', [], elgg_view('output/url', [
	'text' => elgg_echo('install:footer:troubleshooting'),
	'href' => 'http://learn.elgg.org/en/stable/intro/install.html#troubleshooting',
	'target' => '_blank',
]));
$footer_menu_items .= elgg_format_element('li', [], elgg_view('output/url', [
	'text' => elgg_echo('install:footer:community'),
	'href' => 'https://elgg.org/discussion/all',
	'target' => '_blank',
]));

$footer_menu = elgg_format_element('ul', ['class' => 'elgg-menu elgg-menu-footer'], $footer_menu_items);

?>

<div class="elgg-page">
	<div class="elgg-page-body">
		<div class="elgg-layout">
			<div class="elgg-layout-columns">
				<aside class="elgg-sidebar-alt" role="complementary">
					<header class="elgg-page-header" role="banner">
						<img src="<?= elgg_get_site_url() . $elggSubdir; ?>views/default/graphics/elgg_logo.png" alt="Elgg" />
					</header>
					<?php echo elgg_view('page/elements/sidebar', $vars); ?>
				</aside>
				<main class="elgg-body" role="main">
					<h1><?php echo elgg_extract('title', $vars); ?></h1>
					<?php echo elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]); ?>
					<?php echo elgg_extract('body', $vars); ?>
				</main>
			</div>
		</div>
	</div>
	<footer class="elgg-page-footer" role="contentinfo">
		<?php echo $footer_menu; ?>
	</footer>
</div>
<style>
	<?= elgg_view('install.css') ?>
</style>
<script>
	<?= elgg_view('install.js') ?>
</script>
