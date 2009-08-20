<?php
	/**
	 * Email user validation plugin language pack.
	 * 
	 * @package ElggUserValidationByEmail
	 * @author Curverider Ltd
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
	
		
		'email:confirm:success' => "You have confirmed your email address!",
		'email:confirm:fail' => "Your email address could not be verified...",
	
		'uservalidationbyemail:registerok' => "To activate your account, please confirm your email address by clicking on the link we just sent you."
	
	);
					
	add_translation("en",$english);
?>