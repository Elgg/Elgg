<?php
require_once(dirname(dirname(__FILE__))."/../../includes.php");
global $CFG;

$username = optional_param('username');

$ok = false;

if (validate_username($username)) {
  $ok = true;
  $username = strtolower($username);
  if (!record_exists('users','username',$username)) {
    $ok = true; 
  } else {
    $ok = false;
  }
 }


if($ok) {
  $image = file_get_contents(dirname(__FILE__).'/good.png');
 } else {
  $image = file_get_contents(dirname(__FILE__).'/bad.png');
 }


header('Content-Type: image/png');		 
print $image;
?>