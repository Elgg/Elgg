<?php
return array(
	'install:title' => 'Elgg-Installation',
	'install:welcome' => 'Wilkommen',
	'install:requirements' => 'Überprüfung der Systemvoraussetzungen',
	'install:database' => 'Installation der Datenbank',
	'install:settings' => 'Konfiguration der Seite',
	'install:admin' => 'Erstellung des Administrator-Accounts',
	'install:complete' => 'Fertig',

	'install:next' => 'Weiter',
	'install:refresh' => 'Neu laden',

	'install:welcome:instructions' => "Die Installation von Elgg besteht aus 6 einfachen Schritten und der erste Schritt davon ist das Lesen dieser Begrüßung!

Wenn Du es nicht bereits getan hast, lies bitte die mitgelieferten Installations-Hinweise (oder rufe die Hinweise auf indem Du dem Link am Ende dieser Seite folgst).

Wenn Du bereits bist, um fortzufahren, klicke auf den 'Weiter'-Knopf.",
	'install:requirements:instructions:success' => "Dein Server hat die Überprüfung der Systemvoraussetzungen bestanden.",
	'install:requirements:instructions:failure' => "Dein Server erfüllt nicht alle notwendigen Systemvoraussetzungen. Nachdem Du die im folgenden aufgelisteten Probleme beseitigt hast, lade diese Seite erneut. Folge den Links am Ende der Seite, um weitere Informationen zu möglichen Problemlösungen zu erhalten.",
	'install:requirements:instructions:warning' => "Dein Server hat die Überprüfung der Systemvoraussetzungen bestanden, aber es gab mindestens eine Warnmeldung. Wir empfehlen, dass Du die Hinweise zu Installationsproblemen liest, um mehr darüber zu erfahren.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Webserver',
	'install:require:settings' => 'Konfigurationsdatei',
	'install:require:database' => 'Datenbank',

	'install:check:root' => 'Aufgrund fehlender Schreibberechtigung ist es leider nicht möglich, auf Deinem Server im Hauptverzeichnis der Elgg-Installation die Datei .htaccess automatisch zu erzeugen. Du hast zwei Möglichkeiten:

		1. Ändere (nur während der Installation!) die Schreibberechtigung für das Elgg-Hauptverzeichnis,

		2. Lege die Datei .htaccess im Elgg-Hauptverzeichnis selbst an, indem Du sie aus der Datei vendor/elgg/elgg/install/config/htaccess.dist durch Kopieren und Umbenennen erzeugst.',

	'install:check:php:version' => 'Elgg benötigt PHP in Version %s oder neuer. Dieser Server verwendet Version %s.',
	'install:check:php:extension' => 'Elgg benötigt die PHP-Erweiterung %s.',
	'install:check:php:extension:recommend' => 'Es wird empfohlen, dass die PHP-Erweiterung %s auf dem Server installiert ist.',
	'install:check:php:open_basedir' => 'Die auf dem Server gesetzte PHP-Einstellung für open_basedir verhindert möglicherweise, dass Elgg Daten in seinem Datenverzeichnis speichern kann.',
	'install:check:php:safe_mode' => 'Es wird nicht empfohlen, PHP im Safe-Mode zu verwenden, da dies zu Problemen mit Elgg führen kann.',
	'install:check:php:arg_separator' => 'Das von PHP verwendete Trennzeichen arg_separator.output muss \'&\' sein, damit Elgg einwandfrei funktioniert. Das eingestellte Trennzeichen auf Deinem Server ist aber \'%s\'',
	'install:check:php:register_globals' => 'Register globals muss auf dem Server ausgeschaltet sein, d.h. der Wert der PHP-Variable register_globals muss \'0\' sein.',
	'install:check:php:session.auto_start' => "Auf dem Server muss session.auto_start ausgeschaltet sein, damit Elgg einwandfrei funktioniert. Ändere diese Einstellung entweder in der PHP-Konfiguration php.ini oder setze den Wert dieser PHP-Variable in der .htaccess-Datei von Elgg.",

	'install:check:installdir' => 'Aufgrund fehlender Schreibberechtigung ist es leider nicht möglich, auf Deinem Server im Installationsverzeichnis von Elgg die Datei settings.php zu erzeugen. Du hast zwei Möglichkeiten:

		1. Ändere (nur während der Installation!) die Schreibberechtigung für das Verzeichnis elgg-config Deiner Elgg-Installation,

		2. Lege die Datei settings.php selbst an, indem Du die Datei %s/settings.example.php in das Verzeichnis elgg-config kopierst und zu settings.php umbenennst. Folge dann den Anweisungen in dieser Datei, um die Verbindungsparameter für Deine Datenbank einzutragen.',
	'install:check:readsettings' => 'Im Installationsverzeichnis von Elgg ist eine Konfigurationsdatei namens settings.php vorhanden, aber es fehlt die notwendige Leseberechtigung. Du kannst entweder die Datei löschen, damit sie neu angelegt werden kann oder Du kannst die Leseberechtigungen der Datei anpassen.',

	'install:check:php:success' => "Die PHP-Konfiguration auf Deinem Server erfüllt alle notwendigen Voraussetzungen für Elgg.",
	'install:check:rewrite:success' => 'Die Überprüfung der konfigurierten rewrite-Regeln war erfolgreich.',
	'install:check:database' => 'Die Voraussetzungen für den Datenbank-Server werden überprüft, sobald Elgg seine Datenbank lädt.',

	'install:database:instructions' => "Wenn Du nicht bereits eine Datenbank für Elgg angelegt hast, mache dies bitte jetzt. Trage dann die von Dir gewählten Verbindungsparameter unten ein, damit Elgg die Datenbank initialisieren kann.",
	'install:database:error' => 'Bei der Initialisierung der Datenbank ist ein Problem aufgetreten und die Installation kann nicht fortgesetzt werden. Lies bitte die oben angezeigte Meldung und korrigiere die fehlerhaften Einstellungen. Wenn Du weitere Hilfe dafür benötigst, folge dem Link am Ende der Seite, um weitere Informationen zu möglichen Problemlösungen zu erhalten oder bitte in den Elgg-Community-Foren um Hilfe.',

	'install:database:label:dbuser' =>  'Datenbank-Benutzername',
	'install:database:label:dbpassword' => 'Datenbank-Passwort',
	'install:database:label:dbname' => 'Datenbank-Name',
	'install:database:label:dbhost' => 'Datenbank-Host',
	'install:database:label:dbprefix' => 'Tabellen-Prefix',
	'install:database:label:timezone' => "Zeitzone",

	'install:database:help:dbuser' => 'Der Benutzername des MySQL-Accounts, der alle notwendigen Zugriffsprivilegien für die von Elgg zu verwendende Datenbank hat.',
	'install:database:help:dbpassword' => 'Das Passwort für den zu verwendeten Benutzeraccount.',
	'install:database:help:dbname' => 'Der Name der von zu verwendenden Datenbank.',
	'install:database:help:dbhost' => 'Der Hostname des MySQL-Servers (normalerweise \'localhost\').',
	'install:database:help:dbprefix' => "Das Tabellen-Prefix, das bei allen Elgg-Tabellen in der Datenbank gesetzt wird (normalerweise \'elgg_\').",
	'install:database:help:timezone' => "Die Zeitzone, die von der Elggseite standardmäßig verwendet werden soll.",

	'install:settings:instructions' => 'We need some information about the site as we configure Elgg. If you haven\'t <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">created a data directory</a> for Elgg, you need to do so now.',

	'install:settings:label:sitename' => 'Seiten-Name',
	'install:settings:label:siteemail' => 'Email-Adresse',
	'install:database:label:wwwroot' => 'URL der Seite',
	'install:settings:label:path' => 'Elgg-Installationsverzeichnis',
	'install:database:label:dataroot' => 'Datenverzeichnis',
	'install:settings:label:language' => 'Sprache der Seite',
	'install:settings:label:siteaccess' => 'Standard-Zugangslevel',
	'install:label:combo:dataroot' => 'Datenverzeichnis von Elgg anlegen lassen',

	'install:settings:help:sitename' => 'Der Name Deiner neuen Elgg Community-Seite.',
	'install:settings:help:siteemail' => 'Die Email-Adresse die von Elgg für die Kommunikation mit Benutzern verwendet wird.',
	'install:database:help:wwwroot' => 'Die Adresse der Community-Seite (Elgg kann sie in den meisten Fällen korrekt erkennen).',
	'install:settings:help:path' => 'Das Verzeichnis auf dem Server, in das Du die Elgg-Installationsdateien kopiert hast (Elgg kann es in den meisten Fällen korrekt erkennen).',
	'install:database:help:dataroot' => 'Das Verzeichnis, das Du auf dem Server angelegt hast, in dem Elgg hochgeladene Dateien speichern kann (die Zugriffrechte werden beim Klicken auf den "Weiter"-Knopf überprüft). Es muss ein vollständiger (absoluter) Pfad eingegeben werden.',
	'install:settings:help:dataroot:apache' => 'Du hast die Wahl, Elgg das Datenverzeichnis für die Speicherung von Benutzer-Dateien anlegen zu lassen oder den Pfad zum bereits angelegten Datenverzeichnis einzugeben (die Zugriffrechte dieses Verzeichnisses werden beim Klicken auf den "Weiter"-Knopf überprüft).',
	'install:settings:help:language' => 'Die Standardsprache für Deine Community-Seite.',
	'install:settings:help:siteaccess' => 'Der Standard-Zugangslevel für neu von Benutzern erzeugte Inhalte.',

	'install:admin:instructions' => "Nun ist es Zeit, einen Administrator-Account anzulegen.",

	'install:admin:label:displayname' => 'Name',
	'install:admin:label:email' => 'Email-Adresse',
	'install:admin:label:username' => 'Benutzername',
	'install:admin:label:password1' => 'Passwort',
	'install:admin:label:password2' => 'Passwort',

	'install:admin:help:displayname' => 'Der Name, der auf der Community-Seite für diesen Benutzeraccount angezeigt wird.',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Benutzername des Account, der für die Anmeldung verwendet wird.',
	'install:admin:help:password1' => "Das Passwort muss mindestens %u Zeichen lang sein.",
	'install:admin:help:password2' => 'Gebe das Password zur Bestätigung erneut ein.',

	'install:admin:password:mismatch' => 'Passwort muss bei beiden Eingaben übereinstimmen.',
	'install:admin:password:empty' => 'Das Passwort-Feld muss ausgefüllt werden.',
	'install:admin:password:tooshort' => 'Das eingegebene Passwort ist zu kurz.',
	'install:admin:cannot_create' => 'Die Erzeugung des Administrator-Accounts ist fehlgeschlagen.',

	'install:complete:instructions' => 'Die Installation Deiner Elgg Community-Seite ist nun abgeschlossen. Klicke auf den Knopf unten, um zur Startseite weitergeleitet zu werden.',
	'install:complete:gotosite' => 'Zur Seite gehen',
	'install:complete:admin_notice' => 'Willkommen auf Deiner Elgg-Community-Seite! Für weitere Optionen siehe %s.',
	'install:complete:admin_notice:link_text' => 'Seiteneinstellungen',

	'InstallationException:UnknownStep' => '%s ist ein unbekannter Installationsschritt.',
	'InstallationException:MissingLibrary' => 'Das Laden von %s ist fehlgeschlagen.',
	'InstallationException:CannotLoadSettings' => 'Elgg konnte die settings-Datei nicht laden. Entweder existiert die Datei nicht oder es gibt ein Problem aufgrund der Zugriffsrechte, die für die Datei gesetzt sind.',

	'install:success:database' => 'Die Datenbank wurde initialisiert.',
	'install:success:settings' => 'Die Seiten-Einstellungen wurden gespeichert.',
	'install:success:admin' => 'Der Administrator-Account wurde angelegt.',

	'install:error:htaccess' => 'Das Erzeugen der .htaccess-Datei ist fehlgeschlagen.',
	'install:error:settings' => 'Die settings-Datei konnte nicht erzeugt werden.',
	'install:error:settings_mismatch' => 'Der Wert von "%s" in settings.php stimmt nicht mit dem Wert im $params-Array überein.',
	'install:error:databasesettings' => 'Elgg konnte mit den eingegebenen Verbindungsparametern keine Verbindung mit der Datenbank herstellen.',
	'install:error:database_prefix' => 'Das eingegebene Tabellen-Prefix enthält unzulässige Zeichen.',
	'install:error:oldmysql2' => 'Voraussetzung für Elgg ist MySQL in Version 5.5.3 oder neuer. Dein Server verwendet Version %s.',
	'install:error:nodatabase' => 'Der Zugriff auf die Datenbank %s ist nicht möglich. Möglicherweise ist die Datenbank nicht vorhanden.',
	'install:error:cannotloadtables' => 'Der Zugriff auf die Tabellen der Datenbank ist nicht möglich.',
	'install:error:tables_exist' => 'In der Datenbank ist bereits eine Elgg-Tabellenstruktur vorhanden. Du mußt entweder diese Tabellen aus der Datenbank löschen oder die Installation neu starten. Bei einem Neustart kann versucht werden, die bestehende Tabellenstruktur zu verwenden. Um die Installation neu zu starten, entferne \'?step=database\' aus der URL in der Adressleiste Deines Browsers und drücke \'Enter\'.',
	'install:error:readsettingsphp' => 'Die Datei vendor/elgg/elgg/elgg-config/settings.example.php ist nicht lesbar.',
	'install:error:writesettingphp' => 'Es ist nicht möglich, in die Datei elgg-config/settings.php zu schreiben bzw. diese Datei anzulegen.',
	'install:error:requiredfield' => '%s ist eine notwendige Eingabe.',
	'install:error:relative_path' => 'Der eingegebene Pfad "%s" scheint keine absolute Pfadangabe für Dein Datenverzeichnis zu sein.',
	'install:error:datadirectoryexists' => 'Dein Datenverzeichnis %s ist nicht vorhanden.',
	'install:error:writedatadirectory' => 'Dein Webserver hat keine Schreibberechtigung für das Datenverzeichnis %s.',
	'install:error:locationdatadirectory' => 'Dein Datenverzeichnis %s muss aus Sicherheitsgründen außerhalb des Installationspfades von Elgg sein.',
	'install:error:emailaddress' => '%s ist keine zulässige Email-Adresse.',
	'install:error:createsite' => 'Das Erstellen der Community-Seite ist fehlgeschlagen.',
	'install:error:savesitesettings' => 'Das Speichern der Seiteneinstellungen ist fehlgeschlagen.',
	'install:error:loadadmin' => 'Der Administrator-Account kann nicht geladen werden.',
	'install:error:adminaccess' => 'Das Zuweisen von Administrator-Privilegien an den neuen Benutzeraccount ist fehlgeschlagen.',
	'install:error:adminlogin' => 'Das automatische Anmelden des neuen Administrator-Benutzers ist fehlgeschlagen.',
	'install:error:rewrite:apache' => 'Die Überprüfung Deines Servers hat ergeben, dass der Apache-Webserver verwendet wird.',
	'install:error:rewrite:nginx' => 'Die Überprüfung Deines Servers hat ergeben, dass der Nginx-Webserver verwendet wird.',
	'install:error:rewrite:lighttpd' => 'Die Überprüfung Deines Servers hat ergeben, dass der Lighttpd-Webserver verwendet wird.',
	'install:error:rewrite:iis' => 'Die Überprüfung Deines Servers hat ergeben, dass der IIS-Webserver verwendet wird.',
	'install:error:rewrite:allowoverride' => "Der Rewrite-Test ist fehlgeschlagen. Die wahrscheinlichste Ursache ist, dass für das Elgg-Installationsverzeichnis 'AllowOverride All' nicht gesetzt ist. Die verhindert, dass der Apache-Webserver die Einstellungen in der Datei .htaccess verarbeiten kann, in welcher die Rewrite-Regeln gesetzt werden.
		\n\nEine andere mögliche Ursache ist, dass in der Konfiguration des Apache-Webservers ein Alias für Dein Elgg-Installationsverzeichnis definiert ist. Dann mußt Du in der Datei .htaccess die richtige Einstellung für RewriteBase setzen. In der Datei .htaccess in Deinem Elgg-Installationsverzeichnis gibt es weitere Informationen darüber, was zu tun ist.",
	'install:error:rewrite:htaccess:write_permission' => 'Dein Webserver hat nicht die notwendige Schreibberechtigung, um im Elgg-Installationsverzeichnis die Datei .htaccess automatisch zu erzeugen. Du mußt entweder (nur während der Installation!) die Zugriffsberechtigungen für das Elgg-Installationsverzeichnis ändern oder selbst die Datei .htaccess im Elgg-Installationsverzeichnis anlegen, indem Du sie aus der Datei install/config/htaccess.dist durch Kopieren und Umbenennen erzeugst.',
	'install:error:rewrite:htaccess:read_permission' => 'Im Elgg-Installationsverzeichnis ist die Datei .htaccess vorhanden, aber Dein Webserver hat keine Leseberechtigung für diese Datei.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Im Elgg-Installationsverzeichnis ist eine Datei namens .htaccess, die nicht von Elgg angelegt wurde. Bitte entferne diese Datei.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Im Elgg-Installationsverzeichnis scheint eine veraltete .htaccess-Datei vorhanden zu sein. Sie enthält nicht die Rewrite-Regeln für das Überprüfen des Webservers.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Beim Erzeugen der Datei .htaccess im Elgg-Installationsverzeichnis ist ein unbekannter Fehler aufgetreten. Du mußt die Datei .htaccess selbst im Elgg-Installationsverzeichnis anlegen, indem Du sie aus der Datei vendor/elgg/elgg/install/config/htaccess.dist durch Kopieren und Umbenennen erzeugst.',
	'install:error:rewrite:altserver' => 'Der Test der Rewrite-Regeln ist fehlgeschlagen. Du mußt die Rewrite-Regeln von Elgg selbst zur Konfiguration Deines Webservers hinzufügen und es dann nochmals versuchen.',
	'install:error:rewrite:unknown' => 'Uups. Es war nicht möglich festzustellen, welches Webserver-Programm auf Deinem Server verwendet wird. Darüber hinaus ist der Test der Rewrite-Regeln von Elgg fehlgeschlagen. Es ist leider nicht möglich, spezifischere Hinweise zu den Ursachen des Problems zu geben. Bitte folge dem Link zu Hinweisen bei Installationsproblemen.',
	'install:warning:rewrite:unknown' => 'Dein Server unterstützt die automatische Prüfung von Rewrite-Regeln nicht und Dein Browser unterstützt es nicht, mit Hilfe von Javascript die Rewrite-Regeln auf Funktionsfähigkeit zu überprüfen. Du kannst die Installation fortsetzen, aber es kann sein, das Deine Community-Seite nicht einwandfrei funktionieren wird. Du kannst die Überprüfung der Rewrite-Regeln selbst durchführen, indem Du diesem Link folgst: <a href="%s" target="_blank">Test</a>. Du wirst die Meldung \'success\' bekommen, wenn die Rewrite-Regeln funktionieren.',
	'install:error:wwwroot' => '%s ist keine gültige URL.',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Es ist ein nicht behebbarer Fehler aufgetreten. Der Fehler wurde protokolliert. Wenn Du der Seitenadministrator bist, prüfe bitte die Konfiguration in elgg-config/settings.php. Andernfalls leite bitte folgende Informationen an den Seitenadministrator weiter:',
	'DatabaseException:WrongCredentials' => "Elgg konnte mit den gegebenen Verbindungsparametern keine Verbindung zur Datenbank herstellen. Bitte prüfe die Konfiguration in elgg-config/settings.php.",
);
