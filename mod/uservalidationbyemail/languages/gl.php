<?php
return array(
	'email:validate:subject' => "%s confirme o seu enderezo de correo para %s",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Confirmou o seu enderezo de correo electrónico.",
	'email:confirm:fail' => "Non foi posíbel verificar o seu enderezo de correo electrónico",

	'uservalidationbyemail:emailsent' => "Enviouse unha mensaxe por correo electrónico a <em>%s</em>.",
	'uservalidationbyemail:registerok' => "Para activar a conta, siga a ligazón que lle acabamos de enviar para confirmar o seu enderezo de correo electrónico.",
	'uservalidationbyemail:login:fail' => "A conta non está validada, así que non puido acceder. Acabámoslle de enviar un novo correo electrónico de validación.",

	'uservalidationbyemail:admin:resend_validation' => 'Enviar a validación de nov',
	'uservalidationbyemail:confirm_resend_validation' => 'Enviar de novo a mensaxe de validación a %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Volver enviar a validación aos usuarios seleccionados?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Usuarios descoñecidos',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Non foi posíbel enviar de novo a solicitude de validación.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Non foi posíbel enviar de novo as solicitudes de validación aos usuarios seleccionados.',

	'uservalidationbyemail:messages:resent_validation' => 'Enviouse de novo a solicitude de validación.',
	'uservalidationbyemail:messages:resent_validations' => 'Enviáronse de novo as solicitudes de validación aos usuarios seleccionados.'
);
