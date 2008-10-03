<?php
	/**
	 * Email user validation plugin language pack.
	 * 
	 * @package ElggUserValidationByEmail
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		'email:validate:subject' => "%s please confirm your email address!",
		'email:validate:body' => "Hi %s,

Please confirm your email address by clicking on the link below:

%s
",
		'email:validate:success:subject' => "Email validated %s!",
		'email:validate:success:body' => "Hi %s,
			
Congratulations, you have successfully validated your email address.",
	
		'uservalidationbyemail:registerok' => "To activate your account, please confirm your email address by clicking on the link we sent you."
	
	);
					
	add_translation("en",$english);
?>