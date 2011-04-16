<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$site = elgg_get_site_entity();
$title = $site->name;

?>
<html>
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<div class="elgg-page-body">
		<div id="elgg-walledgarden">
			<div id="elgg-walledgarden-intro">
				<h1 class="elgg-heading-walledgarden">
					<?php
						echo elgg_echo('walled_garden:welcome');
						echo ': <br/>';
						echo $title;
					?>
				</h1>
			</div>
			<div id="elgg-walledgarden-login">
				<?php echo $vars['body']; ?>
			</div>
		</div>
		<div id="elgg-walledgarden-bottom"></div>
	</div>
</div>
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>