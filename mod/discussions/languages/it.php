<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Argomenti di discussione",
	
	'add:object:discussion' => 'Aggiungi argomento',
	'edit:object:discussion' => 'Modifica argomento',
	'collection:object:discussion:group' => 'Forum di gruppo',

	'discussion:latest' => 'Ultimi argomenti',
	'discussion:none' => 'Nessuna discussione',
	'discussion:updated' => "Ultima risposta di %s %s",

	'discussion:topic:created' => 'L\'argomento è stato creato.',
	'discussion:topic:updated' => 'L\'argomento è stato aggiornato.',
	'entity:delete:object:discussion:success' => 'L\'argomento è stato eliminato.',

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

	'discussion:comment:notify:summary' => 'Nuova risposta alla discussione: %s',
	'discussion:comment:notify:subject' => 'Nuova risposta alla discussione: %s',

	'groups:tool:forum' => 'Abilita discussioni di gruppo',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Stato dell\'argomento',
	'discussion:topic:closed:title' => 'Questa discussione è chiusa',
	'discussion:topic:closed:desc' => 'Questa discussione è chiusa e non accetta nuovi commenti',

	'discussion:topic:description' => 'Contenuti dell\'argomento',
);
