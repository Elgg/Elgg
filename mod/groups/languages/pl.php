<?php
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
	'groups:delete' => 'Usuń grupę',
	'groups:membershiprequests' => 'Zarządzaj prośbami o dołączenie',
	'groups:membershiprequests:pending' => 'Zarządzaj prośbami o dołączenie (%s)',
	'groups:invitations' => 'Zaproszenia do grupy',
	'groups:invitations:pending' => 'Zaproszenia do grupy (%s)',

	'groups:icon' => 'Ikona grupy',
	'groups:name' => 'Nazwa grupy',
	'groups:username' => 'Krótka nazwa grupy (wyświetlana w adresach URL, tylko litery i cyfry)',
	'groups:description' => 'Opis',
	'groups:briefdescription' => 'Krótki opis',
	'groups:interests' => 'Zainteresowania',
	'groups:website' => 'Strona www',
	'groups:members' => 'Członkowie grupy',
	'groups:my_status' => 'Twój status',
	'groups:my_status:group_owner' => 'Jesteś właścicielem tej grupy',
	'groups:my_status:group_member' => 'Jesteś członkiem tej grupy',
	'groups:subscribed' => 'Group notifications are on',
	'groups:unsubscribed' => 'Group notifications are off',

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
	'groups:widget:membership' => 'Członkostwo grupy',
	'groups:widgets:description' => 'Wyświetl w profilu grupy do których należę',

	'groups:widget:group_activity:title' => 'Aktywność w grupie',
	'groups:widget:group_activity:description' => 'Wyświetla aktywność w jednej z twoich grup',
	'groups:widget:group_activity:edit:select' => 'Wybierz grupę',
	'groups:widget:group_activity:content:noactivity' => 'Brak aktywności w tej grupie',
	'groups:widget:group_activity:content:noselect' => 'Edytuj ustawienia gadżetu aby wybrać grupę',

	'groups:noaccess' => 'Brak dostępu do grupy',
	'groups:ingroup' => 'w grupie',
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
	'groups:inviteto' => "Zaproszeni przyjaciele do '%s'",
	'groups:nofriends' => "Nie masz przyjaciół którzy zostali zaproszeni do tej grupy.",
	'groups:nofriendsatall' => 'Nie masz znajomych, których mógłbyś zaprosić!',
	'groups:viagroups' => "przez grupy",
	'groups:group' => "Grupa",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Szukaj grup oznaczonych tagiem '%s'",
	'groups:search:none' => "Nie znaleziono pasujących grup",
	'groups:search_in_group' => "Szukaj w tej grupie",
	'groups:acl' => "Grupa: %s",

	'groups:activity' => "Aktywność w grupie",
	'groups:enableactivity' => 'Włącz aktywność w grupie',
	'groups:activity:none' => "Jak na razie, brak aktywności w tej grupie",

	'groups:notfound' => "Nie znaleziono grupy",
	'groups:notfound:details' => "Wskazana grupa nie istnieje lub nie masz do niej dostępu",

	'groups:requests:none' => 'Nie ma obecnie próśb o dołączenie.',

	'groups:invitations:none' => 'Nie ma obecnie zaproszeń.',

	'groups:count' => "utworzonych grup",
	'groups:open' => "otwartych grup",
	'groups:closed' => "zamkniętych grup",
	'groups:member' => "członkowie",
	'groups:searchtag' => "Wyszukuj grupy za pomocą tagów",

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
	'groups:lastupdated' => 'Ostatnia aktualizacja %s przez %s',
	'groups:lastcomment' => 'Ostatni komentarz %s przez %s',

	'admin:groups' => 'Grupy',

	'groups:privategroup' => 'Ta grupa jest zamknięta, wysłaliśmy prośbę o członkostwo.',
	'groups:notitle' => 'Grupy muszą mieć tytuł',
	'groups:cantjoin' => 'Nie możesz dołączyć do grupy',
	'groups:cantleave' => 'Nie możesz odejść z grupy.',
	'groups:removeuser' => 'Usuń z grupy',
	'groups:cantremove' => 'Nie powiodło się usunięcie użytkownika z grupy',
	'groups:removed' => 'Pomyślnie usunięto %s z grupy',
	'groups:addedtogroup' => 'Użytkownik został pomyślnie dodany do tej grupy.',
	'groups:joinrequestnotmade' => 'Dołączenie do grupy nie powiodło się.',
	'groups:joinrequestmade' => 'Prośba o dołączenie do grupy wysłana pomyślnie',
	'groups:joined' => 'Pomyślnie dołączyłeś do grupy!',
	'groups:left' => 'Pomyślnie opuściłeś grupę',
	'groups:notowner' => 'Niestety, nie jesteś właścicielem tej grupy.',
	'groups:notmember' => 'Przykro nam, nie jesteś członkiem tej grupy.',
	'groups:alreadymember' => 'Jesteś już członkiem tej grupy!',
	'groups:userinvited' => 'Użytkownik został zaproszony.',
	'groups:usernotinvited' => 'Użytkownik nie został zaproszony.',
	'groups:useralreadyinvited' => 'Użytkownik już został zaproszony',
	'groups:invite:subject' => "%s zostałeś zaproszony do %s!",
	'groups:started' => "Rozpoczął %s",
	'groups:joinrequest:remove:check' => 'Czy na pewno chcesz usunąć tą prośbę o członkostwo?',
	'groups:invite:remove:check' => 'Czy na pewno chcesz usunąć to zaproszenie?',
	'groups:invite:body' => "Cześć %s,

Zostałeś zaproszony do '% s' grupy, kliknij przycisk poniżej aby potwierdzić:

%s",

	'groups:welcome:subject' => "Witamy w %s !",
	'groups:welcome:body' => "Cześć %s!
		
Jesteś już członkiem grupy \"% s\"! Kliknij poniżej aby rozpocząć postowanie!

%s",

	'groups:request:subject' => "%s wystąpił z prośbą o dołączenie do %s",
	'groups:request:body' => "Cześć %s,

%s wystąpił z wnioskiem o dołączenie do grupy '%s' , kliknij poniżej aby zobaczyć profil:

%s

lub kliknij poniżej aby potwierdzić prośbę:

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s utworzył grupę %s',
	'river:join:group:default' => '%s dołączył do grupy %s',

	'groups:nowidgets' => 'Żaden gadżet nie został zdefiniowany dla tej grupy.',


	'groups:widgets:members:title' => 'Użytkownicy grupy',
	'groups:widgets:members:description' => 'Lista użytkowników grupy.',
	'groups:widgets:members:label:displaynum' => 'Lista użytkowników grupy.',
	'groups:widgets:members:label:pleaseedit' => 'Proszę skonfigurować ten gadżet.',

	'groups:widgets:entities:title' => "Obiekty w grupie",
	'groups:widgets:entities:description' => "Lista obiektów zapisanych w grupie",
	'groups:widgets:entities:label:displaynum' => 'Lista obiektów w grupie.',
	'groups:widgets:entities:label:pleaseedit' => 'Proszę skonfigurować ten gadżet.',

	'groups:allowhiddengroups' => 'Czy zezwolić na prywatne (niewidoczne) grupy?',
	'groups:whocancreate' => 'Kto może utworzyć nową grupę?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Grupa i zawartość grupy zostały usunięte',
	'group:notdeleted' => 'Nie powiodło się usunięcie grupy',

	'group:notfound' => 'Nie można znaleźć grupy',
	'groups:deletewarning' => "Czy na pewno chcesz usunąć tą grupę? Tej operacji nie można cofnąć!",

	'groups:invitekilled' => 'Usunięto zaproszenie.',
	'groups:joinrequestkilled' => 'Usunięto prośbę o członkostwo.',
	'groups:error:addedtogroup' => "Nie można było dodać %s do grupy",
	'groups:add:alreadymember' => "%s jest już członkiem tej grupy",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Profile grupy',
);
