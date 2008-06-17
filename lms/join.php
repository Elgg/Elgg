<?php 
// sign up a user from an lms request.

require_once(dirname(dirname(__FILE__)).'/includes.php');
require_once($CFG->dirroot.'lib/lmslib.php');
$showform = false;
$u = new StdClass;
if (empty($USER->signingup)) {
    // the POST parameters we expect are:
    $alias = new StdClass;
    $alias->installid = optional_param('installid');
    $alias->username = optional_param('username');
    $alias->firstname = optional_param('firstname');
    $alias->lastname = optional_param('lastname');
    $alias->email = optional_param('email');
    $signature = optional_param('signature');

    $user = find_lms_user($alias->installid,$alias->username,$signature,'signupconfirmation',$alias->firstname,$alias->lastname,$alias->email);
    if (is_object($user)) {
        // they already exist! 
        echo $user;
        // TODO something
    } else if ($user != LMS_NO_SUCH_USER) {
        // we have a validation error probably.
        echo $user;
        // TODO something
    } else {
        // ok, everything is fine, we need to show them the form.
        $showform = 1;
        $u->name = $alias->firstname.' '.$alias->lastname;
        $u->email = $alias->email;
        $USER->signingup = true;
        $USER->alias = $alias;
    }
} else {
    // process the signup form.
    $u->username = optional_param('username');
    $u->password1 = optional_param('password1');
    $u->password2 = optional_param('password2');
    $u->email = optional_param('email');
    $u->name = optional_param('name');
    $mode = optional_param('mode');

    $messages = array();
    if ($mode == 'join') {
        // validate
        if (!validate_username($u->username)) {
            $messages[] = __gettext("Error! Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
        } elseif (!username_is_available(strtolower($u->username))) {
            $messages[] = __gettext("The username '$username' is already taken by another user. You will need to pick a different one.");
        }

        if (!validate_password($u->password1, $u->password2)) {
            $messages[] = __gettext("Error! Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
        }

        if (empty($u->name)) {
            $messages[] = __gettext("Error! You must enter your fullname");
        }

        if (empty($messages)) {
            // we are good to go!
            $u->password = md5($u->password1);
            $ident = insert_record('users',$u);
        }
    } elseif ($mode == 'login') {
        $alias = $USER->alias;
        if (!authenticate_account($u->username, md5($u->password1))) {
            $messages[] = __gettext('Error! Your username and password does not match our record.');
        }
        $USER->alias = $alias;
        $ident = $USER->ident;
    } elseif (!empty($mode)) {
        $messages[] = __gettext('Fatal Error! Unknown action requested.');
    }

    if (empty($messages)) {
        $alias = $USER->alias;
        $alias->user_id = $ident;
        insert_record('users_alias',$alias);

        // now look for a community for this installation and add them to it.
        if (!$comm = get_record_select('users','user_type = ? AND name = ?',array('community',$alias->installid))) {
            $comm = new StdClass;
            $comm->name = $alias->installid;
            $comm->username = $alias->installid;
            $comm->user_type = 'community';
            $admin = get_admin();
            $comm->owner = $admin->ident;
            $comm->ident = insert_record('users',$comm);
        } 
        $f = new StdClass;
        $f->owner = $ident;
        $f->friend = $comm->ident;
        insert_record('friends',$f);
        $f->owner = $comm->ident;
        $f->friend = $ident;
        insert_record('friends',$f);
        
        unset($USER->signingup);
        unset($USER->alias);
        if ($mode == 'join') { // we don't need to do these if the user has already had an account.
            $_SESSION['messages'][] = __gettext('Your account creation was successful!');
            // authenticate them.
            authenticate_account($u->username,$u->password);
        }
        redirect($CFG->wwwroot.$u->username);
    }
    $showform = true;
}
if (!empty($showform)) {
    define("context", "lmsjoin");
    templates_page_setup();
    
    $title = __gettext('Join up');
    ob_start();
    require_once($CFG->dirroot.'lms/join.html');
    $body = ob_get_contents();
    ob_end_clean();

    $body  = templates_draw( array(
                               'context' => 'contentholder',
                               'title' => $title,
                               'body' => $body
                               ));

    $title1 = __gettext('Login');
    ob_start();
    require_once($CFG->dirroot.'lms/login.html');
    $body1 = ob_get_contents();
    ob_end_clean();

    $body .= templates_draw( array(
                               'context' => 'contentholder',
                               'title' => $title1,
                               'body' => $body1
                               ));

    echo templates_page_draw(array($title, $body, '&nbsp;'));
}

?>