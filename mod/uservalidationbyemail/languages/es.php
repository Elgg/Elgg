<?php
return array(
	'email:validate:subject' => "%s por favor confirma tu dirección de email para %s!",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Has confirmado tu dirección de email!",
	'email:confirm:fail' => "Tu direcci&oacute;n de email no pudo ser verificada...",

	'uservalidationbyemail:emailsent' => "Correo electrónico enviado a <em> %s </em>",
	'uservalidationbyemail:registerok' => "Para activar tu cuenta, por favor confirma tu dirección de email a trav&eacute;s del enlace que se te ha enviado.",
	'uservalidationbyemail:login:fail' => "Tu cuenta no ha sido validada debido a intentos fallidos anteriores. Otra confirmación de dirección de email ha sido enviada.",

	'uservalidationbyemail:admin:resend_validation' => 'Reeniar validaci&oacute;n',
	'uservalidationbyemail:confirm_resend_validation' => '&iquest;Reenviar confirmaci&oacute;n de email a %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => '&iquest;Reenviar validaci&oacute;n a los usuarios marcados?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Uusarios desconocidos',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'No se pudo reenviar la confirmaci&oacute;n de validaci&oacute;n.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'No se pudo reenviar la confirmaci&oacute;n de validaci&oacute;n para los usuarios marcados.',

	'uservalidationbyemail:messages:resent_validation' => 'Solicitud devalidaci&oacute;n reenviada.',
	'uservalidationbyemail:messages:resent_validations' => 'Solicitud devalidaci&oacute;n reenviada a todos los usuarios marcados.'
);
