<?php

return array(
	'discussion' => 'Discuții',
	'discussion:add' => 'Adaugă subiect discuției',
	'discussion:latest' => 'Ultimele discuții',
	'discussion:group' => 'Discuții de grup',
	'discussion:none' => 'Nu există discuții',
	'discussion:reply:title' => 'Răspuns de către %s',
	'discussion:new' => "Adaugă discuție",
	'discussion:updated' => "Ultimul răspuns de către %s %s",

	'discussion:topic:created' => 'Subiectul discuției a fost creat.',
	'discussion:topic:updated' => 'Subiectul discuției a fost actualizat.',
	'discussion:topic:deleted' => 'Subiectul discuției a fost șters.',

	'discussion:topic:notfound' => 'Nu s-a găsit subiectul discuției',
	'discussion:error:notsaved' => 'Nu s-a putut salva acest subiect',
	'discussion:error:missing' => 'Ambele câmpuri de titlu și conținut sunt necesare',
	'discussion:error:permissions' => 'Nu îți este permis să realizezi această acțiune',
	'discussion:error:notdeleted' => 'Nu s-a putut șterge subiectul discuției',

	'discussion:reply:edit' => 'Editează răspunsul',
	'discussion:reply:deleted' => 'Răspunsul discuției a fost șters.',
	'discussion:reply:error:notfound' => 'Nu s-a găsit răspunsul discuției',
	'discussion:reply:error:notfound_fallback' => "Ne cerem scuze, nu am putut găsi răspunsul specific, dar te-am redirecționat la subiectul original al discuției.",
	'discussion:reply:error:notdeleted' => 'Nu s-a putut șterge răspunsul dicuției',

	'discussion:search:title' => 'Răspunsul subiectului: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Nu poți posta un răspuns gol',
	'discussion:reply:topic_not_found' => 'Subiectul discuției nu a fost găsit',
	'discussion:reply:error:cannot_edit' => 'Nu ai permisiunea necesară să editezi acest răspuns',
	'discussion:reply:error:permissions' => 'Nu îți este permis să răspunzi acestui subiect',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s a adăugat un nou subiect de discuție %s',
	'river:reply:object:discussion' => '%s a răspuns la subiectul discuției %s',
	'river:reply:view' => 'vezi răspunsul',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Un nou subiect de discuție numit %s',
	'discussion:topic:notify:subject' => 'Subiect nou de discuție: %s',
	'discussion:topic:notify:body' =>
'%s a adăugat un nou subiect de discuție "%s":

%s

Vizualizează și răspunde la acest subiect de dicuție:
%s
',

	'discussion:reply:notify:summary' => 'Răspuns nou la subiectul: %s',
	'discussion:reply:notify:subject' => 'Răspuns nou la subiectul: %s',
	'discussion:reply:notify:body' =>
'%s a răspuns la subiectul de discuție "%s":

%s

Vizualizează și răspunde discuției:
%s
',

	'item:object:discussion' => "Subiecte de discuție",
	'item:object:discussion_reply' => "Răspunsuri de discuție",

	'groups:enableforum' => 'Activează discuțiile de grup',

	'reply:this' => 'Răspunde la asta',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Discuții de grup',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Starea subiectului',
	'discussion:topic:closed:title' => 'Această discuție este închisă.',
	'discussion:topic:closed:desc' => 'Această discuție este închisă și nu mai poate primii comentarii.',

	'discussion:replies' => 'Răspunsuri',
	'discussion:addtopic' => 'Adaugă subiect',
	'discussion:post:success' => 'Răspunsul tău a fost postat cu succes',
	'discussion:post:failure' => 'A apărut o problemă la postarea răspunsului tău',
	'discussion:topic:edit' => 'Editează subiectul',
	'discussion:topic:description' => 'Mesajul subiectului',

	'discussion:reply:edited' => "Ai editat cu succes răspunsul.",
	'discussion:reply:error' => "A apărut o problemă la editarea răspunsului tău.",
);
