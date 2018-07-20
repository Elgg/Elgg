<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Site-uri',

/**
 * Sessions
 */

	'login' => "Conectează-te",
	'loginok' => "Ai fost conectat/ă.",
	'loginerror' => "Nu te-am putut conecta. Te rugăm să-ţi verifici detaliile şi să încerci din nou.",
	'login:empty' => "Numele de utilizator, email-ul şi parola sunt necesare.",
	'login:baduser' => "Nu am putut încărca contul tău.",
	'auth:nopams' => "Eroare internă. Nu s-a instalat nici o metodă de conectare.",

	'logout' => "Deconectează-te",
	'logoutok' => "Ai fost deconectat.",
	'logouterror' => "Nu te-am putut deconecta. Te rugăm să încerci din nou.",
	'session_expired' => "Sesiunea ta a expirat. Te rugăm să <a href='javascript:location.reload(true)'>reîncarci</a> pagina pentru a te conecta.",
	'session_changed_user' => "Ai fost conectat/ă ca şi alt utilizator. Ar trebui să <a href='javascript:location.reload(true)'>reîncarci</a> pagina.",

	'loggedinrequired' => "Trebuie să fii conectat pentru a vedea pagina cerută.",
	'adminrequired' => "Trebuie să fii administrator pentru a vedea pagina cerută.",
	'membershiprequired' => "Trebuie să fii un membru al acestui grup pentru a vedea această pagină.",
	'limited_access' => "Nu ai permisiunea de a vedea această pagină.",
	'invalid_request_signature' => "Legătura acestei pagini este invalidă sau a expirat",

/**
 * Errors
 */

	'exception:title' => "Eroare fatală.",
	'exception:contact_admin' => 'O eroare nerecuperabilă a avut loc şi a fost înregistrată. Contactează administratorul cu următoarele informaţii:',

	'actionundefined' => "The requested action (%s) was not defined in the system.",
	'actionnotfound' => "The action file for %s was not found.",
	'actionloggedout' => "Ne pare rău, nu poţi efectua această acţiune deconectat.",
	'actionunauthorized' => 'Nu eşti autorizat să efectuezi această acţiune',

	'ajax:error' => 'Unexpected error while performing an AJAX call. Maybe the connection to the server is lost.',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is a misconfigured plugin. It has been disabled. Please search the Elgg wiki for possible causes (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) cannot start and has been deactivated.  Reason: %s',
	'PluginException:InvalidID' => "%s is an invalid plugin ID.",
	'PluginException:InvalidPath' => "%s is an invalid plugin path.",
	'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
	'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
	'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',
	'ElggPlugin:MissingID' => 'Missing plugin ID (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Missing ElggPluginPackage for plugin ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'The required file "%s" is missing.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'This plugin\'s directory must be renamed to "%s" to match the ID in its manifest.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Its manifest contains an invalid dependency type "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',
	'PluginException:NoPluginName' => "The plugin name could not be found",
	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Requires',
	'ElggPlugin:Dependencies:Suggests' => 'Suggests',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflicts',
	'ElggPlugin:Dependencies:Conflicted' => 'Conflicted',
	'ElggPlugin:Dependencies:Provides' => 'Provides',
	'ElggPlugin:Dependencies:Priority' => 'Priority',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'After %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Before %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Missing',

	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items found without parents to link them to',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] found with a missing parent[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Duplicate registration found for menu item [%s]',

	'RegistrationException:EmptyPassword' => 'Câmpurile parolei nu pot fii goale',
	'RegistrationException:PasswordMismatch' => 'Parolele trebuie să corespundă',
	'LoginException:BannedUser' => 'Ai fost blocat/ă de pe acest site şi nu te poţi conecta',
	'LoginException:UsernameFailure' => 'Nu te-am putut conecta. Te rugm să verifici numele de utilizator, email-ul şi parola.',
	'LoginException:PasswordFailure' => 'Nu te-am putut conecta. Te rugm să verifici numele de utilizator, email-ul şi parola.',
	'LoginException:AccountLocked' => 'Contul tău a fost blocat din cauza mai multor eşuări de conectare.',
	'LoginException:ChangePasswordFailure' => 'Verificarea parolei curente a eşuat.',
	'LoginException:Unknown' => 'Nu te-am putut conecta datorită unei erori necunoscute.',

	'UserFetchFailureException' => 'Cannot check permission for user_guid [%s] as the user does not exist.',

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
	'viewfailure' => 'There was an internal failure in the view %s',
	'view:missing_param' => "The required parameter '%s' is missing in the view %s",
	'changebookmark' => 'Te rugăm să schimbi marcajul pentru această pagină',
	'noaccess' => 'Conţinutul pe care ai vrut să-l vezi a fost şters sau nu ai permisiunea să-l accesezi.',
	'error:missing_data' => 'Au fost câteva date lipsă în cererea ta',
	'save:fail' => 'S-a eşuat la salvarea datelor tale',
	'save:success' => 'Datele tale au fost salvate',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops... ceva greşit s-a întâmplat.',
	'error:400:title' => 'Cerere greşită',
	'error:400:content' => 'Ne pare rău, dar cererea este invalidă sau incompletă.',
	'error:403:title' => 'Interzis',
	'error:403:content' => 'Ne pare rău, dar nu poţi accesa pagina cerută.',
	'error:404:title' => 'Pagina nu a fost găsită',
	'error:404:content' => 'Ne pare rău, dar nu am putut găsi pagina cerută.',

	'upload:error:ini_size' => 'Fişierul pe care vrei să-l încarci e prea mare.',
	'upload:error:form_size' => 'Fişierul pe care ai vrut să-l încarci era prea mare.',
	'upload:error:partial' => 'Încărcarea de fişier nu s-a terminat.',
	'upload:error:no_file' => 'Nici un fişier selectat.',
	'upload:error:no_tmp_dir' => 'Nu se poate salva fişierul încărcat.',
	'upload:error:cant_write' => 'Nu se poate salva fişierul încărcat.',
	'upload:error:extension' => 'Nu se poate salva fişierul încărcat.',
	'upload:error:unknown' => 'Încărcarea de fişier a eşuat.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Banned',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Description',
	'table_columns:fromView:link' => 'Name/Title',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Language',
	'table_columns:fromView:owner' => 'Owner',
	'table_columns:fromView:time_created' => 'Time Created',
	'table_columns:fromView:time_updated' => 'Time Updated',
	'table_columns:fromView:user' => 'User',

	'table_columns:fromProperty:description' => 'Description',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Type',
	'table_columns:fromProperty:username' => 'Username',

	'table_columns:fromMethod:getSubtype' => 'Subtype',
	'table_columns:fromMethod:getDisplayName' => 'Name/Title',
	'table_columns:fromMethod:getMimeType' => 'MIME Type',
	'table_columns:fromMethod:getSimpleType' => 'Type',

/**
 * User details
 */

	'name' => "Numele de afişat",
	'email' => "Adresa de email",
	'username' => "Nume utilizator",
	'loginusername' => "Nume de utilizator sau email",
	'password' => "Parolă",
	'passwordagain' => "Parolă (din nou pentru verificare)",
	'admin_option' => "Make this user an admin?",
	'autogen_password_option' => "Generăm automat o parolă securizată?",

/**
 * Access
 */

	'PRIVATE' => "Privat",
	'LOGGED_IN' => "Utilizatori conectaţi",
	'PUBLIC' => "Public",
	'LOGGED_OUT' => "Utilizatori deconectaţi",
	'access:friends:label' => "Prieteni",
	'access' => "Acces",
	'access:overridenotice' => "Notă: Datorită politicii grupului, acest conţinut va fii accesibil doar membrilor acestuia.",
	'access:limited:label' => "Limitat",
	'access:help' => "Nivelul de acces",
	'access:read' => "Acces de citire",
	'access:write' => "Acces de scriere",
	'access:admin_only' => "Numai administratori",
	'access:missing_name' => "Nume de acces lipsă",
	'access:comments:change' => "Această discuţie este limitată numai anumitor persoane. Ai grijă cui o distribui.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Your dashboard lets you track the activity and content on this site that matters to you.",

	'widgets:add' => 'Add widgets',
	'widgets:add:description' => "Click on any widget button below to add it to the page.",
	'widgets:panel:close' => "Close widgets panel",
	'widgets:position:fixed' => '(Fixed position on page)',
	'widget:unavailable' => 'You have already added this widget',
	'widget:numbertodisplay' => 'Number of items to display',

	'widget:delete' => 'Remove %s',
	'widget:edit' => 'Customize this widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "The widget was successfully saved.",
	'widgets:save:failure' => "We could not save your widget.",
	'widgets:add:success' => "The widget was successfully added.",
	'widgets:add:failure' => "We could not add your widget.",
	'widgets:move:failure' => "We could not store the new widget position.",
	'widgets:remove:failure' => "Unable to remove this widget",

/**
 * Groups
 */

	'group' => "Grup",
	'item:group' => "Grupuri",

/**
 * Users
 */

	'user' => "Utilizator",
	'item:user' => "Utilizatori",

/**
 * Friends
 */

	'friends' => "Prieteni",
	'friends:yours' => "Prietenii tăi",
	'friends:owned' => "Prietenii utilizatorului %s",
	'friend:add' => "Adaugă prieten/ă",
	'friend:remove' => "Îndepărtează prieten/ă",

	'friends:add:successful' => "Ai adăugat cu succes pe %s ca şi prieten/ă.",
	'friends:add:duplicate' => "Eşti deja prieten/ă cu %s",
	'friends:add:failure' => "Nu am putut adăuga pe %s ca şi prieten/ă.",

	'friends:remove:successful' => "Ai îndepărtat cu succes pe %s de la prietenii tăi.",
	'friends:remove:no_friend' => "Tu şi %s nu sunteţi prieteni",
	'friends:remove:failure' => "Am putut îndepărta pe %s de la prietenii tăi.",

	'friends:none' => "Nici un prieten încă.",
	'friends:none:you' => "Nu ai nici un prieten încă.",

	'friends:none:found' => "Nu s-au găsit prieteni.",

	'friends:of:none' => "Nimeni să fii adăugat acest utilizator la prieteni încă.",
	'friends:of:none:you' => "Nimeni să te fii adăugat la prieteni încă. Începe să adaugi conţinut şi completează-ţi profilul pentru a permite altora să te găsească!",

	'friends:of:owned' => "Persoane care au făcut ca %s să le fie prieten/ă",

	'friends:of' => "Prieteni ai",
	'friends:collections' => "Colecţiile Prietenilor",
	'collections:add' => "Colecţie nouă",
	'friends:collections:add' => "Colecţie de prieteni nouă",
	'friends:addfriends' => "Selectează prieteni",
	'friends:collectionname' => "Numele colecţiei",
	'friends:collectionfriends' => "Prieteni din colecţie",
	'friends:collectionedit' => "Editează această colecţie",
	'friends:nocollections' => "Nu ai vreo colecţie încă.",
	'friends:collectiondeleted' => "Colecţia ta a fost ştearsă.",
	'friends:collectiondeletefailed' => "Nu am putut şterge colecţia. Ori nu ai permisiunea, ori o altă problemă a apărut.",
	'friends:collectionadded' => "Colecţia ta a fost creată cu succes",
	'friends:nocollectionname' => "Trebuie să-i dai colecţiei tale un nume înainte să o creezi.",
	'friends:collections:members' => "Membrii colecţiei",
	'friends:collections:edit' => "Editează colecţia",
	'friends:collections:edited' => "Colecţie salvată",
	'friends:collection:edit_failed' => 'Nu am putut salva colecţia.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Poza de profil',
	'avatar:noaccess' => "Nu îţi este permis să-i editezi poza de profil a acestui utilizator",
	'avatar:create' => 'Creează-ţi poza de profil',
	'avatar:edit' => 'Editează poza de profil',
	'avatar:preview' => 'Previzualizează',
	'avatar:upload' => 'Încarcă o nouă poză de profil',
	'avatar:current' => 'Poza de profil curentă',
	'avatar:remove' => 'Îndepărtează-ţi poza de profil şi seteaz-o pe cea standard',
	'avatar:crop:title' => 'Unealta de decupare a pozei de profil',
	'avatar:upload:instructions' => "Poza ta de profil va fii afişată pe tot site-ul. O poţi schimba de câte ori doreşti. (Formatele de fişier acceptate: GIF, JPG sau PNG)",
	'avatar:create:instructions' => 'Apasă şi trage un pătrat mai jos pentru a se potrivi cu poza de profil dorită. O previzualizare va apărea în cutia din stânga. Când eşti multumit/ă de previzualizare, apasă \'Creează-ţi poza de profil\'. Această versiune decupată va reprezenta noua ta poză de profil pe întreg site-ul.',
	'avatar:upload:success' => 'Poza de profil a fost cu succes încărcată',
	'avatar:upload:fail' => 'Încărcarea pozei de profil a eşuat',
	'avatar:resize:fail' => 'Redimensionarea pozei de profil a eşuat',
	'avatar:crop:success' => 'Decuparea pozei de profil a fost cu succes',
	'avatar:crop:fail' => 'Decuparea pozei de profil a eşuat',
	'avatar:remove:success' => 'Îndepărtarea pozei de profil a fost cu succes',
	'avatar:remove:fail' => 'Îndepărtarea pozei de profil a eşuat',

	'profile:edit' => 'Editează-ţi profilul',
	'profile:aboutme' => "Despre mine",
	'profile:description' => "Despre mine",
	'profile:briefdescription' => "Descriere sumară",
	'profile:location' => "Locaţie",
	'profile:skills' => "Îndemânări",
	'profile:interests' => "Interese",
	'profile:contactemail' => "Email de contact",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Mobil",
	'profile:website' => "Website",
	'profile:twitter' => "Nume Twitter",
	'profile:saved' => "Priful tău a fost salvat cu succes.",

	'profile:field:text' => 'Short text',
	'profile:field:longtext' => 'Large text area',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Web address',
	'profile:field:email' => 'Email address',
	'profile:field:location' => 'Location',
	'profile:field:date' => 'Date',

	'admin:appearance:profile_fields' => 'Edit Profile Fields',
	'profile:edit:default' => 'Edit profile fields',
	'profile:label' => "Profile label",
	'profile:type' => "Profile type",
	'profile:editdefault:delete:fail' => 'Removing profile field failed',
	'profile:editdefault:delete:success' => 'Profile field deleted',
	'profile:defaultprofile:reset' => 'Profile fields reset to the system default',
	'profile:resetdefault' => 'Reset profile fields to system defaults',
	'profile:resetdefault:confirm' => 'Are you sure you want to delete your custom profile fields?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own using the form below. \n\n Give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags), and click the 'Add' button. To re-order the fields drag on the handle next to the field label. To edit a field label - click on the label's text to make it editable. \n\n At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'New profile field added',
	'profile:editdefault:fail' => 'Profilul implicit nu a putut fii salvat',
	'profile:field_too_long' => 'Nu s-a putut şterge profilul tău deoarece secţiunea "%s" este prea lungă.',
	'profile:noaccess' => "Nu ai permisiunea de a edita acest profil.",
	'profile:invalid_email' => '%s trebuie să fie o adresă de email validă.',


/**
 * Feeds
 */
	'feed:rss' => 'Fluxul RSS al acestei pagini',
/**
 * Links
 */
	'link:view' => 'vezi legătura',
	'link:view:all' => 'Vezi toate',


/**
 * River
 */
	'river' => "Activitate",
	'river:friend:user:default' => "%s este acum prieten/ă cu %s",
	'river:update:user:avatar' => '%s are o poză de profil nouă',
	'river:update:user:profile' => '%s şi-a actualizat profilul',
	'river:noaccess' => 'Nu ai permisiunea să vezi acest element.',
	'river:posted:generic' => '%s a postat',
	'riveritem:single:user' => 'un utilizator',
	'riveritem:plural:user' => 'nişte utilizatori',
	'river:ingroup' => 'în grupul %s',
	'river:none' => 'Fără activitate',
	'river:update' => 'Actualizare pentru %s',
	'river:delete' => 'Îndepărtează acest element de activitate',
	'river:delete:success' => 'Elementul de activitate a fost şters',
	'river:delete:fail' => 'Elementul de activitate nu a putut fii şters',
	'river:delete:lack_permission' => 'Nu ai permisiunea de a şterge acest element de activitate',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Utilizator neconform',
	'activity:owner' => 'Vezi activitatea',

	'river:widget:title' => "Activitate",
	'river:widget:description' => "Afişează activitatea recentă",
	'river:widget:type' => "Tipul activităţii",
	'river:widgets:friends' => 'Activitatea prietenilor',
	'river:widgets:all' => 'Toată activitatea site-ului',

/**
 * Notifications
 */
	'notifications:usersettings' => "Setările de notificări",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Setările de notificare au fost salvate cu succes.",
	'notifications:usersettings:save:fail' => "A fost o problemă la salvarea setărilor de notificări.",

	'notification:subject' => 'Notificare despre %s',
	'notification:body' => 'Vezi noua activitate de aici %s',

/**
 * Search
 */

	'search' => "Căutare",
	'searchtitle' => "Căutare: %s",
	'users:searchtitle' => "Căutare utilizatori: %s",
	'groups:searchtitle' => "Căutare grupuri: %s",
	'advancedsearchtitle' => "%s cu rezultate potrivite cu %s",
	'notfound' => "Nici un rezultat găsit.",
	'next' => "Următoarele",
	'previous' => "Anterioare",

	'viewtype:change' => "Schimbă tipul de listă",
	'viewtype:list' => "Listă",
	'viewtype:gallery' => "Galerie",

	'tag:search:startblurb' => "Elemente cu etichete potrivite cu '%s':",

	'user:search:startblurb' => "Utilizatori potriviţi cu '%s':",
	'user:search:finishblurb' => "Pentru a vedea maii mult, apasă aici.",

	'group:search:startblurb' => "Grupuri potrivite cu '%s':",
	'group:search:finishblurb' => "Pentru a vedea maii mult, apasă aici.",
	'search:go' => 'Caută',
	'userpicker:only_friends' => 'Numai prieteni',

/**
 * Account
 */

	'account' => "Cont",
	'settings' => "Setări",
	'tools' => "Unelte",
	'settings:edit' => 'Editează setările',

	'register' => "Înregistrare",
	'registerok' => "Te-ai înregistrat cu succes pentru %s.",
	'registerbad' => "Înregistrarea ta a fost fără succes din cauza unei erori necunoscute.",
	'registerdisabled' => "Înregistrarea a fost închisă de către adminitratorul de sistem",
	'register:fields' => 'Toate cţmpurile sunt necesare',

	'registration:notemail' => 'Adresa de email furnizată de tine nu pare să fie o adresă de email validă.',
	'registration:userexists' => 'Acel nume de utilizator există deja',
	'registration:usernametooshort' => 'Numele tău de utilizator trebuie să fie de minim %u caractere.',
	'registration:usernametoolong' => 'Numele tău de utilizator este prea lung. Poate avea un maxim de %u caractere.',
	'registration:passwordtooshort' => 'Parola trebuie să fie de minim %u caractere.',
	'registration:dupeemail' => 'Această adresă de email există deja.',
	'registration:invalidchars' => 'Ne pare rău, dar numele tău de utilizator conţine caracterul %s care este neconform. Următoarele caractere sunt neconforme: %s',
	'registration:emailnotvalid' => 'Ne pare rău, dar adresa ta de email este neconformă pe acest sistem',
	'registration:passwordnotvalid' => 'Ne pare rău, dar parola ta este neconformă pe acest sistem',
	'registration:usernamenotvalid' => 'Ne pare rău, dar numele tău de utilizator este neconform pe acest sistem',

	'adduser' => "Add User",
	'adduser:ok' => "You have successfully added a new user.",
	'adduser:bad' => "The new user could not be created.",

	'user:set:name' => "Setări nume de cont",
	'user:name:label' => "Nume de afişat",
	'user:name:success' => "S-a schimbat cu succes numele de afişat pe sistem.",
	'user:name:fail' => "Nu s-a putut schimba numele de afişat pe sistem.",

	'user:set:password' => "Parola de cont",
	'user:current_password:label' => 'Parola curentă',
	'user:password:label' => "Noua parolă",
	'user:password2:label' => "Noua parolă din nou",
	'user:password:success' => "Parolă schimbată",
	'user:password:fail' => "Nu am putut schimba parola pe sistem.",
	'user:password:fail:notsame' => "Cele două parole nu sunt identice!",
	'user:password:fail:tooshort' => "Parola este prea scurtă!",
	'user:password:fail:incorrect_current_password' => 'Parola curentă nu este corectă.',
	'user:changepassword:unknown_user' => 'Utilizator neconform.',
	'user:changepassword:change_password_confirm' => 'Acest lucru îţi va schimba parola.',

	'user:set:language' => "Setările de limbă",
	'user:language:label' => "Limbă",
	'user:language:success' => "Setările de limbă au fost salvate.",
	'user:language:fail' => "Setările de limbă nu au putut fii salvate.",

	'user:username:notfound' => 'Numele de utilizator %s nu a fost găsit.',

	'user:password:lost' => 'Parolă pierdută',
	'user:password:changereq:success' => 'S-a cerut cu succes o nouă parolă, email trimis',
	'user:password:changereq:fail' => 'Nu s-a putut cere o nouă parolă.',

	'user:password:text' => 'Pentru a cere o nouă parolă, introdu numele tău de utilizator sau adresa ta de email şi apasă butonul Cere.',

	'user:persistent' => 'Nu mă uita',

	'walled_garden:welcome' => 'Bun venit pe',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administer',
	'menu:page:header:configure' => 'Configure',
	'menu:page:header:develop' => 'Develop',
	'menu:page:header:default' => 'Other',

	'admin:view_site' => 'View site',
	'admin:loggedin' => 'Logged in as %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Your settings have been saved.",
	'admin:configuration:fail' => "Your settings could not be saved.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',
	'admin:configuration:default_limit' => 'The number of items per page must be at least 1.',

	'admin:unknown_section' => 'Invalid Admin Section.',

	'admin' => "Administration",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:statistics' => "Statistics",
	'admin:statistics:overview' => 'Overview',
	'admin:statistics:server' => 'Server Info',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Latest Cron Jobs',
	'admin:cron:period' => 'Cron period',
	'admin:cron:friendly' => 'Last completed',
	'admin:cron:date' => 'Date and time',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

	'admin:appearance' => 'Appearance',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilities',
	'admin:configure_utilities' => 'Utilities',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Users",
	'admin:users:online' => 'Currently Online',
	'admin:users:newest' => 'Newest',
	'admin:users:admins' => 'Administrators',
	'admin:users:add' => 'Add New User',
	'admin:users:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
	'admin:users:adduser:label' => "Click here to add a new user...",
	'admin:users:opt:linktext' => "Configure users...",
	'admin:users:opt:description' => "Configure users and account information. ",
	'admin:users:find' => 'Find',

	'admin:administer_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Upgrades',

	'admin:settings' => 'Settings',
	'admin:settings:basic' => 'Basic Settings',
	'admin:settings:advanced' => 'Advanced Settings',
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:site:opt:linktext' => "Configure site...",
	'admin:settings:in_settings_file' => 'This setting is configured in settings.php',

	'admin:legend:security' => 'Security',
	'admin:site:secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:site:secret_regenerated' => "Your site secret has been regenerated.",
	'admin:site:secret:regenerate' => "Regenerate site secret",
	'admin:site:secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Weak",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderate",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Strong",
	'site_secret:strength_msg:strong' => "Your site secret is sufficiently strong. There is no need to regenerate it.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Online users',
	'admin:widget:online_users:help' => 'Lists the users currently on the site',
	'admin:widget:new_users' => 'New users',
	'admin:widget:new_users:help' => 'Lists the newest users',
	'admin:widget:banned_users' => 'Banned users',
	'admin:widget:banned_users:help' => 'Lists the banned users',
	'admin:widget:content_stats' => 'Content statistics',
	'admin:widget:content_stats:help' => 'Keep track of the content created by your users',
	'admin:widget:cron_status' => 'Cron status',
	'admin:widget:cron_status:help' => 'Shows the status of the last time cron jobs finished',
	'widget:content_stats:type' => 'Content type',
	'widget:content_stats:number' => 'Number',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "A short introduction to Elgg's admin area",
	'admin:widget:admin_welcome:intro' =>
'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Everyday tasks like monitoring reported content, checking who is online, and viewing statistics.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or activating a plugin.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Control panel',
	'admin:widget:control_panel:help' => "Provides easy access to common controls",

	'admin:cache:flush' => 'Flush the caches',
	'admin:cache:flushed' => "The site's caches have been flushed",

	'admin:footer:faq' => 'Administration FAQ',
	'admin:footer:manual' => 'Administration Manual',
	'admin:footer:community_forums' => 'Elgg Community Forums',
	'admin:footer:blog' => 'Elgg Blog',

	'admin:plugins:category:all' => 'All plugins',
	'admin:plugins:category:active' => 'Active plugins',
	'admin:plugins:category:inactive' => 'Inactive plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Bundled',
	'admin:plugins:category:nonbundled' => 'Non-bundled',
	'admin:plugins:category:content' => 'Content',
	'admin:plugins:category:development' => 'Development',
	'admin:plugins:category:enhancement' => 'Enhancements',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'Communication',
	'admin:plugins:category:security' => 'Security and Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Themes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilities',

	'admin:plugins:markdown:unknown_plugin' => 'Unknown plugin.',
	'admin:plugins:markdown:unknown_file' => 'Unknown file.',

	'admin:notices:could_not_delete' => 'Could not delete notice.',
	'item:object:admin_notice' => 'Admin notice',

	'admin:options' => 'Admin options',

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activate All',
	'admin:plugins:deactivate_all' => 'Deactivate All',
	'admin:plugins:activate' => 'Activate',
	'admin:plugins:deactivate' => 'Deactivate',
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Name",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categories',
	'admin:plugins:label:licence' => "License",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Files",
	'admin:plugins:label:resources' => "Resources",
	'admin:plugins:label:screenshots' => "Screenshots",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Report issue",
	'admin:plugins:label:donate' => "Donate",
	'admin:plugins:label:moreinfo' => 'more info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Location',
	'admin:plugins:label:contributors' => 'Contributors',
	'admin:plugins:label:contributors:name' => 'Name',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Website',
	'admin:plugins:label:contributors:username' => 'Community username',
	'admin:plugins:label:contributors:description' => 'Description',
	'admin:plugins:label:dependencies' => 'Dependencies',

	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'cannot activate',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

	'admin:plugins:set_priority:yes' => "Reordered %s.",
	'admin:plugins:set_priority:no' => "Could not reorder %s.",
	'admin:plugins:set_priority:no_with_msg' => "Could not reorder %s. Error: %s",
	'admin:plugins:deactivate:yes' => "Deactivated %s.",
	'admin:plugins:deactivate:no' => "Could not deactivate %s.",
	'admin:plugins:deactivate:no_with_msg' => "Could not deactivate %s. Error: %s",
	'admin:plugins:activate:yes' => "Activated %s.",
	'admin:plugins:activate:no' => "Could not activate %s.",
	'admin:plugins:activate:no_with_msg' => "Could not activate %s. Error: %s",
	'admin:plugins:categories:all' => 'All categories',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'Plugin Settings',
	'admin:plugins:warning:unmet_dependencies_active' => 'This plugin is active but has unmet dependencies. You may encounter problems. See "more info" below for details.',

	'admin:plugins:dependencies:type' => 'Type',
	'admin:plugins:dependencies:name' => 'Name',
	'admin:plugins:dependencies:expected_value' => 'Expected Value',
	'admin:plugins:dependencies:local_value' => 'Actual value',
	'admin:plugins:dependencies:comment' => 'Comment',

	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:basic' => "Basic site statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Number of users",
	'admin:statistics:label:numonline' => "Number of users online",
	'admin:statistics:label:onlineusers' => "Users online now",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Web Server',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Log Location',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_ini' => 'PHP ini file location',
	'admin:server:label:php_log' => 'PHP Log',
	'admin:server:label:mem_avail' => 'Memory available',
	'admin:server:label:mem_used' => 'Memory used',
	'admin:server:error_log' => "Web server's error log",
	'admin:server:label:post_max_size' => 'POST maximum size',
	'admin:server:label:upload_max_filesize' => 'Upload maximum size',
	'admin:server:warning:post_max_too_small' => '(Note: post_max_size must be larger than this value to support uploads of this size)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure memcache.
	',

	'admin:user:label:search' => "Find users:",
	'admin:user:label:searchbutton' => "Search",

	'admin:user:ban:no' => "Can not ban user",
	'admin:user:ban:yes' => "User banned.",
	'admin:user:self:ban:no' => "You cannot ban yourself",
	'admin:user:unban:no' => "Can not unban user",
	'admin:user:unban:yes' => "User unbanned.",
	'admin:user:delete:no' => "Can not delete user",
	'admin:user:delete:yes' => "The user %s has been deleted",
	'admin:user:self:delete:no' => "You cannot delete yourself",

	'admin:user:resetpassword:yes' => "Password reset, user notified.",
	'admin:user:resetpassword:no' => "Password could not be reset.",

	'admin:user:makeadmin:yes' => "User is now an admin.",
	'admin:user:makeadmin:no' => "We could not make this user an admin.",

	'admin:user:removeadmin:yes' => "User is no longer an admin.",
	'admin:user:removeadmin:no' => "We could not remove administrator privileges from this user.",
	'admin:user:self:removeadmin:no' => "You cannot remove your own administrator privileges.",

	'admin:appearance:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select which menu items you want to show as featured links.  Unused items will be added as "More" at the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

	'admin:appearance:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Unknown widget type',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Edit this site's robots.txt file below",
	'admin:robots.txt:plugins' => "Plugins are adding the following to the robots.txt file",
	'admin:robots.txt:subdir' => "The robots.txt tool will not work because Elgg is installed in a sub-directory",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'This site is down for maintenance',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Maintenance mode',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'The site is in maintenance mode.',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",

	'usersettings:statistics' => "Statisticile tale",
	'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'usersettings:statistics:opt:linktext' => "Statisticile contului",
	
	'usersettings:statistics:login_history' => "Istoria conectării",
	'usersettings:statistics:login_history:date' => "Dată",
	'usersettings:statistics:login_history:ip' => "Adresa de IP",

	'usersettings:user' => "Setări %s",
	'usersettings:user:opt:description' => "This allows you to control user settings.",
	'usersettings:user:opt:linktext' => "Change your settings",

	'usersettings:plugins' => "Tools",
	'usersettings:plugins:opt:description' => "Configure settings (if any) for your active tools.",
	'usersettings:plugins:opt:linktext' => "Configurează-ţi uneltele",

	'usersettings:plugins:description' => "This panel allows you to control and configure the personal settings for the tools installed by your system administrator.",
	'usersettings:statistics:label:numentities' => "Conţinutul tău",

	'usersettings:statistics:yourdetails' => "Detaliile tale",
	'usersettings:statistics:label:name' => "Numele întreg",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Membru din",
	'usersettings:statistics:label:lastlogin' => "Ultima conectare",

/**
 * Activity river
 */

	'river:all' => 'Toată activitatea site-ului',
	'river:mine' => 'Activitatea mea',
	'river:owner' => 'Activitatea %s',
	'river:friends' => 'Activitatea prietenilor',
	'river:select' => 'Afişează %s',
	'river:comments:more' => '+%u mai multe',
	'river:comments:all' => 'Vezi toate cele %u comentarii',
	'river:generic_comment' => 'a adăugat un comentariu la %s %s',

	'friends:widget:description' => "Afişează-ţi câţiva prieteni.",
	'friends:num_display' => "Numărul de prieteni de afişat",
	'friends:icon_size' => "Mărimea pozei",
	'friends:tiny' => "minusculă",
	'friends:small' => "mică",

/**
 * Icons
 */

	'icon:size' => "Mărimea pozei",
	'icon:size:topbar' => "Bara de sus",
	'icon:size:tiny' => "Minusculă",
	'icon:size:small' => "Mică",
	'icon:size:medium' => "Medie",
	'icon:size:large' => "Mare",
	'icon:size:master' => "Foarte Mare",

/**
 * Generic action words
 */

	'save' => "Salvează",
	'reset' => 'Resetează',
	'publish' => "Publică",
	'cancel' => "Anulează",
	'saving' => "Se salvează ...",
	'update' => "Actualizează",
	'preview' => "Previzualizează",
	'edit' => "Editează",
	'delete' => "Şterge",
	'accept' => "Acceptă",
	'reject' => "Respinge",
	'decline' => "Declină",
	'approve' => "Aprobă",
	'activate' => "Activează",
	'deactivate' => "Dezactivează",
	'disapprove' => "Dezaprobă",
	'revoke' => "Revocă",
	'load' => "Încarcă",
	'upload' => "Încarcă",
	'download' => "Descarcă",
	'ban' => "Blochează",
	'unban' => "Deblochează",
	'banned' => "Blocat",
	'enable' => "Porneşte",
	'disable' => "Opreşte",
	'request' => "Cere",
	'complete' => "Complet",
	'open' => 'Deschide',
	'close' => 'Închide',
	'hide' => 'Ascunde',
	'show' => 'Afişează',
	'reply' => "Răspunde",
	'more' => 'Mai mult',
	'more_info' => 'Mai multe informaţii',
	'comments' => 'Comentarii',
	'import' => 'Importă',
	'export' => 'Exportă',
	'untitled' => 'Fără titlu',
	'help' => 'Ajutor',
	'send' => 'Trimite',
	'post' => 'Postează',
	'submit' => 'Trazmite',
	'comment' => 'Comentează',
	'upgrade' => 'Actualizează',
	'sort' => 'Sortează',
	'filter' => 'Filtrează',
	'new' => 'Nou',
	'add' => 'Adaugă',
	'create' => 'Crează',
	'remove' => 'Îndepărtează',
	'revert' => 'Revenire',

	'site' => 'Site',
	'activity' => 'Activitate',
	'members' => 'Membrii',
	'menu' => 'Meniu',

	'up' => 'Sus',
	'down' => 'Jos',
	'top' => 'Vârf',
	'bottom' => 'Fund',
	'right' => 'Dreapta',
	'left' => 'Stânga',
	'back' => 'Înapoi',

	'invite' => "Invită",

	'resetpassword' => "Resetează parola",
	'changepassword' => "Schimbă parola",
	'makeadmin' => "Make admin",
	'removeadmin' => "Remove admin",

	'option:yes' => "Da",
	'option:no' => "Nu",

	'unknown' => 'Necunoscut',
	'never' => 'Niciodată',

	'active' => 'Activ',
	'total' => 'Total',

	'ok' => 'Bine',
	'any' => 'Orice',
	'error' => 'Eroare',

	'other' => 'Altceva',
	'options' => 'Optiuni',
	'advanced' => 'Avansat',

	'learnmore' => "Apasă aici pentru a afla mai multe.",
	'unknown_error' => 'Eroare necunoscută',

	'content' => "conţinut",
	'content:latest' => 'Activitate recentă',
	'content:latest:blurb' => 'Alternativ, apasă aici pentru a vedea conţinutul recent de pe întreg site-ul.',

	'link:text' => 'vezi legătura',

/**
 * Generic questions
 */

	'question:areyousure' => 'Eşti sigur/ă?',

/**
 * Status
 */

	'status' => 'Stare',
	'status:unsaved_draft' => 'Schiță nesalvată',
	'status:draft' => 'Schiță',
	'status:unpublished' => 'Nepublicat',
	'status:published' => 'Publicat',
	'status:featured' => 'Promovat',
	'status:open' => 'Deschis',
	'status:closed' => 'Închis',

/**
 * Generic sorts
 */

	'sort:newest' => 'Noi',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfabetic',
	'sort:priority' => 'Prioritar',

/**
 * Generic data words
 */

	'title' => "Titlu",
	'description' => "Descriere",
	'tags' => "Etichete",
	'all' => "Toate",
	'mine' => "Personale",

	'by' => 'de către',
	'none' => 'nimic',

	'annotations' => "Anotații",
	'relationships' => "Relații",
	'metadata' => "Metadata",
	'tagcloud' => "Nor de etichete",

	'on' => 'Pornit',
	'off' => 'Oprit',

/**
 * Entity actions
 */

	'edit:this' => 'Editează asta',
	'delete:this' => 'Șterge asta',
	'comment:this' => 'Comentează pe asta',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Sigur dorești să ștergi acest element?",
	'deleteconfirm:plural' => "Sigur dorești să ștergi aceste elemente?",
	'fileexists' => "Un fișier a fost deja încărcat. Pentru a-l înlocui, selectează-l mai jos:",

/**
 * User add
 */

	'useradd:subject' => 'Contul de utilizator a fost creat',
	'useradd:body' => '
%s,

Un cont de utilizator a fost creat pentru tine pe %s. Pentru a te conecta, vizitează:

%s

Şi conectează-te cu aceste detalii:

Nume utilizator: %s
Parolă: %s

De îndată ce te vei conecta, îţi recomandăm să-ţi schimbi parola.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "apasă pentru a respinge",


/**
 * Import / export
 */

	'importsuccess' => "Import of data was successful",
	'importfail' => "OpenDD import of data failed.",

/**
 * Time
 */

	'friendlytime:justnow' => "chiar acum",
	'friendlytime:minutes' => "cu %s minute în urmă",
	'friendlytime:minutes:singular' => "cu un minut în urmă",
	'friendlytime:hours' => "cu %s ore în urmă",
	'friendlytime:hours:singular' => "cu o oră în urmă",
	'friendlytime:days' => "cu %s zile în urmă",
	'friendlytime:days:singular' => "ieri",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "în %s minute",
	'friendlytime:future:minutes:singular' => "într-un minut",
	'friendlytime:future:hours' => "în %s ore",
	'friendlytime:future:hours:singular' => "într-o oră",
	'friendlytime:future:days' => "în %s zile",
	'friendlytime:future:days:singular' => "mâine",

	'date:month:01' => 'Ianuarie %s',
	'date:month:02' => 'Februarie %s',
	'date:month:03' => 'Martie %s',
	'date:month:04' => 'Aprilie %s',
	'date:month:05' => 'Mai %s',
	'date:month:06' => 'Iunie %s',
	'date:month:07' => 'Iulie %s',
	'date:month:08' => 'August %s',
	'date:month:09' => 'Septembrie %s',
	'date:month:10' => 'Octombrie %s',
	'date:month:11' => 'Noiembrie %s',
	'date:month:12' => 'Decembrie %s',
	
	'date:month:short:01' => 'Ian %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'Mai %s',
	'date:month:short:06' => 'Iun %s',
	'date:month:short:07' => 'Iul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dec %s',

	'date:weekday:0' => 'Duminică',
	'date:weekday:1' => 'Luni',
	'date:weekday:2' => 'Marţi',
	'date:weekday:3' => 'Miercuri',
	'date:weekday:4' => 'Joi',
	'date:weekday:5' => 'Vineri',
	'date:weekday:6' => 'Sâmbătă',

	'date:weekday:short:0' => 'Dum',
	'date:weekday:short:1' => 'Lun',
	'date:weekday:short:2' => 'Mar',
	'date:weekday:short:3' => 'Mie',
	'date:weekday:short:4' => 'Joi',
	'date:weekday:short:5' => 'Vin',
	'date:weekday:short:6' => 'Sâm',

	'interval:minute' => 'La fiecare minut',
	'interval:fiveminute' => 'La fiecare cinci minute',
	'interval:fifteenmin' => 'La fiecare cincisprezece minute',
	'interval:halfhour' => 'La fiecare jumătate de oră',
	'interval:hourly' => 'Pe oră',
	'interval:daily' => 'Zilnic',
	'interval:weekly' => 'Săptămânal',
	'interval:monthly' => 'Lunar',
	'interval:yearly' => 'Anual',
	'interval:reboot' => 'La repornire',

/**
 * System settings
 */

	'installation:sitename' => "The name of your site:",
	'installation:sitedescription' => "Short description of your site (optional):",
	'installation:wwwroot' => "The site URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path of the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "The default access permissions:",
	'installation:language' => "The default language for your site:",
	'installation:debug' => "Control the amount of information written to the server's log.",
	'installation:debug:label' => "Log level:",
	'installation:debug:none' => 'Turn off logging (recommended)',
	'installation:debug:error' => 'Log only critical errors',
	'installation:debug:warning' => 'Log errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'User registration is enabled by default. Turn this off if you do not want people to register on their own.',
	'installation:registration:label' => 'Allow new users to register',
	'installation:walled_garden:description' => 'Enable this to prevent non-members from viewing the site except for web pages marked as public (such as login and registration).',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "This is the privacy setting suggested to users when they create new content. Changing it does not change access to content.",
	'installation:allow_user_default_access:description' => "Enable this to allow users to set their own suggested privacy setting that overrides the system suggestion.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "The simple cache can also improve performance by compressing JavaScript and CSS files. (Requires that simple cache is enabled.)",
	'installation:minify_js:label' => "Compress JavaScript (recommended)",
	'installation:minify_css:label' => "Compress CSS (recommended)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file so that the path is injected into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "The system cache decreases the loading time of Elgg by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Content Access',
	'admin:legend:site_access' => 'Site Access',
	'admin:legend:debug' => 'Debugging and Logging',

	'upgrading' => 'Upgrading...',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",
	'upgrade:unable_to_upgrade' => 'Unable to upgrade.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (previously Twitter Service) was deactivated during the upgrade. Please activate it manually if required.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",

	'deprecated:function' => '%s() was deprecated by %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Errors:',
	'upgrade:river_update_failed' => 'Failed to update the river entry for item id %s',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',

	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Bun venit",
	'welcome:user' => 'Bun venit %s',

/**
 * Emails
 */

	'email:from' => 'De la',
	'email:to' => 'Către',
	'email:subject' => 'Subiect',
	'email:body' => 'Conţinut',

	'email:settings' => "Setări email",
	'email:address:label' => "Adresa de email",

	'email:save:success' => "Noua adresă de email a fost salvată. Verificarea este cerută.",
	'email:save:fail' => "Noua adresă de email nu a putut fii salvată.",

	'friend:newfriend:subject' => "%s te-a făcut prieten/ă!",
	'friend:newfriend:body' => "%s te-a facut prieten/ă!

Pentru a le vizita profilul, apasă aici:

%s

Te rugăm să nu răspunzi acestui email.",

	'email:changepassword:subject' => "Parolă schimbată!",
	'email:changepassword:body' => "Salutare %s,

Parola ta a fost schimbată.",

	'email:resetpassword:subject' => "Parolă resetată!",
	'email:resetpassword:body' => "Salutare %s,

Parola ta a fost schimbată în: %s",

	'email:changereq:subject' => "Cerere pentru schimbarea de parolă.",
	'email:changereq:body' => "Salutare %s,

Cineva (de pe adresa de IP %s) a cerut o schimbare de parolă pentru contul său.

Dacă tu ai cerut asta, apasă pe legătura de mai jos. Altfel ignoră acest email.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "Nivelul tău iniţial de acces",
	'default_access:label' => "Acces iniţial",
	'user:default_access:success' => "Noul tău nivel de acces iniţial a fost schimbat.",
	'user:default_access:failure' => "Noul tău nivel de acces iniţial nu a putut fii salvat.",

/**
 * Comments
 */

	'comments:count' => "%s comentarii",
	'item:object:comment' => 'Comentarii',

	'river:comment:object:default' => '%s a comentat pe %s',

	'generic_comments:add' => "Lasă un comentariu",
	'generic_comments:edit' => "Editează comentariul",
	'generic_comments:post' => "Postează comentariul",
	'generic_comments:text' => "Comentariu",
	'generic_comments:latest' => "Ultimele comentarii",
	'generic_comment:posted' => "Comentariul tău a fost postat cu succes.",
	'generic_comment:updated' => "Comentariul a fost actualizat cu succes.",
	'generic_comment:deleted' => "Comentariul a fost şters cu succes.",
	'generic_comment:blank' => "Ne pare rău, dar trebuie să scrii ceva în comentariu înainte de a fii salvat.",
	'generic_comment:notfound' => "Ne pare rău, dar nu am putut găsi comentariul specificat.",
	'generic_comment:notfound_fallback' => "Ne pare rău, dar nu am putut găsi comentariul specificat, dar te-am redirecţionat către pagina unde a fost postat.",
	'generic_comment:notdeleted' => "Ne pare rău, dar nu am putut şterge acest comentariu.",
	'generic_comment:failure' => "O eroare necunoscută a avut loc la salvarea comentariului.",
	'generic_comment:none' => 'Fără comentarii',
	'generic_comment:title' => 'Comentariu de către %s',
	'generic_comment:on' => '%s pe %s',
	'generic_comments:latest:posted' => 'a postat un',

	'generic_comment:email:subject' => 'Ai un comentariu nou!',
	'generic_comment:email:body' => "Ai un comentariu nou la conţinutul tău \"%s\" de la %s. Scrie:


%s


Pentru a răspunde sau a vizita conţinutul original, apasă pe:

%s

Pentru a vedea profilul utilizatorului %s, apasă aici:

%s

Te rugăm să nu răspunzi acestui email.",

/**
 * Entities
 */

	'byline' => 'De către %s',
	'byline:ingroup' => 'în grupul %s',
	'entity:default:strapline' => 'Creat pe %s de către %s',
	'entity:default:missingsupport:popup' => 'This entity cannot be displayed correctly. This may be because it requires support provided by a plugin that is no longer installed.',

	'entity:delete:item' => 'Element',
	'entity:delete:item_not_found' => 'Element negăsit.',
	'entity:delete:permission_denied' => 'Nu ai permisiunea necesară pentru a şterge acest element.',
	'entity:delete:success' => '%s a fost şters.',
	'entity:delete:fail' => '%s nu poate fii şters.',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "Pagina pe care o foloseai a expirat. Te rugăm să reîncerci.",
	'actiongatekeeper:timeerror' => 'Pagina pe care o foloseai a expirat. Te rugăm să reîmprospătezi şi să reîncerci.',
	'actiongatekeeper:pluginprevents' => 'Ne pare rău, dar formularul tău nu poate fii trimis dintr-o eroare necunoscută.',
	'actiongatekeeper:uploadexceeded' => 'Mărimea fişierului(lor) pe care le-ai încărcat depăşeşte dimensiunile setate de către administrator',
	'actiongatekeeper:crosssitelogin' => "Ne pare rău, dar conectarea de pe un alt domeniu nu este permisă. Te rugăm să reîncerci",

/**
 * Word blacklists
 */

	'word:blacklist' => 'şi, the, atunci, dar, ea, lui, ei, el, unu, nu, deasemena, despre, acum, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Etichete',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Failed to contact %s. You may experience problems saving content. Please refresh this page.',
	'js:security:token_refreshed' => 'Connection to %s restored!',
	'js:lightbox:current' => "imaginea %s a %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Furnizat de către Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "Mandarin Chinese", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"sr_latin" => "Serbian (Latin)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Necesar',

);
