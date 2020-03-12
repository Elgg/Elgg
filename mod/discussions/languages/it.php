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
	'collection:object:discussion' => 'Discussion topics',
	'collection:object:discussion:group' => 'Forum di gruppo',
	'collection:object:discussion:my_groups' => 'Discussions in my groups',
	
	'discussion:settings:enable_global_discussions' => 'Enable global discussions',
	'discussion:settings:enable_global_discussions:help' => 'Allow discussions to be created outside of groups',

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
	'discussion:error:no_groups' => "You're not a member of any groups.",

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

	'groups:tool:forum' => 'Abilita discussioni di gruppo',

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
