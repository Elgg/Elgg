<?php
/*
 * invite_join_welcome.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */
 global $CFG;

  $sitename = $CFG->sitename;
  $thankYou = sprintf(__gettext("Thank you for registering for an account with %s! Registration is completely free, but before you confirm your details, please take a moment to read the following documents:"), $sitename);
  $terms = __gettext("terms and conditions"); // gettext variable
  $privacy = __gettext("Privacy policy"); // gettext variable
  $age = __gettext("Submitting the form below indicates acceptance of these terms. Please note that currently you must be at least 13 years of age to join the site."); // gettext variable
  $run_result .= <<< END

  <p>
      $thankYou
  </p>
  <ul>
      <li><a href="{$CFG->wwwroot}content/terms.php" target="_blank">$sitename $terms</a></li>
      <li><a href="{$CFG->wwwroot}content/privacy.php" target="_blank">$privacy</a></li>
  </ul>
  <p>
      $age
  </p>
END;



?>
