<?php

return array(
/**
 * Sites
 */

	'item:site:site' => 'Seite',
	'collection:site:site' => 'Seiten',
	'index:content' => '<p>Willkommen auf Deiner Elgg-Community-Seite.</p><p><strong>Tipp:</strong> Viele Seiten verwenden das <code>activity</code>-Plugin, um die neuesten Aktivitäten in der Community auf dieser Seite anzuzeigen.</p>',

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
	'session_expired' => "Deine Session ist abgelaufen. Zum erneuten Anmelden bitte die Seite <a href='javascript:location.reload(true)'>neu laden</a>.",
	'session_changed_user' => "Du wurdest als ein anderer Benutzer eingeloggt. Du solltest die Seite <a href='javascript:location.reload(true)'>neu laden</a>.",

	'loggedinrequired' => "Du mußt angemeldet sein, um diese Seite aufrufen zu können.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "Du mußt ein Administrator sein, um diese Seite aufrufen zu können.",
	'membershiprequired' => "Du mußt Mitglied dieser Gruppe sein, um diese Seite aufrufen zu können.",
	'limited_access' => "Du hast nicht die notwendige Berechtigung, um auf die angeforderte Seite zuzugreifen.",
	'invalid_request_signature' => "Die URL der Seite, auf die Du zugreifen möchtest, ist entweder ungültig oder Deine Session ist abgelaufen.",

/**
 * Errors
 */

	'exception:title' => "Schwerwiegender Fehler.",
	'exception:contact_admin' => 'Es ist ein nicht behebbarer Fehler aufgetreten. Der Fehler wurde protokolliert. Wenn Du der Seitenadministrator bist, prüfe bitte die Konfiguration in elgg-config/settings.php. Andernfalls leite bitte folgende Informationen an den Seitenadministrator weiter:',

	'actionundefined' => "Die angeforderte Aktion (%s) ist im System nicht definiert.",
	'actionnotfound' => "Die Datei für die Ausführung der Aktion %s wurde nicht gefunden.",
	'actionloggedout' => "Entschuldigung, Du kannst diese Aktion nicht ausführen während Du nicht angemeldet bist.",
	'actionunauthorized' => 'Du bist nicht authorisiert, diese Aktion auszuführen',

	'ajax:error' => 'Bei der Durchführung des AJAX-Aufrufs ist ein Fehler aufgetreten. Vielleicht ist die Verbindung zum Server verloren gegangen.',
	'ajax:not_is_xhr' => 'Ein direkter Aufruf von Ajax-Views ist nicht erlaubt.',

	'PluginException:MisconfiguredPlugin' => "%s (GUID: %s) ist ein falsch konfiguriertes Plugin. Es wurde deaktiviert. Im Elgg-Wiki sind einige mögliche Ursachen für das Problem beschrieben (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (GUID: %s) kann nicht gestartet werden und wurde deaktiviert. Ursache: %s.',
	'PluginException:InvalidID' => "%s ist eine ungültig Plugin-ID.",
	'PluginException:InvalidPath' => "%s ist ungültiger Plugin-Dateipfad.",
	'PluginException:InvalidManifest' => 'Ungültige Manifest-Datei für das Plugin %s.',
	'PluginException:InvalidPlugin' => '%s ist kein zulässiges Plugin.',
	'PluginException:InvalidPlugin:Details' => '%s ist kein zulässiges Plugin: %s.',
	'PluginException:NullInstantiated' => 'Ein ElggPlugin-Objekt kann nicht mit NULL instanziiert werden. Es muss eine GUID, eine Plugin-ID oder ein vollständiger Dateipfad übergeben werden.',
	'ElggPlugin:MissingID' => 'Fehlende Plugin-ID (GUID: %s).',
	'ElggPlugin:NoPluginPackagePackage' => 'Das zugehörige Plugin-Paket für die Plugin-ID %s (GUID: %s) fehlt.',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Die benötigte Datei "%s" kann nicht gefunden werden.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Das Plugin-Verzeichnis dieses Plugins muss zu "%s" umbenannt werden, um mit der ID, die in der Manifest-Datei des Plugins angegeben ist, überein zu stimmen.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Die Manifest-Datei enthält den ungültigen Requires-Typ "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Die Manifest-Datei enthält den ungültigen Provides-Typ "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Nicht auflösbare %s-Abhängigkeit "%s" im Plugin %s. Plugins können nicht mit etwas in Konflikt stehen oder etwas voraussetzen, das sie selbst bereitstellen!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Plugin-Konflikt mit Plugin: %s.',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Die Plugin-Datei "elgg-plugin.php" ist vorhanden aber sie kann nicht gelesen werden.',
	'ElggPlugin:Error' => 'Fehler in Plugin.',
	'ElggPlugin:Error:ID' => 'Fehler in Plugin "%s".',
	'ElggPlugin:Error:Path' => 'Fehler in Plugin-Dateipfad "%s".',
	'ElggPlugin:Error:Unknown' => 'Unbekannter Fehler in Plugin.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Einbindung von %s für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Einbindung von %s für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Öffnen des Views-Verzeichnis für Plugin %s (GUID: %s) an %s gescheitert.',
	'ElggPlugin:Exception:NoID' => 'Keine ID für Plugin-GUID %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Das Laden des Pakets ist fehlgeschlagen.',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin-Manifest fehlt oder ist fehlerhaft.',
	'PluginException:NoPluginName' => "Der Name des Plugins kann nicht ermittelt werden.",
	'PluginException:ParserError' => 'Das Parsen der Manifest-Datei mit API-Version %s des Plugins %s ist fehlgeschlagen.',
	'PluginException:NoAvailableParser' => 'Es steht kein Parser für die Manifest-API-Version %s des Plugins %s zur Verfügung.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Fehlendes Required-'%s'-Attribut in der Manifest-Datei des Plugins %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s ist kein gültiges Plugin und wurde deshalb deaktiviert.',
	'ElggPlugin:activate:BadConfigFormat' => 'Die Plugin-Datei "elgg-plugin.php" hat keine serialisierbares Array zurückgegeben.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Die Plugin-Datei "elgg-plugin.php" hat eine Ausgabe erzeugt.',

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

	'ElggPlugin:Dependencies:ActiveDependent' => 'Es sind aktive Plugins vorhanden, die die Verfügbarkeit von %s voraussetzen. Bevor Du dieses Plugin deaktivieren kannst, mußt Du erst folgende Plugins deaktivieren: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menueinträge gefunden, bei denen der übergeordnete und auf sie verweisende Menueintrag nicht vorhanden ist.',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menueintrag [%s] gefunden, bei dem der übergeordnete Menueintrag [%s] fehlt.',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Doppelte Registrierung für den Menueintrag [%s] gefunden.',

	'RegistrationException:EmptyPassword' => 'Die Passwort-Felder dürfen nicht leer sein.',
	'RegistrationException:PasswordMismatch' => 'Die Passwörter müssen übereinstimmen.',
	'LoginException:BannedUser' => 'Dein Benutzeraccount auf dieser Seite wurde gesperrt. Du kannst Dich daher nicht anmelden.',
	'LoginException:UsernameFailure' => 'Die Anmeldung ist fehlgeschlagen. Bitte prüfe, ob Benutzername/Email-Adresse und Passwort richtig sind.',
	'LoginException:PasswordFailure' => 'Die Anmeldung ist fehlgeschlagen. Bitte prüfe, ob Benutzername/Email-Adresse und Passwort richtig sind.',
	'LoginException:AccountLocked' => 'Dein Benutzeraccount wurde aufgrund zu vieler fehlgeschlagener Anmeldeversuche gesperrt.',
	'LoginException:ChangePasswordFailure' => 'Die Überprüfung des derzeitigen Passworts ist fehlgeschlagen.',
	'LoginException:Unknown' => 'Die Anmeldung ist aus unbekannter Ursache fehlgeschlagen.',

	'UserFetchFailureException' => 'Die Überprüfung der Zugriffsrechte des Benutzers mit der Benutzer-GUID [%s] ist fehlgeschlagen, da dieser Benutzer nicht existiert.',

	'PageNotFoundException' => 'Die Seite, auf die Du zugreifen möchtest, existiert entweder nicht oder Du hast nicht die notwendige Berechtigung, um sie anzuzeigen.',
	'EntityNotFoundException' => 'Der Inhalt, auf den Du zugreifen möchtest, wurde entweder entfernt oder Du hast nicht die notwendige Berechtigung, um ihn anzuzeigen.',
	'EntityPermissionsException' => 'Du hast keine ausreichende Berechtigung für diese Aktion.',
	'GatekeeperException' => 'Du hast nicht die notwendige Berechtigung, um auf die gewünschte Seite zuzugreifen.',
	'BadRequestException' => 'Fehlerhafte Anfrage.',
	'ValidationException' => 'Die eingegebenen Daten erfüllen nicht die Anforderungen. Bitte prüfe Deine Eingaben.',
	'LogicException:InterfaceNotImplemented' => '%s muss %s implementieren.',

	'deprecatedfunction' => 'Warnung: Dieser Code verwendet die veraltete Funktion \'%s\' und ist mit dieser Version von Elgg nicht kompatibel.',

	'pageownerunavailable' => 'Warnung: Der Seiten-Eigentümer %d ist nicht erreichbar!',
	'viewfailure' => 'In der View %s ist ein interner Fehler aufgetreten.',
	'view:missing_param' => "Der notwendige Parameter '%s' fehlt in der View %s.",
	'changebookmark' => 'Bitte ändere Dein Lesezeichen für diese Seite.',
	'noaccess' => 'Der Seiteninhalt, den Du aufgerufen hast, wurde entweder gelöscht oder Du hast nicht die notwendige Berechtigung, um darauf zuzugreifen.',
	'error:missing_data' => 'Bei Deiner Anfrage fehlten einige notwendige Daten.',
	'save:fail' => 'Beim Speichern Deiner Daten ist ein Fehler aufgetreten.',
	'save:success' => 'Deine Daten wurden gespeichert.',

	'forward:error' => 'Entschuldigung. Bei der Weiterleitung zu einer anderen Seite ist ein Fehler aufgetreten.',

	'error:default:title' => 'Hoppla...',
	'error:default:content' => 'Hoppla...irgendetwas ist schiefgegangen.',
	'error:400:title' => 'Fehlerhafte Anfrage.',
	'error:400:content' => 'Entschuldigung. Die Anfrage ist ungültig oder unvollständig.',
	'error:403:title' => 'Unzureichende Zugriffsberechtigung.',
	'error:403:content' => 'Entschuldigung. Du hast keine Zugriffsberechtigung für die angeforderte Seite.',
	'error:404:title' => 'Seite nicht gefunden.',
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
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Gesperrt',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Beschreibung',
	'table_columns:fromView:link' => 'Name/Titel',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Eintrag',
	'table_columns:fromView:language' => 'Sprache',
	'table_columns:fromView:owner' => 'Besitzer',
	'table_columns:fromView:time_created' => 'Erstellungszeitpunkt',
	'table_columns:fromView:time_updated' => 'Aktualisierungszeitpunkt',
	'table_columns:fromView:user' => 'Benutzer',

	'table_columns:fromProperty:description' => 'Beschreibung',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Typ',
	'table_columns:fromProperty:username' => 'Benutzername',

	'table_columns:fromMethod:getSubtype' => 'Subtyp',
	'table_columns:fromMethod:getDisplayName' => 'Name/Titel',
	'table_columns:fromMethod:getMimeType' => 'MIME-Typ',
	'table_columns:fromMethod:getSimpleType' => 'Typ',

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
	'autogen_password_option' => "Automatisch ein sicheres Password erzeugen?",

/**
 * Access
 */

	'access:label:private' => "Privat",
	'access:label:logged_in' => "Angemeldete Benutzer",
	'access:label:public' => "Öffentlich",
	'access:label:logged_out' => "Nicht-angemeldete Benutzer",
	'access:label:friends' => "Freunde",
	'access' => "Zugangslevel",
	'access:overridenotice' => "Anmerkung: aufgrund der Einstellungen dieser Gruppe wird dieser Inhalt nur für Gruppenmitglieder sichtbar sein.",
	'access:limited:label' => "Beschränkt",
	'access:help' => "Der Zugangslevel",
	'access:read' => "Zugangslevel",
	'access:write' => "Schreibberechtigung",
	'access:admin_only' => "nur Administratoren",
	'access:missing_name' => "Name des Zugangslevels ist nicht verfügbar.",
	'access:comments:change' => "Dieser Diskussionsbeitrag ist derzeit aufgrund seines Zugangslevels nur für einen eingeschränkten Kreis von Mitgliedern sichtbar. Bitte denke daran, dass durch eine Änderung des Zugangslevels möglicherweise weitere Personen diesen Diskussionsbeitrag sehen können.",

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
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "Das Widget wurde hinzugefügt.",
	'widgets:save:failure' => "Das Widget konnte nicht hinzugefügt werden. Bitte versuche es noch einmal.",
	'widgets:add:success' => "Das Widget wurde hinzugefügt.",
	'widgets:add:failure' => "Das Hinzufügen des Widgets ist fehlgeschlagen.",
	'widgets:move:failure' => "Die Position des Widgets auf Deiner Seite konnte nicht gespeichert werden.",
	'widgets:remove:failure' => "Das Widget konnte nicht entfernt werden.",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Gruppe",
	'item:group' => "Gruppen",
	'collection:group' => 'Gruppen',
	'item:group:group' => "Gruppe",
	'collection:group:group' => 'Gruppen',
	'groups:tool_gatekeeper' => "Die angeforderte Funktionalität ist derzeit in dieser Gruppe nicht aktiviert.",

/**
 * Users
 */

	'user' => "Benutzer",
	'item:user' => "Mitglieder",
	'collection:user' => 'Mitglieder',
	'item:user:user' => 'Benutzer',
	'collection:user:user' => 'Mitglieder',

	'friends' => "Freunde",
	'collection:friends' => '%s von Freunden',

	'avatar' => 'Profilbild',
	'avatar:noaccess' => "Du hast keine Berechtigung, das Profilbild dieses Benutzers zu bearbeiten.",
	'avatar:create' => 'Profilbild erstellen',
	'avatar:edit' => 'Profilbild bearbeiten',
	'avatar:upload' => 'Profilbild hochladen',
	'avatar:current' => 'Derzeitiges Profilbild',
	'avatar:remove' => 'Löschen Deines Profilbildes und Wiederherstellen des Standardbildes',
	'avatar:crop:title' => 'Tool zum Zuschneiden des Profilbildes',
	'avatar:upload:instructions' => "Das Profilbild ist das Bild, das auf Deiner Profilseite und bei all Deinen Beiträgen auf der Community-Seite angezeigt wird. Du kannst es so oft ändern wie Du willst (mögliche Dateiformate: GIF, JPG oder PNG).",
	'avatar:create:instructions' => 'Um Dein Profilbild nach Deinen Wünschen zuzuschneiden (optional), klicke es an und markiere einen quadratischen Ausschnitt während Du die Maustaste gedrückt hältst. Du kannst den Ausschnitt anschließend auch verschieben und in der Größe anpassen. Eine Vorschau Deines Profilbildes wird in der Box rechts daneben dargestellt. Wenn Du zufrieden mit dem Zuschneiden bist, klicke auf \'Profilbild erstellen\', um das zugeschnittene Profilbild zu übernehmen. Das zugeschnittene Bild wird dann auf der ganzen Community-Seite als Dein Profilbild verwendet.',
	'avatar:upload:success' => 'Dein Profilbild wurde hochgeladen.',
	'avatar:upload:fail' => 'Das Hochladen des Profilbildes ist fehlgeschlagen.',
	'avatar:resize:fail' => 'Die Größenanpassung des Profilbildes ist fehlgeschlagen.',
	'avatar:crop:success' => 'Das Zuschneiden des Profilbildes war erfolgreich.',
	'avatar:crop:fail' => 'Das Zuschneiden des Profilbildes ist fehlgeschlagen.',
	'avatar:remove:success' => 'Das Profilbild wurde gelöscht.',
	'avatar:remove:fail' => 'Das Löschen des Profilbildes ist fehlgeschlagen.',
	
	'action:user:validate:already' => "Der Account von %s ist bereits validiert.",
	'action:user:validate:success' => "Der Account von %s wurde erfolgreich validiert.",
	'action:user:validate:error' => "Bei der Validierung des Accounts von %s ist ein Fehler aufgetreten.",

/**
 * Feeds
 */
	'feed:rss' => 'RSS-Feed für diese Seite',
	'feed:rss:title' => 'RSS-Feed für diese Seite',
/**
 * Links
 */
	'link:view' => 'Link aufrufen',
	'link:view:all' => 'Alle ansehen',


/**
 * River
 */
	'river' => "River",
	'river:user:friend' => "%s ist nun mit %s befreundet",
	'river:update:user:avatar' => '%s hat ein neues Profilbild hochgeladen',
	'river:noaccess' => 'Du hast keine Berechtigung um diesen Eintrag anzuzeigen.',
	'river:posted:generic' => '%s schrieb',
	'riveritem:single:user' => 'ein Mitglied',
	'riveritem:plural:user' => 'einige Mitglieder',
	'river:ingroup' => 'in der Gruppe %s',
	'river:none' => 'Keine Aktivität',
	'river:update' => 'Aktualisierung für %s',
	'river:delete' => 'Entferne diesen Aktivitäts-Eintrag',
	'river:delete:success' => 'Der Aktivitäts-Eintrag wurde gelöscht.',
	'river:delete:fail' => 'Das Löschen des Aktivitäts-Eintrags ist fehlgeschlagen.',
	'river:delete:lack_permission' => 'Du hast keine ausreichende Berechtigung, um diesen Aktivitäts-Eintrag zu löschen.',
	'river:can_delete:invaliduser' => 'Ein Aufruf der canDelete()-Funktion für den Benutzer mit der Benutzer-GUID [%s] ist nicht möglich, da dieser Benutzer nicht existiert.',
	'river:subject:invalid_subject' => 'Ungültiger Benutzer',
	'activity:owner' => 'Aktivitäten anzeigen',

	

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

	'registration:noname' => 'Die Eingabe eines Namens ist notwendig.',
	'registration:notemail' => 'Die von Dir angegebene Email-Adresse scheint keine gültige Email-Adresse zu sein.',
	'registration:userexists' => 'Dieser Benutzername ist schon vergeben.',
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
	'user:username:success' => "Die Änderung Deines Benutzernames wurde gespeichert.",
	'user:username:fail' => "Die Änderung Deines Benutzernamens ist fehlgeschlagen.",

	'user:set:password' => "Account-Passwort ändern",
	'user:current_password:label' => 'Dein derzeitiges Passwort',
	'user:password:label' => "Neues Passwort",
	'user:password2:label' => "Neues Passwort noch einmal eingeben",
	'user:password:success' => "Das Passwort wurde geändert.",
	'user:password:fail' => "Die Änderung Deines Passworts ist fehlgeschlagen.",
	'user:password:fail:notsame' => "Die zwei eingegebenen Passwörter stimmen nicht überein!",
	'user:password:fail:tooshort' => "Das eingegebene Passwort ist zu kurz!",
	'user:password:fail:incorrect_current_password' => 'Deine Eingabe stimmt nicht mit Deinem derzeitigen Passwort überein.',
	'user:changepassword:unknown_user' => 'Unbekannter Benutzername.',
	'user:changepassword:change_password_confirm' => 'Dein Passwort wird nun geändert.',

	'user:set:language' => "Sprache der Community-Seite",
	'user:language:label' => "Deine Sprache",
	'user:language:success' => "Deine Spracheinstellung wurde aktualisiert.",
	'user:language:fail' => "Die Änderung Deiner Spracheinstellung konnte nicht gespeichert werden.",

	'user:username:notfound' => 'Benutzername %s unbekannt.',
	'user:username:help' => 'Achtung: beachte bitte, dass sich durch die Änderung Deines Benutzernamens alle dynamischen URLs, die Deinen Benutzernamen enthalten, ebenfalls ändern.',

	'user:password:lost' => 'Neues Passwort',
	'user:password:hash_missing' => 'Wir müssen Dich leider darum bitten, Dein Passwort zurückzusetzen. Durch ein Update der Community-Seite hat sich der Schutz der Passwörter verbessert. Es war aber im Zuge dieses Updates bedauerlicherweise nicht möglich, die Passwörter aller Accounts automatisch in das neue System zu übernehmen.',
	'user:password:changereq:success' => 'Die Anforderung eines neuen Passworts war erfolgreich. Eine Email mit dem neuen Passwort wurde gesendet.',
	'user:password:changereq:fail' => 'Die Anforderung eines neuen Passworts ist fehlgeschlagen.',

	'user:password:text' => 'Um ein neues Passwort anzufordern, gebe im folgenden Textfeld Deinen Benutzernamen oder Deine Email-Adresse ein. Wir werden Dir dann eine Email zur Bestätigung der Anfrage zusenden. Folge dem Link in dieser Email, um Deine Passwort-Anfrage zu bestätigen. Dann wird Dir ein neues Passwort zugesandt.',

	'user:persistent' => 'Merken',

	'walled_garden:home' => 'Startseite',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrieren',
	'menu:page:header:configure' => 'Konfigurieren',
	'menu:page:header:develop' => 'Entwickeln',
	'menu:page:header:information' => 'Informationen',
	'menu:page:header:default' => 'Anderes',

	'admin:view_site' => 'Seite anzeigen',
	'admin:loggedin' => 'Angemeldet als %s',
	'admin:menu' => 'Menü',

	'admin:configuration:success' => "Deine Einstellungen wurden gespeichert.",
	'admin:configuration:fail' => "Deine Einstellungen konnten nicht gespeichert werden.",
	'admin:configuration:dataroot:relative_path' => 'Der Pfad "%s" ist als Pfad zum Datenverzeichnis nicht zulässig, da es kein absoluter Pfad ist.',
	'admin:configuration:default_limit' => 'Die Anzahl der Einträge pro Seite muss mindestens 1 sein.',

	'admin:unknown_section' => 'Unbekannter Adminbereich.',

	'admin' => "Admin",
	'admin:description' => "Der Admin-Bereich ermöglicht es Dir, Systemeinstellungen vorzunehmen. Du hast Zugriff beispielsweise auf die Benutzerverwaltung und die Konfiguration von Plugins. Bitte wähle eine der unten angebotenen Optionen.",

	'admin:statistics' => 'Statistiken',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Zuletzt ausgeführte Cron-Jobs',
	'admin:cron:period' => 'Cron-Zeitintervall',
	'admin:cron:friendly' => 'Zuletzt abgeschlossen',
	'admin:cron:date' => 'Datum und Zeit',
	'admin:cron:msg' => 'Statusausgaben',
	'admin:cron:started' => 'Cronjobs für "%s" gestarted am %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
	'admin:cron:complete' => 'Cronjobs für "%s" abgeschlossen am %s',

	'admin:appearance' => 'Design',
	'admin:administer_utilities' => 'Werkzeuge',
	'admin:develop_utilities' => 'Werkzeuge',
	'admin:configure_utilities' => 'Werkzeuge',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Benutzer",
	'admin:users:online' => 'Online',
	'admin:users:newest' => 'Neueste',
	'admin:users:admins' => 'Administratoren',
	'admin:users:add' => 'Benutzer hinzufügen',
	'admin:users:description' => "In diesem Admin-Bereich kannst Du Einstellugen für Benutzeraccounts konfigurieren. Wähle eine der unten gezeigten Optionen.",
	'admin:users:adduser:label' => "Hier klicken um einen neuen Benutzeraccount zu erstellen...",
	'admin:users:opt:linktext' => "Benutzeraccount konfigurieren...",
	'admin:users:opt:description' => "Benutzeraccounts und Accountinformationen bearbeiten.",
	'admin:users:find' => 'Finde',
	'admin:users:unvalidated' => 'Nicht validiert',
	'admin:users:unvalidated:no_results' => 'Es gibt derzeit keine Benutzeraccounts, die noch nicht validiert sind.',
	'admin:users:unvalidated:registered' => 'Registriert: %s',
	
	'admin:configure_utilities:maintenance' => 'Wartungs-Modus',
	'admin:upgrades' => 'Aktualisierungen',
	'admin:upgrades:finished' => 'Completed',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Start time',
	'admin:upgrades:db:end_time' => 'End time',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Pending upgrades',
	'admin:upgrades:menu:completed' => 'Completed upgrades',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Run this upgrade',
	'admin:upgrades:run' => 'Aktualisierungen jetzt ausführen',
	'admin:upgrades:error:invalid_upgrade' => 'Entität %s existiert nicht oder sie ist keine gültige ElggUpgrade-Instanz.',
	'admin:upgrades:error:invalid_batch' => 'Der Batch-Prozeß für die Aktualisierung %s (%s) konnte nicht instanziiert werden.',
	'admin:upgrades:completed' => 'Aktualisierung "%s" abgeschlossen um %s',
	'admin:upgrades:completed:errors' => 'Aktualisierung "%s" abgeschlossen um %s. Es traten aber %s Fehler auf.',
	'admin:upgrades:failed' => 'Aktualisierung "%s" fehlgeschlagen.',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'Einstellungen',
	'admin:settings:basic' => 'Grundeinstellungen',
	'admin:settings:advanced' => 'Erweiterte Einstellungen',
	'admin:site:description' => "Hier kannst Du einige globale Einstellungen für Deine Community-Seite vornehmen.",
	'admin:site:opt:linktext' => "Community-Seite konfigurieren...",
	'admin:settings:in_settings_file' => 'Diese Einstellung kann in elgg-config/settings.php angepasst werden.',

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
	'admin:widget:banned_users:help' => 'Liste gesperrter Benutzer.',
	'admin:widget:content_stats' => 'Inhalts-Statistiken',
	'admin:widget:content_stats:help' => 'Auflistung der Inhalte, die von Benutzern erzeugt wurden.',
	'admin:widget:cron_status' => 'Cron-Status',
	'admin:widget:cron_status:help' => 'Anzeige der Zeitpunkte, zu denen die Cronjobs zuletzt ausgeführt wurden.',
	'admin:statistics:numentities' => 'Inhalts-Statistik',
	'admin:statistics:numentities:type' => 'Inhaltstyp',
	'admin:statistics:numentities:number' => 'Anzahl',
	'admin:statistics:numentities:searchable' => 'Durchsuchbare Entitäten',
	'admin:statistics:numentities:other' => 'Andere Entitäten',

	'admin:widget:admin_welcome' => 'Willkommen',
	'admin:widget:admin_welcome:help' => "Eine kurze Einführung in den Admin-Bereich von Elgg.",
	'admin:widget:admin_welcome:intro' =>
'Willkommen auf Deiner Elgg-Seite! Du siehst gerade das Administrator-Dashboard. Es ist hilfreich, um auf Deiner Seite den Überblick zu behalten.',

	'admin:widget:admin_welcome:admin_overview' =>
"Der Admin-Bereich ist in Unterseiten aufgeteilt, die über das Menu auf der rechten Seite aufgerufen werden können. Der Admin-Bereich ist
 in vier Abschnitte aufgeteilt:
	<dl>
		<dt>Administrieren</dt><dd>Alltägliche Aufgaben wie die Benutzerverwaltung, Kontrolle gemeldeter Inhalte oder das Aktivieren eines Plugins.</dd>
		<dt>Konfigurieren</dt><dd>Gelegentlich notwendige Aufgaben wie die Konfiguration des Namens der Seite oder die Änderung von Plugineinstellungen.</dd>
		<dt>Information</dt><dd>Informationen über Deine Seite wie beispielsweise Inhalts-Statistiken.</dd>
		<dt>Entwickeln</dt><dd>Für Entwickler, die ein Plugin oder ein Theme testen wollen. (Ein Entwickler-Plugin ist dafür notwendig.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Wirf auch einen Blick auf die zusätzlichen Resourcen, die Du über die Links in der Fußzeile des Admin-Bereichs aufrufen kannst. Und vielen Dank, dass Du Elgg verwendest!',

	'admin:widget:control_panel' => 'Seiten-Administration',
	'admin:widget:control_panel:help' => "Schnellzugriff auf einige allgemeine administrative Funktionen.",

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

	'admin:notices:delete_all' => 'Alle %s Admin-Benachrichtigungen schließen',
	'admin:notices:could_not_delete' => 'Das Schließen der Admin-Benachrichtigung ist fehlgeschlagen.',
	'item:object:admin_notice' => 'Admin-Benachrichtigung',
	'collection:object:admin_notice' => 'Admin-Benachrichtigungen',

	'admin:options' => 'Admin-Optionen',

	'admin:security' => 'Sicherheit',
	'admin:security:settings' => 'Einstellungen',
	'admin:security:settings:description' => 'Auf dieser Seite kannst Du einige Sicherheitseinstellungen vornehmen. Bitte lese die Informationen bei den Einstelloptionen sorgfältig durch bevor Du Änderungen vornimmst.',
	'admin:security:settings:label:hardening' => 'Server-Absicherung',
	'admin:security:settings:label:notifications' => 'Benachrichtigungen',
	'admin:security:settings:label:site_secret' => 'Geheimschlüssel',
	
	'admin:security:settings:notify_admins' => 'Benachrichtige alle Administratoren, wenn ein Admin hinzugefügt oder entfernt wird',
	'admin:security:settings:notify_admins:help' => 'Damit wird eine Benachrichtigung an alle Administratoren gesendet, wenn einer der Admins einen Benutzer zu einem Admin macht oder einen Admin wieder zu einem normalen Benutzer macht.',
	
	'admin:security:settings:notify_user_admin' => 'Benachrichtige den Benutzer wenn er Admin-Privilegien bekommt oder sie ihm entzogen werden',
	'admin:security:settings:notify_user_admin:help' => 'Damit wird eine Benachrichtigung an den Benutzer gesendet, wenn er zu einem Admin gemacht wird oder wenn er von einem Admin wieder zu einem normalen Benutzer gemacht wird.',
	
	'admin:security:settings:notify_user_ban' => 'Benachrichtige den Benutzer, wenn sein Account gesperrt oder wieder entsperrt wird',
	'admin:security:settings:notify_user_ban:help' => 'Damit wird eine Benachrichtigung an den Benutzer gesendet, wenn ein Admin seinen Account sperrt oder ihn wieder entsperrt.',
	
	'admin:security:settings:protect_upgrade' => 'Ausführung von upgrade.php absichern',
	'admin:security:settings:protect_upgrade:help' => 'Damit wird die Ausführung von upgrade.php abgesichert. Es wird entweder ein gültiges Token benötigt, damit upgrade.php ausgeführt werden kann, oder Du mußt als Administrator angemeldet sein.',
	'admin:security:settings:protect_upgrade:token' => 'Um upgrade.php ausführen zu können, wenn man nicht angemeldet ist oder nicht als Administrator angemeldet ist, muss die folgende URL verwendet werden:',
	
	'admin:security:settings:protect_cron' => 'Absichern der /cron URLs',
	'admin:security:settings:protect_cron:help' => 'Damit wird der Zugriff auf die /cron URLs mit einem Token abgesichert. Die Cronjobs von Elgg werden dann nur noch ausgeführt, wenn die /cron URLs mit einem gültigen Token aufgerufen werden.',
	'admin:security:settings:protect_cron:token' => 'Um die /cron URLs verwenden zu können, sind die folgenden Tokens notwendig. Bitte beachten, dass jedes Croninterval sein eigenes Token hat.',
	'admin:security:settings:protect_cron:toggle' => 'Anzeigen/Verbergen der /cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Autovervollständigung bei Passwort-Eingabefeldern deaktivieren',
	'admin:security:settings:disable_password_autocomplete:help' => 'Die Eingaben in diese Felder werden von Browsern gecacht. Ein Angreifer, der Zugriff auf den Browser erlangt, könnte diese gecachten Daten entwenden. Dies ist vor allem bei Computern ein Risiko, die von vielen verschiedenen Anwendern genutzt werden können, wie in Internet Cafes oder bei öffentlichen Terminals beispielsweise an Flughäfen. Wenn Du die Autovervollständigung deaktivierst, können Passwort-Tools die Passwort-Eingabefelder nicht mehr automatisch ausfüllen. Das Autocomplete-Attribute kann von den verschiedenen Browsern unterschiedlich gut (möglicherweise nicht vollständig) unterstützt werden.',
	
	'admin:security:settings:email_require_password' => 'Passworteingabe bei Änderung der Email-Adresse notwendig',
	'admin:security:settings:email_require_password:help' => 'Möchte ein Benutzer die Email-Adresse seines Accounts ändern, muss er diese Änderung durch die Eingabe seines derzeitigen Passworts bestätigen.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg verwendet einen seitenspezifischen Geheimschlüssel, um darauf basierend Sicherheits-Token zu generieren, die für verschiedene Authentifizierungszwecke verwendet werden.',
	'admin:security:settings:site_secret:regenerate' => "Geheimschlüssel neu erzeugen",
	'admin:security:settings:site_secret:regenerate:help' => "Anmerkung: die Neuerstellung des Geheimschlüssels kann einigen Benutzern Deiner Seite leider zwangsläufig einige Unannehmlichkeiten bereiten, da als Folge davon die Authentifizierungs-Token, beispielsweise in den Cookies für die persistente Anmeldung, in den Validierungs-Emails und den Einladungscodes, die noch mit dem alten Geheimschlüssel erzeugt wurden, nicht mehr gültig sind.",
	
	'admin:site:secret:regenerated' => "Der Geheimschlüssel Deiner Community-Seite wurde neu erzeugt.",
	'admin:site:secret:prevented' => "Die Neuerstellung des Geheimschlüssels Deiner Community-Seite wurde unterbunden.",
	
	'admin:notification:make_admin:admin:subject' => 'Neuer Admin auf %s',
	'admin:notification:make_admin:admin:body' => 'Hallo %s,

%s hat %s zu einem Administrator auf der Community-Seite %s gemacht.

Um das Profil des neuen Administrators aufzurufen, folge diesem Link:

%s

Um die Community-Seite aufzurufen, folge diesem Link:

%s',
	
	'admin:notification:make_admin:user:subject' => 'Du bist nun Admin auf %s',
	'admin:notification:make_admin:user:body' => 'Hallo %s,

%s hat Dich zu einem Administrator der Community-Seite %s gemacht.

Um die Community-Seite aufzurufen, folge diesem Link:

%s',
	'admin:notification:remove_admin:admin:subject' => 'Zurückstufung eines Admins auf %s',
	'admin:notification:remove_admin:admin:body' => 'Hallo %s,

%s hat %s auf %s von einen Administrator zu einem normalen Benutzer zurückgestuft.

Um das Profil des frühreren Administrators aufzurufen, folge diesem Link:

%s

Um die Community-Seite aufzurufen, folge diesem Link:

%s',
	
	'admin:notification:remove_admin:user:subject' => 'Entlassung als Admin auf %s',
	'admin:notification:remove_admin:user:body' => 'Hallo %s,

%s hat Dich auf %s von einem Administrator zu einem normalen Benutzer zurückgestuft.

Um die Community-Seite aufzurufen, folge diesem Link:

%s',
	'user:notification:ban:subject' => 'Dein Account auf %s wurde gesperrt',
	'user:notification:ban:body' => 'Hallo %s,

Dein Account auf %s wurde gesperrt.

Um die Community-Seite aufzurufen, folge diesem Link:
%s',
	
	'user:notification:unban:subject' => 'Dein Account auf %s ist nicht länger gesperrt',
	'user:notification:unban:body' => 'Hallo %s,

Dein Account auf %s ist nicht länger gesperrt. Du kannst Dich auf der Community-Seite wieder anmelden.

Um die Community-Seite aufzurufen, folge diesem Link:
%s',
	
/**
 * Plugins
 */

	'plugins:disabled' => 'Die Plugins werden nicht geladen, da eine Datei namens "disabled" im mod-Verzeichnis vorhanden ist.',
	'plugins:settings:save:ok' => "Die Einstellungen für das Plugin %s wurden gespeichert.",
	'plugins:settings:save:fail' => "Beim Speichern der Einstellungungen für das Plugin %s ist ein Problem aufgetreten.",
	'plugins:usersettings:save:ok' => "Die Benutzereinstellungen für das Plugin %s wurden gespeichert.",
	'plugins:usersettings:save:fail' => "Beim Speichern der Benutzereinstellungungen für das Plugin %s ist ein Problem aufgetreten.",
	'item:object:plugin' => 'Plugins',
	'collection:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Alle aktivieren',
	'admin:plugins:deactivate_all' => 'Alle deaktivieren',
	'admin:plugins:activate' => 'Aktivieren',
	'admin:plugins:deactivate' => 'Deaktivieren',
	'admin:plugins:description' => "Hier kannst Du die auf Deiner Community-Seite installierten Tools (Plugins) verwalten und hast Zugriff auf die von ihnen angebotenen Konfigurationsoptionen.",
	'admin:plugins:opt:linktext' => "Tools konfigurieren...",
	'admin:plugins:opt:description' => "Konfigurieren der installierten Tools der Community-Seite.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Name",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorien',
	'admin:plugins:label:licence' => "Lizenz",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Dateien",
	'admin:plugins:label:resources' => "Ressourcen",
	'admin:plugins:label:screenshots' => " Bildschirmphotos",
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
	'admin:plugins:label:missing_dependency' => 'Unerfüllte Abhängigkeit [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'Dieses Plugin hat unerfüllte Abhängigkeiten und kann deshalb nicht aktiviert werden. Prüfe die Abhängigkeiten für weitere Informationen.',
	'admin:plugins:warning:invalid' => 'Dieses Plugin ist nicht standardkonform: %s.',
	'admin:plugins:warning:invalid:check_docs' => 'Bitte schau in der <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elgg-Dokumentation</a> nach, um weitere Hinweise zur Problemlösung zu erhalten.',
	'admin:plugins:cannot_activate' => 'Aktivierung nicht möglich.',
	'admin:plugins:cannot_deactivate' => 'Deaktivierung nicht möglich.',
	'admin:plugins:already:active' => 'Das ausgewählte Plugin bzw. die ausgewählten Plugins sind bereits aktiviert.',
	'admin:plugins:already:inactive' => 'Das ausgewählte Plugin bzw. die ausgewählten Plugins sind bereits deaktiviert.',

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
	'admin:statistics:label:user' => "Benutzer-Statistiken",
	'admin:statistics:label:numentities' => "Entitäten Deiner Community-Seite",
	'admin:statistics:label:numusers' => "Anzahl der Mitglieder",
	'admin:statistics:label:numonline' => "Anzahl der angemeldeten Mitglieder",
	'admin:statistics:label:onlineusers' => "Momentan angemeldete Mitglieder",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg-Version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",
	'admin:statistics:label:version:code' => "Code-Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'PHP-Info anzeigen',
	'admin:server:label:web_server' => 'Webserver',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Speicherort des Logs',
	'admin:server:label:php_version' => 'PHP-Version',
	'admin:server:label:php_ini' => 'Speicherort der php.ini-Datei',
	'admin:server:label:php_log' => 'Speicherort des PHP-Logs',
	'admin:server:label:mem_avail' => 'Verfügbarer Hauptspeicher',
	'admin:server:label:mem_used' => 'Verwendeter Hauptspeicher',
	'admin:server:error_log' => "Speicherort des Webserver-Logs",
	'admin:server:label:post_max_size' => 'PHP-Einstellung von post_max_size',
	'admin:server:label:upload_max_filesize' => 'PHP-Einstellung von upload_max_filesize',
	'admin:server:warning:post_max_too_small' => '(Bemerkung: Der Wert von post_max_size muss größer als dieser Wert sein, damit Uploads dieser Größe möglich sind)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache ist auf diesem Server nicht eingerichtet oder es wurde in der Konfigurationsdatei von Elgg noch nicht aktiviert.
		Für eine bessere Performance ist es empfehlenswert, Memcache (oder Redis) auf dem Server zu installieren und einzurichten und auch die Elgg-Konfigurationdatei entsprechend anzupassen.
',

	'admin:server:label:redis' => 'Redis',
	'admin:server:redis:inactive' => '
		Redis is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure redis (or memcache).
',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => '
		OPcache is not available on this server or it has not yet been enabled.
		For improved performance, it is recommended that you enable and configure OPcache.
',
	
	'admin:user:label:search' => "Suche Benutzeraccount:",
	'admin:user:label:searchbutton' => "Suche",

	'admin:user:ban:no' => "Benutzeraccount sperren",
	'admin:user:ban:yes' => "Der Benutzeraccount wurde gesperrt.",
	'admin:user:self:ban:no' => "Du kannst Dich nicht selbst sperren!",
	'admin:user:unban:no' => "Die Sperrung des Benutzeraccounts kann nicht aufgehoben werden.",
	'admin:user:unban:yes' => "Die Sperrung des Benutzeraccounts wurde aufgehoben.",
	'admin:user:delete:no' => "Der Benutzeraccount konnte nicht gelöscht werden.",
	'admin:user:delete:yes' => "Der Benutzeraccount %s wurde gelöscht.",
	'admin:user:self:delete:no' => "Du kannst Deinen eigenen Account nicht löschen!",

	'admin:user:resetpassword:yes' => "Das Passwort wurde zurückgesetzt und der Benutzer benachrichtigt.",
	'admin:user:resetpassword:no' => "Das Passwort konnte nicht zurückgesetzt werden.",

	'admin:user:makeadmin:yes' => "Der Benutzer ist nun ein Administrator.",
	'admin:user:makeadmin:no' => "Die Zuweisung von Administratorrechten für den Benutzer ist fehlgeschlagen.",

	'admin:user:removeadmin:yes' => "Der Benutzer ist nicht länger ein Administrator.",
	'admin:user:removeadmin:no' => "Die Rücknahme von Administratorrechten für den Benutzer ist fehlgeschlagen.",
	'admin:user:self:removeadmin:no' => "Du kannst Dir nicht selbst die Administratorrechte entziehen!",

	'admin:configure_utilities:menu_items' => 'Menü-Einträge',
	'admin:menu_items:configure' => 'Konfiguriere die Einträge im Hauptmenü.',
	'admin:menu_items:description' => 'Wähle aus, welche Links Du als separate Menü-Einträge anzeigen lassen willst. Die restlichen Links werden unter "Mehr" am Ende der Liste zusammengefaßt.',
	'admin:menu_items:hide_toolbar_entries' => 'Links aus dem Toolbar-Menü entfernen?',
	'admin:menu_items:saved' => 'Die Menü-Einträge wurden gespeichert.',
	'admin:add_menu_item' => 'Benutzerdefinierten Menü-Eintrag hinzufügen',
	'admin:add_menu_item:description' => 'Gebe den anzuzeigenden Namen und die URL an, um einen Eintrag zum Menü hinzuzufügen.',

	'admin:configure_utilities:default_widgets' => 'Standard-Widgets',
	'admin:default_widgets:unknown_type' => 'Unbekannter Widget-Typ.',
	'admin:default_widgets:instructions' => 'Hinzufügen, Entfernen, Positionieren und Konfigurieren der standardmäßig anzuzeigenden Widgets für die gewünschte Seite.
Diese Änderungen werden nur neu erstellte Benutzeraccounts auf Deiner Community-Seite betreffen.',

	'admin:robots.txt:instructions' => "Bearbeite im Folgenden die robots.txt-Datei dieser Seite:",
	'admin:robots.txt:plugins' => "Plugins fügen das Folgende zur robots.txt-Datei hinzu:",
	'admin:robots.txt:subdir' => "Die robots.txt-Datei für diese Community-Seite kann nicht bearbeitet werden, da Elgg in einem Unterverzeichnis installiert ist.",
	'admin:robots.txt:physical' => "Die Konfiguration des Inhalts der robots.txt-Datei ist nicht möglich, da auf dem Server bereits eine robots.txt-Datei vorhanden ist.",

	'admin:maintenance_mode:default_message' => 'Diese Community-Seite ist aufgrund von Wartungsarbeiten derzeit nicht verfügbar.',
	'admin:maintenance_mode:instructions' => 'Der Wartungs-Modus sollte während einer Aktualisierung der Community-Seite oder anderen größeren Änderungen an der Seite aktiviert werden.
		Wenn er aktiviert ist, können sich nur Admins anmelden und auf der Community-Seite browsen.',
	'admin:maintenance_mode:mode_label' => 'Wartungs-Modus',
	'admin:maintenance_mode:message_label' => 'Die Nachricht, die Benutzern angezeigt wird, wenn der Wartungsmodus aktiviert ist.',
	'admin:maintenance_mode:saved' => 'Die Einstellungen für den Wartungs-Modus wurden gespeichert.',
	'admin:maintenance_mode:indicator_menu_item' => 'Die Community-Seite ist im Wartungs-Modus.',
	'admin:login' => 'Administrator-Anmeldung',

/**
 * User settings
 */

	'usersettings:description' => "Hier kannst Du alle Deine persönlichen Einstellungen vornehmen, beispielsweise Accounteinstellungen oder von Tools (Plugins) angebotene Konfigurationsoptionen.",

	'usersettings:statistics' => "Deine persönliche Statistik",
	'usersettings:statistics:opt:description' => "Überblick über Statistiken zu Benutzern und Objekten Deiner Community-Seite.",
	'usersettings:statistics:opt:linktext' => "Account-Statistik",

	'usersettings:statistics:login_history' => "Login-Übersicht",
	'usersettings:statistics:login_history:date' => "Datum",
	'usersettings:statistics:login_history:ip' => "IP-Adresse",

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
	'river:comments:all' => 'Zeige alle %u Kommentare',
	'river:generic_comment' => 'kommentierte %s %s',

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
	
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Speichern",
	'save_go' => "Speichern, und weiter zu %s",
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
	'validate' => 'Validieren',
	'read_more' => 'Read more',

	'site' => 'Webseite',
	'activity' => 'Aktivitäten',
	'members' => 'Mitglieder',
	'menu' => 'Menü',

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
	'fileexists' => "Es wurde bereits eine Datei hochgeladen. Um sie zu ersetzen, wähle im Folgenden eine neue aus.",
	'input:file:upload_limit' => 'Maximal erlaubte Dateigröße ist %s',

/**
 * User add
 */

	'useradd:subject' => 'Benutzeraccount erstellt',
	'useradd:body' => '%s,

Auf der Community-Seite %s wurde ein Benutzeraccount für Dich erstellt. Um Dich anzumelden, gehe zu:

%s

und melde Dich mit diesen Zugangsdaten an:

Benutzername (Username): %s
Passwort: %s

Nachdem Du Dich angemeldet hast, solltest Du Dein Passwort ändern.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Hier klicken, um die Statusmeldung auszublenden.",


/**
 * Messages
 */
	'messages:title:success' => 'Erfolg',
	'messages:title:error' => 'Fehler',
	'messages:title:warning' => 'Warnung',
	'messages:title:help' => 'Hilfe',
	'messages:title:notice' => 'Hinweis',

/**
 * Import / export
 */

	'importsuccess' => "Das Importieren der Daten war erfolgreich.",
	'importfail' => "Das Importieren der OpenDD-Daten ist fehlgeschlagen.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "soeben",
	'friendlytime:minutes' => "vor %s Minuten",
	'friendlytime:minutes:singular' => "vor einer Minute",
	'friendlytime:hours' => "vor %s Stunden",
	'friendlytime:hours:singular' => "vor einer Stunde",
	'friendlytime:days' => "vor %s Tagen",
	'friendlytime:days:singular' => "gestern",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "in %s Minuten",
	'friendlytime:future:minutes:singular' => "in einer Minute",
	'friendlytime:future:hours' => "in %s Stunden",
	'friendlytime:future:hours:singular' => "in einer Stunde",
	'friendlytime:future:days' => "in %s Tagen",
	'friendlytime:future:days:singular' => "morgen",

	'date:month:01' => 'Januar %s',
	'date:month:02' => 'Februar %s',
	'date:month:03' => 'März %s',
	'date:month:04' => 'April %s',
	'date:month:05' => 'Mai %s',
	'date:month:06' => 'Juni %s',
	'date:month:07' => 'Juli %s',
	'date:month:08' => 'August %s',
	'date:month:09' => 'September %s',
	'date:month:10' => 'Oktober %s',
	'date:month:11' => 'November %s',
	'date:month:12' => 'Dezember %s',

	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mrz %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'Mai %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Okt %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dez %s',

	'date:weekday:0' => 'Sonntag',
	'date:weekday:1' => 'Montag',
	'date:weekday:2' => 'Dienstag',
	'date:weekday:3' => 'Mittwoch',
	'date:weekday:4' => 'Donnerstag',
	'date:weekday:5' => 'Freitag',
	'date:weekday:6' => 'Samstag',

	'date:weekday:short:0' => 'So',
	'date:weekday:short:1' => 'Mo',
	'date:weekday:short:2' => 'Di',
	'date:weekday:short:3' => 'Mi',
	'date:weekday:short:4' => 'Do',
	'date:weekday:short:5' => 'Fr',
	'date:weekday:short:6' => 'Sa',

	'interval:minute' => 'Jede Minute',
	'interval:fiveminute' => 'Alle fünf Minuten',
	'interval:fifteenmin' => 'Alle 15 Minuten',
	'interval:halfhour' => 'Jede halbe Stunde',
	'interval:hourly' => 'Stündlich',
	'interval:daily' => 'Täglich',
	'interval:weekly' => 'Wöchentlich',
	'interval:monthly' => 'Monatlich',
	'interval:yearly' => 'Jährlich',

/**
 * System settings
 */

	'installation:sitename' => "Name Deiner Community-Seite:",
	'installation:sitedescription' => "Eine kurze Beschreibung Deiner Seite (optional):",
	'installation:sitedescription:help' => "Von Elgg und den mitgelieferten Plugins wird diese Kurzbeschreibung nur im \"description\"-Metatag verwendet, das von Suchmaschinen indiziert wird.",
	'installation:wwwroot' => "URL Deiner Community-Seite:",
	'installation:path' => "Vollständiger Pfad zum Elgg-Installationsverzeichnis auf Deinem Server:",
	'installation:dataroot' => "Vollständiger Pfad zum Elgg-Datenverzeichnis auf Deinem Server:",
	'installation:dataroot:warning' => "Du mußt dieses Verzeichnis selbst erzeugen. Es muß in einem Verzeichnis außerhalb Deines Elgg-Installationsverzeichnisses sein.",
	'installation:sitepermissions' => "Standardmäßige Zugangslevel: ",
	'installation:language' => "Standardsprache Deiner Community-Seite: ",
	'installation:debug' => "Detailgrad der Informationen / Anzahl der Einträge, die im Serverlog protokolliert werden sollen.",
	'installation:debug:label' => "Log-Level:",
	'installation:debug:none' => 'Debug-Modus aus (empfohlen für Nicht-Testinstallationen)',
	'installation:debug:error' => 'Nur kritische Fehler protokollieren',
	'installation:debug:warning' => 'Kritische Fehler und Warnungen protokollieren',
	'installation:debug:notice' => 'Alle Fehler, Warnungen und Hinweise protokollieren',
	'installation:debug:info' => 'Alles protokollieren',

	// Walled Garden support
	'installation:registration:description' => 'Aktiviere diese Option, wenn Besucher der Community-Seite erlaubt sein soll, einen Benutzeraccount zu registrieren.',
	'installation:registration:label' => 'Registrierung neuer Benutzeraccounts erlauben',
	'installation:walled_garden:description' => 'Aktiviere diese Option, um Besuchern Deiner Community-Seite, die nicht angemeldet sind, den Zugriff auf die Inhalte zu verwehren mit Ausnahme der Seiten, die als "public" konfiguriert sind (beispielsweise die Login- und Registrierungsseiten).',
	'installation:walled_garden:label' => 'Zugriff auf angemeldete Benutzer beschränken',

	'installation:view' => "Gebe den Ansichtsmodus an, der für Deine Community-Seite verwendet werden soll. Wenn Du nicht sicher bist was Du eingeben sollst, lass das Textfeld leer oder verwende \"default\", um den Standardmodus zu verwenden:",

	'installation:siteemail' => "Email-Adresse Deiner Community-Seite (wird vom System verwendet, um Benachrichtigungen zu versenden)",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Standardmäßige Anzahl der Einträge pro Seite",

	'admin:site:access:warning' => "Dies ist der Zugangslevel, der Benutzern standardmäßig vorgeschlagen wird, wenn sie neue Inhalte erstellen. Eine Änderung hier verändert nicht den Zugangslevel der Inhalte selbst.",
	'installation:allow_user_default_access:description' => "Aktiviere diese Option, um Benutzern zu erlauben, selbst den Zugangslevel festzulegen, der standardmäßig ausgewählt ist, wenn sie neue Inhalte erstellen. Die benutzerspezifische Einstellung für den vorausgewählten Zugangslevel setzt die seitenweite Einstellung für diese Benutzer außer Kraft.",
	'installation:allow_user_default_access:label' => "Benutzerspezifischen Standard-Zugangslevel erlauben",

	'installation:simplecache:description' => "Simple-Cache verbessert die Systemleistung durch Caching von statischen Seiteninhalten inklusive einiger CSS- und JavaScript-Dateien.",
	'installation:simplecache:label' => "Simple-Cache aktivieren (empfohlen)",

	'installation:cache_symlink:description' => "Der symbolische Link zum Simple-Cache-Verzeichnis ermöglicht es dem Server, statische Views ohne Zuhilfenahme der Elgg-Engine anzuzeigen. Dies verbessert die Performance und reduziert die Serverlast.",
	'installation:cache_symlink:label' => "Symbolischen Link zum Simple-Cache-Verzeichnis verwenden (empfohlen)",
	'installation:cache_symlink:warning' => "Der symbolische Link wurde erstellt. Wenn Du zu einem späteren Zeitpunkt keinen Link zum Simple-Cache-Verzeichnis mehr verwenden möchtest, lösche bitte den symbolischen Link zum Simple-Cache-Verzeichnis aus dem Hauptverzeichnis Deiner Elgg-Installation.",
	'installation:cache_symlink:paths' => 'Damit der symbolische Link richtig funktioniert, muss er <i>%s</i> auf <i>%s</i> verlinken.',
	'installation:cache_symlink:error' => "Die automatische Erstellung des symbolischen Links ist fehlgeschlagen. Bitte schau in der Elgg-Dokumentation nach, wie Du den Link manuell erstellen kannst.",

	'installation:minify:description' => "Der Simple-Cache kann die Performance auch zusätzlich noch durch Komprimierung der CSS- und JavaScript-Dateien verbessern. (Voraussetzung ist, das der Simple-Cache aktiviert ist.)",
	'installation:minify_js:label' => "JavaScript-Dateien komprimieren (empfohlen)",
	'installation:minify_css:label' => "CSS-Dateien komprimieren (empfohlen)",

	'installation:htaccess:needs_upgrade' => "Du mußt die .htaccess-Datei auf Deinem Server aktualisieren bzw. anpassen, damit der Pfad in den GET-Parameter __elgg_uri eingebunden wird (Du kannst die Datei vendor/elgg/elgg/install/config/htaccess.dist als Vorlage für Deine .htacess-Datei verwenden).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg war es nicht möglich, eine Verbindung zu sich selbst aufzubauen, um die Rewrite-Regeln zu testen. Stelle sicher, dass curl auf dem Server installiert ist und korrekt funktioniert. Es dürfen auch keine IP Adressen-Beschränkungen vorhanden sein, die möglicherweise Verbindungen auf localhost selbst unterbinden.",

	'installation:systemcache:description' => "Der Systemcache veringert die Ladezeit von Elgg, indem einige häufig verwendete Daten in Dateien vorgehalten werden.",
	'installation:systemcache:label' => "Systemcache aktivieren (empfohlen)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching-Mechanismen',
	'admin:legend:content_access' => 'Zugangslevel für Inhalte',
	'admin:legend:site_access' => 'Zugangsbeschränkungen zur Community-Seite',
	'admin:legend:debug' => 'Debuggen und Protokollieren',
	
	'config:remove_branding:label' => "Elgg-Branding verbergen",
	'config:remove_branding:help' => "Aktiviere diese Option, um die Links und Logos, die auf den Seiten der Community eingeblendet werden und darauf hinweisen, dass diese Community-Seite mit Elgg erstellt wurde, zu verbergen. Falls Du Dich dazu entschließt, das Elgg-Branding zu verbergen, wäre es nett, wenn Du eine Spende für das Elgg-Projekt in Betracht ziehen würdest (https://elgg.org/about/supporters).",
	'config:disable_rss:label' => "RSS-Feeds deaktivieren",
	'config:disable_rss:help' => "Aktiviere diese Option, um den RSS-Feed-Link auf den Seiten Deiner Community zu verbergen.",
	'config:friendly_time_number_of_days:label' => "Anzahl der Tage, für die das \"Friendly Time\"-Anzeigeformat verwendet werden soll",
	'config:friendly_time_number_of_days:help' => "Mit dieser Option kannst Du festlegen, für wieviele Tage das \"Friendly Time\"-Format (z.B. \"vor 5 Tagen\") für die Anzeige eines Datums verwendet werden soll. Nach Ablauf dieser Zeit wird stattdessen ein normales Datumsformat verwendet. Wenn Du die Option auf 0 setzt, wird das \"Friendly Time\"-Format gar nicht verwendet.",
	
	'upgrading' => 'Aktualisieren...',
	'upgrade:core' => 'Deine Elgg-Installation wurde aktualisiert.',
	'upgrade:unlock' => 'Upgrade entsperren',
	'upgrade:unlock:confirm' => "Die Datenbank ist durch einen anderen Upgrade-Prozess gesperrt. Gleichzeitig ausgeführte Upgrade-Prozesse sind gefährlich. Du solltest nur dann weitermachen, wenn Du sicher bist, dass momentan wirklich kein anderer Upgrade-Prozess ausgeführt wird. Entsperren?",
	'upgrade:terminated' => 'Das Upgrade wurde durch einen Event-Handler abgebrochen.',
	'upgrade:locked' => "Upgrade ist nicht möglich. Ein anderer Upgrade-Prozess wird momentan ausgeführt. Um die Upgrade-Sperre zu entfernen, öffne den Admin-Bereich Deiner Seite.",
	'upgrade:unlock:success' => "Die Upgrade-Sperre wurde entfernt.",
	'upgrade:unable_to_upgrade' => 'Die Aktualisierung ist fehlgeschlagen.',
	'upgrade:unable_to_upgrade_info' => 'Diese Elgg-Installation kann nicht aktualisiert werden, da im views-Verzeichnis von Elgg veraltete Views-Dateien von einer älteren Elgg-Version gefunden wurden, die es in der neuen Elgg-Version nicht mehr gibt. Diese Views müssen entfernt werden, damit die neue Version von Elgg ohne Probleme funktionieren kann. Wenn Du keine Änderungen an den Dateien im views-Verzeichnis vorgenommen hast, kannst Du einfachheitshalber das views-Verzeichnis komplett löschen und es mit dem views-Verzeichnis aus dem Elgg-Paket der Version auf die Du aktualisieren willst und das Du von <a href="https://elgg.org">elgg.org</a> herunterladen kannst, ersetzen.<br /><br />

Wenn Du genauere Installationsanweisungen benötigst, lese die  <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">Dokumentation über die Aktualisierung von Elgg</a>. Falls Du Hilfe benötigst, stelle Deine Frage in den <a href="https://elgg.org/discussion/all">Community Support-Foren</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (bisher OAuth Lib genannt) wurde während der Seitenaktualisierung deaktiviert. Bitte aktiviere die API manuell, falls sie benötigt wird.',
	'upgrade:site_secret_warning:moderate' => "Es ist empfehlenswert, den Geheimschlüssel Deiner Community-Seite neu erzeugen zu lassen, um die Sicherheit für Deine Seite zu verbessern. Siehe Konfigurieren &gt; Einstellungen &gt; Erweiterte Einstellungen",
	'upgrade:site_secret_warning:weak' => "Wir empfehlen nachdrücklich, den Geheimschlüssel Deiner Community-Seite neu erzeugen zu lassen, um die Sicherheit für Deine Seite zu verbessern. Siehe Konfigurieren &gt; Einstellungen &gt; Erweiterte Einstellungen",

	'deprecated:function' => '%s() wurde durch %s() als veraltet markiert.',

	'admin:pending_upgrades' => 'Es gibt ausstehende Aktualisierungen auf Deiner Community-Seite. Du solltest diese baldmöglichst durchführen.',
	'admin:view_upgrades' => 'Ausstehende Aktualisierungen anzeigen.',
	'item:object:elgg_upgrade' => 'Community-Seite-Aktualisierung',
	'collection:object:elgg_upgrade' => 'Community-Seite-Aktualisierungen',
	'admin:upgrades:none' => 'Deine Installation ist aktuell!',

	'upgrade:item_count' => 'Es gibt <b>%s</b> Elemente, die aktualisiert werden müssen.',
	'upgrade:warning' => '<b>Warnung:</b> auf einer großen Community-Seite kann die Durchführung dieser Aktualisierung einige Zeit in Anspruch nehmen!',
	'upgrade:success_count' => 'Aktualisiert:',
	'upgrade:error_count' => 'Fehler:',
	'upgrade:finished' => 'Die Aktualisierung ist abgeschlossen.',
	'upgrade:finished_with_errors' => '<p>Die Aktualisierung wurde beendet. Allerdings sind dabei Fehler aufgetreten. Lade die Seite erneut und versuche, die Aktualisierung nochmals durchzuführen.</p></p><br />Wenn dabei wieder Fehler auftreten, schaue in der Logdatei Deines Servers nach, ob es dort Einträge gibt, die eventuell weitere Informationen zur Ursache der Fehler liefern. Du kannst auch in der <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support-Gruppe</a> auf der Elgg-Community-Seite um Hilfe bei Deinem Problem bitten.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Datentyp-Angleichung der GUID-Spalten in der Datenbank',
	
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
	'email:address:password' => "Passwort",
	'email:address:password:help' => "Für die Änderung Deiner Email-Adresse ist die Eingabe Deines derzeitigen Passworts notwendig.",

	'email:save:success' => "Die neue Email-Adresse wurde gespeichert. Eine Verifizierungs-Email wurde versandt.",
	'email:save:fail' => "Die neue Email-Adresse konnte nicht gespeichert werden.",
	'email:save:fail:password' => "Das eingegebene Passwort ist nicht gleich Deinem derzeitigen Passwort. Daher kann Deine neue Email-Adresse nicht gespeichert werden.",

	'friend:newfriend:subject' => "Du bist nun mit %s befreundet!",
	'friend:newfriend:body' => "Du bist nun mit %s befreundet!

Um ihr/sein Profil aufzurufen, folge diesem Link:

%s",

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

Andernfalls ignoriere bitte diese Email.",

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

	'comments:count' => "%s Kommentare",
	'item:object:comment' => 'Kommentar',
	'collection:object:comment' => 'Kommentare',

	'river:object:default:comment' => '%s kommentierte %s',

	'generic_comments:add' => "Kommentieren",
	'generic_comments:edit' => "Kommentar bearbeiten",
	'generic_comments:post' => "Kommentieren",
	'generic_comments:text' => "Kommentar",
	'generic_comments:latest' => "Neueste Kommentare",
	'generic_comment:posted' => "Dein Kommentar wurde gespeichert.",
	'generic_comment:updated' => "Der Kommenatar wurde aktualisiert.",
	'entity:delete:object:comment:success' => "Der Kommentar wurde gelöscht.",
	'generic_comment:blank' => "Entschuldigung, aber Du mußt zuerst etwas schreiben bevor wir Deinen Kommentar abspeichern können.",
	'generic_comment:notfound' => "Entschuldigung, der gewünschte Kommentar konnte nicht gefunden werden.",
	'generic_comment:notfound_fallback' => "Entschuldigung, der gewünschte Kommentar konnte nicht gefunden werden aber Du wurdest zu der Seite weitergeleitet auf der er ursprünglich hinterlassen wurde.",
	'generic_comment:failure' => "Beim Speichern Deines Kommentars ist ein Fehler aufgetreten.",
	'generic_comment:none' => 'Keine Kommentare.',
	'generic_comment:title' => 'Kommentar von %s',
	'generic_comment:on' => '%s zu %s',
	'generic_comments:latest:posted' => 'schrieb einen',

	'generic_comment:notification:owner:subject' => 'Du hast einen neuen Kommentar erhalten!',
	'generic_comment:notification:owner:summary' => 'Du hast einen neuen Kommentar erhalten!',
	'generic_comment:notification:owner:body' => "Zu Deinem Beitrag \"%s\" wurde von %s ein neuer Kommentar geschrieben. Der Kommentar lautet:

%s

Um zu antworten oder Deinen ursprünglichen Beitrag aufzurufen, folge diesem Link:

%s

Um das Profil von %s aufzurufen, folge diesem Link:

%s",
	
	'generic_comment:notification:user:subject' => 'Neuer Kommentar zu: %s',
	'generic_comment:notification:user:summary' => 'Neuer Kommentar zu: %s',
	'generic_comment:notification:user:body' => "Zu dem Beitrag \"%s\" wurde von %s ein neuer Kommentar geschrieben. Der Kommentar lautet:

%s

Um zu antworten oder den ursprünglichen Beitrag aufzurufen, folge diesem Link:

%s

Um das Profil von %s aufzurufen, folge diesem Link:

%s",

/**
 * Entities
 */

	'byline' => 'Von %s',
	'byline:ingroup' => 'in der Gruppe %s',
	'entity:default:missingsupport:popup' => 'Diese Entität kann nicht richtig angezeigt werden. Dies kann daran liegen, dass dafür ein Plugin benötigt wird, das nicht mehr installiert ist.',

	'entity:delete:item' => 'Beitrag',
	'entity:delete:item_not_found' => 'Dieser Beitrag konnte nicht gefunden werden.',
	'entity:delete:permission_denied' => 'Du hast keine ausreichende Berechtigung, um diesen Beitrag zu löschen.',
	'entity:delete:success' => '%s wurde gelöscht.',
	'entity:delete:fail' => 'Das Löschen von %s ist fehlgeschlagen.',

	'entity:can_delete:invaliduser' => 'Ein Aufruf der canDelete()-Funktion für den Benutzer mit der Benutzer-GUID [%s] ist nicht möglich, da dieser Benutzer nicht existiert.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Der Form fehlt der __token und/oder __ts Eintrag.',
	'actiongatekeeper:tokeninvalid' => "Die Gültigkeit des Authentifizierungs-Token für die gerade betrachtete Seite ist abgelaufen. Bitte lade die Seite neu.",
	'actiongatekeeper:timeerror' => 'Das Authentifizierungs-Token für die die Seite, die Du betrachtet hast, ist abgelaufen. Bitte lade die Seite neu und versuche es noch einmal.',
	'actiongatekeeper:pluginprevents' => 'Entschuldigung, die Verarbeitung der von Dir eingegeben Daten ist fehlgeschlagen.',
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
	"eu_es" => "Baskisch (Spanien)",
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
	"pt_br" => "Portugiesisch (Brasilien)",
	"qu" => "Quechua",
	"rm" => "Rätoromanisch",
	"rn" => "Kirundi",
	"ro" => "Rumänisch",
	"ro_ro" => "Rumänisch (Rumänien)",
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
	"sr_latin" => "Serbisch (Lateinisches Alphabet)",
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
	"zh_hans" => "Chinesisch (Kurzzeichen)",
	"zu" => "Zulu",

	"field:required" => 'Erforderlich',

	"core:upgrade:2017080900:title" => "Umwandlung des Datenbank-Encodings für Multi-Byte-Support",
	"core:upgrade:2017080900:description" => "Damit wird das Encoding der Datenbank und der Datenbanktabellen auf UTF8mb4 umgestellt, damit Multi-Byte-Characters wie beispielsweise Emojies gespeichert werden können.",

	"core:upgrade:2017080950:title" => "Initialisierung neuer Sicherheitsoptionen mit Standardeinstellungen",
	"core:upgrade:2017080950:description" => "In der neuen Elgg-Version wurden einige Sicherheitsoptionen hinzugefügt. Es wird empfohlen, dieses Update durchzuführen, damit diese Optionen mit den Standardwerten initialisiert werden. Du kannst Dir diese neuen Optionen anschließend in den Seiteneinstellungen ansehen und wenn nötig die Werte anpassen.",

	"core:upgrade:2017121200:title" => "Umwandeln der Freundeslisten in Zugriffslevel-Collections von Freunden",
	"core:upgrade:2017121200:description" => "Damit werden die bisherigen Freundeslisten in native Zugriffslevel-Collections (weiterhin Freundeslisten genannt) umgewandelt.",

	"core:upgrade:2018041800:title" => "Neue Plugins aktivieren",
	"core:upgrade:2018041800:description" => "Bestimmte Funktionalitäten von Elgg wurden in (mitgelieferte) Plugins verlagert. Dieses Update aktiviert diese Plugins, um die Kompatibilität von 3rd Party-Plugins zu gewährleisten, deren Funktionsfähigkeit möglicherweise von dieser Funktionalität abhängt.",

	"core:upgrade:2018041801:title" => "Löschen von Plugin-Entitäten von entfernten Plugins",
	"core:upgrade:2018041801:description" => "Damit werden die Entitäten gelöscht, die zu Plugins gehören, die mit Elgg 3.0 nicht mehr mitgeliefert werden.",
	
	"core:upgrade:2018061401:title" => "Umwandlung der Cronlog-Datenbankeinträge",
	"core:upgrade:2018061401:description" => "Damit werden die Cronlog-Einträge innerhalb der Datenbank verschoben, um mit dem neuen Datenbank-Schema kompatibel zu sein.",
);
