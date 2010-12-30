<?php
/**
 * Main index page
 */

$title = 'CSS Preview Pages';

include dirname(__FILE__) . '/head.php';

?>
<body>
	<div class="elgg-page" style="width: 800px; margin: 20px auto;">
		<h1><?php echo $title; ?></h1>
		<ul class="mtl">
			<li><a href="general.php">General CSS</a></li>
			<li><a href="nav.php">Navigation CSS</a></li>
			<li><a href="forms.php">Form CSS</a></li>
			<li><a href="objects.php">Lists, modules, image blocks CSS</a></li>
			<li><a href="grid.php">Grid CSS</a></li>
			<li><a href="widgets.php">Widgets CSS</a></li>
		</ul>
	</div>
</body>
</html>