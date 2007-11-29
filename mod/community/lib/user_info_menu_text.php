<?php
global $CFG,$USER;
global $user_type;

// If we've been passed a valid user ID as a parameter ...
if (isset($parameter) && (isset($parameter[0])) && ($parameter[0] != $_SESSION['userid']) && logged_on) {

    $user_id = (int) $parameter[0];

    if (user_type($user_id) == "community") {
        $result = count_records_sql('SELECT COUNT(u.ident) FROM '.$CFG->prefix.'friends f
                                     LEFT JOIN '.$CFG->prefix.'users u ON u.ident = f.friend
                                     WHERE f.owner = ? AND f.friend = ?',
                                    array($USER->ident,$user_id));
        if ($result == 0) {
            $moderation = user_info('moderation',$user_id);
            switch($moderation) {
            case "no":        $run_result = "<a href=\"".url."mod/community/index.php?friends_name=".$_SESSION['username']."&amp;action=friend&amp;friend_id=$user_id\" onclick=\"return confirm('". __gettext("Are you sure you want to join this community?") ."')\">" . __gettext("Click here to join this community."). "</a>";
                break;
            case "yes":        $run_result = "<a href=\"".url."mod/community/index.php?friends_name=".$_SESSION['username']."&amp;action=friend&amp;friend_id=$user_id\" onclick=\"return confirm('". __gettext("Are you sure you want to apply to join this community?") ."')\">" . __gettext("Click here to apply to join this community."). "</a>";
                break;
            case "priv":    $run_result = "";
                break;
            }
        } else {
            $run_result = "<a href=\"".url."mod/community/index.php?friends_name=".$_SESSION['username']."&amp;action=unfriend&amp;friend_id=$user_id\" onclick=\"return confirm('". __gettext("Are you sure you want to leave this community?") ."')\">" . __gettext("Click here to leave this community."). "</a>";
        }
    }
}

?>