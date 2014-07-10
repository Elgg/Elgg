<?php
return array(
	'admin:users:unvalidated' => 'Niezatwierdzony',
	
	'email:validate:subject' => "%s proszę potwierdź swój adres e-mail!",
	'email:validate:body' => "Witaj %s,

Proszę potwierdź swój adres e-mail poprzez klikniecie poniższego linku :

%s
",
	'email:confirm:success' => "Potwierdzono adres e-mail!",
	'email:confirm:fail' => "Adres e-mail nie mógł zostać zweryfikowany...",

	'uservalidationbyemail:emailsent' => "Wysłano e-mail do <em>%s</em>",
	'uservalidationbyemail:registerok' => "Aby aktywować swoje konto proszę potwierdź swój adres e-mail poprzez kliknięcie w link który do Ciebie wysłaliśmy.",
	'uservalidationbyemail:login:fail' => "Twoje konto jest niezweryfikowane więc nie możesz się logować. Wysłaliśmy kolejny e-mail potwierdzający.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Brak niezatwierdzonych użytkowników.',

	'uservalidationbyemail:admin:unvalidated' => 'Niezatwierdzony',
	'uservalidationbyemail:admin:user_created' => 'Zarejestrowano %s',
	'uservalidationbyemail:admin:resend_validation' => 'Wyślij ponownie e-mail potwierdzający',
	'uservalidationbyemail:admin:validate' => 'Zatwierdź',
	'uservalidationbyemail:confirm_validate_user' => 'Zatwierdzić %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Wysłać ponownie e-mail potwierdzający do %s?',
	'uservalidationbyemail:confirm_delete' => 'Usunąć %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Zatwierdzić zaznaczonych użytkowników?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Wysłać ponownie e-mail potwierdzający do zaznaczonych użytkowników?',
	'uservalidationbyemail:confirm_delete_checked' => 'Usunąć zaznaczonych użytkowników?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Nieznani użytkownicy',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Nie można zatwierdzić użytkownika.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Nie można zatwierdzić wszystkich zaznaczonych użytkowników.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Nie można było skasować użytkownika.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Nie można było skasować wszystkich zaznaczonych użytkowników.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Nie udało się ponowne wysłanie e-maila potwierdzającego.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Nie udało się ponowne wysłanie e-maila potwierdzającego do wszystkich z zaznaczonych użytkowników.',

	'uservalidationbyemail:messages:validated_user' => 'Użytkownik zatwierdzony.',
	'uservalidationbyemail:messages:validated_users' => 'Wszyscy zaznaczeni użytkownicy zostali zatwierdzeni.',
	'uservalidationbyemail:messages:deleted_user' => 'Użytkownik usunięty.',
	'uservalidationbyemail:messages:deleted_users' => 'Wszyscy zaznaczeni użytkownicy zostali usunięci.',
	'uservalidationbyemail:messages:resent_validation' => 'Wysłano e-mail potwierdzający.',
	'uservalidationbyemail:messages:resent_validations' => 'Wysłano e-maile potwierdzające do wszystkich zaznaczonych użytkowników.'

);
