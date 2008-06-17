<?php
/*
 * invite_config.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */

 /**
  * Defines if the user name would be suggestend in the register page
  * Type: boolean
  */
 define('INVITE_USERNAME_SUGGEST',false);

 /**
  * Defines if in the confirmation message it sends the user and password
  * Type: boolean
  */
 define('INVITE_MAIL_CLEAR_PASSWORD',false);

 /**
  * Defines if auto log in after the registration process
  * Type: boolean
  */
 define('INVITE_AUTO_LOGIN',true);

 /**
  * Defines if news would be added automaticaly how friend of the new users
  * Type: boolean
  */
 define('INVITE_AUTOADD_NEWS_FRIEND',false);

 /**
  * Defines if when the system send the invitation it shows the register page
  * again
  * Type: boolean
  */
 define('INVITE_NO_RETURN_TO_REGISTER_PAGE',true);

 /**
  * Defines if its allowed to use the email instead username to request a new password
  * Type: boolean
  */
 define('INVITE_ALLOW_EMAIL_BY_USERNAME',true);

?>
