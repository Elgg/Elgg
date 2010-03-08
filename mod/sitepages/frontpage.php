<?php
/**
 * Elgg custom frontpage
 */

// Load Elgg engine will not include plugins
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
    
global $CONFIG;
    
$page_contents = elgg_get_entities(array('type' => 'object', 'subtype' => 'frontpage', 'limit' => 1));
if($page_contents){
	foreach($page_contents as $pc){
		 $css = "<style>" . $pc->title . "</style>";
		 $frontContents = $pc->description;
	 }
}
	
// Set title
$title = $CONFIG->sitename;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ElggRelease" content="<?php echo $release; ?>" />
	<meta name="ElggVersion" content="<?php echo $version; ?>" />
	<title><?php echo $title; ?></title>
	<link REL="SHORTCUT ICON" HREF="<?php echo $CONFIG->wwwroot; ?>_graphics/favicon.ico">

	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery-1.4.min.js"></script> 
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery-ui-1.7.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/custom-form-elements.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>_css/js.php?lastcache=<?php echo $CONFIG->lastcache; ?>&amp;js=initialise_elgg&amp;viewtype=<?php echo $vars['view']; ?>"></script>

	<?php
		echo $feedref;
		//custom css - need to cache this. It is here instead of extending the main css
		//as we don't want it appearing anywhere else throughout the site, only the frontpage.
		echo $css; 
		echo elgg_view('metatags',$vars);
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		});
	</script>

<?php
	global $pickerinuse;
	if (isset($pickerinuse) && $pickerinuse == true) {
?>
	<!-- only needed on pages where we have friends collections and/or the friends picker -->
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery.easing.1.3.packed.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>_css/js.php?lastcache=<?php echo $vars['config']->lastcache; ?>&amp;js=friendsPickerv1&amp;viewtype=<?php echo $vars['view']; ?>"></script>
<?php
	}
?>
	<!-- include the default css file -->
	<link rel="stylesheet" href="<?php echo $CONFIG->wwwroot; ?>_css/css.css?lastcache=<?php echo $CONFIG->lastcache; ?>&amp;viewtype=<?php echo $CONFIG->view; ?>" type="text/css" />
</head>
<body>
<?php echo elgg_view('page_elements/elgg_topbar', $vars); ?>
<?php echo elgg_view('page_elements/elgg_header', $vars); ?>
<?php 
	echo parse_frontpage($frontContents); 
?>
<?php echo elgg_view('page_elements/elgg_footer', $vars); ?>
<?php echo elgg_view('page_elements/html_end', $vars); ?>