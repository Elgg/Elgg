<?php

    /*
     * Utility functions
     */
     
     function userNameToId($username)
     {
     
     /*
        if ($_SERVER['PHP_AUTH_USER'] )
        {
            $username = addslashes($_SERVER['PHP_AUTH_USER']);
            $result = db_query("select ident from users where username = '$username'");
        }

     */ 
        //$username = addslashes($username);
        $result = db_query("select ident from users where username = '$username'");
        return $result[0]->ident;
     }
?>
