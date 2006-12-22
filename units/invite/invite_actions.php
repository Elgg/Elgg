<?php
global $USER;
global $CFG;
// Kill all old invitations

delete_records_select('invitations',"added < ?",array(time() - (86400 * 7)));

// Get site name

$sitename = sitename;

$action = optional_param('action');
switch ($action) {
        
    // Add a new invite code
     case "invite_invite":        
         $invite = new StdClass;
         $invite->name = trim(optional_param('invite_name'));
         $invite->email = trim(optional_param('invite_email'));
         if (!empty($invite->name) && !empty($invite->email)) {
             if (logged_on || ($CFG->publicinvite == true)) {
                 if (($CFG->maxusers == 0 || (count_users('person') < $CFG->maxusers))) {
                 if (validate_email(stripslashes($invite->email))) {
                     $strippedname = stripslashes($invite->name); // for the message text.
                     $invitations = count_records('invitations','email',$invite->email);
                     if ($invitations == 0) {
                         if (!$account = get_record('users','email',$invite->email)) {
                             $invite->code = 'i' . substr(base_convert(md5(time() . $USER->username), 16, 36), 0, 7);
                             $invite->added = time();
                             $invite->owner = $USER->ident;
                             insert_record('invitations',$invite);
                             $invitetext = trim(optional_param('invite_text'));
                             if (!empty($invitetext)) {
                                 $invitetext = __gettext("They included the following message:") . "\n\n----------\n" . stripslashes($invitetext) . "\n----------";
                             }
                             $url = url . "_invite/join.php?invitecode=" . $invite->code;
                             if (!logged_on) {
                                 $greetingstext = sprintf(__gettext("Thank you for registering with %s."),$sitename);
                                 $subjectline = sprintf(__gettext("%s account verification"),$sitename);
                                 $from_email = email;
                             } else {
                                 $greetingstext = $USER->name . " " . __gettext("has invited you to join") ." $sitename, ". __gettext("a learning landscape system.") ."";
                                 $subjectline = $USER->name . " " . __gettext("has invited you to join") ." $sitename";
                                 $from_email = $USER->email;
                             }
                             $emailmessage = sprintf(__gettext("Dear %s,\n\n%s %s\n\nTo join, visit the following URL:\n\n\t%s\n\n"
                                                             ."Your email address has not been passed onto any third parties,"
                                                             ." and will be removed from our system within seven days.\n\nRegards,\n\nThe %s team.")
                                                     ,$strippedname,$greetingstext,$invitetext,$url, $sitename);
                             $emailmessage = wordwrap($emailmessage);
                             $messages[] = sprintf(__gettext("Your invitation was sent to %s at %s. It will be valid for seven days."),$strippedname,$invite->email);
                             email_to_user($invite,null,$subjectline,$emailmessage);
                         } else {
                             $messages[] = sprintf(__gettext("User %s already has that email address. Invitation not sent."),$account->username);
                         }
                     } else {
                         $messages[] = __gettext("Someone with that email address has already been invited to the system. ");
                     }
                 } else {
                     $messages[] = __gettext("Invitation failed: The email address was not valid.");
                 }
             } else {
                 $messages[] = __gettext("Error: This community has reached its maximum number of users.");
             }
             } else {
                 $messages[] = __gettext("Invitation failed: you are not logged in.");
             }
         } else {
                 $messages[] = __gettext("Invitation failed: you must specify both a name and an email address.");
         }
         break;
         // Join using an invitation
     case "invite_join":
         $name = trim(optional_param('join_name'));
         $code = trim(optional_param('invitecode'));
         $over13 = optional_param('over13');
         $username = trim(optional_param('join_username'));
         $password1 = trim(optional_param('join_password1'));
         $password2 = trim(optional_param('join_password2'));
         if (isset($name) && isset($code)) {
             if (!($CFG->maxusers == 0 || (count_users('person') < $CFG->maxusers))) {
                 $messages[] = __gettext("Unfortunately this community has reached its account limit and you are unable to join at this time.");
             }
             if (empty($over13)) {
                 $messages[] = __gettext("You must indicate that you are at least 13 years old to join.");
                 break;
             }
             if (!$details = get_record('invitations','code',$code)) {
                 $messages[] = __gettext("Error! Invalid invite code.");
                 break;
             } 
             if ($password1 != $password2 || strlen($password1) < 6 || strlen($password2) > 16) {
                 $messages[] = __gettext("Error! Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
                 break;
             }
             if (!preg_match("/^[A-Za-z0-9]{3,12}$/",$username)) {
                 $messages[] = __gettext("Error! Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                 break;
             }
             $username = strtolower($username);
             if (record_exists('users','username',$username)) {
                 $messages[] = __gettext("The username '$username' is already taken by another user. You will need to pick a different one.");
                 break;
             }
             $displaypassword = $password1;
             $u = new StdClass;
             $u->name = $name;
             $u->password = md5($password1);
             $u->email = $details->email;
             $u->username = $username;
             $ident = insert_record('users',$u);
             //    Calendar code is in the wrong place!
             global $function;
             if(isset($function["calendar:init"])) {
                 $c = new StdClass;
                 $c->owner = $ident;
                 insert_record('calendar',$c);
             }
             $owner = (int)$details->owner;
             if ($owner != -1) {
                 $f = new StdClass;
                 $f->owner = $owner;
                 $f->friend = $ident;
                 insert_record('friends',$f);
                 $f->owner = $ident;
                 $f->friend = $owner;
                 insert_record('friends',$f);
             }
             if ($owner != 1) {
                 $f = new StdClass;
                 $f->owner = $ident;
                 $f->friend = 1;
                 insert_record('friends',$f);
             }
             $rssresult = run("weblogs:rss:publish", array($ident, false));
             $rssresult = run("files:rss:publish", array($ident, false));
             $rssresult = run("profile:rss:publish", array($ident, false));
             $_SESSION['messages'][] = __gettext("Your account was created! You can now log in using the username and password you supplied. You have been sent an email containing these details for reference purposes.");
             delete_records('invitations','code',$code);
             email_to_user($u,null,sprintf(__gettext("Your %s account"),$sitename), 
                  sprintf(__gettext("Thanks for joining %s!\n\nFor your records, your %s username and password are:\n\n\t"
                                  ."Username: %s\n\tPassword: %s\n\nYou can log in at any time by visiting %s and entering these details into the login form.\n\n"
                                  ."We hope you enjoy using the system.\n\nRegards,\n\nThe %s Team")
                          ,$sitename,$sitename,$username,$displaypassword,url,$sitename));
             header("Location: " . url);
             exit();
         }
         break;

     // Request a new password
     case "invite_password_request":        
         $username = optional_param('password_request_name');
         if (!empty($username)) {
             if ($user = get_record('users','username',trim($username),'user_type','person')) {
                 $pwreq = new StdClass;
                 $pwreq->code = 'i' . substr(base_convert(md5(time() . $username), 16, 36), 0, 7);
                 $pwreq->owner = $user->ident;
                 insert_record('password_requests',$pwreq);
                 $url = url . "_invite/new_password.php?passwordcode=" . $pwreq->code;
                 email_to_user($user,null,sprintf(__gettext("Verify your %s account password request"),$sitename), 
                               sprintf(__gettext("A request has been received to generate your account at %s a new password.\n\n"
                                               ."To confirm this request and receive a new password by email, please click the following link:\n\n\t%s\n\n"
                                               ."Please let us know if you have any further problems.\n\nRegards,\n\nThe %s Team")
                                       ,$sitename,$url,$sitename));
                 $messages[] = __gettext("Your verification email was sent. Please check your inbox.");
             } else {
                 $messages[] = __gettext("No user with that username was found.");
             }
         }
         break;
}
            

?>