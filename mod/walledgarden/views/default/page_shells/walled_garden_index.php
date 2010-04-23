<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['config'] The site configuration settings, imported
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} else if (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}

echo elgg_view('page_elements/html_begin', $vars);
echo elgg_view('messages/list', array('object' => $vars['sysmessages']));
?>	
	<style type="text/css">
	/*
body {background: white;}
	#walledgardenlogin {
		position:absolute;
		bottom:0;
		left:280px;
		height:250px;
		width:272px;
	}
	#walledgardenintro {
		position: absolute;
		bottom:15px;
		left:25px;
		height:215px;
		width:232px;
		padding:10px;
		background-color: white;
		-webkit-border-radius: 8px; 
		-moz-border-radius: 8px;
	}
	#walledgardenlogin #login-box {
		background: none;
	}
	#walledgardenlogin #login-box h2 {
		display:none;
	}
	#walledgardenlogin #login-box form {height:224px;padding:10px 10px 0;}
	
	.messages, .messages_error {
		position: relative;
		margin: auto;
	}
*/
	
	</style>
<?php	
		echo "<div style='margin:20px auto;position:relative;padding:20px;width:523px;height:355px;background: url({$vars['url']}_graphics/login_back.gif) no-repeat top left;'>";				
		echo "<div id='walledgardenintro'><h1>Welcome to:<br />" . $title . "</h1></div>";
		echo "<div id='walledgardenlogin' class='whereamIused'>";
		echo $vars['body'];
		echo "</div></div>";
		echo elgg_view('page_elements/html_end', $vars);
?>

