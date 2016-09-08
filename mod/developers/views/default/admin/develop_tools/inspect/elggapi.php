<?php
/**
 * WARNING! This view is internal and may change at any time.
 * Plugins should not use/modify/override this view.
 */

$data = $vars['data'];

$module = 'admin/develop_tools/inspect/elggapi';
elgg_require_js($module);
elgg_register_css($module, elgg_get_simplecache_url("$module.css"));
elgg_load_css($module);

$h = function ($s) {
	return htmlspecialchars($s, ENT_QUOTES);
};

echo "<ul class='elgg-menu elgg-tabs'>";
foreach ($data->sections as $section) {
	$name = elgg_echo("developers:api:{$section->id}:name");

	echo "<li><a href='#{$section->id}'>{$h($name)}</a></li>";
}
echo "</ul>";

foreach ($data->sections as $section) {
	echo "<section class='elgg-api-section' data-type='{$section->type}'>";

	$heading = $h(elgg_echo("developers:api:{$section->id}:heading"));
	if (!empty($section->url)) {
		$heading = elgg_view('output/url', [
			'text' => $heading,
			'href' => $section->url,
			'is_trusted' => true,
		]);
	}

	echo "<h3 class='mtm mbm' id='{$section->id}'>$heading</h3>";

	$intro = elgg_echo("developers:api:{$section->id}:intro");
	if ($intro) {
		echo "<div class='elgg-api-intro'>$intro</div>";
	}

	foreach ($section->items as $item) {
		if ($item->type === 'function') {
			?>
			<div class="elgg-api-func" id="<?= $item->id ?>">
				<h4><?= $h($item->name) ?> <span class="elgg-api-func-args"><?= $h($item->args) ?></span></h4>
				<p class='elgg-api-func-summary'><?= $h($item->summary) ?></p>
				<section>
					<pre><?= $item->doc_html ?></pre>
				</section>
			</div>
			<?php
		}
	}

	echo "</section>";
}
