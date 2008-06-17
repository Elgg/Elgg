<?php
/*
 * invite_join_mailwithoutpass.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */

if(is_array($parameter)){
  $sitename = $parameter[0];
  $url = $parameter[1];

  $run_result=sprintf(__gettext("Thanks for joining %s!\n\n")
                                        .__gettext("You can log in at any time by visiting %s and entering your login and password.\n\n")
                                        .__gettext("We hope you enjoy using the system.\n\nRegards,\n\nThe %s Team")
                                ,$sitename,$url,$sitename);
}
?>
