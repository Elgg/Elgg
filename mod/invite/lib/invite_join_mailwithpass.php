<?php
/*
 * invite_join_mailwithpass.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
if(is_array($parameter)){
  $sitename = $parameter[0];
  $username = $parameter[1];
  $displaypassword = $parameter[2];
  $url = $parameter[3];

  $run_result= sprintf(__gettext("Thanks for joining %s!\n\nFor your records, your username and password in %s are:\n\n\t")
                                        .__gettext("Username: %s\n\tPassword: %s\n\nYou can log in at any time by visiting %s and entering these details into the login form.\n\n")
                                        .__gettext("We hope you enjoy using the system.\n\nRegards,\n\nThe %s Team")
                                ,$sitename,$sitename,$username,$displaypassword,$url,$sitename);

}
?>
