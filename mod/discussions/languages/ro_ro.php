<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:discussion' => "Subiectul discuției",
	
	'add:object:discussion' => 'Adaugă subiect de discuție',
	'edit:object:discussion' => 'Editează subiectul',
	'collection:object:discussion' => 'Subiecte de dicuție',
	'collection:object:discussion:group' => 'Discuții de grup',
	'collection:object:discussion:my_groups' => 'Discuțiile din grupurile mele',
	
	'discussion:settings:enable_global_discussions' => 'Activează discuțiile globale',
	'discussion:settings:enable_global_discussions:help' => 'Permite ca discuțiile să fie create în afara grupurilor',

	'discussion:latest' => 'Ultimele discuții',
	'discussion:none' => 'Nu sunt discuții',
	'discussion:updated' => "Ultimul comentariu de către %s%s",

	'discussion:topic:created' => 'Subiectul de discuție a fost creat',
	'discussion:topic:updated' => 'Subiectul de discuție a fost actualizat.',
	'entity:delete:object:discussion:success' => 'Subiectul de discuție a fost șters.',

	'discussion:topic:notfound' => 'Nu s-a găsit subiectul de discuție',
	'discussion:error:notsaved' => 'Nu s-a putut salva acest subiect',
	'discussion:error:missing' => 'Ambele câmpuri de titlu și mesaj sunt necesare',
	'discussion:error:permissions' => 'Nu ai permisiunea pentru a efectua această acțiune',
	'discussion:error:no_groups' => "Nu ești membru al vreunui grup.",

	/**
	 * River
	 */
	'river:object:discussion:create' => '%s a adăugat un nou subiect de discuție %s',
	'river:object:discussion:comment' => '%s a comentat la subiectul de discuție %s',
	
	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Subiect nou de discuție numit %s',
	'discussion:topic:notify:subject' => 'Subiect nou de discuție: %s',
	'discussion:topic:notify:body' =>
'%s a adăugat un subiect nou de discuție "%s":

%s

Vezi și răspunde la subiectul de discuție:
%s
',

	'discussion:comment:notify:summary' => 'Comentariu nou la subiect: %s',
	'discussion:comment:notify:subject' => 'Comentariu nou la subiect: %s',
	'discussion:comment:notify:body' =>
'%s a comentat la subiectul de discuție "%s":

%s

Vezi și comentează la discuția:
%s
',

	'groups:tool:forum' => 'Activează discuțiile de grup',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Starea subiectului',
	'discussion:topic:closed:title' => 'Această discuție este închisă.',
	'discussion:topic:closed:desc' => 'Această discuție este închisă și nu acceptă comentarii noi.',

	'discussion:topic:description' => 'Mesajul subiectului',

	// upgrades
	'discussions:upgrade:2017112800:title' => "Migrează răspunsurile de discuție la comentarii",
	'discussions:upgrade:2017112800:description' => "Răspunsurile de discuție aveau cândva propriul subtip, acest lucru a fost unificat în comentarii.",
	'discussions:upgrade:2017112801:title' => "Migrează activitatea de flux asociată cu răspunsurile de discuție",
	'discussions:upgrade:2017112801:description' => "Răspunsurile de discuție aveau cândva propriul subtip, acest lucru a fost unificat în comentarii.",
);
