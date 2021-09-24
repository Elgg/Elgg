<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	
	'groups' => "Gruppen",
	'groups:owned' => "Von mir gegründete Gruppen",
	'groups:owned:user' => 'Gruppen gegründet von %s',
	'groups:yours' => "Meine Gruppen",
	'groups:user' => "Gruppen von %s",
	'groups:all' => "Alle Gruppen",
	'groups:add' => "Neue Gruppe",
	'groups:edit' => "Gruppen-Einstellungen bearbeiten",
	'groups:edit:profile' => "Profil",
	'groups:edit:access' => "Zugang",
	'groups:edit:tools' => "Tools",
	'groups:edit:settings' => "Einstellungen",
	'groups:membershiprequests' => 'Verwalte Beitritts-Anfragen',
	'groups:membershiprequests:pending' => 'Verwalte Beitritts-Anfragen (%s)',
	'groups:invitedmembers' => "Beitritts-Anfragen verwalten",
	'groups:invitations' => 'Einladungen zum Gruppenbeitritt',
	'groups:invitations:pending' => 'Einladungen zum Gruppenbeitritt (%s)',
	
	'relationship:invited' => '%2$s wurde eingeladen, %1$s beizutreten',
	'relationship:membership_request' => '%s hat angefragt, %s beizutreten',

	'groups:icon' => 'Gruppen-Icon (leer lassen, um nicht zu ändern)',
	'groups:name' => 'Gruppenname',
	'groups:description' => 'Beschreibung',
	'groups:briefdescription' => 'Kurzbeschreibung',
	'groups:interests' => 'Tags',
	'groups:website' => 'Webseite',
	'groups:members' => 'Gruppen-Mitglieder',

	'groups:members_count' => '%s Mitglieder',

	'groups:members:title' => 'Mitglieder von %s',
	'groups:members:more' => "Alle Mitglieder auflisten",
	'groups:membership' => "Beschränkung des Gruppenbeitritts",
	'groups:content_access_mode' => "Zugriffsberechtigung für Inhalte der Gruppe",
	'groups:content_access_mode:warning' => "Warnung: eine Änderung dieser Einstellung verändert nicht die Zugangsberechtigungen für schon existierende Inhalte der Gruppe.",
	'groups:content_access_mode:unrestricted' => "Ohne Beschränkung - Zugriffsbeschränkung allein durch inhaltsspezifischen Zugangslevel",
	'groups:content_access_mode:membersonly' => "Nur Mitglieder - Inhalte der Gruppe für andere nicht zugänglich",
	'groups:access' => "Zugangslevel",
	'groups:owner' => "Gründer",
	'groups:owner:warning' => "Warnung: wenn Du diesen Wert veränderst, bist Du nicht länger der Gründer dieser Gruppe.",
	'groups:widget:num_display' => 'Anzahl der anzuzeigenden Gruppen',
	'widgets:a_users_groups:name' => 'Gruppen-Mitgliedschaft',
	'widgets:a_users_groups:description' => 'Auflistung der Gruppen, in denen Du Mitglied bist.',

	'groups:noaccess' => 'Zugang zur Gruppe verweigert.',
	'groups:cantcreate' => 'Du kannst keine Gruppe erstellen. Dies können nur Administratoren.',
	'groups:cantedit' => 'Du kannst die Gruppen-Einstellungen nicht bearbeiten.',
	'groups:saved' => 'Gruppe angelegt.',
	'groups:save_error' => 'Beim Anlegen der Gruppe ist ein Fehler aufgetreten.',
	'groups:featured' => 'Besondere Gruppen',
	'groups:makeunfeatured' => 'Aus "Besondere Gruppen" entfernen',
	'groups:makefeatured' => 'Zu "Besondere Gruppen" hinzufügen',
	'groups:featuredon' => '%s ist nun eine "Besondere Gruppe".',
	'groups:unfeatured' => '%s wurde aus der Liste der "Besonderen Gruppen" entfernt.',
	'groups:featured_error' => 'Ungültige Gruppe.',
	'groups:nofeatured' => 'Keine "Besonderen Gruppen" vorhanden.',
	'groups:joinrequest' => 'Gruppenbeitritt beantragen',
	'groups:join' => 'Gruppe beitreten',
	'groups:leave' => 'Gruppe verlassen',
	'groups:invite' => 'Freunde einladen',
	'groups:invite:title' => 'Lade Deine Freunde ein, dieser Gruppe beizutreten',
	'groups:invite:friends:help' => 'Suche mit Name oder Benutzername nach einem Freund und wähle den Freund aus der Liste aus',
	'groups:invite:resend' => 'Einladungen an bereits eingeladene Mitglieder erneut senden',
	'groups:invite:member' => 'Bereits Mitglied dieser Gruppe',
	'groups:invite:invited' => 'Bereits eingeladen, dieser Gruppe beizutreten',

	'groups:nofriendsatall' => 'Du hast leider noch keine Freunde, die Du einladen könntest!',
	'groups:group' => "Gruppe",
	'groups:search:title' => "Suche nach Gruppen mit dem Tag '%s'",
	'groups:search:none' => "Es wurden keine passenden Gruppen gefunden.",
	'groups:search_in_group' => "In dieser Gruppe suchen",
	'groups:acl' => "Gruppe: %s",
	'groups:acl:in_context' => 'Gruppen-Mitglieder',

	'groups:notfound' => "Gruppe nicht gefunden.",
	
	'groups:requests:none' => 'Derzeit gibt es keine ausstehenden Anfragen für einen Beitritt zu dieser Gruppe.',

	'groups:invitations:none' => 'Derzeit gibt es keine unbeantworteten Einladungen zum Beitreten in diese Gruppe.',

	'groups:open' => "Öffentliche Gruppe",
	'groups:closed' => "Nicht-öffentliche Gruppe",
	'groups:member' => "Mitglieder",
	'groups:search' => "Suche nach Gruppen",

	'groups:more' => 'Weitere Gruppen',
	'groups:none' => 'Keine Gruppen',

	/**
	 * Access
	 */
	'groups:access:private' => 'Nicht-öffentliche Gruppe - Gruppenbeitritt nur mit Einladung möglich',
	'groups:access:public' => 'Öffentliche Gruppe - jeder Benutzer kann der Gruppe beitreten',
	'groups:access:group' => 'Nur für Gruppenmitglieder',
	'groups:closedgroup' => "Diese Gruppe ist nicht-öffentlich.",
	'groups:closedgroup:request' => 'Um dieser Gruppe beitreten zu dürfen, wähle bitte den Menueintrag "Gruppenbeitritt beantragen".',
	'groups:closedgroup:membersonly' => "Diese Gruppe ist nicht-öffentlich und ihr Inhalt ist nur für Gruppenmitglieder zugänglich.",
	'groups:opengroup:membersonly' => "Der Inhalt dieser Gruppe ist nur für Gruppenmitglieder zugänglich.",
	'groups:opengroup:membersonly:join' => 'Um dieser Gruppe beizutreten, wähle bitte den Menueintrag "Gruppe beitreten".',
	'groups:visibility' => 'Wer kann diese Gruppe sehen?',
	'groups:content_default_access' => 'Standardmäßiger Zugangslevel der Gruppe',
	'groups:content_default_access:help' => 'Hier kannst Du den Zugangslevel festlegen, der für neue Inhalte dieser Gruppe standardmäßig verwendet werden soll. Allerdings setzt möglicherweise setzt der Zugangsmodus der Gruppe den gewählten Zugangslevel außer Kraft (falls der Zugriff auf Inhalte der Gruppe insgesamt beschränkt wurde).',
	'groups:content_default_access:not_configured' => 'Keinen Standard-Zugangslevel festlegen, Einstellung dem Benutzer überlassen',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Gruppen',

	'groups:notitle' => 'Gruppen müssen einen Titel haben.',
	'groups:cantjoin' => 'Du kannst dieser Gruppe nicht beitreten.',
	'groups:cantleave' => 'Das Verlassen der Gruppe ist fehlgeschlagen.',
	'groups:removeuser' => 'Aus Gruppe entfernen',
	'groups:cantremove' => 'Der Benutzer konnte nicht aus der Gruppe entfernt werden.',
	'groups:removed' => '%s wurde aus der Gruppe entfernt.',
	'groups:addedtogroup' => 'Der Benutzer wurde als Mitglied der Gruppe hinzugefügt.',
	'groups:joinrequestnotmade' => 'Die Anfrage zum Beitritt zur Gruppe ist fehlgeschlagen.',
	'groups:joinrequestmade' => 'Die Anfrage zum Beitritt zur Gruppe wurde gesendet.',
	'groups:joinrequest:exists' => 'Du hast bereits eine Anfrage zum Beitritt zu dieser Gruppe gestellt.',
	'groups:button:joined' => 'Beigetreten',
	'groups:button:owned' => 'Gegründet',
	'groups:joined' => 'Du bist der Gruppe beigetreten!',
	'groups:left' => 'Du hast die Gruppe verlassen.',
	'groups:userinvited' => 'Das Community-Mitglied wurde eingeladen.',
	'groups:usernotinvited' => 'Die Einladung an das Mitglied konnte nicht gesendet werden.',
	'groups:useralreadyinvited' => 'Dieses Mitglied wurde bereits eingeladen.',
	'groups:invite:subject' => "Hallo %s, Du wurdest eingeladen, der Gruppe %s beizutreten!",
	'groups:joinrequest:remove:check' => 'Bist Du sicher, dass Du diese Anfrage zum Gruppenbeitritt löschen willst?',
	'groups:invite:remove:check' => 'Bist Du sicher, dass Du diese Einladung zum Gruppenbeitritt löschen willst?',
	'groups:invite:body' => "%s hat Dich eingeladen, der Gruppe '%s' beizutreten.

Folge dem Link um Deine ausstehenden Einladungen zum Beitreten in Gruppen zu sehen:

%s",

	'groups:welcome:subject' => "Willkommen in der Gruppe %s!",
	'groups:welcome:body' => "Du bist nun ein Mitglied der Gruppe '%s'.

Folge dem Link um einen Beitrag in der Gruppe zu schreiben!

%s",

	'groups:request:subject' => "%s hat beantragt, der Gruppe %s beitreten zu dürfen",
	'groups:request:body' => "%s hat beantragt, der Gruppe '%s' beitreten zu dürfen.

Folge dem Link um ihr/sein Profil zu sehen:

%s

oder folge dem nächsten Link, um die ausstehenden Anfragen zum Gruppenbeitritt zu sehen:

%s",

	'river:group:create' => '%s hat die Gruppe %s gegründet',
	'river:group:join' => '%s ist der Gruppe %s beigetreten',

	'groups:allowhiddengroups' => 'Möchtest Du private (versteckte) Gruppen zulassen?',
	'groups:whocancreate' => 'Wer soll neue Gruppen hinzufügen dürfen?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Die Einladung wurde gelöscht.',
	'groups:joinrequestkilled' => 'Der Antrag zum Gruppenbeitritt wurde gelöscht.',
	'groups:error:addedtogroup' => "Das Hinzufügen von %s als Mitglied der Gruppe ist fehlgeschlagen.",
	'groups:add:alreadymember' => "%s ist bereits ein Mitglied dieser Gruppe.",
	
	// Notification settings
	'groups:usersettings:notification:group_join:description' => "Standardeinstellung für Gruppen-Benachrichtigungen beim Beitritt zu einer neuen Gruppe",
	
	'groups:usersettings:notifications:title' => 'Gruppen-Benachrichtigungen',
	'groups:usersettings:notifications:description' => 'Um Benachrichtigungen zu erhalten, wenn zu einer Gruppe, in der Du Mitglied bist, neue Inhalte hinzugefügt werden, kannst Du individuell für jede Gruppe im Folgenden die Methode(n) festlegen, die verwendet werden soll(en).',
);
