<?php

return array(
	'discussion' => 'Forum',
	'discussion:add' => 'Aggiungi argomento',
	'discussion:latest' => 'Ultimi argomenti',
	'discussion:group' => 'Forum di gruppo',
	'discussion:none' => 'Nessuna discussione',
	'discussion:reply:title' => 'Rispondi con %s',
	'discussion:new' => "Aggiungi un nuovo argomento",
	'discussion:updated' => "Ultima risposta di %s %s",

	'discussion:topic:created' => 'L\'argomento è stato creato.',
	'discussion:topic:updated' => 'L\'argomento è stato aggiornato.',
	'discussion:topic:deleted' => 'L\'argomento è stato eliminato.',

	'discussion:topic:notfound' => 'Argomento non trovato.',
	'discussion:error:notsaved' => 'Impossibile salvare questo argomento',
	'discussion:error:missing' => 'Sia il titolo sia il messaggio sono campi obbligatori',
	'discussion:error:permissions' => 'Permessi insufficienti per completare questa azione',
	'discussion:error:notdeleted' => 'Impossibile eliminare l\'argomento',

	'discussion:reply:edit' => 'Modifica la risposta',
	'discussion:reply:deleted' => 'La risposta è stata eliminata.',
	'discussion:reply:error:notfound' => 'La risposta non è stata trovata',
	'discussion:reply:error:notfound_fallback' => "Spiacenti, non abbiamo trovato la risposta specificata ma ti abbiamo inoltrato alla pagina dell'argomento di discussione iniziale",
	'discussion:reply:error:notdeleted' => 'Impossibile eliminare la risposta',

	'discussion:search:title' => 'Risposta all\'argomento: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Non si può inviare una risposta vuota',
	'discussion:reply:topic_not_found' => 'Argomento non trovato',
	'discussion:reply:error:cannot_edit' => 'Permessi insufficienti per modificare questa risposta',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s ha aggiunto il nuovo argomento di discussione: %s',
	'river:reply:object:discussion' => '%s ha risposto alla discussione: %s',
	'river:reply:view' => 'visualizza risposta',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nuovo argomento di discussione: %s',
	'discussion:topic:notify:subject' => 'Nuovo argomento di discussione: %s',
	'discussion:topic:notify:body' =>
'%s ha aggiunto un nuovo argomento di discussione: %s

%s

Visualizza e rispondi qui:

%s
',

	'discussion:reply:notify:summary' => 'Nuova risposta alla discussione: %s',
	'discussion:reply:notify:subject' => 'Nuova risposta alla discussione: %s',
	'discussion:reply:notify:body' =>
'%s ha inviato una risposta nella discussione "%s":

%s

Visualizza e rispondi qui:

%s
',

	'item:object:discussion' => "Argomenti di discussione",
	'item:object:discussion_reply' => "Risposte alle discussioni",

	'groups:enableforum' => 'Abilita discussioni di gruppo',

	'reply:this' => 'Rispondi a questo',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Discussioni di gruppo',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Stato dell\'argomento',
	'discussion:topic:closed:title' => 'Questa discussione è chiusa',
	'discussion:topic:closed:desc' => 'Questa discussione è chiusa e non accetta nuovi commenti',

	'discussion:replies' => 'Risposte',
	'discussion:addtopic' => 'Aggiungi un argomento',
	'discussion:post:success' => 'La tua risposta è stata pubblicata',
	'discussion:post:failure' => 'Si è verificato un problema durante il salvataggio della tua risposta',
	'discussion:topic:edit' => 'Modifica argomento',
	'discussion:topic:description' => 'Messaggio dell\'argomento',

	'discussion:reply:edited' => "Hai modificato con successo il post",
	'discussion:reply:error' => "Problema durante la modifica del post",
);
