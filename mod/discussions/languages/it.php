<?php

return array(
	'add:object:discussion' => 'Add discussion topic',
	'edit:object:discussion' => 'Edit topic',

	'discussion:latest' => 'Ultimi argomenti',
	'collection:object:discussion:group' => 'Group discussions',
	'discussion:none' => 'Nessuna discussione',
	'discussion:updated' => "Ultima risposta di %s %s",

	'discussion:topic:created' => 'L\'argomento è stato creato.',
	'discussion:topic:updated' => 'L\'argomento è stato aggiornato.',
	'entity:delete:object:discussion:success' => 'Discussion topic has been deleted.',

	'discussion:topic:notfound' => 'Argomento non trovato.',
	'discussion:error:notsaved' => 'Impossibile salvare questo argomento',
	'discussion:error:missing' => 'Sia il titolo sia il messaggio sono campi obbligatori',
	'discussion:error:permissions' => 'Permessi insufficienti per completare questa azione',

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s ha aggiunto un nuovo argomento di discussione %s',
	'river:object:discussion:comment' => '%s ha commentato la discussione %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nuovo argomento intitolato %s',
	'discussion:topic:notify:subject' => 'Nuovo argomento: %s',
	'discussion:topic:notify:body' =>
'%s added a new discussion topic "%s":

%s

View and reply to the discussion topic:
%s
',

	'discussion:comment:notify:summary' => 'Nuova risposta alla discussione: %s',
	'discussion:comment:notify:subject' => 'Nuova risposta alla discussione: %s',
	'discussion:comment:notify:body' =>
'%s commented on the discussion topic "%s":

%s

View and comment on the discussion:
%s
',

	'item:object:discussion' => "Argomenti di discussione",
	'collection:object:discussion' => 'Discussion topics',

	'groups:tool:forum' => 'Enable group discussions',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Stato dell\'argomento',
	'discussion:topic:closed:title' => 'Questa discussione è chiusa',
	'discussion:topic:closed:desc' => 'Questa discussione è chiusa e non accetta nuovi commenti',

	'discussion:topic:description' => 'Contenuti dell\'argomento',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Converti le risposte alle discussioni in commenti",
	'discussions:upgrade:2017112800:description' => "Le risposte alle discussioni erano dei sottotipi a parte che ora sono stati uniformati ai commenti",
	'discussions:upgrade:2017112801:title' => "Migra le risposte alle discussioni sul river",
	'discussions:upgrade:2017112801:description' => "Le risposte alle discussioni erano dei sottotipi a parte che ora sono stati uniformati ai commenti",
);
