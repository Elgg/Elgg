<?php
/**
 * Main index for theme preview
 */

$url = "pg/theme_preview";
$url = elgg_normalize_url($url);

echo <<<HTML
<div class="elgg-page mal">
	<h1 class="mbl"><a href="$url/index">Index</a></h1>
	<ul class="mtl">
		<li><a href="$url/general">General CSS</a></li>
		<li><a href="$url/nav">Navigation CSS</a></li>
		<li><a href="$url/forms">Form CSS</a></li>
		<li><a href="$url/objects">Lists, modules, image blocks CSS</a></li>
		<li><a href="$url/grid">Grid CSS</a></li>
		<li><a href="$url/widgets">Widgets CSS</a></li>
		<li><a href="$url/icons">Icons CSS</a></li>
	</ul>
</div>
HTML;
