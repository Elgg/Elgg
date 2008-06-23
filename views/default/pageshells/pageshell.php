<?php

	/**
	 * Elgg pageshell
	 * The standard HTML page shell that everything else fits into
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

?>

<?php echo elgg_view('page_elements/header', $vars); ?>

<!-- main contents -->
    
<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>



<!-- left sidebar -->
<div id="layout_sidebar_left">
<div id="wrapper_sidebar_left">
    
<?php 
	//if logged in, display the toolbar, else display a sample ad
	if(isloggedin()){
		echo elgg_view("navigation/toolbox", $vars); 
	}else{
		echo "<img src=\"{$vars['url']}_graphics/skyscraper.gif\" alt=\"sample skyscraper ad\" />";
	}

?>

<?php echo $vars['sidebar']; ?>

</div><!-- /#wrapper_sidebar_left -->
</div><!-- /#layout_sidebar_left -->


<!-- canvas -->
<div id="layout_canvas">
<h2 class="page_title"><?php echo $vars['title']; ?></h2>
<?php echo $vars['body']; ?>
<div class="clearfloat"></div>
</div><!-- /#layout_canvas -->



<!-- spotlight -->
<?php echo elgg_view('page_elements/spotlight', $vars); ?>


<!-- footer -->
<?php echo elgg_view('page_elements/footer', $vars); ?>
