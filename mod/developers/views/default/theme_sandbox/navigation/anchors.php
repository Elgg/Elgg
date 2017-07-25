<?php


$anchor1 = elgg_view('output/url', [
	'text' => 'Simple anchor',
	'href' => '#anchor',
]);

$anchor2 = elgg_view('output/url', [
	'text' => 'Icon anchor',
	'href' => 'http://elgg.org/',
	'icon' => 'external-link',
	'target' => '_blank',
]);

$query = [];
for ($i=0; $i<50; $i++) {
	$query['q'][$i] = generate_random_cleartext_password();
}

$anchor3 = elgg_view('output/url', [
	'href' => elgg_http_add_url_query_elements(current_page_url(), $query),
	'icon' => 'globe',
	'title' => 'Very long URL',
]);

$anchor4 = elgg_view('output/url', [
	'href' => '#anchor2',
	'text' => 'Image anchor',
	'icon' => elgg_view('output/img', [
		'src' => elgg_get_simplecache_url('graphics/favicon-16.png'),
		'alt' => 'favicon',
	]),
]);

$anchor5 = elgg_view('output/url', [
	'href' => '#anchor3',
	'text' => 'Anchor with badge',
	'icon' => 'bank',
	'badge' => '$500',
]);

?>
<p>Lorem ipsum dolor sit amet (<?= $anchor1 ?>)
adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec
faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam <?= $anchor5 ?> libero
nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent
mattis, massa quis luctus <?= $anchor2 ?>, turpis mi volutpat justo, eu
volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus
eget sapien <?= $anchor3 ?> nonummy. Mauris a ante. Suspendisse quam sem,
consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue
quis tellus <?= $anchor4 ?>.</p>


