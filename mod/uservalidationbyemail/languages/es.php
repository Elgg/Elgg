<?php
return array(
	'admin:users:unvalidated' => 'Sin validar',
	
	'email:validate:subject' => "%s por favor confirma tu dirección de email para %s!",
	'email:validate:body' => "%s,

Antes de comenzar a usar %s, debes confirmar tu dirección de email.

Por favor confirma tu registro a trav&eacute;s del siguiente enlace:

%s

Si no puedes hacer click en el enlace, copia y pega la URL en el navegador.

%s
%s
",
	'email:confirm:success' => "Has confirmado tu dirección de email!",
	'email:confirm:fail' => "Tu direcci&oacute;n de email no pudo ser verificada...",

	'uservalidationbyemail:emailsent' => "Correo electrónico enviado a <em> %s </em>",
	'uservalidationbyemail:registerok' => "Para activar tu cuenta, por favor confirma tu dirección de email a trav&eacute;s del enlace que se te ha enviado.",
	'uservalidationbyemail:login:fail' => "Tu cuenta no ha sido validada debido a intentos fallidos anteriores. Otra confirmación de dirección de email ha sido enviada.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'No hay usuarios sin validar.',

	'uservalidationbyemail:admin:unvalidated' => 'Sin validar',
	'uservalidationbyemail:admin:user_created' => '%s ha sido registrado',
	'uservalidationbyemail:admin:resend_validation' => 'Reeniar validaci&oacute;n',
	'uservalidationbyemail:admin:validate' => 'Validar',
	'uservalidationbyemail:confirm_validate_user' => '&iquest;Validar %s?',
	'uservalidationbyemail:confirm_resend_validation' => '&iquest;Reenviar confirmaci&oacute;n de email a %s?',
	'uservalidationbyemail:confirm_delete' => '&iquest;borrar %s?',
	'uservalidationbyemail:confirm_validate_checked' => '&iquest;Validar a los usuarios marcados?',
	'uservalidationbyemail:confirm_resend_validation_checked' => '&iquest;Reenviar validaci&oacute;n a los usuarios marcados?',
	'uservalidationbyemail:confirm_delete_checked' => '&iquest;Borrar a los usuarios marcados?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Uusarios desconocidos',
	'uservalidationbyemail:errors:could_not_validate_user' => 'No se pudo validar al usuario.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'No se pudieron validar a los usuarios marcados.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'No se pudo borrar al usuario.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'No se pudo borrar a los usuarios marcados.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'No se pudo reenviar la confirmaci&oacute;n de validaci&oacute;n.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'No se pudo reenviar la confirmaci&oacute;n de validaci&oacute;n para los usuarios marcados.',

	'uservalidationbyemail:messages:validated_user' => 'Uusario validado.',
	'uservalidationbyemail:messages:validated_users' => 'Todos los usuarios marcados han sido validados.',
	'uservalidationbyemail:messages:deleted_user' => 'Uusario borrado.',
	'uservalidationbyemail:messages:deleted_users' => 'Todos los usuarios marcados han sido borrados.',
	'uservalidationbyemail:messages:resent_validation' => 'Solicitud devalidaci&oacute;n reenviada.',
	'uservalidationbyemail:messages:resent_validations' => 'Solicitud devalidaci&oacute;n reenviada a todos los usuarios marcados.'

);
