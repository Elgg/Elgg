<?php
return array(
	'admin:users:unvalidated' => 'No validats',
	
	'email:validate:subject' => "%s si us plau confirma la teva adreça de correu electrònic per a %s!",
	'email:validate:body' => "%s,

Abans que pugis començar a utilitzar %s, has de confirmar l'adreça de correu-e.

Si us plau, confirma la teva adreça de correu electrònic fent clic en aquest enllaç:

%s

Si no pots fer clic, copia i enganxa'l al teu navegador manualment.

%s
%s
",
	'email:confirm:success' => "Has confirmat la teva adreça de correu electrònic!",
	'email:confirm:fail' => "No es pot verificar la teva adreça de correu electrònic...",

	'uservalidationbyemail:emailsent' => "Email sent to <em>%s</em>",
	'uservalidationbyemail:registerok' => "Per activar el teu compte, confirma la teva adreça de correu electrònic clicant l'enllaç que t'hem enviat.",
	'uservalidationbyemail:login:fail' => "El teu compte no es troba validat. La identificació ha fallat. S´ha enviat un altre correu de verificació.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'No hi ha usuàries no validades.',

	'uservalidationbyemail:admin:unvalidated' => 'No validat',
	'uservalidationbyemail:admin:user_created' => 'Registrat %s',
	'uservalidationbyemail:admin:resend_validation' => 'Reenviar validació',
	'uservalidationbyemail:admin:validate' => 'Validar',
	'uservalidationbyemail:confirm_validate_user' => 'Validar %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Reenviar correu de validació a %s?',
	'uservalidationbyemail:confirm_delete' => 'Eliminar %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Validar les usuàries seleccionades?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Reenviar validació a les usuàries seleccionades?',
	'uservalidationbyemail:confirm_delete_checked' => 'Eliminar les usuàries seleccionades?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Usuàries desconegudes',
	'uservalidationbyemail:errors:could_not_validate_user' => 'No es pot validar l\'usuària.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'No es pot validar totes les usuàries seleccionades.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'No es pot eliminar la usuària.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'No es poden eliminar totes les usuàries seleccionades.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'No es pot reenviar la sol·licitud de validació.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'No es poden reenviar les sol·licituds de validació a totes les usuàries seleccionades.',

	'uservalidationbyemail:messages:validated_user' => 'Usuària validada.',
	'uservalidationbyemail:messages:validated_users' => 'Totes les usuàries seleccionades han estat validades.',
	'uservalidationbyemail:messages:deleted_user' => 'Usuària eliminada.',
	'uservalidationbyemail:messages:deleted_users' => 'Totes les usuàries seleccionades han estat eliminades.',
	'uservalidationbyemail:messages:resent_validation' => 'Sol·licitud de validació reenviada.',
	'uservalidationbyemail:messages:resent_validations' => 'Sol·licituds de validació reenviades a les usuàries seleccionades.'

);
