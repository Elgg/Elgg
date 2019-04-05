<?php
return array(
	'email:validate:subject' => "%s proszę potwierdź swój adres e-mail!",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Potwierdzono adres e-mail!",
	'email:confirm:fail' => "Adres e-mail nie mógł zostać zweryfikowany...",

	'uservalidationbyemail:emailsent' => "Wysłano e-mail do <em>%s</em>",
	'uservalidationbyemail:registerok' => "Aby aktywować swoje konto proszę potwierdź swój adres e-mail poprzez kliknięcie w link który do Ciebie wysłaliśmy.",
	'uservalidationbyemail:login:fail' => "Twoje konto jest niezweryfikowane więc nie możesz się logować. Wysłaliśmy kolejny e-mail potwierdzający.",

	'uservalidationbyemail:admin:resend_validation' => 'Wyślij ponownie e-mail potwierdzający',
	'uservalidationbyemail:confirm_resend_validation' => 'Wysłać ponownie e-mail potwierdzający do %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Wysłać ponownie e-mail potwierdzający do zaznaczonych użytkowników?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Nieznani użytkownicy',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Nie udało się ponowne wysłanie e-maila potwierdzającego.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Nie udało się ponowne wysłanie e-maila potwierdzającego do wszystkich z zaznaczonych użytkowników.',

	'uservalidationbyemail:messages:resent_validation' => 'Wysłano e-mail potwierdzający.',
	'uservalidationbyemail:messages:resent_validations' => 'Wysłano e-maile potwierdzające do wszystkich zaznaczonych użytkowników.'
);
