<?php
  /** wall-to-wall viewing page */

  // Load the Elgg framework

require_once("../../includes.php");
global $CFG, $messages;

/*
 * Variable initialisation
 */

$owner = optional_param('owner', page_owner());

$other = required_param('other');

// swap user id's if 'other' is the current session
// this way, a logged-in user won't be prompted to write on their own wall in a wall-to-wall view
if ($other == $_SESSION['userid']) {
  $other = $owner;
  $owner = $_SESSION['userid'];
}

$offset = optional_param('offset', 0);
$limit = optional_param('limit', 10);
    
$title = sprintf(__gettext("%s and %s's Wall-to-wall"), user_info("name", $owner), user_info("name", $other));
    
$wall = commentwall_getwalltowall($owner, $other, $limit, $offset);

$html = commentwall_displaywall_html($wall, true, $other);
$html.= commentwall_display_footer($owner, $limit, $offset);
    
templates_page_output($title, $html);
    
?>