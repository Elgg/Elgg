<?php
return array(
	'admin:users:unvalidated' => 'Invalido',
	
	'email:validate:subject' => "%s per favore conferma il tuo indirizzo email per %s!",
	'email:validate:body' => "Ciao %s,

Per iniziare ad usare %s devi confermare il tuo indirizzo email.

Per favore confermalo cliccando sul seguente link:

%s

Se non riesci a cliccare sul link, copia ed incollalo manualmente nel tuo browser

%s
%s",
	'email:confirm:success' => "Hai confermato il tuo indirizzo email!",
	'email:confirm:fail' => "Il tuo indirizzo email non può essere convalidato...",

	'uservalidationbyemail:emailsent' => "Inviata email a <em>%s</em>",
	'uservalidationbyemail:registerok' => "Per attivare il tuo account, per favore apri la tua casella di posta e clicca sul link che ti abbiamo inviato. Controlla anche la cartella Spam, potrebbe succedere che al primo invio da parte nostra il tuo provider di posta elettronica inserisca il messaggio in questa cartella. Se così fosse, importantissimo, seleziona il nostro messaggio e contrassegnalo come 'Non è Spam', grazie.",
	'uservalidationbyemail:login:fail' => "Il tuo account non è stato convalidato quindi il tuo tentativo di entrare non ha avuto successo. Ti è stata inviata un'altra e-mail di convalida.
Controlla anche la cartella Spam, potrebbe succedere che al primo invio da parte nostra il tuo provider di posta elettronica inserisca il messaggio in questa cartella. Se così fosse, importantissimo, seleziona il nostro messaggio e contrassegnalo come 'Non è Spam', grazie.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Nessun utente non ancora validato',

	'uservalidationbyemail:admin:unvalidated' => 'Non convalidato',
	'uservalidationbyemail:admin:user_created' => 'Registrato %s',
	'uservalidationbyemail:admin:resend_validation' => 'Reinvia validazione',
	'uservalidationbyemail:admin:validate' => 'Valida',
	'uservalidationbyemail:confirm_validate_user' => 'Validare %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Reinvia la mail di validazione a %s?',
	'uservalidationbyemail:confirm_delete' => 'Cancellare %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Validare gli utenti selezionati?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Reinviare la validazione agli utenti selezionati?',
	'uservalidationbyemail:confirm_delete_checked' => 'Eliminare gli utenti selezionati?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utenti sconosciuti',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Non è stato possibile validare l\'utente',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Non è stato possibile validare gli utenti selezionati',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Non è stato possibile eliminare l\'utente',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Non è stato possibile eliminare tutti gli utenti selezionati',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Non è stato possibile reinviare la richiesta di validazione',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Non è stato possibile reinviare tutte le richieste di validazione agli utenti selezionati',

	'uservalidationbyemail:messages:validated_user' => 'Utente validato',
	'uservalidationbyemail:messages:validated_users' => 'Tutti gli utenti selezionati sono stati valutati',
	'uservalidationbyemail:messages:deleted_user' => 'Utente eliminato',
	'uservalidationbyemail:messages:deleted_users' => 'Tutti gli utenti selezionati sono stati eliminati',
	'uservalidationbyemail:messages:resent_validation' => 'Richiesta di validazione reinviata',
	'uservalidationbyemail:messages:resent_validations' => 'Richieste di validazione reinviate a tutti gli utenti selezionati'

);
