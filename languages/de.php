<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Seiten',

/**
 * Sessions
 */

	'login' => "Anmelden",
	'loginok' => "Du bist nun angemeldet.",
	'loginerror' => "Die Anmeldung ist fehlgeschlagen. Bitte prüfe, ob Deine Eingaben für die Anmeldung richtig sind und versuche es noch einmal.",
	'login:empty' => "Benutzername/Email-Adresse und Passwort müssen eingegeben werden.",
	'login:baduser' => "Dein Benutzeraccount ist nicht verfügbar.",
	'auth:nopams' => "Interner Fehler. Keine Methode zur Benutzerauthentifizierung installiert.",

	'logout' => "Abmelden",
	'logoutok' => "Du bist nun abgemeldet.",
	'logouterror' => "Wir konnten Dich nicht abmelden. Bitte versuche es noch einmal.",
	'session_expired' => "Deine Session ist abgelaufen. Bitte lade die Seite erneut, um Dich anzumelden.",

	'loggedinrequired' => "Du mußt angemeldet sein, um diese Seite aufrufen zu können.",
	'adminrequired' => "Du mußt ein Administrator sein, um diese Seite aufrufen zu können.",
	'membershiprequired' => "Du mußt Mitglied dieser Gruppe sein, um diese Seite aufrufen zu können.",
	'limited_access' => "Du hast nicht die erforderliche Berechtigung, um auf die angeforderte Seite zuzugreifen.",


/**
 * Errors
 */

	'exception:title' => "Schwerwiegender Fehler.",
	'exception:contact_admin' => 'Es ist ein nicht behebbarer Fehler aufgetreten. Der Fehler wurde protokolliert. Wenn Du der Seitenadministrator bist, prüfe bitte die Konfiguration in settings.php. Andernfalls leite bitte leite folgende Informationen an den Seitenadministrator weiter:',

	'actionundefined' => "Die angeforderte Aktion (%s) ist im System nicht definiert.",
	'actionnotfound' => "Die Datei für die Ausführung der Aktion %s wurde nicht gefunden.",
	'actionloggedout' => "Entschuldigung, Du kannst diese Aktion nicht ausführen während Du nicht angemeldet bist.",
	'actionunauthorized' => 'Du bist nicht authorisiert, diese Aktion auszuführen',

	'PluginException:MisconfiguredPlugin' => "%s (GUID: %s) ist ein falsch konfiguriertes Plugin. Es wurde deaktiviert. Im Elgg-Wiki sind einige mögliche Ursachen für das Problem beschrieben (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (GUID: %s) kann nicht gestartet werden und wurde deaktiviert. Ursache: %s.',
	'PluginException:InvalidID' => "%s ist eine ungültig Plugin-ID.",
	'PluginException:InvalidPath' => "%s ist ungültiger Plugin-Dateipfad.",
	'PluginException:InvalidManifest' => 'Ungültige Manifest-Datei für das Plugin %s.',
	'PluginException:InvalidPlugin' => '%s ist kein zulässiges Plugin.',
	'PluginException:InvalidPlugin:Details' => '%s ist kein zulässiges Plugin: %s',
	'PluginException:NullInstantiated' => 'Ein ElggPlugin-Objekt kann nicht mit NULL instanziiert werden. Es muss eine GUID, eine Plugin-ID oder ein vollständiger Dateipfad übergeben werden.',
	'ElggPlugin:MissingID' => 'Fehlende Plugin-ID (GUID: %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Das zugehörige Plugin-Paket für die Plugin-ID %s (GUID: %s) fehlt.',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Die benötigte Datei "%s" kann nicht gefunden werden.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Das Plugin-Verzeichnis dieses Plugins muss zu "%s" umbenannt werden, um mit der ID, die in der Manifest-Datei des Plugins angegeben ist, überein zu stimmen.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Die Manifest-Datei enthält den ungültigen Requires-Typ "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Die Manifest-Datei enthält den ungültigen Provides-Typ "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Nicht auflösbare %s-Abhängigkeit "%s" im Plugin %s. Plugins können nicht mit etwas in Konflikt stehen oder etwas voraussetzen, das sie selbst bereitstellen!',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Einbindung von %s für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Öffnen des Views-Verzeichnis für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Einbinden der Sprachen für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:NoID' => 'Keine ID für Plugin-GUID %s!',
	'PluginException:NoPluginName' => "Der Name des Plugins kann nicht ermittelt werden.",
	'PluginException:ParserError' => 'Das Parsen der Manifest-Datei mit API-Version %s des Plugins %s ist fehlgeschlagen.',
	'PluginException:NoAvailableParser' => 'Es steht kein Parser für die Manifest-API-Version %s des Plugins %s zur Verfügung.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Fehlendes Required-'%s'-Attribut in der Manifest-Datei des Plugins %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s ist kein gültiges Plugin und wurde deshalb deaktiviert.',

	'ElggPlugin:Dependencies:Requires' => 'Benötigt',
	'ElggPlugin:Dependencies:Suggests' => 'Schlägt vor',
	'ElggPlugin:Dependencies:Conflicts' => 'Im Konflikt mit',
	'ElggPlugin:Dependencies:Conflicted' => 'Im Konflikt mit',
	'ElggPlugin:Dependencies:Provides' => 'Stellt bereit',
	'ElggPlugin:Dependencies:Priority' => 'Priorität',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg-Version',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP-Version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP-Erweiterung: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini-Einstellung: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Nach %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Vor %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s ist nicht installiert.',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Fehlt',
	
	'ElggPlugin:Dependencies:ActiveDependent' => 'Es sind aktive Plugins vorhanden, die die Verfügbarkeit von %s voraussetzen. Bevor Du es deaktivieren kannst, mußt Du erst folgende Plugins deaktivieren: %s',


	'RegistrationException:EmptyPassword' => 'Die Passwort-Felder dürfen nicht leer sein',
	'RegistrationException:PasswordMismatch' => 'Die Passwörter müssen übereinstimmen',
	'LoginException:BannedUser' => 'Dein Benutzeraccount auf dieser Seite wurde gesperrt. Du kannst Dich daher nicht anmelden.',
	'LoginException:UsernameFailure' => 'Die Anmeldung ist fehlgeschlagen. Bitte prüfe, ob Benutzername/Email-Adresse und Passwort richtig sind.',
	'LoginException:PasswordFailure' => 'Die Anmeldung ist fehlgeschlagen. Bitte prüfe, ob Benutzername/Email-Adresse und Passwort richtig sind.',
	'LoginException:AccountLocked' => 'Dein Benutzeraccount wurde aufgrund zu vieler fehlgeschlagener Anmeldeversuche gesperrt.',
	'LoginException:ChangePasswordFailure' => 'Die Überprüfung des derzeitigen Passworts ist fehlgeschlagen.',
	'LoginException:Unknown' => 'Die Anmeldung ist aus unbekannter Ursache fehlgeschlagen.',

	'deprecatedfunction' => 'Warnung: Dieser Code verwendet die veraltete Funktion \'%s\' und ist mit dieser Version von Elgg nicht kompatibel.',

	'pageownerunavailable' => 'Warnung: Der Seiten-Eigentümer %d ist nicht erreichbar!',
	'viewfailure' => 'In der View %s ist ein interner Fehler aufgetreten.',
	'view:missing_param' => "Der notwendige Parameter '%s' fehlt in der View %s.",
	'changebookmark' => 'Bitte ändere Dein Lesezeichen für diese Seite.',
	'noaccess' => 'Der Seiteninhalt, den Du aufgerufen hast, wurde entweder gelöscht oder Du hast keine ausreichende Berechtigung, um darauf zuzugreifen.',
	'error:missing_data' => 'Bei Deiner Anfrage fehlten einige notwendige Daten.',
	'save:fail' => 'Beim Speichern Deiner Daten ist ein Fehler aufgetreten.',
	'save:success' => 'Deine Daten wurden gespeichert.',

	'error:default:title' => 'Hoppla...',
	'error:default:content' => 'Hoppla...irgendetwas ist schiefgegangen.',
	'error:404:title' => 'Seite nicht gefunden',
	'error:404:content' => 'Entschuldigung. Die gewünschte Seite konnte nicht gefunden werden.',

	'upload:error:ini_size' => 'Die Datei, die Du hochladen willst, ist zu groß.',
	'upload:error:form_size' => 'Die Datei, die Du hochladen willst, ist zu groß.',
	'upload:error:partial' => 'Das Hochladen der Datei konnte nicht abgeschlossen werden.',
	'upload:error:no_file' => 'Es wurde keine Datei ausgewählt.',
	'upload:error:no_tmp_dir' => 'Das Speichern der hochgeladenen Datei ist fehlgeschlagen.',
	'upload:error:cant_write' => 'Das Speichern der hochgeladenen Datei ist fehlgeschlagen.',
	'upload:error:extension' => 'Das Speichern der hochgeladenen Datei ist fehlgeschlagen.',
	'upload:error:unknown' => 'Das Hochladen der Datei ist fehlgeschlagen.',


/**
 * User details
 */

	'name' => "Name",
	'email' => "Email-Adresse",
	'username' => "Benutzername",
	'loginusername' => "Benutzername oder Email",
	'password' => "Passwort",
	'passwordagain' => "Passwort (wiederholen für Verifikation)",
	'admin_option' => "Diesen Benutzer zum Admin machen?",

/**
 * Access
 */

	'PRIVATE' => "Privat",
	'LOGGED_IN' => "Angemeldete Benutzer",
	'PUBLIC' => "Öffentlich",
	'LOGGED_OUT' => "Nicht-angemeldete Benutzer",
	'access:friends:label' => "Freunde",
	'access' => "Zugangslevel",
	'access:overridenotice' => "Anmerkung: aufgrund der Einstellungen dieser Gruppe wird dieser Inhalt nur für Gruppenmitglieder sichtbar sein.",
	'access:limited:label' => "Beschränkt",
	'access:help' => "Der Zugangslevel",
	'access:read' => "Zugangslevel",
	'access:write' => "Schreibberechtigung",
	'access:admin_only' => "nur Administratoren",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Dein persönliches Dashboard ermöglicht es Dir, die Aktivitäten auf dieser Commuity-Seite zu verfolgen und schnellen Zugriff auf die Inhalte zu bekommen, die Dich besonders interessieren.",

	'widgets:add' => 'Widgets hinzufügen',
	'widgets:add:description' => "Klicke auf eines der unten aufgelisteten Widgets, um es zu Deiner Seite hinzuzufügen.",
	'widgets:position:fixed' => '(Feste Position auf der Seite)',
	'widget:unavailable' => 'Du hast dieses Widget bereits hinzugefügt.',
	'widget:numbertodisplay' => 'Anzahl der anzuzeigenden Einträge.',

	'widget:delete' => '%s entfernen',
	'widget:edit' => 'Dieses Widget konfigurieren',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "Das Widget wurde hinzugefügt.",
	'widgets:save:failure' => "Das Widget konnte nicht hinzugefügt werden. Bitte versuche es noch einmal.",
	'widgets:add:success' => "Das Widget wurde hinzugefügt.",
	'widgets:add:failure' => "Das Hinzufügen des Wigets ist fehlgeschlagen.",
	'widgets:move:failure' => "Die Position des Wigets auf Deiner Seite konnte nicht gespeichert werden.",
	'widgets:remove:failure' => "Das Wiget konnte nicht entfernt werden.",

/**
 * Groups
 */

	'group' => "Gruppe",
	'item:group' => "Gruppen",

/**
 * Users
 */

	'user' => "Benutzer",
	'item:user' => "Mitglieder",

/**
 * Friends
 */

	'friends' => "Freunde",
	'friends:yours' => "Deine Freunde",
	'friends:owned' => "Freunde von %s",
	'friend:add' => "Zu Freundesliste hinzufügen",
	'friend:remove' => "Aus Freundesliste entfernen",

	'friends:add:successful' => "%s wurde zu Deiner Freundesliste hinzugefügt.",
	'friends:add:failure' => "%s konnte nicht zu Deiner Freundesliste hinzugefügt werden. Bitte versuche es noch einmal.",

	'friends:remove:successful' => "%s wurde aus Deiner Freundesliste entfernt.",
	'friends:remove:failure' => "%s konnte nicht aus Deiner Freundesliste entfernt werden. Bitte versuche es noch einmal.",

	'friends:none' => "Noch keine Freunde.",
	'friends:none:you' => "Du hast noch niemanden in Deine Freundenliste aufgenommen!",

	'friends:none:found' => "Es wurden keine Freunde gefunden.",

	'friends:of:none' => "Bisher hat diesen Benutzer noch niemand in die Freundesliste aufgenommen.",
	'friends:of:none:you' => "Bisher hat Dich noch niemand in seine Freundesliste aufgenomen. Werde aktiv und trage etwas zur Community bei, fülle Dein Profil, damit Dich die anderen Mitglieder finden können!",

	'friends:of:owned' => "Mitglieder, die mit %s befreundet sind",

	'friends:of' => "Befreundet mit",
	'friends:collections' => "Freundeslisten",
	'collections:add' => "Neue Liste",
	'friends:collections:add' => "Neue Freundesliste erstellen",
	'friends:addfriends' => "Freunde hinzufügen",
	'friends:collectionname' => "Name der Freundesliste",
	'friends:collectionfriends' => "Freunde in dieser Liste",
	'friends:collectionedit' => "Bearbeite diese Liste",
	'friends:nocollections' => "Du hast noch keine Freundesliste erstellt.",
	'friends:collectiondeleted' => "Deine Freundesliste wurde gelöscht.",
	'friends:collectiondeletefailed' => "Wir konnten die Freundesliste nicht löschen. Entweder hast Du dafür keine Berechtigung oder ein anderes Problem ist aufgetreten.",
	'friends:collectionadded' => "Die Freundesliste wurde erstellt",
	'friends:nocollectionname' => "Du mußt Deiner Freundesliste einen Namen geben, bevor sie abgespeichert werden kann.",
	'friends:collections:members' => "Mitglieder in der Freundesliste",
	'friends:collections:edit' => "Freundesliste bearbeiten",
	'friends:collections:edited' => "Die Freundesliste wurde gespeichert.",
	'friends:collection:edit_failed' => 'Das Speichern der Freundesliste ist fehlgeschlagen.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Profilbild',
	'avatar:noaccess' => "Du hast keine Berechtigung, das Profilbild dieses Benutzers zu bearbeiten.",
	'avatar:create' => 'Profilbild erstellen',
	'avatar:edit' => 'Profilbild bearbeiten',
	'avatar:preview' => 'Vorschau',
	'avatar:upload' => 'Profilbild hochladen',
	'avatar:current' => 'Derzeitiges Profilbild',
	'avatar:remove' => 'Löschen Deines Profilbildes und Wiederherstellen des Standardbildes',
	'avatar:crop:title' => 'Tool zum Zuschneiden des Profilbildes',
	'avatar:upload:instructions' => "Das Profilbild ist das Bild, das auf Deiner Profilseite und bei all Deinen Beiträgen auf der Community-Seite angezeigt wird. Du kannst es so oft ändern wie Du willst. (Mögliche Dateiformate: GIF, JPG oder PNG)",
	'avatar:create:instructions' => 'Um Dein Profilbild nach Deinen Wünschen zuzuschneiden (optional), klicke es an und markiere einen quadratischen Ausschnitt während Du die Maustaste gedrückt hältst. Du kannst den Ausschnitt anschließend auch verschieben und in der Größe anpassen. Eine Vorschau Deines Profilbildes wird in der Box rechts daneben dargestellt. Wenn Du zufrieden mit dem Zuschneiden bist, klicke auf \'Profilbild erstellen\', um das Profilbild zu übernehmen. Das zugeschnittene Bild wird dann auf der ganzen Community-Seite als Dein Profilbild verwendet.',
	'avatar:upload:success' => 'Dein Profilbild wurde hochgeladen.',
	'avatar:upload:fail' => 'Das Hochladen des Profilbildes ist fehlgeschlagen.',
	'avatar:resize:fail' => 'Die Größenanpassung des Profilbildes ist fehlgeschlagen.',
	'avatar:crop:success' => 'Das Zuschneiden des Profilbildes war erfolgreich.',
	'avatar:crop:fail' => 'Das Zuschneiden des Profilbildes ist fehlgeschlagen.',
	'avatar:remove:success' => 'Das Profilbild wurde gelöscht.',
	'avatar:remove:fail' => 'Das Löschen des Profilbildes ist fehlgeschlagen.',

	'profile:edit' => 'Profil bearbeiten',
	'profile:aboutme' => "Über mich",
	'profile:description' => "Über mich",
	'profile:briefdescription' => "Kurzbeschreibung",
	'profile:location' => "Heimatort",
	'profile:skills' => "Fähigkeiten",
	'profile:interests' => "Interessen",
	'profile:contactemail' => "Email-Kontaktadresse",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Handy",
	'profile:website' => "Webseite",
	'profile:twitter' => "Twitter-Benutzername",
	'profile:saved' => "Die Änderungen an Deinem Profil wurden gespeichert.",

	'profile:field:text' => 'Kurzbeschreibung',
	'profile:field:longtext' => 'Ausführliche Beschreibung',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Webseite',
	'profile:field:email' => 'Email-Adresse',
	'profile:field:location' => 'Heimatort',
	'profile:field:date' => 'Datum',

	'admin:appearance:profile_fields' => 'Profilfelder konfigurieren',
	'profile:edit:default' => 'Anpassen der Profilfelder',
	'profile:label' => "Name des Profilfeldes",
	'profile:type' => "Feldtyp",
	'profile:editdefault:delete:fail' => 'Das Entfernen des Profilfeldes ist fehlgeschlagen.',
	'profile:editdefault:delete:success' => 'Das Profilfeld wurde gelöscht!',
	'profile:defaultprofile:reset' => 'Die Standard-Profilfelder wurden wiederhergestellt.',
	'profile:resetdefault' => 'Standard-Profilfelder wiederherstellen',
	'profile:resetdefault:confirm' => 'Bist Du sicher, dass Du Deine benutzerdefinierten Profilfelder löschen willst?',
	'profile:explainchangefields' => "Hier kannst Du die existierenden Profilfelder durch eigene Felder ersetzen. Zuerst mußt Du einen Namen für das neue Feld eingeben, z.B. 'Lieblingsteam'. Dann mußt Du den Typ des Feldes auswählen (z.B. Text, URL, Tags). Mit einem Klick auf 'Hinzufügen' wird das Feld dann in das Profil aufgenommen. Um die Reihenfolge der Felder zu ändern, kannst Du ein Feld am Greifer neben dem Namen in seine gewünschte Position ziehen. Um den Namen eines Feldes zu ändern, klicke auf den Text, um ihn editierbar zu machen. \n\n Du kannst jederzeit das ursprüngliche Standard-Profil wiederherstellen. Aber alle Informationen, die in die benutzerdefinierten Felder auf den Profilseiten eingegeben wurden, gehen dann verloren.",
	'profile:editdefault:success' => 'Das Profilfeld wurde hinzugefügt.',
	'profile:editdefault:fail' => 'Die Änderung der Profilfelder konnte nicht gespeichert werden.',
	'profile:field_too_long' => 'Die Profilinformationen können nicht gespeichert werden, da der Inhalt des "%s"-Feldes zu lang ist.',
	'profile:noaccess' => "Du hast keine ausreichende Berechtigung, um dieses Profil zu editieren.",
	'profile:invalid_email' => '%s muss eine gültige Email-Adresse sein.',


/**
 * Feeds
 */
	'feed:rss' => 'RSS-Feed für diese Seite',
/**
 * Links
 */
	'link:view' => 'Link aufrufen',
	'link:view:all' => 'Alle ansehen',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s ist nun mit %s befreundet",
	'river:update:user:avatar' => '%s hat ein neues Profilbild hochgeladen',
	'river:update:user:profile' => '%s hat ihre/seine Profilseite aktualisiert',
	'river:noaccess' => 'Du hast keine Berechtigung um diesen Eintrag anzuzeigen.',
	'river:posted:generic' => '%s schrieb',
	'riveritem:single:user' => 'ein Mitglied',
	'riveritem:plural:user' => 'einige Mitglieder',
	'river:ingroup' => 'in der Gruppe %s',
	'river:none' => 'Keine Aktivität',
	'river:update' => 'Aktualisierung für %s',
	'river:delete' => 'Entferne diesen Aktivitäts-Eintrag',
	'river:delete:success' => 'Der Eintrag wurde gelöscht.',
	'river:delete:fail' => 'Der Eintrag konnte nicht gelöscht werden.',
	'river:subject:invalid_subject' => 'Ungültiger Benutzer',
	'activity:owner' => 'Aktivitäten anzeigen',

	'river:widget:title' => "Aktivitäten",
	'river:widget:description' => "Neueste Aktivitäten anzeigen",
	'river:widget:type' => "Art der Aktivitäten",
	'river:widgets:friends' => 'Aktivitäten Deiner Freunde',
	'river:widgets:all' => 'Alle Aktivitäten der Seite',

/**
 * Notifications
 */
	'notifications:usersettings' => "Benachrichtigungs-Einstellungen",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Die Benachrichtigungs-Einstellungen wurden gespeichert.",
	'notifications:usersettings:save:fail' => "Beim Speichern Deiner Benachrichtigungs-Einstellungen ist ein Problem aufgetreten.",

	'notification:subject' => 'Benachrichtigung über %s',
	'notification:body' => 'Schau Dir die neue Aktivität hier an: %s',

/**
 * Search
 */

	'search' => "Suche",
	'searchtitle' => "Suche: %s",
	'users:searchtitle' => "Suche nach Mitgliedern: %s",
	'groups:searchtitle' => "Suche nach Gruppen: %s",
	'advancedsearchtitle' => "%s mit Treffern passend zu %s",
	'notfound' => "Keine Treffer.",
	'next' => "Weiter",
	'previous' => "Zurück",

	'viewtype:change' => "Ansicht wechseln",
	'viewtype:list' => "Listen-Ansicht",
	'viewtype:gallery' => "Gallerie-Ansicht",

	'tag:search:startblurb' => "Einträge mit Tags passend zu '%s':",

	'user:search:startblurb' => "Mitglieder passend zu '%s':",
	'user:search:finishblurb' => "Für weitere Treffer hier klicken.",

	'group:search:startblurb' => "Gruppen passend zu '%s':",
	'group:search:finishblurb' => "Für weitere Treffer hier klicken.",
	'search:go' => 'Los',
	'userpicker:only_friends' => 'Nur Freunde',

/**
 * Account
 */

	'account' => "Account",
	'settings' => "Einstellungen",
	'tools' => "Tools",
	'settings:edit' => 'Einstellungen bearbeiten',

	'register' => "Registrieren",
	'registerok' => "Du hast Dich erfolgreich auf %s registriert.",
	'registerbad' => "Deine Registrierung ist aufgrund eines unbekannten Fehlers fehlgeschlagen.",
	'registerdisabled' => "Die Registrierung wurde durch den Administrator deaktiviert.",
	'register:fields' => 'Es müssen alle Felder ausgefüllt werden.',

	'registration:notemail' => 'Die von Dir angegebene Email-Adresse scheint keine gültige Email-Adresse zu sein.',
	'registration:userexists' => 'Dieser Benutzername ist schon belegt.',
	'registration:usernametooshort' => 'Dein Benutzername muss mindestens %u Zeichen lang sein.',
	'registration:usernametoolong' => 'Dein Benutzername ist zu lang. Es sind maximal %u Zeichen zulässig.',
	'registration:passwordtooshort' => 'Das Passwort muss mindestens %u Zeichen lang sein.',
	'registration:dupeemail' => 'Diese Email-Adresse ist schon bei einer früheren Registrierung verwendet worden.',
	'registration:invalidchars' => 'Entschuldigung, Dein Benutzername enthält das unzulässige Zeichen %s. Folgende Zeichen sind nicht zulässig: %s',
	'registration:emailnotvalid' => 'Entschuldigung, die angegebene Email-Adresse ist auf dieser Seite nicht zulässig.',
	'registration:passwordnotvalid' => 'Entschuldigung, das angegebene Passwort ist auf dieser Seite nicht zulässig.',
	'registration:usernamenotvalid' => 'Entschuldigung, der angegebene Benutzername ist auf dieser Seite nicht zulässig.',

	'adduser' => "Benutzer hinzufügen",
	'adduser:ok' => "Es wurde ein neuer Benutzer hinzugefügt.",
	'adduser:bad' => "Der neue Benutzeraccount konnte nicht erzeugt werden.",

	'user:set:name' => "Benutzernamen-Einstellungen",
	'user:name:label' => "Name",
	'user:name:success' => "Dein auf der Seite angezeigter Name wurde geändert.",
	'user:name:fail' => "Die Änderung Deines Namens konnte nicht gespeichert werden.",

	'user:set:password' => "Account-Passwort ändern",
	'user:current_password:label' => 'Dein derzeitiges Passwort',
	'user:password:label' => "Neues Passwort",
	'user:password2:label' => "Neues Passwort noch einmal eingeben",
	'user:password:success' => "Das Passwort wurde geändert.",
	'user:password:fail' => "Die Änderung Deines Passworts ist fehlgeschlagen.",
	'user:password:fail:notsame' => "Die zwei eingegebenen Passwörter stimmen nicht überein!",
	'user:password:fail:tooshort' => "Das angegebene Passwort ist zu kurz!",
	'user:password:fail:incorrect_current_password' => 'Deine Eingabe stimmt nicht mit Deinem derzeitigen Passwort überein.',
	'user:changepassword:unknown_user' => 'Unbekannter Benutzername.',
	'user:changepassword:change_password_confirm' => 'Dein Passwort wird nun geändert.',

	'user:set:language' => "Sprache der Community-Seite",
	'user:language:label' => "Deine Sprache",
	'user:language:success' => "Deine Spracheinstellungen wurden aktualisiert.",
	'user:language:fail' => "Deine Spracheinstellungen konnten nicht gespeichert werden.",

	'user:username:notfound' => 'Benutzername %s unbekannt.',

	'user:password:lost' => 'Neues Passwort',
	'user:password:changereq:success' => 'Die Anforderung eines neuen Passworts war erfolgreich. Eine Email mit dem neuen Passwort wurde gesendet.',
	'user:password:changereq:fail' => 'Die Anforderung eines neuen Passworts ist fehlgeschlagen.',

	'user:password:text' => 'Um ein neues Passwort anzufordern, gebe im folgenden Textfeld Deinen Benutzernamen oder Deine Email-Adresse ein. Wir werden Dir dann eine Email zur Bestätigung der Anfrage zusenden. Folge dem Link in dieser Email, um Deine Passwort-Anfrage zu bestätigen. Dann wird Dir ein neues Passwort zugesandt.',

	'user:persistent' => 'Merken',

	'walled_garden:welcome' => 'Willkommen auf',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrieren',
	'menu:page:header:configure' => 'Konfigurieren',
	'menu:page:header:develop' => 'Entwickeln',
	'menu:page:header:default' => 'Anderes',

	'admin:view_site' => 'Seite anzeigen',
	'admin:loggedin' => 'Angemeldet als %s',
	'admin:menu' => 'Menü',

	'admin:configuration:success' => "Deine Einstellungen wurden gespeichert.",
	'admin:configuration:fail' => "Deine Einstellungen konnten nicht gespeichert werden.",
	'admin:configuration:dataroot:relative_path' => 'Der Pfad "%s" ist nicht als Pfad zum Datenverzeichnis zulässig, da es kein absoluter Pfad ist.',

	'admin:unknown_section' => 'Unbekannter Adminbereich.',

	'admin' => "Admin",
	'admin:description' => "Der Admin-Bereich ermöglicht es Dir, Systemeinstellungen vorzunehmen. Du hast Zugriff beispielsweise auf die Benutzerverwaltung und die Konfiguration von Plugins. Bitte wähle eine der unten angebotenen Optionen.",

	'admin:statistics' => "Statistiken",
	'admin:statistics:overview' => 'Übersicht',
	'admin:statistics:server' => 'Server-Info',
	'admin:statistics:cron' => 'Cronjobs-Info',
	'admin:cron:record' => 'Zuletzt ausgeführte Cron-Jobs',
	'admin:cron:period' => 'Cron-Zeitintervall',
	'admin:cron:friendly' => 'Zuletzt abgeschlossen',
	'admin:cron:date' => 'Datum und Zeit',

	'admin:appearance' => 'Design',
	'admin:administer_utilities' => 'Werkzeuge',
	'admin:develop_utilities' => 'Werkzeuge',
	'admin:configure_utilities' => 'Werkzeuge',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Benutzer",
	'admin:users:online' => 'Online',
	'admin:users:newest' => 'Neueste',
	'admin:users:admins' => 'Administratoren',
	'admin:users:add' => 'Neuen Benutzer hinzufügen',
	'admin:users:description' => "In diesem Admin-Bereich kannst Du Benutzereinstellugen für Benutzeraccounts konfigurieren. Wähle eine der unten gezeigten Optionen.",
	'admin:users:adduser:label' => "Hier klicken um einen neuen Benutzeraccount zu erstellen...",
	'admin:users:opt:linktext' => "Benutzeraccount konfigurieren...",
	'admin:users:opt:description' => "Benutzeraccounts und Accountinformationen bearbeiten. ",
	'admin:users:find' => 'Finde',

	'admin:administer_utilities:maintenance' => 'Wartungs-Modus',
	'admin:upgrades' => 'Aktualisierungen',

	'admin:settings' => 'Einstellungen',
	'admin:settings:basic' => 'Grundeinstellungen',
	'admin:settings:advanced' => 'Erweiterte Einstellungen',
	'admin:site:description' => "Hier kannst Du einige globale Einstellungen für Deine Community-Seite vornehmen.",
	'admin:site:opt:linktext' => "Community-Seite konfigurieren...",
	'admin:settings:in_settings_file' => 'Diese Einstellung kann in settings.php angepasst werden.',

	'admin:legend:security' => 'Sicherheit',
	'admin:site:secret:intro' => 'Elgg verwendet einen seitenspezifischen Geheimschlüssel, um darauf basierend Sicherheits-Token zu generieren, die für verschiedenen Authentifizierungszwecke verwendet werden.',
	'admin:site:secret_regenerated' => "Der Geheimschlüssel Deiner Community-Seite wurde neu erzeugt.",
	'admin:site:secret:regenerate' => "Geheimschlüssel neu erzeugen",
	'admin:site:secret:regenerate:help' => "Anmerkung: die Neuerstellung des Geheimschlüssels kann einigen Benutzern Deiner Seite leider zwangsläufig einige Unannehmlichkeiten bereiten, da als Folge davon die Authentifizierungs-Token beispielsweise in den Cookies für die persistente Anmeldung, in den Validierungs-Emails und den Einladungscodes, die noch mit dem alten Geheimschlüssel erzeugt wurden, nicht mehr gültig sind",
	'site_secret:current_strength' => 'Verschlüsselungsstärke',
	'site_secret:strength:weak' => "Schwach",
	'site_secret:strength_msg:weak' => "Wir empfehlen nachdrücklich, dass Du den Geheimschlüssel Deiner Community-Seite neu erzeugst.",
	'site_secret:strength:moderate' => "Moderat",
	'site_secret:strength_msg:moderate' => "Wir empfehlen, den Geheimschlüssel Deiner Community-Seite neu zu erzeugen, um die höchstmögliche Sicherheit für Deine Community-Seite zu gewährleisten.",
	'site_secret:strength:strong' => "Stark",
	'site_secret:strength_msg:strong' => "Die Verschlüsselungsstärke des Geheimschlüssels Deiner Community-Seite ist ausreichend hoch. Es ist nicht notwendig, den Schlüssel neu zu erzeugen.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Angemeldete Benutzer',
	'admin:widget:online_users:help' => 'Auflistung der Benutzer, die momentan online sind.',
	'admin:widget:new_users' => 'Neue Benutzer',
	'admin:widget:new_users:help' => 'Auflistung der neuesten Benutzer.',
	'admin:widget:banned_users' => 'Gesperrte Benutzer',
	'admin:widget:banned_users:help' => 'Liste gesperrter Benutzer',
	'admin:widget:content_stats' => 'Inhalts-Statistiken',
	'admin:widget:content_stats:help' => 'Auflistung der Inhalte, die von Benutzern erzeugt wurden.',
	'widget:content_stats:type' => 'Typ des Inhalts',
	'widget:content_stats:number' => 'Anzahl',

	'admin:widget:admin_welcome' => 'Willkommen',
	'admin:widget:admin_welcome:help' => "Eine kurze Einführung in den Admin-Bereich von Elgg.",
	'admin:widget:admin_welcome:intro' =>
'Willkommen auf Deiner Elgg-Seite! Du siehst gerade das Administrator-Dashboard. Es ist hilfreich, um auf Deiner Seite den Überblick zu behalten.',

	'admin:widget:admin_welcome:admin_overview' =>
"Der Admin-Bereich ist in Unterseiten aufgeteilt, die über das Menu auf der rechten Seite aufgerufen werden können. Der Admin-Bereich ist
 in drei Abschnitte aufgeteilt:
	<dl>
		<dt>Administrieren</dt><dd>Alltägliche Aufgaben wie das Prüfen von gemeldeten Beiträgen, Auflisten der gerade angemeldeten Benutzern und das Anzeigen von Statistiken.</dd>
		<dt>Konfigurieren</dt><dd>Gelegentlich notwendige Aufgaben wie die Konfiguration des Namens der Seite oder das Aktivieren eines Plugins.</dd>
		<dt>Entwickeln</dt><dd>Für Entwickler, die ein Plugin oder ein Theme testen wollen. (Ein Entwickler-Plugin ist dafür notwendig.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Wirf auch einen Blick auf die zusätzlichen Resourcen, die Du über die Links in der Fußzeile des Admin-Bereichs aufrufen kannst. Und vielen Dank, dass Du Elgg verwendest!',

	'admin:widget:control_panel' => 'Seiten-Administration',
	'admin:widget:control_panel:help' => "Schnellzugriff auf einige allgemeine administrative Funktionen",

	'admin:cache:flush' => 'Seitencaches zurücksetzen',
	'admin:cache:flushed' => "Die Caches der Seite wurden zurückgesetzt.",

	'admin:footer:faq' => 'Administrations-FAQs',
	'admin:footer:manual' => 'Administrator-Handbuch',
	'admin:footer:community_forums' => 'Elgg-Community-Foren',
	'admin:footer:blog' => 'Elgg-Blog',

	'admin:plugins:category:all' => 'Alle Plugins',
	'admin:plugins:category:active' => 'Aktivierte Plugins',
	'admin:plugins:category:inactive' => 'Deaktivierte Plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Mitgeliefert',
	'admin:plugins:category:nonbundled' => 'Nicht mitgeliefert',
	'admin:plugins:category:content' => 'Inhalte',
	'admin:plugins:category:development' => 'Entwicklung',
	'admin:plugins:category:enhancement' => 'Erweiterung',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'Kommunikation',
	'admin:plugins:category:security' => 'Sicherheit und Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Themes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Werkzeuge',

	'admin:plugins:markdown:unknown_plugin' => 'Unbekanntes Plugin.',
	'admin:plugins:markdown:unknown_file' => 'Unbekannte Datei.',

	'admin:notices:could_not_delete' => 'Die Benachrichtigung kann nicht gelöscht werden.',
	'item:object:admin_notice' => 'Admin-Benachrichtigung',

	'admin:options' => 'Admin-Optionen',

/**
 * Plugins
 */

	'plugins:disabled' => 'Die Plugins werden nicht geladen, da eine Datei namens "disabled" im mod-Verzeichnis vorhanden ist.',
	'plugins:settings:save:ok' => "Die Einstellungen für das Plugin %s wurden gespeichert.",
	'plugins:settings:save:fail' => "Beim Speichern der Einstellungungen für das Plugin %s ist ein Problem aufgetreten.",
	'plugins:usersettings:save:ok' => "Die Benutzereinstellungen für das Plugin %s wurden gespeichert.",
	'plugins:usersettings:save:fail' => "Beim Speichern der Benutzereinstellungungen für das Plugin %s ist ein Problem aufgetreten.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Alle aktivieren',
	'admin:plugins:deactivate_all' => 'Alle deaktivieren',
	'admin:plugins:activate' => 'Aktivieren',
	'admin:plugins:deactivate' => 'Deaktivieren',
	'admin:plugins:description' => "Hier kannst Du die auf Deiner Community-Seite installierten Tools (Plugins) verwalten und hast Zugriff auf die von ihnen angebotenen Konfigurationsoptionen.",
	'admin:plugins:opt:linktext' => "Tools konfigurieren...",
	'admin:plugins:opt:description' => "Konfigurieren der installierten Tools der Community-Seite.",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorien',
	'admin:plugins:label:licence' => "Lizenz",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Problem melden",
	'admin:plugins:label:donate' => "Spenden",
	'admin:plugins:label:moreinfo' => 'Weitere Informationen',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Pfad zum Plugin-Verzeichnis',
	'admin:plugins:label:contributors' => 'Mitwirkende',
	'admin:plugins:label:contributors:name' => 'Name',
	'admin:plugins:label:contributors:email' => 'Email-Adresse',
	'admin:plugins:label:contributors:website' => 'Webseite',
	'admin:plugins:label:contributors:username' => 'Community-Benutzername',
	'admin:plugins:label:contributors:description' => 'Beschreibung',
	'admin:plugins:label:dependencies' => 'Abhängigkeiten',

	'admin:plugins:warning:elgg_version_unknown' => 'Dieses Plugin verwendet eine veraltete Manifest-Datei und es gibt keine Informationen bezüglich den kompatiblen Elgg-Versionen. Es wird wahrscheinlich nicht funktionieren!',
	'admin:plugins:warning:unmet_dependencies' => 'Dieses Plugin hat unerfüllte Abhängigkeiten und kann deshalb nicht aktiviert werden. Prüfe die Abhängigkeiten für weitere Informationen.',
	'admin:plugins:warning:invalid' => 'Dieses Plugin ist nicht standardkonform: %s.',
	'admin:plugins:warning:invalid:check_docs' => 'Bitte schau in der <a href="http://docs.elgg.org/Invalid_Plugin">Elgg-Dokumentation</a> nach, um weitere Hinweise zur Problemlösung zu erhalten.',
	'admin:plugins:cannot_activate' => 'Aktivierung nicht möglich.',

	'admin:plugins:set_priority:yes' => "%s neu angeordnet.",
	'admin:plugins:set_priority:no' => "%s kann nicht neu angeordnet werden.",
	'admin:plugins:set_priority:no_with_msg' => "%s konnte nicht neu angeordnet werden. Fehlermeldung: %s",
	'admin:plugins:deactivate:yes' => "%s deaktiviert.",
	'admin:plugins:deactivate:no' => "%s kann nicht deaktiviert werden.",
	'admin:plugins:deactivate:no_with_msg' => "%s konnte nicht deaktiviert werden. Fehlermeldung: %s",
	'admin:plugins:activate:yes' => "%s aktiviert.",
	'admin:plugins:activate:no' => "%s kann nicht aktiviert werden.",
	'admin:plugins:activate:no_with_msg' => "%s konnte nicht aktiviert werden. Fehlermeldung: %s",
	'admin:plugins:categories:all' => 'Alle Kategorien',
	'admin:plugins:plugin_website' => 'Plugin-Webseite',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'Plugin-Einstellungen',
	'admin:plugins:warning:unmet_dependencies_active' => 'Dieses Plugin ist aktiviert aber es hat unerfüllte Abhängigkeiten. Dies kann zu Problemen führen. Siehe "mehr Info" unten für weitere Einzelheiten.',

	'admin:plugins:dependencies:type' => 'Typ',
	'admin:plugins:dependencies:name' => 'Name',
	'admin:plugins:dependencies:expected_value' => 'Erwarteter Wert',
	'admin:plugins:dependencies:local_value' => 'Tatsächlicher Wert',
	'admin:plugins:dependencies:comment' => 'Kommentar',

	'admin:statistics:description' => "Dies ist ein Überblick über die Seiten-Statistik Deiner Community. Falls Du detailiertere Statistiken benötigst, ist ein ausführlicheres, professionelles Administations-Tool verfügbar.",
	'admin:statistics:opt:description' => "Überblick über Statistiken zu Benutzern und Objekten Deiner Community-Seite.",
	'admin:statistics:opt:linktext' => "Statistiken anzeigen...",
	'admin:statistics:label:basic' => "Kurzstatistik Deiner Community-Seite",
	'admin:statistics:label:numentities' => "Entitäten Deiner Community-Seite",
	'admin:statistics:label:numusers' => "Anzahl der Mitglieder",
	'admin:statistics:label:numonline' => "Anzahl der angemeldeten Mitglieder",
	'admin:statistics:label:onlineusers' => "Momentan angemeldete Mitglieder",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg-Version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Webserver',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Speicherort des Logs',
	'admin:server:label:php_version' => 'PHP-Version',
	'admin:server:label:php_ini' => 'Speicherort der PHP ini-Datei',
	'admin:server:label:php_log' => 'PHP-Log',
	'admin:server:label:mem_avail' => 'Verfügbarer Hauptspeicher',
	'admin:server:label:mem_used' => 'Verwendeter Hauptspeicher',
	'admin:server:error_log' => "Fehler-Log des Webservers",
	'admin:server:label:post_max_size' => 'Php-Einstellung von post_max_size',
	'admin:server:label:upload_max_filesize' => 'Php-Einstellung von upload_max_filesize',
	'admin:server:warning:post_max_too_small' => '(Bemerkung: Der Wert von post_max_size muss größer als dieser Wert sein, damit Uploads dieser Größe möglich sind)',

	'admin:user:label:search' => "Suche Benutzeraccount:",
	'admin:user:label:searchbutton' => "Suche",

	'admin:user:ban:no' => "Benutzeraccount sperren",
	'admin:user:ban:yes' => "Benutzeraccount gesperrt.",
	'admin:user:self:ban:no' => "Du kannst Dich nicht selbst sperren!",
	'admin:user:unban:no' => "Sperrung des Benutzeraccounts kann nicht aufgehoben werden.",
	'admin:user:unban:yes' => "Sperrung des Benutzeraccounts aufgehoben.",
	'admin:user:delete:no' => "Benutzeraccount kann nicht gelöscht werden.",
	'admin:user:delete:yes' => "Der Benutzeraccount %s wurde gelöscht.",
	'admin:user:self:delete:no' => "Du kannst Deinen eigenen Account nicht löschen!",

	'admin:user:resetpassword:yes' => "Das Passwort wurde zurückgesetzt und der Benutzer benachrichtigt.",
	'admin:user:resetpassword:no' => "Das Passwort konnte nicht zurückgesetzt werden.",

	'admin:user:makeadmin:yes' => "Der Benutzer ist nun ein Administrator.",
	'admin:user:makeadmin:no' => "Die Zuweisung von Administratorrechten für den Benutzer ist fehlgeschlagen.",

	'admin:user:removeadmin:yes' => "Der Benutzer ist nicht länger ein Administrator.",
	'admin:user:removeadmin:no' => "Die Rücknahme von Administratorrechten für den Benutzer ist fehlgeschlagen.",
	'admin:user:self:removeadmin:no' => "Du kannst Dir nicht selbst die Administratorrechte entziehen.",

	'admin:appearance:menu_items' => 'Menü-Einträge',
	'admin:menu_items:configure' => 'Konfiguriere die Einträge im Hauptmenü.',
	'admin:menu_items:description' => 'Wähle aus, welche Links Du als separate Menü-Einträge anzeigen lassen willst. Die restlichen Links werden unter "Mehr" am Ende der Liste zusammengefaßt.',
	'admin:menu_items:hide_toolbar_entries' => 'Links aus dem Toolbar-Menü entfernen?',
	'admin:menu_items:saved' => 'Menü-Einträge gespeichert.',
	'admin:add_menu_item' => 'Einen benutzerdefinierten Menü-Eintrag hinzufügen',
	'admin:add_menu_item:description' => 'Gebe den anzuzeigenden Namen und die URL an, um einen Eintrag zum Menü hinzuzufügen.',

	'admin:appearance:default_widgets' => 'Standard-Widgets',
	'admin:default_widgets:unknown_type' => 'Unbekannter Widget-Typ.',
	'admin:default_widgets:instructions' => 'Hinzufügen, Entfernen, Positionieren und Konfigurieren der standardmäßig anzuzeigenden Widgets für die gewünschte Seite.
Diese Änderungen werden nur neu erstellte Benutzeraccounts auf Deiner Community-Seite betreffen.',

	'admin:robots.txt:instructions' => "Bearbeite im Folgenden die robots.txt-Datei dieser Seite:",
	'admin:robots.txt:plugins' => "Plugins fügen das Folgende zur robots.txt-Datei hinzu:",
	'admin:robots.txt:subdir' => "Die robots.txt-Datei für diese Community-Seite kann nicht bearbeitet werden, da Elgg in einem Unterverzeichnis installiert ist.",

	'admin:maintenance_mode:default_message' => 'Diese Community-Seite ist aufgrund von Wartungsarbeiten derzeit nicht verfügbar.',
	'admin:maintenance_mode:instructions' => 'Der Wartungs-Modus sollte während einer Aktualisierung der Community-Seite oder anderen größeren Änderungen an der Seite aktiviert werden.
		Wenn er aktiviert ist, können sich nur Admins anmelden und auf der Community-Seite browsen.',
	'admin:maintenance_mode:mode_label' => 'Wartungs-Modus',
	'admin:maintenance_mode:message_label' => 'Die Nachricht, die Benutzern angezeigt wird, wenn der Wartungsmodus aktiviert ist',
	'admin:maintenance_mode:saved' => 'Die Einstellungen für den Wartungs-Modus wurden gespeichert.',
	'admin:maintenance_mode:indicator_menu_item' => 'Die Community-Seite ist im Wartungs-Modus.',
	'admin:login' => 'Administrator-Anmeldung',

/**
 * User settings
 */
		
	'usersettings:description' => "Hier kannst Du alle Deine persönlichen Einstellungen vornehmen, beispielsweise Einstellungen der Benutzeraccounts order Konfiguration von Plugins.",

	'usersettings:statistics' => "Deine persönliche Statistik",
	'usersettings:statistics:opt:description' => "Überblick über Statistiken zu Benutzern und Objekten Deiner Community-Seite.",
	'usersettings:statistics:opt:linktext' => "Account-Statistik",

	'usersettings:user' => "Einstellungen von %s",
	'usersettings:user:opt:description' => "Hier kannst Du Benutzereinstellungen konfigurieren.",
	'usersettings:user:opt:linktext' => "Account konfigurieren",

	'usersettings:plugins' => "Tools",
	'usersettings:plugins:opt:description' => "Einstellungen (falls vorhanden) der aktivierten Tools konfigurieren.",
	'usersettings:plugins:opt:linktext' => "Konfiguriere Deine Tools",

	'usersettings:plugins:description' => "Hier kannst Du Deine persönlichen Einstellungen für die vom Administrator installierten Tools konfigurieren.",
	'usersettings:statistics:label:numentities' => "Deine Beiträge",

	'usersettings:statistics:yourdetails' => "Deine Accountdetails",
	'usersettings:statistics:label:name' => "Vollständiger Name",
	'usersettings:statistics:label:email' => "Email-Adresse",
	'usersettings:statistics:label:membersince' => "Mitglied seit",
	'usersettings:statistics:label:lastlogin' => "Zuletzt angemeldet",

/**
 * Activity river
 */
		
	'river:all' => 'Alle Aktivitäten',
	'river:mine' => 'Meine Aktivitäten',
	'river:owner' => 'Aktivitäten von %s',
	'river:friends' => 'Aktivitäten von Freunden',
	'river:select' => 'Zeige %s',
	'river:comments:more' => '+%u weitere',
	'river:generic_comment' => 'kommentierte %s %s',

	'friends:widget:description' => "Auflistung einiger Deiner Freunde",
	'friends:num_display' => "Anzahl der anzuzeigenden Freunde.",
	'friends:icon_size' => "Icon-Größe",
	'friends:tiny' => "sehr klein",
	'friends:small' => "klein",

/**
 * Icons
 */

	'icon:size' => "Icon-Größe",
	'icon:size:topbar' => "Topbar",
	'icon:size:tiny' => "Sehr klein",
	'icon:size:small' => "Klein",
	'icon:size:medium' => "Mittelgroß",
	'icon:size:large' => "Groß",
	'icon:size:master' => "Sehr groß",
		
/**
 * Generic action words
 */

	'save' => "Speichern",
	'reset' => 'Zurücksetzen',
	'publish' => "Veröffentlichen",
	'cancel' => "Abbrechen",
	'saving' => "Wird gespeichert...",
	'update' => "Aktualisieren",
	'preview' => "Vorschau",
	'edit' => "Bearbeiten",
	'delete' => "Löschen",
	'accept' => "Zustimmen",
	'reject' => "Abweisen",
	'decline' => "Ablehnen",
	'approve' => "Akzeptieren",
	'activate' => "Aktivieren",
	'deactivate' => "Deaktivieren",
	'disapprove' => "Zurückweisen",
	'revoke' => "Widerrufen",
	'load' => "Laden",
	'upload' => "Hochladen",
	'download' => "Herunterladen",
	'ban' => "Sperren",
	'unban' => "Sperrung aufheben",
	'banned' => "Gesperrt",
	'enable' => "Aktivieren",
	'disable' => "Deaktivieren",
	'request' => "Anfordern",
	'complete' => "vollständig",
	'open' => 'Öffnen',
	'close' => 'Schließen',
	'hide' => 'Verbergen',
	'show' => 'Anzeigen',
	'reply' => "Antworten",
	'more' => 'Weitere',
	'more_info' => 'Weitere Informationen',
	'comments' => 'Kommentare',
	'import' => 'Importieren',
	'export' => 'Exportieren',
	'untitled' => 'Ohne Titel',
	'help' => 'Hilfe',
	'send' => 'Absenden',
	'post' => 'Schreiben',
	'submit' => 'Abschicken',
	'comment' => 'Kommentieren',
	'upgrade' => 'Aktualisieren',
	'sort' => 'Sortieren',
	'filter' => 'Filtern',
	'new' => 'Neu',
	'add' => 'Hinzufügen',
	'create' => 'Hinzufügen',
	'remove' => 'Entfernen',
	'revert' => 'Zurücksetzen',

	'site' => 'Webseite',
	'activity' => 'Aktivitäten',
	'members' => 'Mitglieder',
	'menu' => 'Menu',

	'up' => 'Höher',
	'down' => 'Tiefer',
	'top' => 'Anfang',
	'bottom' => 'Ende',
	'right' => 'Rechts',
	'left' => 'Links',
	'back' => 'Zurück',

	'invite' => "Einladen",

	'resetpassword' => "Passwort zurücksetzen",
	'changepassword' => "Passwort ändern",
	'makeadmin' => "Zum Admin machen",
	'removeadmin' => "Admin entlassen",

	'option:yes' => "Ja",
	'option:no' => "Nein",

	'unknown' => 'Unbekannt',
	'never' => 'Nie',

	'active' => 'Aktiv',
	'total' => 'Gesamt',
	
	'ok' => 'OK',
	'any' => 'Irgendeine',
	'error' => 'Fehler',
	
	'other' => 'Andere',
	'options' => 'Optionen',
	'advanced' => 'Erweiterte',

	'learnmore' => "Hier klicken um mehr zu erfahren.",
	'unknown_error' => 'Unbekannter Fehler',

	'content' => "Beiträge",
	'content:latest' => 'Letzte Aktivitäten',
	'content:latest:blurb' => 'Oder hier klicken, um die neuesten Beiträge dieser Community-Seite zu sehen.',

	'link:text' => 'Link besuchen',
	
/**
 * Generic questions
 */

	'question:areyousure' => 'Bist Du sicher?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Nicht-gespeicherter Entwurf',
	'status:draft' => 'Entwurf',
	'status:unpublished' => 'Nicht veröffentlicht',
	'status:published' => 'Veröffentlicht',
	'status:featured' => 'Vorgestellt',
	'status:open' => 'Offen',
	'status:closed' => 'Geschlossen',

/**
 * Generic sorts
 */

	'sort:newest' => 'Neueste',
	'sort:popular' => 'Beliebt',
	'sort:alpha' => 'Alphabetisch',
	'sort:priority' => 'Priorität',
		
/**
 * Generic data words
 */

	'title' => "Titel",
	'description' => "Beschreibung",
	'tags' => "Tags",
	'all' => "Alle",
	'mine' => "Meine",

	'by' => 'von',
	'none' => 'keine',

	'annotations' => "Kommentare",
	'relationships' => "Beziehungen",
	'metadata' => "Metadaten",
	'tagcloud' => "Tagcloud",

	'on' => 'An',
	'off' => 'Aus',

/**
 * Entity actions
 */
		
	'edit:this' => 'Bearbeiten',
	'delete:this' => 'Löschen',
	'comment:this' => 'Kommentieren',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Bist Du sicher, dass Du diesen Eintrag löschen willst?",
	'deleteconfirm:plural' => "Bist Du sicher, dass Du diese Einträge löschen willst?",
	'fileexists' => "Es wurde bereits eine Datei hochgeladen. Um sie zu ersetzen, unten auswählen:",

/**
 * User add
 */

	'useradd:subject' => 'Benutzeraccount erstellt',
	'useradd:body' => '
%s,

Auf der Community-Seite %s wurde ein Benutzeraccount für Dich erstellt. Um Dich anzumelden, gehe zu:

%s

und melde Dich mit diesen Zugangsdaten an:

Benutzername (Username): %s
Passwort: %s

Nachdem Du Dich angemeldet hast, solltest Du Dein Passwort ändern.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Hier klicken, um die Statusmeldung auszublenden.",


/**
 * Import / export
 */
		
	'importsuccess' => "Das Importieren der Daten war erfolgreich",
	'importfail' => "Das Importieren der OpenDD-Daten ist fehlgeschlagen.",

/**
 * Time
 */

	'friendlytime:justnow' => "soeben",
	'friendlytime:minutes' => "vor %s Minuten",
	'friendlytime:minutes:singular' => "vor einer Minute",
	'friendlytime:hours' => "vor %s Stunden",
	'friendlytime:hours:singular' => "vor einer Stunde",
	'friendlytime:days' => "vor %s Tagen",
	'friendlytime:days:singular' => "gestern",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	
	'friendlytime:future:minutes' => "in %s Minuten",
	'friendlytime:future:minutes:singular' => "in einer Minute",
	'friendlytime:future:hours' => "in %s Stunden",
	'friendlytime:future:hours:singular' => "in einer Stunde",
	'friendlytime:future:days' => "in %s Tagen",
	'friendlytime:future:days:singular' => "morgen",

	'date:month:01' => '%s Januar',
	'date:month:02' => '%s Februar',
	'date:month:03' => '%s März',
	'date:month:04' => '%s April',
	'date:month:05' => '%s Mai',
	'date:month:06' => '%s Juni',
	'date:month:07' => '%s Juli',
	'date:month:08' => '%s August',
	'date:month:09' => '%s September',
	'date:month:10' => '%s Oktober',
	'date:month:11' => '%s November',
	'date:month:12' => '%s Dezember',

	'date:weekday:0' => 'Sonntag',
	'date:weekday:1' => 'Montag',
	'date:weekday:2' => 'Dienstag',
	'date:weekday:3' => 'Mittwoch',
	'date:weekday:4' => 'Donnerstag',
	'date:weekday:5' => 'Freitag',
	'date:weekday:6' => 'Samstag',
	
	'interval:minute' => 'Jede Minute',
	'interval:fiveminute' => 'Alle fünf Minuten',
	'interval:fifteenmin' => 'Alle 15 Minuten',
	'interval:halfhour' => 'Jede halbe Stunde',
	'interval:hourly' => 'Stündlich',
	'interval:daily' => 'Täglich',
	'interval:weekly' => 'Wöchentlich',
	'interval:monthly' => 'Monatlich',
	'interval:yearly' => 'Jährlich',
	'interval:reboot' => 'Bei Neustart',

/**
 * System settings
 */

	'installation:sitename' => "Der Name Deiner Community-Seite:",
	'installation:sitedescription' => "Eine kurze Beschreibung Deiner Seite (optional):",
	'installation:wwwroot' => "Die URL Deiner Community-Seite:",
	'installation:path' => "Der vollständige Pfad zum Elgg-Installationsverzeichnis auf Deinem Server:",
	'installation:dataroot' => "Der vollständige Pfad zum Elgg-Datenverzeichnis auf Deinem Server:",
	'installation:dataroot:warning' => "Du mußt dieses Verzeichnis selbst erzeugen. Es muß in einem anderen Verzeichnis als Dein Elgg-Installationsverzeichnis sein.",
	'installation:sitepermissions' => "Der standardmäßige Zugangslevel: ",
	'installation:language' => "Die Standardsprache Deiner Community-Seite: ",
	'installation:debug' => "Lege den Detailgrad / die Anzahl der Einträge der Informationen fest, die im Serverlog protokolliert werden sollen.",
	'installation:debug:label' => "Log-Level:",
	'installation:debug:none' => 'Debug-Modus deaktivieren (empfohlen)',
	'installation:debug:error' => 'Nur kritische Fehler protokollieren',
	'installation:debug:warning' => 'Kritische Fehler und Warnungen protokollieren',
	'installation:debug:notice' => 'Alle Fehler, Warnungen und Benachrichtigungen protokollieren',
	'installation:debug:info' => 'Alles protokollieren',

	// Walled Garden support
	'installation:registration:description' => 'Standardmäßig ist die Registrierung neuer Benutzeraccounts erlaubt. Schalte dies ab, wenn Du nicht willst, dass Besucher Deiner Community-Seite selbst Accounts registrieren dürfen.',
	'installation:registration:label' => 'Registrierung neuer Benutzeraccounts erlauben',
	'installation:walled_garden:description' => 'Aktiviere diese Option, um Nicht-Mitgliedern Deiner Community-Seite den Zugriff auf die Seite zu verwehren mit Ausnahme der Webseiten, die als "public" konfiguriert sind (beispielsweise die Login- und Registrierungsseiten).',
	'installation:walled_garden:label' => 'Zugriff auf angemeldete Benutzer beschränken',

	'installation:httpslogin' => "Aktivieren, um den Benutzern die Anmeldung via HTTPS zu ermöglichen. Dafür ist es notwendig, dass Dein Server das https-Protokoll unterstützt.",
	'installation:httpslogin:label' => "HTTPS-Anmeldungen zulassen",
	'installation:view' => "Gebe den Ansichtsmodus an, der für Deine Community-Seite verwendet werden soll. Wenn Du nicht sicher bist was Du eingeben sollst, lass das Textfeld leer oder verwende \"default\", um den Standardmodus zu verwenden:",

	'installation:siteemail' => "Email-Adresse Deiner Community-Seite (wird vom System verwendet, um Benachrichtigungen zu versenden)",

	'admin:site:access:warning' => "Dies ist der Zugangslevel, der Benutzern standardmäßig vorgeschlagen wird, wenn sie neue Inhalte erstellen. Eine Änderung hier verändert nicht den Zugangslevel der Inhalte selbst.",
	'installation:allow_user_default_access:description' => "Aktiviere diese Option, um Benutzern zu erlauben, selbst den Zugangslevel festzulegen, der standardmäßig für sie ausgewählt ist, wenn sie neue Inhalte erstellen. Die benutzerspezifische Einstellung für den vorausgewählten Zugangslevel setzt die seitenweite Einstellung für diese Benutzer außer Kraft.",
	'installation:allow_user_default_access:label' => "Benutzerspezifischen Standard-Zugangslevel erlauben",

	'installation:simplecache:description' => "Simple-Cache verbessert die Systemleistung durch Caching von statischen Seiteninhalten inklusive einiger CSS- und JavaScript-Dateien.",
	'installation:simplecache:label' => "Simple-Cache aktivieren (empfohlen)",

	'installation:minify:description' => "Der Simple-Cache kann die Performance auch zusätzlich noch durch Komprimierung der CSS- und JavaScript-Dateien verbessern. (Voraussetzung ist, das der Simple-Cache aktiviert ist.)",
	'installation:minify_js:label' => "JavaScript-Dateien komprimieren (empfohlen)",
	'installation:minify_css:label' => "CSS-Dateien komprimieren (empfohlen)",

	'installation:htaccess:needs_upgrade' => "Du mußt die .htaccess-Datei auf Deinem Server anpassen, damit der Pfad in den GET-Parameter __elgg_uri eingebunden wird (Du kannst die htaccess_dist-Datei als Vorlage für Deine .htacess-Datei dafür verwenden).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg war es nicht möglich, eine Verbindung zu sich selbst aufzubauen, um die Rewrite-Regeln zu testen. Stelle sicher, dass curl auf dem Server installiert ist und korrekt funktioniert. Es dürfen auch keine IP Adressen-Beschränkungen vorhanden sein, die möglicherweise Verbindungen auf localhost selbst unterbinden.",
	
	'installation:systemcache:description' => "Der Systemcache veringert die Ladezeit von Elgg, indem einige häufig notwendige Daten in Dateien vorgehalten werden.",
	'installation:systemcache:label' => "Systemcache aktivieren (empfohlen)",

	'admin:legend:caching' => 'Caching-Mechanismen',
	'admin:legend:content_access' => 'Zugangslevel für Inhalte',
	'admin:legend:site_access' => 'Zugangsbeschränkungen zur Community-Seite',
	'admin:legend:debug' => 'Debuggen und Protokollieren',

	'upgrading' => 'Aktualisieren...',
	'upgrade:db' => 'Deine Datenbank wurde aktualisiert.',
	'upgrade:core' => 'Deine Elgg-Installation wurde aktualisiert.',
	'upgrade:unlock' => 'Upgrade entsperren',
	'upgrade:unlock:confirm' => "Die Datenbank ist durch einen anderen Upgrade-Prozess gesperrt. Gleichzeitig ausgeführte Upgrade-Prozesse sind gefährlich. Du solltest nur dann weitermachen, wenn Du sicher bist, dass momentan wirklich kein anderer Upgrade-Prozess ausgeführt wird. Entsperren?",
	'upgrade:locked' => "Upgrade ist nicht möglich. Ein anderer Upgrade-Prozess wird momentan ausgeführt. Um die Upgrade-Sperre zu entfernen, öffne den Admin-Bereich Deiner Seite.",
	'upgrade:unlock:success' => "Die Upgrade-Sperre wurde entfernt.",
	'upgrade:unable_to_upgrade' => 'Die Aktualisierung ist fehlgeschlagen.',
	'upgrade:unable_to_upgrade_info' =>
		'Diese Installation kann nicht aktualisiert werden, da in den Views-Verzeichnissen der
		Elgg-Basisinstallation veraltete Views gefunden wurden. Diese Views werden nicht mehr
		unterstützt und müssen entfernt werden, damit Elgg funktionieren kann. Wenn Du keine
		Dateien modifiziert hast, die in der Standardinstallation von Elgg enthalten sind,
		kannst Du einfach das Views-Verzeichnis löschen und durch das View-Verzeichnis ersetzen,
		das im Paket der neuesten Version von Elgg enthalten ist und von <a href="http://elgg.org">elgg.org</a>
		heruntergeladen werden kann.<br /><br />

		Wenn Du genauere Installationsanweisungen benötigst, lese die <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Dokumentation über die Aktualisierung von Elgg</a>.  Falls Du Hilfe benötigst, stelle Deine Frage in den
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support-Foren</a>.',

	'update:twitter_api:deactivated' => 'Die Twitter API (bisher Twitter Service genannt) wurde während der Seitenaktualisierung deaktiviert. Bitte aktiviere die API manuell, falls sie benötigt wird.',
	'update:oauth_api:deactivated' => 'OAuth API (bisher OAuth Lib genannt) wurde während der Seitenaktualisierung deaktiviert. Bitte aktiviere die API manuell, falls sie benötigt wird.',
	'upgrade:site_secret_warning:moderate' => "Es ist emfehlenswert, den Geheimschlüssel Deiner Community-Seite neu erzeugen zu lassen, um die Sicherheit für Deine Seite zu verbessern. Siehe Konfigurieren &gt; Einstellungen &gt; Erweiterte Einstellungen",
	'upgrade:site_secret_warning:weak' => "Wir empfehlen nachdrücklich, den Geheimschlüssel Deiner Community-Seite neu erzeugen zu lassen, um die Sicherheit für Deine Seite zu verbessern. Siehe Konfigurieren &gt; Einstellungen &gt; Erweiterte Einstellungen",

	'deprecated:function' => '%s() wurde durch %s() als veraltet markiert.',

	'admin:pending_upgrades' => 'Es gibt ausstehende Aktualisierungen auf Deiner Community-Seite. Du solltest diese baldmöglichst durchführen.',
	'admin:view_upgrades' => 'Ausstehende Aktualisierungen anzeigen.',
 	'admin:upgrades' => 'Aktualisierungen',
	'item:object:elgg_upgrade' => 'Community-Seite-Aktualisierungen',
	'admin:upgrades:none' => 'Deine Installation ist aktuell!',

	'upgrade:item_count' => 'Es gibt <b>%s</b> Elemente, die aktualisiert werden müssen.',
	'upgrade:warning' => '<b>Warnung:</b> auf einer großen Community-Seite kann die Durchführung dieser Aktualisierung einige Zeit in Anspruch nehmen!',
	'upgrade:success_count' => 'Aktualisiert:',
	'upgrade:error_count' => 'Fehler:',
	'upgrade:river_update_failed' => 'Die Aktualisierung des River-Eintrags für das Element mit ID %s ist fehlgeschlagen.',
	'upgrade:timestamp_update_failed' => 'Die Aktualisierung der Zeitstempel für das Element mit ID %s ist fehlgeschlagen.',
	'upgrade:finished' => 'Die Aktualisierung ist abgeschlossen.',
	'upgrade:finished_with_errors' => '<p>Die Aktualisierung wurde beendet. Allerdings sind dabei Fehler aufgetreten. Lade die Seite erneut und versuche, die Aktualisierung nochmals durchzuführen.</p></p><br />Wenn dabei wieder Fehler auftreten, schaue in der Logdatei Deines Servers nach, ob es dort Einträge gibt, die eventuell weitere Informationen zur Ursache der Fehler liefern. Du kannst auch in der <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support-Gruppe</a> auf der Elgg-Community-Seite um Hilfe bei Deinem Problem bitten.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Aktualisierung der Kommentare',
	'upgrade:comment:create_failed' => 'Die Konvertierung des Kommentars mit ID %s in eine Entität ist fehlgeschlagen.',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Aktualisierung des Datenverzeichnisses',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Aktualisierung der Antworten in Diskussionsbeiträgen',
	'discussion:upgrade:replies:create_failed' => 'Die Konvertierung des Diskussions-Antwort-Eintrags mit ID %s in eine Entität ist fehlgeschlagen..',

/**
 * Welcome
 */

	'welcome' => "Willkommen",
	'welcome:user' => 'Willkommen %s',

/**
 * Emails
 */
		
	'email:from' => 'Von',
	'email:to' => 'An',
	'email:subject' => 'Betreff',
	'email:body' => 'Email-Text',
	
	'email:settings' => "Email",
	'email:address:label' => "Email-Adresse",

	'email:save:success' => "Die neue Email-Adresse wurde gespeichert. Eine Verifizierungs-Email wurde versandt.",
	'email:save:fail' => "Die neue Email-Adresse konnte nicht gespeichert werden.",

	'friend:newfriend:subject' => "Du bist nun mit %s befreundet!",
	'friend:newfriend:body' => "Du bist nun mit %s befreundet!

Klicke auf den folgenden Link um ihr/sein Profil zu besuchen:

%s

Du kannst auf diese Email NICHT antworten.",

	'email:changepassword:subject' => "Änderung des Passworts!",
	'email:changepassword:body' => "Hallo %s,

Dein Passwort wurde geändert.",

	'email:resetpassword:subject' => "Zurücksetzung des Passworts!",
	'email:resetpassword:body' => "Hallo %s,

Dein Passwort wurde zurückgesetzt. Dein neues Passwort ist: %s",

	'email:changereq:subject' => "Verifizierung der Änderung Deines Passworts.",
	'email:changereq:body' => "Hallo %s,

es wurde eine Änderung des Passworts Deines Accounts angefordert (von der IP-Adresse %s).

Falls Du die Änderung des Passworts angefordert hast, klicke bitte auf den folgenden Link, um dies zu bestätigen:

%s

Andernfalls ignoriere bitte diese Email.
",

/**
 * user default access
 */

	'default_access:settings' => "Standard-Zugangslevel für Deine Inhalte",
	'default_access:label' => "Standard-Zugangslevel",
	'user:default_access:success' => "Dein neuer Standard-Zugangslevel wurde gespeichert.",
	'user:default_access:failure' => "Dein neuer Standard-Zugangslevel konnte nicht gespeichert werden.",

/**
 * Comments
 */

	'comments:count' => "Kommentare von %s",
	'item:object:comment' => 'Kommentare',

	'river:comment:object:default' => '%s kommentierte %s',

	'generic_comments:add' => "Kommentieren",
	'generic_comments:edit' => "Kommentar bearbeiten",
	'generic_comments:post' => "Kommentieren",
	'generic_comments:text' => "Kommentar",
	'generic_comments:latest' => "Neueste Kommentare",
	'generic_comment:posted' => "Dein Kommentar wurde gespeichert.",
	'generic_comment:updated' => "Der Kommenatar wurde aktualisiert.",
	'generic_comment:deleted' => "Der Kommentar wurde gelöscht.",
	'generic_comment:blank' => "Entschuldigung, aber Du mußt zuerst etwas schreiben bevor wir Deinen Kommentar abspeichern können.",
	'generic_comment:notfound' => "Entschuldigung, aber wird konnten den gesuchten Eintrag nicht finden.",
	'generic_comment:notdeleted' => "Entschuldigung, dieser Kommentar konnte nicht gelöscht werden.",
	'generic_comment:failure' => "Beim Speichern Deines Kommentars ist ein Fehler aufgetreten.",
	'generic_comment:none' => 'Keine Kommentare.',
	'generic_comment:title' => 'Kommentar von %s',
	'generic_comment:on' => '%s zu %s',
	'generic_comments:latest:posted' => 'schrieb einen',

	'generic_comment:email:subject' => 'Du hast einen neuen Kommentar erhalten!',
	'generic_comment:email:body' => "Zu Deinem Eintrag \"%s\" wurde von %s ein neuer Kommentar geschrieben. Der Kommentar lautet:


%s


Um zu antworten oder Deinen ursprünglichen Eintrag aufzurufen, folge diesem Link:

%s

Um das Profil von %s aufzurufen, folge diesem Link:

%s

Du kannst auf diese Email NICHT antworten.",

/**
 * Entities
 */
	
	'byline' => 'Von %s',
	'entity:default:strapline' => '%s erzeugt von %s',
	'entity:default:missingsupport:popup' => 'Diese Entität kann nicht richtig angezeigt werden. Dies kann daran liegen, dass dafür ein Plugin benötigt wird, das nicht mehr installiert ist.',

	'entity:delete:success' => 'Die Entität %s wurde gelöscht',
	'entity:delete:fail' => 'Die Entität %s konnte nicht gelöscht werden',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Der Form fehlt der __token und/oder __ts Eintrag',
	'actiongatekeeper:tokeninvalid' => "Die Gültigkeit des Authentifizierungs-Token für die gerade betrachtete Seite ist abgelaufen. Bitte lade die Seite neu.",
	'actiongatekeeper:timeerror' => 'Das Authentifizierungs-Token für die die Seite, die Du betrachtet hast, ist abgelaufen. Bitte lade die Seite neu und versuche es noch einmal.',
	'actiongatekeeper:pluginprevents' => 'Durch eine der auf dieser Seite installierten Erweiterungen wurde die Verarbeitung der im Formular gemachten Eingaben blockiert.',
	'actiongatekeeper:uploadexceeded' => 'Die Dateigröße der hochgeladenen Datei(en) übersteigt das Limit, das vom Administrator dieser Seite eingestellt worden ist.',
	'actiongatekeeper:crosssitelogin' => "Entschuldigung, die Anmeldung zu dieser Webseite von einer anderen Webadresse ist nicht erlaubt. Bitte versuche es noch einmal von der richtigen Webadresse aus.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'und, der, die, das, den, dem, des, ein, eine, eines, einen, einem, einer, dann, aber, sie, er, es, ihr, ihm, ihres, ihrer, ihrem, ihren, sein, seiner, seines, seinem, seinen, nicht, also, über, jetzt, deshalb, daher, darum, demzufolge, deswegen, folglich, somit, allerdings, immer, noch, ebenso, andernfalls, umgekehrt, eher, infolgedessen, darüberhinaus, darüber, hinaus, trotzdem, dennoch, anstatt, stattdessen, inzwischen, mittlerweile, unterdessen, dementsprechend, dies, dieser, dieses, diesem, diesen, was, wem, wessen, deren, dessen',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Verbindung mit %s fehlgeschlagen. Eventuell wird das Speichern neuer Inhalte fehlschlagen. Bitte lade die Seite neu.',
	'js:security:token_refreshed' => 'Verbindung mit %s wiederhergestellt!',
	'js:lightbox:current' => "Bild %s von %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Community-Seite erstellt mit Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abchasisch",
	"af" => "Afrikaans",
	"am" => "Amharisch",
	"ar" => "Arabisch",
	"as" => "Assamesisch",
	"ay" => "Aymara",
	"az" => "Aserbaidschanisch",
	"ba" => "Baschkirisch",
	"be" => "Weissrussisch",
	"bg" => "Bulgarisch",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengalisch",
	"bo" => "Tibetisch",
	"br" => "Bretonisch",
	"ca" => "Katalanisch",
	"cmn" => "Chinesisch (Mandarin)", // ISO 639-3
	"co" => "Korsisch",
	"cs" => "Tschechisch",
	"cy" => "Walisisch",
	"da" => "Dänisch",
	"de" => "Deutsch",
	"dz" => "hutanisch",
	"el" => "Griechisch",
	"en" => "Englisch",
	"eo" => "Esperanto",
	"es" => "Spanisch",
	"et" => "Estnisch",
	"eu" => "Baskisch",
	"fa" => "Persisch",
	"fi" => "Finnisch",
	"fj" => "Fidschi",
	"fo" => "Färöisch",
	"fr" => "Französisch",
	"fy" => "Friesisch",
	"ga" => "Irisch",
	"gd" => "Schottisch / Gälisch",
	"gl" => "Galizisch",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebräisch",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Kroatisch",
	"hu" => "Ungarisch",
	"hy" => "Armenisch",
	"ia" => "Interlingua",
	"id" => "Indonesisch",
	"ie" => "Interlingue",
	"ik" => "Inupiaq",
	//"in" => "Indonesisch",
	"is" => "Isländisch",
	"it" => "Italienisch",
	"iu" => "Inuit",
	"iw" => "Hebräisch (obsolet)",
	"ja" => "Japanisch",
	"ji" => "Yiddish (obsolet)",
	"jw" => "Javanisch",
	"ka" => "Georgisch",
	"kk" => "Kasachisch",
	"kl" => "Grönländisch",
	"km" => "Kambodschanisch",
	"kn" => "Kanadisch",
	"ko" => "Koreanisch",
	"ks" => "Kashmiri",
	"ku" => "Kurdisch",
	"ky" => "Kirgisisch",
	"la" => "Latein",
	"ln" => "Lingala",
	"lo" => "Laotisch",
	"lt" => "Litauisch",
	"lv" => "Lettisch",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Mazedonisch",
	"ml" => "Malayalam",
	"mn" => "Mongolisch",
	"mo" => "Moldawisch",
	"mr" => "Marathi",
	"ms" => "Malaiisch",
	"mt" => "Maltesisch",
	"my" => "Burmanisch",
	"na" => "Nauruisch",
	"ne" => "Nepalesisch",
	"nl" => "Niederländisch",
	"no" => "Norwegisch",
	"oc" => "Ossetisch",
	"om" => "Oromo",
	"or" => "Oriya",
	"pa" => "Panjabi",
	"pl" => "Polnisch",
	"ps" => "Paschtunisch",
	"pt" => "Portugiesisch",
	"pt_br" => 'Portugiesisch (Brasilianisch)',
	"qu" => "Quechua",
	"rm" => "Rätoromanisch",
	"rn" => "Kirundi",
	"ro" => "Rumänisch",
	"ru" => "Russisch",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbokroatisch",
	"si" => "Singhalesisch",
	"sk" => "Slowakisch",
	"sl" => "Slowenisch",
	"sm" => "Samoisch",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanisch",
	"sr" => "Serbisch",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sudanesisch",
	"sv" => "Schwedisch",
	"sw" => "Swahili",
	"ta" => "Tamilisch",
	"te" => "Tegulu",
	"tg" => "Tadschikisch",
	"th" => "Thailändisch",
	"ti" => "Tigrinya",
	"tk" => "Turkmenisch",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tongaisch",
	"tr" => "Türkisch",
	"ts" => "Tsonga",
	"tt" => "Tatarisch",
	"tw" => "Twi",
	"ug" => "Uigurisch",
	"uk" => "Ukrainisch",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamesisch",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinesisch",
	"zu" => "Zulu",
);
