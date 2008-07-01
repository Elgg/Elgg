<?php

	/**
	 * Elgg pageshell when logged out
	 * The standard HTML header that displays across the site
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */
	 
	 // Set title
		if (empty($vars['title'])) {
			$title = $vars['config']->sitename;
		} else if (empty($vars['config']->sitename)) {
			$title = $vars['title'];
		} else {
			$title = $vars['config']->sitename . ": " . $vars['title'];
		}
		
		global $autofeed;
		if (isset($autofeed) && $autofeed == true) {
			$url = full_url();
			if (substr_count($url,'?')) {
				$url .= "&view=rss";
			} else {
				$url .= "?view=rss";
			}
			$feedref = <<<END
			
	<link rel="alternate" type="application/rss+xml" title="RSS" href="{$url}" />
			
END;
		} else {
			$feedref = "";
		}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $title; ?></title>
	<!-- include links to jQuery here? -->
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery-1.2.6.pack.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>vendors/jquery/jquery-ui-personalized-1.5.packed.js"></script>
	<script type="text/javascript" src="<?php echo $vars['url']; ?>javascript/initialise_elgg.js"></script>
	<!-- include the default css file -->
	<link rel="stylesheet" href="<?php echo $vars['url']; ?>_css/css.css" type="text/css" />
	<?php 

		echo $feedref;
		echo elgg_view('metatags',$vars); 
		
	?>
</head>

<body>

<?php echo elgg_view('page_elements/elgg_topbar', $vars); ?>
	
<div id="page_container">
<div id="page_wrapper">

<div id="layout_header">
<div id="wrapper_header">
	<!-- display the page title -->
	<h1><a href="<?php echo $vars['url']; ?>"><?php echo $vars['config']->sitename; ?></a></h1>
</div><!-- /#wrapper_header -->
</div><!-- /#layout_header -->
