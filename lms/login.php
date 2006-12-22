<?php
// log in a user from an lms.

require_once(dirname(dirname(__FILE__)).'/includes.php');
require_once($CFG->dirroot.'lib/lmslib.php');

$installid = optional_param('installid');
$username = optional_param('username');
$firstname = optional_param('firstname');
$lastname = optional_param('lastname');
$email = optional_param('email');
$signature = optional_param('signature');
$url = optional_param('url');

$user = find_lms_user($installid,$username,$signature,'authenticateconfirmation',$firstname,$lastname,$email);
if (is_object($user)) {
    authenticate_account($user->username,$user->password);
    redirect($url);
    exit;
} else {
    echo $user;
}
?>