<?php
/**
 * Email user validation plugin language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage ElggUserValidationByEmail
 */

$english = array(
	'email:validate:subject' => "%s please confirm your email address for %s!",
	'email:validate:body' => "%s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s
",
	'email:confirm:success' => "You have confirmed your email address!",
	'email:confirm:fail' => "Your email address could not be verified...",

	'uservalidationbyemail:registerok' => "To activate your account, please confirm your email address by clicking on the link we just sent you."

);

add_translation("en", $english);