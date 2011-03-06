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
		<div class="elgg-inner">
			<div class="elgg-grid elgg-grid-walledgarden">
				<div class="elgg-col elgg-col-1of2">
					<h1 class="elgg-heading-walledgarden">
						<?php
							echo elgg_echo('walled_garden:welcome');
							echo ': <br/>';
							echo $title;
						?>
					</h1>
				</div>
				<div class="elgg-col elgg-col-1of2">
					<?php echo $vars['body']; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo elgg_view('footer/analytics'); ?>
</body>
</html>