<?php
// this is internal or default authentication
function internal_authenticate_user_login($username,$password) {
    
    return get_record_select('users',"username = ? AND password = ? AND active = ? AND user_type = ? ",
                             array($username,md5($password),'yes','person'));
}

?>
