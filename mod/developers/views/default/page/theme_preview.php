<?php
/**
 * Page shell for theme preview
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		<ul class="elgg-system-messages">
			<li class="hidden"></li>
		</ul>
	</div>
	<div class="elgg-page-header">
		<div class="elgg-inner">
			<h1 class="elgg-heading-site">Theme Sandbox</h1>
			<?php
				if (get_input("site_menu", false)) {
					echo elgg_view_menu('site');
				}
			?>
		</div>
	</div>
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/body', $vars); ?>
		</div>
	</div>
</div>
</body>
</html>