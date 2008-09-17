<?php

        $german = array(

                /**
                 * Sites
                 */

                        'item:site' => 'Seiten',

                /**
                 * Sessions
                 */

                        'login' => "Anmelden",
                        'loginok' => "Du wurdest angemeldet.",
                        'loginerror' => "Wir konnten Dich nicht anmelden. Vielleicht hast Du dein Nutzerkonto noch nicht best&auml;tigt oder die angegeben Daten waren nicht korrekt. &Uuml;berpr&uuml;fe bitte, ob Du alle Daten richtig eingegeben hast und versuche es noch einmal.",

                        'logout' => "Abmelden",
                        'logoutok' => "Du wurdest abgemeldet.",
                        'logouterror' => "Fehler beim Abmelden. Bitte versuche es noch einmal.",

                /**
                 * Errors
                 */
                        'exception:title' => "Willkommen bei Elgg.",

                        'InstallationException:CantCreateSite' => "Fehler beim Einrichten der Elgg Seite mit dem Namen:%s, Url: %s",

                        'actionundefined' => "Die gew&auml;hlte Aktion(%s) ist im System nicht registriert.",
                        'actionloggedout' => "Du kannst diese Aktion nicht durchf&uuml;hren, wenn Du abgemeldet bist.",

                        'notfound' => "Die Seite wurde nicht gefunden oder Du hast keine Zugangsberechtigung.",

                        'SecurityException:Codeblock' => "Du hast keine Berechtigung den Programmcode auszuf&uuml;hren",
                        'DatabaseException:WrongCredentials' => "Elgg konnte mit den angegebenen Daten %s@%s (pw: %s) keine Verbindung zur Datenbank aufbauen.",
                        'DatabaseException:NoConnect' => "Elgg konnte nicht auf die Datenbank '%s' zugreifen. Bitte &uuml;berpr&uuml;fe, ob die Datenbank richtig eingerichtet wurde und ob Du Zugang zu ihr hast.",
                        'SecurityException:FunctionDenied' => "Die Ausf&uuml;hrung der Funktion '%s' ist nicht erlaubt.",
                        'DatabaseException:DBSetupIssues' => "Folgende Fehler sind aufgetreten: ",
                        'DatabaseException:ScriptNotFound' => "Elgg konnte das ben&ouml;tigte Daktenbankskript %s nicht finden.",

                        'IOException:FailedToLoadGUID' => " %s konnte vom GUID:%d nicht erneut geladen werden.",
                        'InvalidParameterException:NonElggObject' => "Ein Nicht-ElggObject wurde an den ElggObject Constructor &uuml;bergeben!",
                        'InvalidParameterException:UnrecognisedValue' => "Der an den Constructor &uuml;bergebene Wert konnte nicht erkannt werden.",

                        'InvalidClassException:NotValidElggStar' => "GUID:%d ist kein g&uuml;ltiger %s",

                        'PluginException:MisconfiguredPlugin' => "%s ist ein falsch konfiguriertes Plugin.",

                        'InvalidParameterException:NonElggUser' => "Ein Nicht-ElggUser wurde and den ElggUser Constructor &uuml;bergeben!",

                        'InvalidParameterException:NonElggSite' => "Eine Nicht-ElggSite wurde an den ElggSite Constructor &uuml;bergeben!",

                        'InvalidParameterException:NonElggGroup' => "Eine Nicht-ElggGroup wurde and en ElggGroup Constructor &uuml;bergeben!",

                        'IOException:UnableToSaveNew' => "Fehler beim Speichern von %s .",

                        'InvalidParameterException:GUIDNotForExport' => "GUID wurde beim Export nicht angegeben, das sollte nie passieren.",
                        'InvalidParameterException:NonArrayReturnValue' => "Die Funtion der Serialisierung der Entit&auml;t gab als R&uuml;ckgabeparameter einen Nicht-Array aus",

                        'ConfigurationException:NoCachePath' => "Cache Pfad nicht gesetzt!",
                        'IOException:NotDirectory' => "%s ist kein Verzeichnis.",

                        'IOException:BaseEntitySaveFailed' => "Die Base Entity Information des neuen Objekts konnte nicht gespeichert werden!",
                        'InvalidParameterException:UnexpectedODDClass' => "import() hatt eine unerwartete ODD Klasse &uuml;bergeben",
                        'InvalidParameterException:EntityTypeNotSet' => "Der Typ der Entit&auml;t muss gestetzt sein.",

                        'ClassException:ClassnameNotClass' => "%s ist kein %s.",
                        'ClassNotFoundException:MissingClass' => "Die Klasse '%s' was wurde nicht gefunden. Fehlendes Plugin?",
                        'InstallationException:TypeNotSupported' => "Der Typ %s wurd nicht unterst&uuml;tzt. Das kann an einem Fehler in der Installation liegen, der wahrscheinlich durch ein unvollst&auml;ndiges Update verursacht wurde.",

                        'ImportException:ImportFailed' => "Das Element %d konnte nicht importiert werden",
                        'ImportException:ProblemSaving' => "Beim Speichern von %s ist ein Problem aufgetreten.",
                        'ImportException:NoGUID' => "Neue Entit&auml;t wurde ohne GUID angelegt. Das sollte nicht passieren.",

                        'ImportException:GUIDNotFound' => "Die Entit&auml;t '%d' konnte nicht gefunden werden.",
                        'ImportException:ProblemUpdatingMeta' => "Beim Update von '%s' zur Entit&auml;t '%d' ist ein Fehler aufgetreten.",

                        'ExportException:NoSuchEntity' => "Kein solcher GUID f&uuml;r die Entit&auml;t vorhanden:%d",

                        'ImportException:NoODDElements' => "Beim Datenimport konnten keine OpenDD Elemente gefunden werden. Import fehlgeschlagen.",
                        'ImportException:NotAllImported' => "Es wurden nicht alle Elemente wurden importiert.",

                        'InvalidParameterException:UnrecognisedFileMode' => "Der Dateimodus '%s' wurde nicht erkannt.",
                        'InvalidParameterException:MissingOwner' => "F&uuml;r alle Dateien muss ein Eigent&uuml;mer angegeben werden!",
                        'IOException:CouldNotMake' => "%s konnte nicht durchgef&uuml;hrt werden.",
                        'IOException:MissingFileName' => "Du musst den Namen der Datei angeben, die Du &ouml;ffnen willst.",
                        'ClassNotFoundException:NotFoundNotSavedWithFile' => "Gespeicherte Datei wurde nicht gefunden, oder die Klasse wurde nicht mit Datei gespeichert!",
                        'NotificationException:NoNotificationMethod' => "Keine Methode zur Benachrichtigung angegeben.",
                        'NotificationException:NoHandlerFound' => "Keiner oder nicht aufrufbarer Handler '%s' .",
                        'NotificationException:ErrorNotifyingGuid' => "Bei der Benachrichtigung von %d ist ein Fehler aufgetreten.",
                        'NotificationException:NoEmailAddress' => "F&uuml;r GUID:%d konnte keine Emailadresse gefunden werden",
                        'NotificationException:MissingParameter' => "Fehlender Parameter '%s'",

                        'DatabaseException:WhereSetNonQuery' => "Die Where Abfrage enth&auml;lt eine Nicht-WhereQueryComponent",
                        'DatabaseException:SelectFieldsMissing' => "Fehlende Felder in der Select-Abfrage",
                        'DatabaseException:UnspecifiedQueryType' => "Nicht erkannter oder nicht angegebener Abfragetyp.",
                        'DatabaseException:NoTablesSpecified' => "F&uuml;r die Abfrage wurden keine Tabellen angegeben.",
                        'DatabaseException:NoACL' => "Zu der Abfrage wurde keine Zugangskontrolle angegeben.",

                        'InvalidParameterException:NoEntityFound' => "Keine Entit&auml;t gefunden. Entweder gibt es keine oder Du hast keine Zugangsberechtigung.",

                        'InvalidParameterException:GUIDNotFound' => "GUID:%s wurde nicht gefunden oder Du hast keinen Zugang.",
                        'InvalidParameterException:IdNotExistForGUID' => "'%s' gibt es fÃºr GUID:%d nicht",
                        'InvalidParameterException:CanNotExportType' => "Der Exporttyp f&uuml;r '%s' ist nicht klar.",
                        'InvalidParameterException:NoDataFound' => "Es wurden keine Daten gefunden.",
                        'InvalidParameterException:DoesNotBelong' => "Geh&ouml;rt nicht zur Entit&auml;t.",
                        'InvalidParameterException:DoesNotBelongOrRefer' => "Geh&ouml;rt nicht oder bezieht sich nicht auf die Entit&auml;t.",
                        'InvalidParameterException:MissingParameter' => "Fehlender Parameter, Du musst einen GUID angeben.",

                        'SecurityException:APIAccessDenied' => "Der API-Zugang wurde vom Administrator abgeschaltet.",
                        'SecurityException:NoAuthMethods' => "F&uuml;r diese API-Anfrage wurde keine Authentifikationsmethode angegeben.",
                        'APIException:ApiResultUnknown' => "Die API Anfrage lieferte als Ergebnis einen unbekannten Typ. Dies sollte nicht geschehen.",

                        'ConfigurationException:NoSiteID' => "Es wurde kein Site ID angegeben.",
                        'InvalidParameterException:UnrecognisedMethod' => "Nicht erkannter Methodenaufruf '%s'",
                        'APIException:MissingParameterInMethod' => "Fehlender Parameter %s in Methode %s",
                        'APIException:ParameterNotArray' => "%s scheint kein Array zu sein.",
                        'APIException:UnrecognisedTypeCast' => "Falscher Typ in Cast %s f&uuml;r die Variable  '%s' in Methode '%s'",
                        'APIException:InvalidParameter' => "Ung&uuml;ltiger Parameter f&uuml;r '%s' in der Method '%s' gefunden.",
                        'APIException:FunctionParseError' => "%s(%s) f&uuml;hrte zu einem Parserfehler.",
                        'APIException:FunctionNoReturn' => "%s(%s) hat keinen Wert zur&uuml;ckgegeben.",
                        'SecurityException:AuthTokenExpired' => "Fehlender, ung&uuml;ltiger oder abgelaufener Token bei der Authentifizierung.",
                        'CallException:InvalidCallMethod' => "%s muss mit '%s' aufgerufen werden",
                        'APIException:MethodCallNotImplemented' => "Der Methodenaufruf '%s' wurde nicht implementiert.",
                        'APIException:AlgorithmNotSupported' => "Der Algorythmus '%s' wird nicht unterst&uuml;tzt oder wurde deaktiviert.",
                        'ConfigurationException:CacheDirNotSet' => "Das Cacheverzeichnis 'cache_path' wurde nicht gesetzt.",
                        'APIException:NotGetOrPost' => "Die Requestmethode muss GET oder POST sein",
                        'APIException:MissingAPIKey' => "Fehlender X-Elgg-apikey HTTP header",
                        'APIException:MissingHmac' => "Fehlender  X-Elgg-hmac header",
                        'APIException:MissingHmacAlgo' => "Fehlender X-Elgg-hmac-algo header",
                        'APIException:MissingTime' => "Fehlender X-Elgg-time header",
                        'APIException:TemporalDrift' => "X-Elgg-time liegt zu weit in der Vergangenheit oder in der Zukunft.",
                        'APIException:NoQueryString' => "Keine Daten im Query String",
                        'APIException:MissingPOSTHash' => "Fehlender X-Elgg-posthash header",
                        'APIException:MissingPOSTAlgo' => "Fehlender X-Elgg-posthash_algo header",
                        'APIException:MissingContentType' => "Fehlender Inhaltstyp f&uuml;r Post-Daten.",
                        'SecurityException:InvalidPostHash' => "POST Daten Hash is ung&uuml;ltig - Erwartet wurde %s angegeben wurde %s.",
                        'SecurityException:DupePacket' => "Paketsignatur schon benutzt.",
                        'SecurityException:InvalidAPIKey' => "Ung&uuml;ltiger oder fehlender API Key.",
                        'NotImplementedException:CallMethodNotImplemented' => "Die Aufrufmethode '%s' wird gegenw&auml;rtig nicht unterst&uuml;tzt.",

                        'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC Methodenaufruf '%s' ist nicht implementiert.",
                        'InvalidParameterException:UnexpectedReturnFormat' => "Der Aufruf der Methode '%s' gab ein unerwartetes Ergebnis zur&uuml;ck.",
                        'CallException:NotRPCCall' => "Der Aufruf scheint kein g&uuml;ltiger XML-RPC Aufruf zu sein.",

                        'PluginException:NoPluginName' => "Der Pluginname konnte nicht gefunden werden.",

                /**
                 * User details
                 */

                        'name' => "Angezeigter Name",
                        'email' => "Emailadresse",
                        'username' => "Nutzername",
                        'password' => "Passwort",
                        'passwordagain' => "Passwort (Wiederholung)",
                        'admin_option' => "Dem Nutzer Administratorenrechte erteilen?",

                /**
                 * Access
                 */

                        'ACCESS_PRIVATE' => "Privat",
                        'ACCESS_LOGGED_IN' => "Eingeloggte Nutzer",
                        'ACCESS_PUBLIC' => "&Ouml;ffentlich",
                        'PRIVATE' => "Privat",
                        'LOGGED_IN' => "Eingeloggte Nutzer",
                        'PUBLIC' => "&Ouml;ffentlich",
                        'access' => "Zugang",

                /**
                 * Dashboard and widgets
                 */

                        'dashboard' => "Startseite",
                        'dashboard:nowidgets' => "Deine Startseite ist der Eingang zur Community. Klicke 'Seite bearbeiten' um Widgets hinzuzuf&uuml;gen, die Inhalte und Deine Aktivit&auml;t in diesem System dokumentieren.",

                        'widgets:add' => 'Widgets zu Deiner Seite hinzuf&uuml;gen',
                        'widgets:add:description' => "W&auml;hle aus der <b>Widget Gallerie</b> rechts aus, welche Elemente Du zu deiner Seite hinzuf&uuml;gen willst und ziehe diese mit der Maus in eine der drei unteren Widgetzonen. Du kannst die Widgets in der Position einordnen, in welcher sie dann auf Deiner Seite angezeigt werden sollen.

Um ein Widget wieder zu entfernen, ziehst Du es einfach zur&uuml;ck in die <b>Widget Gallerie</b>.",
                        'widgets:position:fixed' => '(Feste Position auf der Seite)',

                        'widgets' => "Widgets",
                        'widget' => "Widget",
                        'item:object:widget' => "Widgets",
                        'layout:customise' => "Layout &auml;ndern",
                        'widgets:gallery' => "Widget Gallerie",
                        'widgets:leftcolumn' => "Widgets Links",
                        'widgets:fixed' => "Feste Position",
                        'widgets:middlecolumn' => "Widgets Mitte",
                        'widgets:rightcolumn' => "Widgets Rechts",
                        'widgets:profilebox' => "Profilbox",
                        'widgets:panel:save:success' => "Deine Widgets wurden erfolgreich gespeichert.",
                        'widgets:panel:save:failure' => "Beim Speichern der Widgets trat ein Fehler auf. Bitte versuche es noch einmal.",
                        'widgets:save:success' => "Das Widget wurde erfolgreich gespeichert.",
                        'widgets:save:failure' => "Beim Speichern des Widgets trat ein Fehler auf. Bitte versuche es noch einmal.",


                /**
                 * Groups
                 */

                        'group' => "Gruppe",
                        'item:group' => "Gruppen",

                /**
                 * Profile
                 */

                        'profile' => "Profil",
                        'user' => "Nutzer",
                        'item:user' => "Nutzer",

                /**
                 * Profile menu items and titles
                 */

                        'profile:yours' => "Dein Profil",
                        'profile:user' => "Profil von %s",

                        'profile:edit' => "Profil bearbeiten",
                        'profile:editicon' => "Profilbild hochladen",
                        'profile:profilepictureinstructions' => "Das Profilbild ist das Bild, das auf Deiner Profilseite angezeigt wird. <br /> Du kannst es &auml;ndern, so oft Du willst. (Erlaubte Formate: GIF, JPG oder PNG)",
                        'profile:icon' => "Profilbild",
                        'profile:createicon' => "Avatar erstellen",
                        'profile:currentavatar' => "Dein Avatar",
                        'profile:createicon:header' => "Profilbild",
                        'profile:profilepicturecroppingtool' => "Profilbild ausschneiden",
                        'profile:createicon:instructions' => "Klicke auf das Bild und ziehe mit der Maus ein Quadrat, um dann den gew&uuml;nschten Ausschnitt zu w&auml;hlen. Eine Vorschau des Ausschnitts wird auf der rechten Seite angezeigt. Wenn Du mit dem Bildausschnitt zufrieden bist, klicke bitte auf 'Avatar erstellen'. Der Bildausschnitt wird dann auf der ganzen Seite als Dein Avatar benutzt. ",

                        'profile:editdetails' => "Details bearbeiten",
                        'profile:editicon' => "Avatar &auml;ndern",

                        'profile:aboutme' => "&Uuml;ber mich",
                        'profile:description' => "&Uuml;ber mich",
                        'profile:briefdescription' => "Kurzbeschreibung",
                        'profile:location' => "Wohnort",
                        'profile:skills' => "Was ich kann.",
                        'profile:interests' => "Interessen",
                        'profile:contactemail' => "Emailadresse",
                        'profile:phone' => "Telefon",
                        'profile:mobile' => "Handy",
                        'profile:website' => "Webseite",

                        'profile:river:update' => "Das Profil von %s wurde bearbeitet.",
                        'profile:river:iconupdate' => "Der Avatar von %s wurde bearbeitet.",

                /**
                 * Profile status messages
                 */

                        'profile:saved' => "Dein Profil wurde erfolgreich gespeichert.",
                        'profile:icon:uploaded' => "Dein Profilbild wurde erfolgreich hochgeladen.",

                /**
                 * Profile error messages
                 */

                        'profile:noaccess' => "Du bist nicht berechtigt, dieses Profil zu bearbeiten.",
                        'profile:notfound' => "Leider konnten wir das angegebene Profil nicht finden.",
                        'profile:cantedit' => "Du bist nicht berechtigt, dieses Profil zu bearbeiten.",
                        'profile:icon:notfound' => "Leider gab es ein Problem beim Hochladen von Deinem Profilbild.",

                /**
                 * Friends
                 */

                        'friends' => "Freunde",
                        'friends:yours' => "Deine Freunde",
                        'friends:owned' => "Freunde von %s",
                        'friend:add' => "Als Freund hinzuf&uuml;gen",
                        'friend:remove' => "Freund entfernen",

                        'friends:add:successful' => "Du hast %s erfolgreich als Freund hinzugef&uuml;gt.",
                        'friends:add:failure' => "Beim Versuch %s als ein Freund hinzuzuf&uuml;gen ist ein Fehler aufgetreten. Bitte versuche es noch einmal.",

                        'friends:remove:successful' => "Du hast %s erfolgreich aus der Liste Deiner Freunde gel&ouml;scht.",
                        'friends:remove:failure' => "Beim Versuch %s aus der Liste Deiner Freunde zu l&ouml;schen, ist ein Fehler aufgetreten. Bitte versuche es noch einmal.",

                        'friends:none' => "Dieser Nutzer hat bisher noch keine Freunde hinzugef&uuml;gt.",
                        'friends:none:you' => "Du hast noch keine Freunde hinzugef&uuml;gt! Suchen andere Nutzer mit &auml;hnlichen Interessen auf der Seite.",

                        'friends:none:found' => "Es wurden keine Freunde gefunden.",

                        'friends:of:none' => "Bisher hat keiner diesen Nutzer als Freund hinzugef&uuml;gt.",
                        'friends:of:none:you' => "Bisher hat Dich niemand als Freund hinzugef&uuml;gt. Gestalte Deine Profilseite, damit andere Dich finden k&ouml;nnen.!",

                        'friends:of' => "Freunde von",
                        'friends:of:owned' => "Leute, die %s als Freund haben.",

                         'friends:num_display' => "Anzahl der Freunde, die angezeigt werden sollen",
                         'friends:icon_size' => "Icongr&ouml;&szlig;e",
                         'friends:tiny' => "winzig",
                         'friends:small' => "klein",
                         'friends' => "Freunde",
                         'friends:of' => "Freunde von",
                         'friends:collections' => "Freundesgruppen",
                         'friends:collections:add' => "Neue Freundesgruppe",
                         'friends:addfriends' => "Freunde hinzuf&uuml;gen",
                         'friends:collectionname' => "Name der Freundesgruppe",
                         'friends:collectionfriends' => "Freunde in dieser Gruppe",
                         'friends:collectionedit' => "Freundesgruppe bearbeiten",
                         'friends:nocollections' => "Sie haben noch keine Freundesgruppen.",
                         'friends:collectiondeleted' => "Die Freundesgruppe wurde gel&ouml;scht.",
                         'friends:collectiondeletefailed' => "Die Freundesgruppe konnte leider nicht gel&ouml;scht werden. Entweder bist Du dazu nicht berechtigt oder es ist ein Fehler aufgetreten.",
                         'friends:collectionadded' => "Die neue Freundesgruppe wurde eingerichtet.",
                         'friends:nocollectionname' => "Um eine Freundesgruppe einzurichten musst Du einen Namen angeben.",

                'friends:river:created' => "%s hat ein Freunde-Widget hinzugef&uuml;gt.",
                'friends:river:updated' => "%s hat das Freunde-Widget aktualisiert.",
                'friends:river:delete' => "%s hat das Freunde-Widget entfernt.",
                'friends:river:add' => "%s hat einen Freund hinzugef&uuml;gt.",

                /**
                 * Feeds
                 */
                        'feed:rss' => 'Feed abonnieren',
                        'feed:odd' => 'OpenDD abonniert',

                /**
                 * River
                 */
                        'river' => "River",
                        'river:relationship:friend' => 'ist jetzt befreundet mit',

                /**
                 * Plugins
                 */
                        'plugins:settings:save:ok' => "Die Einstellungen f&uuml;r das %s Plugin wurden erfolgreich gespeichert.",
                        'plugins:settings:save:fail' => "Beim Speichern der Einstellungen f&uuml;r das %s Plugin ist ist ein Fehler aufgetreten.",
                        'plugins:usersettings:save:ok' => "Die Nutzereinstellungen f&uuml;r das %s Plugin wurden erfolgreich gespeichert.",
                        'plugins:usersettings:save:fail' => "Beim Speichern der Nutzereinstellungen f&uuml;r das %s Plugin ist ist ein Fehler aufgetreten.",

                /**
                 * Notifications
                 */
                        'notifications:usersettings' => "Einstellungen f&uuml;r Benachrichtigungen",
                        'notifications:methods' => "Bitte gebe an, welche Methoden Du erlauben willst...",

                        'notifications:usersettings:save:ok' => "Die Einstellungen f&uuml;r Benachrichtigungen wurden erfolgreich gespeichert.",
                        'notifications:usersettings:save:fail' => "Beim Speichern der  Einstellungen f&uuml;r Benachrichtigungen ist ist ein Fehler aufgetreten.",
                /**
                 * Search
                 */

                        'search' => "Suche",
                        'searchtitle' => "Suche: %s",
                        'users:searchtitle' => "Nutzersuche: %s",
                        'advancedsearchtitle' => "%s kombiniert mit den Suchergebnissen %s",
                        'notfound' => "Es wurden leider keine Ergebnisse gefunden.",
                        'next' => "Weiter",
                        'previous' => "Zur&uuml;ck",

                        'viewtype:change' => "Ansicht &auml;ndern",
                        'viewtype:list' => "Listenansicht",
                        'viewtype:gallery' => "Galerie",

                        'tag:search:startblurb' => "Elemente, die auf das Tag '%s' passen:",

                        'user:search:startblurb' => "Nutzer die zu '%s' gefunden wurden:",
                        'user:search:finishblurb' => "Um mehr anzuzeigen, hier klicken.",

                /**
                 * Account
                 */

                        'account' => "Nutzerkonto",
                        'settings' => "Einstellungen",

                        'register' => "Anmelden",
                        'registerok' => "Du wurdest bei %s erfolgreich angemeldet. Um Dein Nutzerkonto zu aktivieren, best&auml;tige bitte Deine Emailadresse, indem Du auf den Link in der Email klickst, die Ihnen soeben geschickt wurde.",
                        'registerbad' => "Deine Anmeldung war nicht erfolgreich. M&ouml;gliche Gr&uuml;nde: Den Nutzernamen gibt es schon, die Passw&ouml;rter waren nicht gleich oder aber der Nutzernamen oder das Passwort waren zu kurz.",
                        'registerdisabled' => "Die Anmeldung wurde vom Systemadministrator abgeschaltet.",

                        'registration:notemail' => 'Die angegebene Emailadresse scheint nicht g&uuml;ltig zu sein...',
                        'registration:userexists' => 'Diesen Nutzernamen gibt es leider bereits',
                        'registration:usernametooshort' => 'Der Nutzername muss mindestens vier Buchstaben lang sein.',
                        'registration:passwordtooshort' => 'Das Passwort muss mindestens sechs Buchstaben lang sein.',
                        'registration:dupeemail' => 'Diese Emailadresse wurde bereits f&uuml;r ein Nutzerkonto angegeben.',

                        'adduser' => "Nutzer hinzuf&uuml;gen",
                        'adduser:ok' => "Du hast erfolgreich einen neuen Nutzer hinzugef&uuml;gt.",
                        'adduser:bad' => "Der neue Nutzer konnte nicht eingerichtet werden.",

                        'item:object:reported_content' => "Bericht &uuml;ber folgende Objekte",

                        'user:set:name' => "Einstellungen zum Nutzernamen",
                        'user:name:label' => "Dein Name",
                        'user:name:success' => "Dein Nutzername wurde im System erfolgreich ge&auml;ndert.",
                        'user:name:fail' => "Dein Nutzername konnte nicht ge&auml;ndert werden.",

                        'user:set:password' => "Passwort f&uuml;r das Nutzerkonto",
                        'user:password:label' => "Dein neues Passwort",
                        'user:password2:label' => "Neues Passwort wiederholen",
                        'user:password:success' => "Das Passwort wurde ge&auml;ndert.",
                        'user:password:fail' => "Dein Passwort konnte nicht ge&auml;ndert werden.",
                        'user:password:fail:notsame' => "Die beiden Passw&ouml;rter stimmen nicht &uuml;berein!",
                        'user:password:fail:tooshort' => "Das Passwort ist zu kurz!",

                        'user:set:language' => "Spracheinstellungen",
                        'user:language:label' => "Deine Sprache",
                        'user:language:success' => "Deine Spracheinstellungen wurden ge&auml;ndert.",
                        'user:language:fail' => "Deine Spracheinstellungen konnten nicht ge&auml;ndert werden.",

                        'user:username:notfound' => 'Der Nutzername %s wurde nicht gefunden.',

                        'user:password:lost' => 'Passwort vergessen',
                        'user:password:resetreq:success' => 'Dir wurde soeben eine Email mit dem neuen Passwort geschickt.',
                        'user:password:resetreq:fail' => 'Das Passwort konnte nicht neu erstellt werden.',

                        'user:password:text' => 'F&uuml;r ein neues Passwort gibst Du bitte Deinen Nutzernamen ein. Wir schicken Dir per Email dann einen Link, mit dem Du die Anfrage eines neuen Passworts best&auml;tigen musst. Danach erh&auml;ltst Du eine Mail mit Deinem neuen Passwort.',

                /**
                 * Administration
                 */

                        'admin:configuration:success' => "Deine Einstellungen wurden gespeichert.",
                        'admin:configuration:fail' => "Deine Einstellungen konnten nicht gespeichert werden.",

                        'admin' => "Administration",
                        'admin:description' => "Die Admin-Seite erlaubt es Dir, alle Einstellungen des Systems von der Nutzerverwaltung bis hin zum Verhalten der Plugins zu kontrollieren. W&auml;hle unten eine Option um zu beginnen.",

                        'admin:user' => "Nutzerverwaltung",
                        'admin:user:description' => "Diese Adminseite erlaubt Dir die Nutzereinstellungen auf der Seite zu kontrollieren. W&auml;hle eine Option unten um zu beginnen.",
                        'admin:user:adduser:label' => "Klickehier, um einen neuen Nutzer hinzuzuf&uuml;gen...",
                        'admin:user:opt:linktext' => "Nutereinstellungen...",
                        'admin:user:opt:description' => "Nutereinstellungen und Informationen zu Nutzerkonten. ",

                        'admin:site' => "Seitenadministration",
                        'admin:site:description' => "Diese Adminseite erlaubt Dir die globlalen Einstellungen der Seite zu kontrollieren. W&auml;hle eine Option unten umd zu beginnen.",
                        'admin:site:opt:linktext' => "Seite konfigurieren...",
                        'admin:site:opt:description' => "Kontrolliere technische und nichttechnische Einstellungen. ",

                        'admin:plugins' => "Tool Verwaltung",
                        'admin:plugins:description' => "Diese Adminseite erlaubt Dir, die auf der Seite installierten Anwendungen zu kontrollieren.",
                        'admin:plugins:opt:linktext' => "Anwendungen konfigurieren...",
                        'admin:plugins:opt:description' => "Konfiguriere die Anwendungen, welche auf der Seite installiert sind... ",
                        'admin:plugins:label:author' => "Autor",
                        'admin:plugins:label:copyright' => "Copyright",
                        'admin:plugins:label:licence' => "Lizenz",
                        'admin:plugins:label:website' => "URL",
                        'admin:plugins:disable:yes' => "Das Plugin %s wurde erfolgreich deaktiviert.",
                        'admin:plugins:disable:no' => "Das Plugin %s konnte nicht deaktiviert werden.",
                        'admin:plugins:enable:yes' => "Das Plugin %s wurde erfolgreich aktiviert.",
                        'admin:plugins:enable:no' => "Das Plugin %s konnte nicht aktiviert werden.",

                        'admin:statistics' => "Statistik",
                        'admin:statistics:description' => "Auf dieser Seite erh&auml;tst Du einen Statistik&uuml;berblick. Falls Du detailliertere Statistiken brauchst, steht Dir auch ein professionelles Administrations-Feature zur Verf&uuml;gung.",
                        'admin:statistics:opt:description' => "Statistische Informationen &uuml;ber Nutzer und Objekte auf Deiner Seite.",
                        'admin:statistics:opt:linktext' => "Statistiken einsehen...",
                        'admin:statistics:label:basic' => "Allgemeine Statistik der Seite",
                        'admin:statistics:label:numentities' => "Einheiten auf der Seite",
                        'admin:statistics:label:numusers' => "Zahl der Nutzer",
                        'admin:statistics:label:numonline' => "Nutzer online",
                        'admin:statistics:label:onlineusers' => "Jetzt online",
                        'admin:statistics:label:version' => "Elgg Version",
                        'admin:statistics:label:version:release' => "Release",
                        'admin:statistics:label:version:version' => "Version",

                        'admin:user:label:search' => "Nutzer suchen:",
                        'admin:user:label:seachbutton' => "Suche",

                        'admin:user:ban:no' => "Nutzerkonto kann nicht gesperrt werden",
                        'admin:user:ban:yes' => "Nutzerkonto gesperrt.",
                        'admin:user:unban:no' => "Nutzerkonto kann nicht freigeschaltet werden",
                        'admin:user:unban:yes' => "Nutzerkonto freigeschaltet.",
                        'admin:user:delete:no' => "Nutzerkonto kann nicht gel&ouml;scht werden",
                        'admin:user:delete:yes' => "Nutzerkonto gel&ouml;scht",

                        'admin:user:resetpassword:yes' => "Das Passwort wurde neu gesetzt, der Nutzer wurde benachrichtigt.",
                        'admin:user:resetpassword:no' => "Das Passwort konnte nicht neu gesetzt werden.",

                        'admin:user:makeadmin:yes' => "Der Nutzer hat nun Administratorenrechte.",
                        'admin:user:makeadmin:no' => "Dem Nutzer konnten keine Administratorenrechte erteilt werden.",

                /**
                 * User settings
                 */
                        'usersettings:description' => "Unter Nutzereinstellungen kannst Du alle pers&ouml;nlichen Einstellungen vom Nutzermanagment bis zum Verhalten der Plugins kontrollieren. W&auml;hle unten eine der Optionen.",

                        'usersettings:statistics' => "Deine Statistik",
                        'usersettings:statistics:opt:description' => "Statistische Information &uuml;ber Nutzer und Objekte auf Deiner Seite.",
                        'usersettings:statistics:opt:linktext' => "Statistik f&uuml;r das Nutzerkonto",

                        'usersettings:user' => "Deine Einstellungen",
                        'usersettings:user:opt:description' => "Hier kannst Du die Nutzereinstellungen kontrollieren.",
                        'usersettings:user:opt:linktext' => "Einstellungen &auml;ndern",

                        'usersettings:plugins' => "Anwendungen",
                        'usersettings:plugins:opt:description' => "Einstellungen f&uuml;r die aktiven Anwendungen.",
                        'usersettings:plugins:opt:linktext' => "Konfiguriere Deine Anwendungen...",

                        'usersettings:plugins:description' => "Auf dieser Seite kannst Du die pers&ouml;nlichen Einstellungen f&uuml;r die Tools, die vom Administrator f&uuml;r Dich installiert wurden, kontrollieren und konfigurieren.",
                        'usersettings:statistics:label:numentities' => "Deine Objekte",

                        'usersettings:statistics:yourdetails' => "Deine Details",
                        'usersettings:statistics:label:name' => "Voller Name",
                        'usersettings:statistics:label:email' => "Emailadresse",
                        'usersettings:statistics:label:membersince' => "Angemeldet seit",
                        'usersettings:statistics:label:lastlogin' => "Zuletzt online",



                /**
                 * Generic action words
                 */

                        'save' => "Speichern",
                        'cancel' => "Abbrechen",
                        'saving' => "Speichern ...",
                        'update' => "Aktualisieren",
                        'edit' => "Bearbeiten",
                        'delete' => "L&ouml;schen",
                        'load' => "Laden",
                        'upload' => "Hochladen",
                        'ban' => "Sperren",
                        'unban' => "Freischalten",
                        'enable' => "Aktivieren",
                        'disable' => "Deaktivieren",
                        'request' => "Anfrage",

                        'invite' => "Einladung",

                        'resetpassword' => "Passwort &auml;ndern",
                        'makeadmin' => "Adminrechte geben",

                        'option:yes' => "Ja",
                        'option:no' => "Nein",

                        'unknown' => 'Unbekannt',

                        'learnmore' => "Mehr Informationen.",

                        'content' => "Inhalt",
                        'content:latest' => 'Neuste Aktivit&auml;ten',
                        'content:latest:blurb' => 'Alternativ kannst Du hier die neusten Inhalte der ganzen Seite sehen.',

                /**
                 * Generic data words
                 */

                        'title' => "Titel",
                        'description' => "Beschreibung",
                        'tags' => "Tags",
                        'spotlight' => "Spotlight",
                        'all' => "Alle",

                        'by' => 'von',

                        'annotations' => "Anmerkungen",
                        'relationships' => "Beziehungen",
                        'metadata' => "Metadaten",

                /**
                 * Input / output strings
                 */

                        'deleteconfirm' => "Bist du sicher, dass Du diesen Eintrag l&ouml;schen willst?",
                        'fileexists' => "Die Datei wurde bereits hochgeladen. Wähle unten, welche ersetzt werden soll:",

                /**
                 * Import / export
                 */
                        'importsuccess' => "Die Daten wurden erfolgreich importiert.",
                        'importfail' => "OpenDD Import der Daten ist fehlgeschlagen.",

                /**
                 * Time
                 */

                        'friendlytime:justnow' => "jetzt gerade",
                        'friendlytime:minutes' => "vor %s Minuten",
                        'friendlytime:minutes:singular' => "vor einer Minute",
                        'friendlytime:hours' => "vor %s Stunden",
                        'friendlytime:hours:singular' => "vor einer Stunde",
                        'friendlytime:days' => "vor %s Tagen",
                        'friendlytime:days:singular' => "gestern",

                /**
                 * Installation und Systemeinstellungen
                 */

                        'installation:error:htaccess' => "Elgg braucht eine Datei mit dem Namen .htaccess im Root-Verzeichnis der Installation. Das Installationsprogramm hat versucht diese Datei einzurichten, aber Elgg hat keine Schreibrechte in dem Verzeichnis.

Du kannst diese Datei leicht einrichten. Kopiere den Inhalt aus dem Textfeld unten in einen Texteditor und speicher die Datei als .htaccess in deinem Root-Verzeichnis.

",
                        'installation:error:settings' => "Elgg konnte die Datei mit den Einstellungen nicht finden. Die meisten Einstellungen von Elgg kannst Du pers&ouml;nlich steuern, aber wir brauchen die Details f&uuml;r Deine Datenbankverbindung. Mache bitte folgendes:

1. &Auml;nder den Namen der Datei engine/settings.example.php in Ihrer Elgg-Installation zu settings.php.

2. &Ouml;ffne die Datei in einem Texteditor und gebe die Daten f&uuml;r Deine MySQL Datenbank ein. Wenn Du diese nicht kennst, frage Deinen Systemadministrator oder bitte den technischen Support um Hilfe.

Alternativ kannst Du die Datenbankverbindung unten eingeben und das Installationsprogramm versucht diesen Schritt f&uuml;r Dich durchzuf&uuml;hren ...",

                        'installation:error:configuration' => "Korrigiere die Einstellungen und aktualisiere die Seite.",

                        'installation' => "Installation",
                        'installation:success' => "Die Datenbank von Elgg wurde erfolgreich installiert.",
                        'installation:configuration:success' => "Die Einstellungen wurden gespeichert. Melde nun den ersten Nutzer an. Er wird auch der erste Administrator der Seite sein.",

                        'installation:settings' => "Systemeinstellungen",
                        'installation:settings:description' => "Die Elgg-Datenbank wurde erfolgreich installiert. Nun musst Du noch einige weitere Informationen eingeben, um die Seite vollst&auml;ndig einzurichten. Das Installationsprogramm versucht, die notwendigen Daten selbst herauszufinden, aber vielleicht m&uuml;ssen einige der Details korrigiert werden.",

                        'installation:settings:dbwizard:prompt' => "Gebe die Datenbankverbindung ein und klicke speichern:",
                        'installation:settings:dbwizard:label:user' => "Datenbanknutzer",
                        'installation:settings:dbwizard:label:pass' => "Datenbankpasswort",
                        'installation:settings:dbwizard:label:dbname' => "Elggdatenbank",
                        'installation:settings:dbwizard:label:host' => "Datenbankhost (normalerweise 'localhost')",
                        'installation:settings:dbwizard:label:prefix' => "Datenbanktabellenpr&auml;fix (normalerweise 'elgg')",

                        'installation:settings:dbwizard:savefail' => "Die neue Datei settings.php konnte nicht gespeichert werden. Bitte speicher die folgende Datei als engine/settings.php in einem Texteditor.",

                        'sitename' => "Name f&uuml;r Deine Seite (eg \"Mein soziales Netzwerk\"):",
                        'sitedescription' => "Kurzbeschreibung f&uuml;r Deine Seite (optional)",
                        'wwwroot' => "URL zu Deiner Seite, mit Schr&auml;gstrich abgeschlossen:",
                        'path' => "Voller Pfad zu Deiner Installation, abgeschlossen mit einem Schr&auml;gstrich:",
                        'dataroot' => "Voller Pfad zum Verzeichnis in dem die hochgeladenen Dateien gespeichert werden, abgeschlossen mit einem Schr&auml;gstrich:",
                        'language' => "Sprache f&uuml;r Deine Seite:",
                        'debug' => "Der Debug-Modus gibt Dir zus&auml;tzliche Informationen, die bei der Behebung von Fehlern hilfreich sein k&ouml;nnen. Allerdings l&auml;uft Ihr System dadurch langsamer. Daher sollte dieser Modus nur aktiviert werden, wenn Du Probleme hast:",
                        'debug:label' => "Debug Modus aktivieren",
                        'usage' => "Diese Option erlaubt es Elgg anonyme Statistiken &uuml;ber die Nutzung an Curverider zu &uuml;bermitteln.",
                        'usage:label' => "Anonyme Statistiken &uuml;ber die Nutzung &uuml;bermitteln",
                        'view' => "Geben Sie an, welche Standardansicht f&uuml;r Ihre Seite benutzt werden soll (z.B mobil) oder lassen Sie den Eintrag leer, um die Standardansicht zu verwenden:",

                /**
                 * Welcome
                 */

                        'welcome' => "Wilkommen %s",
                        'welcome_message' => "Wilkommen zur Installation von Elgg.",

                /**
                 * Emails
                 */
                        'email:settings' => "Emaileinstellungen",
                        'email:address:label' => "Ihre Emailadresse",

                        'email:save:success' => "Die neue Emailadresse wurde gespeichert, eine Best&auml;tigungsanfrage wurde verschickt.",
                        'email:save:fail' => "Deine neue Emailadresse konnte nicht gespeichert werden.",

                        'email:confirm:success' => "Die Emailadresse wurde best&auml;tigt!",
                        'email:confirm:fail' => "Die angegebene Emailadresse konnte nicht best&auml;tigt werden ...",

                        'friend:newfriend:subject' => "%s hat Dich als Freund hinzugef&uuml;gt!",
                        'friend:newfriend:body' => "%s hat Dich als Freund hinzugef&uuml;gt!

Hier kommst Du zur Profilseite:

        %s

Nicht auf diese Email antworten.",


                        'email:validate:subject' => "%s bitte best&auml;tige Deine Emailadresse!",
                        'email:validate:body' => "Hallo %s,

Bitte best&auml;tige Deine Emailadresse, indem Du unten auf den Link klickst:

%s
",
                        'email:validate:success:subject' => "Die Emailadresse wurde best&auml;tigt %s!",
                        'email:validate:success:body' => "Hallo %s,

Deine Emailadresse wurde erfolgreich best&auml;tigt.",


                        'email:resetpassword:subject' => "Neues Passwort!",
                        'email:resetpassword:body' => "Hallo %s,

Dein neues Passwort ist: %s",


                        'email:resetreq:subject' => "Anfrage f&uuml;r ein neues Passwort.",
                        'email:resetreq:body' => "Hallo %s,

Jemand hat (von der IP Adresse %s aus) ein neuese Passwort f&uuml;r Dein Nutzerkonto angefordert.

Wenn Du dieses Passwort angefordert hast, klicken Sie auf den Link unten und es wird dir erneut eine Email mit einem vorläufigen Passwort an Dich geschickt. 
Ansonsten mache bitte nichts weiter.

%s
",


                /**
                 * XML-RPC
                 */
                        'xmlrpc:noinputdata'        =>        "Keine Daten",

                /**
                 * Comments
                 */

                        'comments:count' => "%s Kommentare",
                        'generic_comments:add' => "Einen Kommentar schreiben",
                        'generic_comments:text' => "Kommentar",
                        'generic_comment:posted' => "Dein Kommentar wurde abgeschickt.",
                        'generic_comment:deleted' => "Dein Kommentar wurde gel&ouml;scht.",
                        'generic_comment:blank' => "Entschuldigung, aber Du musst erst etwas schreiben, bevor Du den Kommentar speichern kannst.",
                        'generic_comment:notfound' => "Wir konnten den angegebenen Eintrag leider nicht finden.",
                        'generic_comment:notdeleted' => "Der Kommentar konnte leider nicht gel&ouml;scht werden.",
                        'generic_comment:failure' => "Beim Speichern des Kommentars ist ein Fehler aufgetreten. Bitte versuche es nochmal.",

                        'generic_comment:email:subject' => 'Du hast einen neuen Kommentar!',
                        'generic_comment:email:body' => "Du hast zum Eintrag \"%s\" einen neuen Kommentar von %s bekommen. Hier der Text:


%s


Wenn Du antworten oder den Orginaleintrag sehen willst, klicke bitte hier:

        %s

Um das Profil von %s zu sehen, klickebitte hier:

        %s

Bitte nicht auf diese Mail anworten!",

                /**
                 * Entities
                 */
                        'entity:default:strapline' => '%s geschrieben von %s',
                        'entity:default:missingsupport:popup' => 'Dieser Eintrag kann nicht angezeigt werden. Das k&ouml;nnte daran liegen, dass ein Plugin ben&ouml;tigt wird, das deinstalliert wurde..',

                        'entity:delete:success' => 'Der Eintrag %s wurde gel&ouml;scht',
                        'entity:delete:fail' => 'Der Eintrag %s konnte nicht gel&ouml;scht werden',


                /**
                 * Action gatekeeper
                 */
                        'actiongatekeeper:missingfields' => 'Im Formular fehlen die Felder __token oder __ts ',
                        'actiongatekeeper:tokeninvalid' => 'Der im Formular mitgeschickte Schl&uuml;ssel entspricht nicht dem auf dem Server hinterlegten.',
                        'actiongatekeeper:timeerror' => 'Das Formular ist abgelaufen. Bitte aktualisiere die Seite und versuche es noch einmal.',
                        'actiongatekeeper:pluginprevents' => 'Eine Erweiterung verhindert, dass das Formular korrekt abgeschickt werden kann.',

                /**
                 * Languages according to ISO 639-1
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
"be" => "Belorussisch",
"bg" => "Bulgarisch",
"bh" => "Biharisch",
"bi" => "Bislamisch",
"bn" => "Bengalisch",
"bo" => "Tibetanisch",
"br" => "Bretonisch",
"ca" => "Katalanisch",
"co" => "Korsisch",
"cs" => "Tschechisch",
"cy" => "Walisisch",
"da" => "D&auml;nisch",
"de" => "Deutsch",
"dz" => "Dzongkha, Bhutani",
"el" => "Griechisch",
"en" => "Englisch",
"eo" => "Esperanto",
"es" => "Spanisch",
"et" => "Estnisch",
"eu" => "Baskisch",
"fa" => "Persisch",
"fi" => "Finnisch",
"fj" => "Fiji",
"fo" => "F&auml;r&ouml;isch",
"fr" => "Franz&ouml;sisch",
"fy" => "Friesisch",
"ga" => "Irisch",
"gd" => "Schottisches G&auml;lisch",
"gl" => "Galizisch",
"gn" => "Guarani",
"gu" => "Gujaratisch",
"ha" => "Haussa",
"he" => "Hebr&auml;isch",
"hi" => "Hindi",
"hr" => "Kroatisch",
"hu" => "Ungarisch",
"hy" => "Armenisch",
"ia" => "Interlingua",
"id" => "Indonesisch",
"ie" => "Interlingue",
"ik" => "Inupiak",
"in" => "Indonesisch",
"is" => "Isl&auml;ndisch",
"it" => "Italienisch",
"iu" => "Inuktitut",
"ja" => "Japanisch",
"ji" => "Jiddish (veraltet, nun: yi)",
"jw" => "Javanisch",
"ka" => "Georgisch",
"kk" => "Kasachisch",
"kl" => "Kalaallisut (Gr&ouml;nl&auml;ndisch)",
"km" => "Kambodschanisch",
"kn" => "Kannada",
"ko" => "Koreanisch",
"ks" => "Kaschmirisch",
"ku" => "Kurdisch",
"ky" => "Kirgisisch",
"la" => "Lateinisch",
"ln" => "Lingala",
"lo" => "Laotisch",
"lt" => "Litauisch",
"lv" => "Lettisch",
"mg" => "Malagasisch",
"mi" => "Maorisch",
"mk" => "Mazedonisch",
"ml" => "Malajalam",
"mn" => "Mongolisch",
"mo" => "Moldawisch",
"mr" => "Marathi",
"mt" => "Maltesisch",
"my" => "Burmesisch",
"na" => "Nauruisch",
"ne" => "Nepalesisch",
"nl" => "Holl&auml;ndisch",
"no" => "Norwegisch",
"oc" => "Okzitanisch",
"om" => "Oromo",
"or" => "Oriya",
"pa" => "Pundjabisch",
"pl" => "Polnisch",
"ps" => "Paschtu",
"pt" => "Portugiesisch",
"qu" => "Quechua",
"rm" => "R&auml;toromanisch",
"rn" => "Kirundisch",
"ro" => "Rum&auml;nisch",
"ru" => "Russisch",
"rw" => "Kijarwanda",
"sa" => "Sanskrit",
"sd" => "Zinti",
"sg" => "Sango",
"sh" => "Serbokroatisch (veraltet)",
"si" => "Singhalesisch",
"sk" => "Slowakisch",
"sl" => "Slowenisch",
"sm" => "Samoanisch",
"sn" => "Schonisch",
"so" => "Somalisch",
"sq" => "Albanisch",
"sr" => "Serbisch",
"ss" => "Swasil&auml;ndisch",
"st" => "Sesothisch",
"su" => "Sudanesisch",
"sv" => "Schwedisch",
"sw" => "Suaheli",
"ta" => "Tamilisch",
"te" => "Tegulu",
"tg" => "Tadschikisch",
"th" => "Thai",
"ti" => "Tigrinja",
"tk" => "Turkmenisch",
"tl" => "Tagalog",
"tn" => "Sezuan",
"to" => "Tongaisch",
"tr" => "T&uuml;rkisch",
"ts" => "Tsongaisch",
"tt" => "Tatarisch",
"tw" => "Twi",
"ug" => "Uigur",
"uk" => "Ukrainisch",
"ur" => "Urdu",
"uz" => "Usbekisch",
"vi" => "Vietnamesisch",
"vo" => "Volap&uuml;k",
"wo" => "Wolof",
"xh" => "Xhosa",
"yi"  => "Jiddish",
"yo" => "Joruba",
"za" => "Zhuang",
"zh" => "Chinesisch",
"zu" => "Zulu",
        );


add_translation("de",$german);
