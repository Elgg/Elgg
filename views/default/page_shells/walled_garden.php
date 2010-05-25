<?php
/**
 * 
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

// Set title
if (empty($vars['title'])) {
	$title = $vars['config']->sitename;
} elseif (empty($vars['config']->sitename)) {
	$title = $vars['title'];
} else {
	$title = $vars['config']->sitename . ": " . $vars['title'];
}

echo elgg_view('page_elements/html_begin', $vars);

$view = elgg_view('messages/list', array('object' => $vars['sysmessages']));

echo "<div id='walledgarden_sysmessages'>$view</div>";       
echo '<div id="walledgarden_container"><div id="walledgarden" class="clearfloat">';
echo "<div class=\"walledgardenintro clearfloat\"><h1>Welcome to:<br />$title</h1></div>";
echo "<div class=\"walledgardenlogin clearfloat\">{$vars['body']}</div>";
echo '</div>';
echo '<div id="walledgarden_bottom"></div>';
echo '</div>';

echo elgg_view('page_elements/html_end', $vars);
