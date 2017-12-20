<?php
return array(
	'admin:users:unvalidated' => 'Neověření',
	
	'email:validate:subject' => "%s, ověřte prosím svůj e-mail pro %s!",
	'email:validate:body' => "%s,

než budete moci začít používat %s, je třeba ověřit váš e-mail.

Potvrďte prosím vaši e-mailovou adresu kliknutím na následující odkaz:

%s

Pokud na odkaz nemůžete kliknout, zkopírujte ho adresního řádku vašeho prohlížeče.

%s
%s
",
	'email:confirm:success' => "Vaše e-mailová adresa byla ověřena!",
	'email:confirm:fail' => "Vaše e-mailová adresa nemohla být ověřena...",

	'uservalidationbyemail:emailsent' => "E-mail byl odeslán na <em>%s</em>",
	'uservalidationbyemail:registerok' => "Potvrďte prosím vaši e-mailovou adresu přes odkaz, který jsme vám poslali. Poté bude váš účet aktivován.",
	'uservalidationbyemail:login:fail' => "Přihlášení bylo neúspěšné, protože váš účet není ověřený. Byl vám zaslán další ověřovací e-mail.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Žádní neověření uživatelé.',

	'uservalidationbyemail:admin:unvalidated' => 'Neověření',
	'uservalidationbyemail:admin:user_created' => '%s byl/a zaregistrován/a',
	'uservalidationbyemail:admin:resend_validation' => 'Znovu poslat ověření',
	'uservalidationbyemail:admin:validate' => 'Ověřit',
	'uservalidationbyemail:confirm_validate_user' => 'Ověřit %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Znovu poslat ověřovací e-mail na %s?',
	'uservalidationbyemail:confirm_delete' => 'Smazat %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Ověřit označené uživatele?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Znovu poslat ověřovací e-maily označeným uživatelům?',
	'uservalidationbyemail:confirm_delete_checked' => 'Smazat označené uživatele?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Neznámí uživatelé',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Nemohu ověřit uživatele.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Nemohu ověřit všechny označené uživatele.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Nemohu smazat uživatele.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Nemohu smazat všechny označené uživatele.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Nemohu poslat ověřovací požadavek.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Nemohu poslat ověřovací požadavek všem označeným uživatelům.',

	'uservalidationbyemail:messages:validated_user' => 'Uživatel byl ověřen.',
	'uservalidationbyemail:messages:validated_users' => 'Všichni označení uživatelé byli ověřeni.',
	'uservalidationbyemail:messages:deleted_user' => 'Uživatel by smazán.',
	'uservalidationbyemail:messages:deleted_users' => 'Všichni označení uživatelé byli smazáni.',
	'uservalidationbyemail:messages:resent_validation' => 'Ověřovací požadavek byl odeslán.',
	'uservalidationbyemail:messages:resent_validations' => 'Všem označeným uživatelům byl odeslán ověřovací požadavek.'

);
