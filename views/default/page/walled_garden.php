<?php
/**
 * 
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
$site_title = elgg_get_config('sitename');
if (empty($vars['title'])) {
	$title = $site_title;
} else if (empty($site_title)) {
	$title = $vars['title'];
} else {
	$title = $site_title . ": " . $vars['title'];
}

// @todo - move the css below into it's own style-sheet 
// that is called when running as a private network
?>
<html>
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
	<style type="text/css">
	/* ***************************************
		WalledGarden
	*************************************** */
	.elgg-grid-walledgarden {
		margin:100px auto 0 auto;
		padding:0;
		width:600px;
		text-align: left;
		word-wrap:break-word;
		background: gray;
	}
	
	.elgg-grid-walledgarden > .elgg-col {
		background: white;
	}
	
	.elgg-heading-walledgarden {
		color:#666666;
		margin-top:80px;
		line-height: 1.1em;
	}
	
	.walledgardenlogin h2 {
		color:#666666;
		border-bottom:1px solid #CCCCCC;
		margin-bottom:5px;
		padding-bottom:5px;
	}

		
	</style>
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
					<h1 class="elgg-heading-walledgarden">Welcome to:<br /><?php echo $title; ?></h1>
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