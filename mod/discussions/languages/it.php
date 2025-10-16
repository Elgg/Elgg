<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Argomento di discussione",
	'collection:object:discussion' => 'Argomenti di discussione',
	'list:object:discussion:no_results' => 'Nessuna discussione trovata',
	
	'add:object:discussion' => 'Aggiungi argomento',
	'edit:object:discussion' => 'Modifica argomento',
	'collection:object:discussion:group' => 'Forum di gruppo',
	'collection:object:discussion:my_groups' => 'Discussioni nei miei gruppi',
	
	'notification:object:discussion:create' => "Invia una notifica quando viene creata una discussione",
	
	'discussion:settings:enable_global_discussions' => 'Abilita discussioni globali',
	'discussion:settings:enable_global_discussions:help' => 'Permetti di creare discussioni al di fuori dei gruppi',

	'discussion:latest' => 'Ultimi argomenti',
	'discussion:updated' => "Ultima risposta di %s %s",

	'discussion:topic:created' => 'L\'argomento è stato creato.',
	'discussion:topic:updated' => 'L\'argomento è stato aggiornato.',
	'entity:delete:object:discussion:success' => 'L\'argomento è stato eliminato.',
	
	'entity:edit:object:discussion:success' => 'L\'argomento è stato salvato con successo',
	
	'discussion:topic:notfound' => 'Argomento non trovato.',
	'discussion:error:notsaved' => 'Impossibile salvare questo argomento',
	'discussion:error:missing' => 'Sia il titolo sia il messaggio sono campi obbligatori',
	'discussion:error:permissions' => 'Permessi insufficienti per completare questa azione',
	'discussion:error:no_groups' => "Non sei membro di nessun gruppo",

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

	'discussion:comment:notify:summary' => 'Nuova risposta alla discussione: %s',
	'discussion:comment:notify:subject' => 'Nuova risposta alla discussione: %s',

	'groups:tool:forum' => 'Abilita discussioni di gruppo',
	'groups:tool:forum:description' => 'Permetti ai membri del gruppo di iniziare un argomento in questo gruppo',

	'discussions:groups:edit:add_group_subscribers_to_discussion_comments' => 'Aggiungi un membro del gruppo alle notifiche dei commenti sull\'argomento',
	
	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Stato dell\'argomento',
	'discussion:topic:closed:title' => 'Questa discussione è chiusa',
	'discussion:topic:closed:desc' => 'Questa discussione è chiusa e non accetta nuovi commenti',

	'discussion:topic:description' => 'Contenuti dell\'argomento',
	
	// widgets
);
