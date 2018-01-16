<?php

return array(
	'discussion' => 'Diskuse',
	'discussion:add' => 'Přidat téma do diskuse',
	'discussion:latest' => 'Nejnovější diskuse',
	'discussion:group' => 'Skupinové diskuse',
	'discussion:none' => 'Žádné diskuse',
	'discussion:reply:title' => 'Odpověď od %s',
	'discussion:new' => "Přidat příspěvek do diskuse",
	'discussion:updated' => "Poslední odpověď od uživatele %s %s",

	'discussion:topic:created' => 'Téma diskuse bylo založeno.',
	'discussion:topic:updated' => 'Téma diskuse bylo aktualizováno.',
	'discussion:topic:deleted' => 'Téma diskuse bylo smazáno.',

	'discussion:topic:notfound' => 'Téma diskuse nebylo nalezeno.',
	'discussion:error:notsaved' => 'Toto téma nemohu uložit.',
	'discussion:error:missing' => 'Je vyžadován nadpis a sdělení.',
	'discussion:error:permissions' => 'Nemáte oprávnění k této akci',
	'discussion:error:notdeleted' => 'Nemohu smazat téma diskuse',

	'discussion:reply:edit' => 'Upravit odpověď',
	'discussion:reply:deleted' => 'Odpověď do diskuse byla smazána.',
	'discussion:reply:error:notfound' => 'Odpověď do diskuse nebyla nalezena.',
	'discussion:reply:error:notfound_fallback' => "Bohužel jsme nemohli najít požadovanou odpověď, ale přesměrovali jsme vás na původní diskusní téma.",
	'discussion:reply:error:notdeleted' => 'Nemohu smazat odpověď do diskuse',

	'discussion:search:title' => 'Odpověď na téma: %s',

	/**
	 * Action messages
	 */
	'discussion:reply:missing' => 'Nemůžete odeslat prázdnou odpověď',
	'discussion:reply:topic_not_found' => 'Téma diskuse nebylo nalezeno',
	'discussion:reply:error:cannot_edit' => 'Nemáte oprávnění upravovat tuto odpověď',
	'discussion:reply:error:permissions' => 'Nemáte povoleno odpovídat na toto téma',

	/**
	 * River
	 */
	'river:create:object:discussion' => '%s přidal/a novou diskusi na téma %s',
	'river:reply:object:discussion' => '%s odpověděl/a do diskuse na téma %s',
	'river:reply:view' => 'zobrazit odpověď',

	/**
	 * Notifications
	 */
	'discussion:topic:notify:summary' => 'Nové diskusní téma s názvem %s',
	'discussion:topic:notify:subject' => 'Nové diskusní téma: %s',
	'discussion:topic:notify:body' =>
'%s přidal/a novou diskusi na téma "%s":

%s

Pro zobrazení a komentáře použijte následující odkaz:
%s
',

	'discussion:reply:notify:summary' => 'Nová odpověď v tématu: %s',
	'discussion:reply:notify:subject' => 'Nová odpověď v tématu: %s',
	'discussion:reply:notify:body' =>
'%s odpověděl/a na diskusní téma "%s":

%s

Pro zobrazení a komentáře použijte následující odkaz:
%s
',

	'item:object:discussion' => "Témata diskusí",
	'item:object:discussion_reply' => "Odpovědi v diskusích",

	'groups:enableforum' => 'Povolit skupinové diskuse',

	'reply:this' => 'Odpovědět',

	/**
	 * ecml
	 */
	'discussion:ecml:discussion' => 'Skupinové diskuse',

	/**
	 * Discussion status
	 */
	'discussion:topic:status' => 'Stav tématu',
	'discussion:topic:closed:title' => 'Tato diskuse je uzavřena.',
	'discussion:topic:closed:desc' => 'Tato diskuse je uzavřena a nepřijímá žádné další komentáře.',

	'discussion:replies' => 'Odpovědi',
	'discussion:addtopic' => 'Přidat téma',
	'discussion:post:success' => 'Váše odpověď byla úspěšně publikována.',
	'discussion:post:failure' => 'Při ukládání vaší odpovědi se vyskytl problém',
	'discussion:topic:edit' => 'Upravit téma',
	'discussion:topic:description' => 'Popis tématu',

	'discussion:reply:edited' => "Úspěšně jste upravil/a diskusní příspěvek.",
	'discussion:reply:error' => "Při ukládání diskusního příspěvku se vyskytl problém.",
);
