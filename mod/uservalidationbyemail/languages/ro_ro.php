<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s te rugăm să-ți confirmi adresa de email pentru %s!",
	'email:confirm:success' => "Ți-ai confirmat adresa de email!",
	'email:confirm:fail' => "Adresa ta de email nu a putu fi verificată...",

	'uservalidationbyemail:emailsent' => "Email trimis către <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pentru a-ți activa contul, te rugăm să-ți confirmi adresa de email apăsând pe link-ul pe care tocmai ce ți l-am trimis.",

	'uservalidationbyemail:admin:resend_validation' => 'Retrimite validarea',
	'uservalidationbyemail:confirm_resend_validation' => 'Retrimiți email-ul de validare către %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Retrimiți validarea către utilizatorii selectați?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilizatori necunoscuți',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Nu am putut retrimite cererea de validare.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Nu am putut retrimite toate cererile de validare către utilizatorii selectați.',

	'uservalidationbyemail:messages:resent_validation' => 'Cererea de validare a fost retrimisă.',
	'uservalidationbyemail:messages:resent_validations' => 'Cererile de validare au fost retrimise către toți utilizatorii selectați.',
);
