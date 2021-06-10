<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s, ověřte prosím svůj e-mail pro %s!",
	'email:confirm:success' => "Vaše e-mailová adresa byla ověřena!",
	'email:confirm:fail' => "Vaše e-mailová adresa nemohla být ověřena...",

	'uservalidationbyemail:emailsent' => "E-mail byl odeslán na <em>%s</em>",
	'uservalidationbyemail:registerok' => "Potvrďte prosím vaši e-mailovou adresu přes odkaz, který jsme vám poslali. Poté bude váš účet aktivován.",
	'uservalidationbyemail:login:fail' => "Přihlášení bylo neúspěšné, protože váš účet není ověřený. Byl vám zaslán další ověřovací e-mail.",

	'uservalidationbyemail:admin:resend_validation' => 'Znovu poslat ověření',
	'uservalidationbyemail:confirm_resend_validation' => 'Znovu poslat ověřovací e-mail na %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Znovu poslat ověřovací e-maily označeným uživatelům?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Neznámí uživatelé',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Nemohu poslat ověřovací požadavek.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Nemohu poslat ověřovací požadavek všem označeným uživatelům.',

	'uservalidationbyemail:messages:resent_validation' => 'Ověřovací požadavek byl odeslán.',
	'uservalidationbyemail:messages:resent_validations' => 'Všem označeným uživatelům byl odeslán ověřovací požadavek.',
);
