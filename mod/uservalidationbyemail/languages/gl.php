<?php
return array(
	'admin:users:unvalidated' => 'Sen validar',
	
	'email:validate:subject' => "%s confirme o seu enderezo de correo para %s",
	'email:validate:body' => "%s.

Antes de comezar a usar %s debe confirmar o seu enderezo de correo electrónico.

Siga esta ligazón para confirmalo:

%s

Se non pode seguila, cópiea e péguea nun navegador manualmente.

%s
%s
",
	'email:confirm:success' => "Confirmou o seu enderezo de correo electrónico.",
	'email:confirm:fail' => "Non foi posíbel verificar o seu enderezo de correo electrónico",

	'uservalidationbyemail:emailsent' => "Enviouse unha mensaxe por correo electrónico a <em>%s</em>.",
	'uservalidationbyemail:registerok' => "Para activar a conta, siga a ligazón que lle acabamos de enviar para confirmar o seu enderezo de correo electrónico.",
	'uservalidationbyemail:login:fail' => "A conta non está validada, así que non puido acceder. Acabámoslle de enviar un novo correo electrónico de validación.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Non hai usuarios sen validar.',

	'uservalidationbyemail:admin:unvalidated' => 'Sen validar',
	'uservalidationbyemail:admin:user_created' => 'Rexistrouse %s',
	'uservalidationbyemail:admin:resend_validation' => 'Enviar a validación de nov',
	'uservalidationbyemail:admin:validate' => 'Validar',
	'uservalidationbyemail:confirm_validate_user' => 'Validar %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Enviar de novo a mensaxe de validación a %s?',
	'uservalidationbyemail:confirm_delete' => 'Eliminar %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Validar os usuarios seleccionados?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Volver enviar a validación aos usuarios seleccionados?',
	'uservalidationbyemail:confirm_delete_checked' => 'Eliminar os usuarios seleccionados?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Usuarios descoñecidos',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Non foi posíbel validar o usuario.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Non foi posíbel validar todos os usuarios seleccionados.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Non foi posíbel eliminar o usuario.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Non foi posíbel eliminar todos os usuarios seleccionados.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Non foi posíbel enviar de novo a solicitude de validación.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Non foi posíbel enviar de novo as solicitudes de validación aos usuarios seleccionados.',

	'uservalidationbyemail:messages:validated_user' => 'Validouse o usuario.',
	'uservalidationbyemail:messages:validated_users' => 'Validáronse os usuarios seleccionados',
	'uservalidationbyemail:messages:deleted_user' => 'Eliminouse o usuario.',
	'uservalidationbyemail:messages:deleted_users' => 'Elimináronse os usuarios seleccionados.',
	'uservalidationbyemail:messages:resent_validation' => 'Enviouse de novo a solicitude de validación.',
	'uservalidationbyemail:messages:resent_validations' => 'Enviáronse de novo as solicitudes de validación aos usuarios seleccionados.'

);
