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
	
	'groups' => "Grupy",
	'groups:owned' => "Własne grupy",
	'groups:owned:user' => 'Grupy użytkownika %s',
	'groups:yours' => "Twoje grupy",
	'groups:user' => "%s grupy",
	'groups:all' => "Wszystkie grupy",
	'groups:add' => "Utwórz nową grupę",
	'groups:edit' => "Edytuj grupę",
	'groups:membershiprequests' => 'Zarządzaj prośbami o dołączenie',
	'groups:membershiprequests:pending' => 'Zarządzaj prośbami o dołączenie (%s)',
	'groups:invitations' => 'Zaproszenia do grupy',
	'groups:invitations:pending' => 'Zaproszenia do grupy (%s)',

	'groups:icon' => 'Ikona grupy',
	'groups:name' => 'Nazwa grupy',
	'groups:description' => 'Opis',
	'groups:briefdescription' => 'Krótki opis',
	'groups:interests' => 'Zainteresowania',
	'groups:website' => 'Strona www',
	'groups:members' => 'Członkowie grupy',

	'groups:members:title' => 'Członkowie %s',
	'groups:members:more' => "Wyświetl wszystkich członków",
	'groups:membership' => "Członkostwo",
	'groups:content_access_mode' => "Poziom dostępu do treści grupy.",
	'groups:content_access_mode:warning' => "Uwaga: Zmiana tego ustawienia nie wpłynie na uprawnienia do treści istniejących już w grupie.",
	'groups:content_access_mode:unrestricted' => "Brak ograniczeń - Dostęp zależy od ustawień poszczególnych elementów",
	'groups:content_access_mode:membersonly' => "Tylko dla członków - Osoby spoza grupy nie mają dostępu do treści w grupie",
	'groups:access' => "Uprawnienia dostępu",
	'groups:owner' => "Właściciel",
	'groups:owner:warning' => "Uwaga: jeśli zmienisz tą wartość, to przestaniesz być właścicielem tej grupy.",
	'groups:widget:num_display' => 'Liczba grup do wyświetlenia',
	'widgets:a_users_groups:name' => 'Członkostwo grupy',
	'widgets:a_users_groups:description' => 'Wyświetl w profilu grupy do których należę',

	'groups:noaccess' => 'Brak dostępu do grupy',
	'groups:cantcreate' => 'Nie możesz utworzyć grupy. Tylko administrator ma taką możliwość.',
	'groups:cantedit' => 'Nie można edytować tej grupy',
	'groups:saved' => 'Grupa zapisana',
	'groups:save_error' => 'Nie można zapisać grupy',
	'groups:featured' => 'Promowane grupy',
	'groups:makeunfeatured' => 'Przestań promować',
	'groups:makefeatured' => 'Promuj',
	'groups:featuredon' => '%s jest teraz promowaną grupą.',
	'groups:unfeatured' => '%s przestała być promowaną grupą.',
	'groups:featured_error' => 'Niepoprawna grupa.',
	'groups:nofeatured' => 'Brak wyróżnionych grup',
	'groups:joinrequest' => 'Prośba o członkostwo',
	'groups:join' => 'Dołącz do grupy',
	'groups:leave' => 'Odejdź z grupy',
	'groups:invite' => 'Zaproś przyjaciół',
	'groups:invite:title' => 'Zaproś znajomych do tej grupy',

	'groups:nofriendsatall' => 'Nie masz znajomych, których mógłbyś zaprosić!',
	'groups:group' => "Grupa",
	'groups:search:title' => "Szukaj grup oznaczonych tagiem '%s'",
	'groups:search:none' => "Nie znaleziono pasujących grup",
	'groups:search_in_group' => "Szukaj w tej grupie",
	'groups:acl' => "Grupa: %s",
	'groups:acl:in_context' => 'Członkowie grupy',

	'groups:notfound' => "Nie znaleziono grupy",
	
	'groups:requests:none' => 'Nie ma obecnie próśb o dołączenie.',

	'groups:invitations:none' => 'Nie ma obecnie zaproszeń.',

	'groups:open' => "otwartych grup",
	'groups:closed' => "zamkniętych grup",
	'groups:member' => "członkowie",

	'groups:more' => 'Więcej grup',
	'groups:none' => 'Brak grup',

	/**
	 * Access
	 */
	'groups:access:private' => 'Zamknięta - Użytkownik musi mieć zaproszenie',
	'groups:access:public' => 'Otwarta - Każdy użytkownik może dołączyć',
	'groups:access:group' => 'Tylko członkowie grupy',
	'groups:closedgroup' => "Ta grupa jest zamknięta.",
	'groups:closedgroup:request' => 'Aby zostać dodanym, użyj linku "Prośba o członkostwo".',
	'groups:closedgroup:membersonly' => "Ta grupa jest zamknięta i jej treść jest dostępna wyłącznie dla członków.",
	'groups:opengroup:membersonly' => "Treść grupy jest dostępna tylko dla jej członków.",
	'groups:opengroup:membersonly:join' => 'Aby zostać członkiem, kliknij link "Dołącz do grupy".',
	'groups:visibility' => 'Kto może zobaczyć tą grupę?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupy',

	'groups:notitle' => 'Grupy muszą mieć tytuł',
	'groups:cantjoin' => 'Nie możesz dołączyć do grupy',
	'groups:cantleave' => 'Nie możesz odejść z grupy.',
	'groups:removeuser' => 'Usuń z grupy',
	'groups:cantremove' => 'Nie powiodło się usunięcie użytkownika z grupy',
	'groups:removed' => 'Pomyślnie usunięto %s z grupy',
	'groups:addedtogroup' => 'Użytkownik został pomyślnie dodany do tej grupy.',
	'groups:joinrequestnotmade' => 'Dołączenie do grupy nie powiodło się.',
	'groups:joinrequestmade' => 'Prośba o dołączenie do grupy wysłana pomyślnie',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:joined' => 'Pomyślnie dołączyłeś do grupy!',
	'groups:left' => 'Pomyślnie opuściłeś grupę',
	'groups:userinvited' => 'Użytkownik został zaproszony.',
	'groups:usernotinvited' => 'Użytkownik nie został zaproszony.',
	'groups:useralreadyinvited' => 'Użytkownik już został zaproszony',
	'groups:invite:subject' => "%s zostałeś zaproszony do %s!",
	'groups:joinrequest:remove:check' => 'Czy na pewno chcesz usunąć tą prośbę o członkostwo?',
	'groups:invite:remove:check' => 'Czy na pewno chcesz usunąć to zaproszenie?',

	'groups:welcome:subject' => "Witamy w %s !",

	'groups:request:subject' => "%s wystąpił z prośbą o dołączenie do %s",

	'groups:allowhiddengroups' => 'Czy zezwolić na prywatne (niewidoczne) grupy?',
	'groups:whocancreate' => 'Kto może utworzyć nową grupę?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Usunięto zaproszenie.',
	'groups:joinrequestkilled' => 'Usunięto prośbę o członkostwo.',
	'groups:error:addedtogroup' => "Nie można było dodać %s do grupy",
	'groups:add:alreadymember' => "%s jest już członkiem tej grupy",
	
	// Notification settings
);
