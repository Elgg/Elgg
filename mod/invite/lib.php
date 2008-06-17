<?php
/*
 * lib.php
 *
 * Created on Apr 18, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 */

 function invite_init(){
  global $CFG, $function;
  // Actions
      $function['invite:init'][] = $CFG->dirroot . "mod/invite/lib/invite_config.php";
      $function['invite:init'][] = $CFG->dirroot . "mod/invite/lib/invite_actions.php";

  // Introductory text
      $function['content:invite:invite'][] = $CFG->dirroot . "mod/invite/lib/content_invite.php";

  // Allow user to invite a friend
      $function['invite:invite'][] = $CFG->dirroot . "mod/invite/lib/invite.php";
      $function['invite:join'][] = $CFG->dirroot . "mod/invite/lib/invite_join.php";

  // Allow a new user to sign up
      $function['join:no_invite'][] = $CFG->dirroot . "mod/invite/lib/join_noinvite.php";

  // Allow the user to request a new password
      $function['invite:password:request'][] = $CFG->dirroot . "mod/invite/lib/password_request.php";
      $function['invite:password:new'][] = $CFG->dirroot . "mod/invite/lib/new_password.php";

  // Default pages messages
    $function['invite:register:default:welcome'][] = $CFG->dirroot . "mod/invite/lib/invite_register_welcome.php";
    $function['invite:join:default:welcome'][] = $CFG->dirroot . "mod/invite/lib/invite_join_welcome.php";
    $function['invite:join:default:footer'][] = $CFG->dirroot . "mod/invite/lib/invite_join_footer.php";
    $function['invite:join:default:mailwithpass'][] = $CFG->dirroot . "mod/invite/lib/invite_join_mailwithpass.php";
    $function['invite:join:default:mailwithoutpass'][] = $CFG->dirroot . "mod/invite/lib/invite_join_mailwithoutpass.php";

 }

function invite_pagesetup() {
  global $PAGE, $CFG;

    if (defined('context') && context == 'network' && isloggedin()) {
        if ($CFG->publicinvite && !maxusers_limit()) {
            $PAGE->menu_sub[] = array( 'name' => 'invite:friend',
                                       'html' => a_href(get_url(null, 'invite::invite'),
                                                        __gettext("Invite a friend")));
        }
    }
}

function invite_url($object_id, $object_type) {
    $url = null;

    switch ($object_type) {
        case 'invite::':
        case 'invite::invite':
            global $CFG;
            $url = $CFG->wwwroot . 'mod/invite/';
            break;
    }

    return $url;
}
?>
