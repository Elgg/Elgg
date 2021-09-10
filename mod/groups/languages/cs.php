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
	
	'groups' => "Skupiny",
	'groups:owned' => "Skupiny, které vlastním",
	'groups:owned:user' => 'Skupiny, které vlastní %s',
	'groups:yours' => "Moje skupiny",
	'groups:user' => "%s - skupiny",
	'groups:all' => "Všechny skupiny",
	'groups:add' => "Vytvořit novou skupinu",
	'groups:edit' => "Upravit skupinu",
	'groups:membershiprequests' => 'Spravovat žádosti o přidání',
	'groups:membershiprequests:pending' => 'Spravovat žádosti o přidání (%s)',
	'groups:invitations' => 'Pozvání do skupin',
	'groups:invitations:pending' => 'Pozvání do skupin (%s)',

	'groups:icon' => 'Portrét skupiny (prázdné = žádné změny)',
	'groups:name' => 'Jméno skupiny',
	'groups:description' => 'Popis',
	'groups:briefdescription' => 'Stručný popis',
	'groups:interests' => 'Štítky',
	'groups:website' => 'Webové stránky',
	'groups:members' => 'Počet členů',

	'groups:members:title' => 'Členové skupiny %s',
	'groups:members:more' => "Zobrazit všechny členy",
	'groups:membership' => "Práva přístupu ke skupině",
	'groups:content_access_mode' => "Přístupnost obsahu skupiny",
	'groups:content_access_mode:warning' => "Varování: změna tohoto nastavení nemá vliv na již existující obsah skupiny.",
	'groups:content_access_mode:unrestricted' => "Volný - Přístup závisí na nastavení přístupu u obsahu",
	'groups:content_access_mode:membersonly' => "Pouze pro členy - Ne-členové nemají přístup k obsahu skupiny",
	'groups:access' => "Práva přístupu",
	'groups:owner' => "Vlastník",
	'groups:owner:warning' => "Varování: pokud tuto hodnotu změníte, nebudete nadále vlastníkem této skupiny.",
	'groups:widget:num_display' => 'Počet zobrazených skupin',
	'widgets:a_users_groups:name' => 'Členství ve skupině',
	'widgets:a_users_groups:description' => 'Zobrazuje skupiny kterých jste členem',

	'groups:noaccess' => 'K této skupině nemáte přístup',
	'groups:cantcreate' => 'Nemůžete vytvořit skupinu, to mohou pouze správci.',
	'groups:cantedit' => 'Nemůžete upravit tuto skupinu',
	'groups:saved' => 'Skupina uložena',
	'groups:save_error' => 'Skupinu není možné uložit',
	'groups:featured' => 'Zajímavé skupiny',
	'groups:makeunfeatured' => 'Odstranit ze zajímavých',
	'groups:makefeatured' => 'Zařadit do zajímavých',
	'groups:featuredon' => '%s je nyní zajímavá skupina.',
	'groups:unfeatured' => '%s přestala být zajímavou skupinou.',
	'groups:featured_error' => 'Neplatná skupina.',
	'groups:nofeatured' => 'Žádné zajímavé skupiny',
	'groups:joinrequest' => 'Žádost o členství',
	'groups:join' => 'Přidat se ke skupině',
	'groups:leave' => 'Opustit skupinu',
	'groups:invite' => 'Pozvat přátele',
	'groups:invite:title' => 'Pozvat přátele do této skupiny',

	'groups:nofriendsatall' => 'Nemáte žádné přátele, které byste mohl/a pozvat!',
	'groups:group' => "Skupina",
	'groups:search:title' => "Hledat skupiny se štítkem '%s'",
	'groups:search:none' => "Hledání neodpovídá žádná skupina",
	'groups:search_in_group' => "Hledat v této skupině",
	'groups:acl' => "Skupina: %s",
	'groups:acl:in_context' => 'Počet členů',

	'groups:notfound' => "Skupina nebyla nalezena",
	
	'groups:requests:none' => 'Aktuálně nejsou žádné požadavky na členství.',

	'groups:invitations:none' => 'Aktuálně neexistují žádná pozvání.',

	'groups:open' => "veřejná skupina",
	'groups:closed' => "uzavřená skupina",
	'groups:member' => "členů",

	'groups:more' => 'Více skupin',
	'groups:none' => 'Žádné skupiny',

	/**
	 * Access
	 */
	'groups:access:private' => 'Uzavřená - uživatelé musí být pozváni',
	'groups:access:public' => 'Veřejná - jakýkoliv uživatel se může přidat',
	'groups:access:group' => 'Pouze členové skupiny',
	'groups:closedgroup' => "Tato skupina je přístupna pouze jejím členům.",
	'groups:closedgroup:request' => 'Kliknutím na odkaz "Žádost o členství" odešlete požadavek na přidání do skupiny.',
	'groups:closedgroup:membersonly' => "Tato skupina je uzavřená a její obsah je dostupný pouze jejím členům.",
	'groups:opengroup:membersonly' => "Obsah této skupiny je dostupný pouze jejím členům.",
	'groups:opengroup:membersonly:join' => 'Kliknutím na odkaz "Připojit se ke skupině" se stanete členem skupiny.',
	'groups:visibility' => 'Kdo může vidět tuto skupinu?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Skupiny',

	'groups:notitle' => 'Skupina musí mít název',
	'groups:cantjoin' => 'Není možné přidat se ke skupině',
	'groups:cantleave' => 'Není možné opustit skupinu',
	'groups:removeuser' => 'Odebrat ze skupiny',
	'groups:cantremove' => 'Není možné odebrat uživatele ze skupiny',
	'groups:removed' => '%s byl/a úspěšně odebrán/a ze skupiny',
	'groups:addedtogroup' => 'Přidání uživatele do skupiny bylo úspěšné',
	'groups:joinrequestnotmade' => 'Požadavek k připojení ke skupině nelze provést',
	'groups:joinrequestmade' => 'Požadavek na přidání do skupiny byl úspěšně odeslán',
	'groups:joinrequest:exists' => 'Již jste požadoval/a přidání do této skupiny',
	'groups:joined' => 'Byl/a jste úspěšně přidán/a do skupiny!',
	'groups:left' => 'Odebrání ze skupiny bylo úspěšné',
	'groups:userinvited' => 'Uživatel byl pozván.',
	'groups:usernotinvited' => 'Uživatel nemohl být pozván.',
	'groups:useralreadyinvited' => 'Uživatel byl již pozván',
	'groups:invite:subject' => "%s vás pozval/a do skupiny %s!",
	'groups:joinrequest:remove:check' => 'Jste si jistý/á, že chcete smazat tuto žádost o přidání?',
	'groups:invite:remove:check' => 'Jste si jistý/á, že chcete smazat toto pozvání?',

	'groups:welcome:subject' => "Vítejte ve skupině %s!",

	'groups:request:subject' => "%s se chce přidat ke skupině %s",

	'groups:allowhiddengroups' => 'Chcete povolit soukromé (neviditelné) skupiny?',
	'groups:whocancreate' => 'Kdo může vytvářet nové skupiny?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Pozvání bylo smazáno.',
	'groups:joinrequestkilled' => 'Žádost o přidání byla smazána.',
	'groups:error:addedtogroup' => "Není možné přidat %s do skupiny",
	'groups:add:alreadymember' => "%s je již členem této skupiny",
	
	// Notification settings
);
