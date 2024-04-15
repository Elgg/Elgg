<?php
/**
 * Elgg install body
 *
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] Array of system status messages
 */

use Elgg\Project\Paths;

$isElggAtRoot = Paths::elgg() === Paths::project();
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
			<aside class="elgg-sidebar-alt">
				<header class="elgg-page-header">
					<?php
					echo elgg_format_element('img', [
						'src' => elgg_get_site_url() . $elggSubdir . 'views/default/graphics/elgg_logo.png',
						'alt' => 'Elgg',
					]);
					?>
				</header>
				<?php echo elgg_view('page/elements/sidebar', $vars); ?>
			</aside>
			<main class="elgg-body">
				<?php
					echo elgg_format_element('h1', [], (string) elgg_extract('title', $vars));
					echo elgg_view('page/elements/messages', ['object' => elgg_extract('sysmessages', $vars)]);
					echo elgg_extract('body', $vars);
				?>
			</main>
		</div>
	</div>
	<footer class="elgg-page-footer">
		<?php echo $footer_menu; ?>
	</footer>
</div>
<?php

echo elgg_format_element('style', [], elgg_view('install.css'));
echo elgg_format_element('script', [], elgg_view('install.js'));
