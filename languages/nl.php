<?php

return array(
/**
 * Sites
 */

	'item:site:site' => 'Site',
	'collection:site:site' => 'Sites',
	'index:content' => '<p>Welkom op je Elgg site.</p><p><strong>Tip:</strong> Veel sites gebruiken de <code>activity</code> plugin om een activiteiten stroom op de voorpagina te plaatsen.</p>',

/**
 * Sessions
 */

	'login' => "Aanmelden",
	'loginok' => "Je bent aangemeld.",
	'loginerror' => "We konden je niet aanmelden. Controleer je gegevens en probeer het nogmaals",
	'login:empty' => "Gebruikersnaam en wachtwoord zijn verplicht.",
	'login:baduser' => "Je account kon niet worden geladen.",
	'auth:nopams' => "Interne fout. Geen methode voor gebruikersvalidatie gedefinieerd.",

	'logout' => "Afmelden",
	'logoutok' => "Je bent afgemeld.",
	'logouterror' => "We konden je niet afmelden. Probeer het nogmaals.",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "Je moet aangemeld zijn om die pagina te kunnen bekijken.",
	'loggedoutrequired' => "Je moet afgemeld zijn om die pagina te kunnen bekijken.",
	'adminrequired' => "Je moet een beheerder zijn om die pagina te kunnen bekijken.",
	'membershiprequired' => "Je moet lid zijn van deze groep om deze pagina te kunnen bekijken.",
	'limited_access' => "Je hebt niet de juiste rechten om deze pagina te zien.",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "Fatale fout.",
	'exception:contact_admin' => 'Er is een onherstelbare fout opgetreden en gelogd. Neem contact op met de sitebeheerder met de volgende informatie:',

	'actionundefined' => "De gevraagde actie (%s) is niet gedefinieerd in het systeem.",
	'actionnotfound' => "Het actiebestand voor %s kon niet worden gevonden.",
	'actionloggedout' => "Sorry, je kunt deze actie niet uitvoeren als je bent afgemeld.",
	'actionunauthorized' => 'Je bent niet geautoriseerd om deze actie uit te voeren',

	'ajax:error' => 'Onverwacht probleem opgetreden tijdens de uitvoer van een AJAX call. Mogelijk is de verbinding met de server verloren.',
	'ajax:not_is_xhr' => 'Je kan AJAX views niet rechtstreeks aanroepen',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is een verkeerd geconfigureerde plugin. Hij is uitgeschakeld. In de Elgg documentatie kun je mogelijke oorzaken vinden (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) kan niet starten. Reden: %s',
	'PluginException:InvalidID' => "%s is een ongeldige plugin-ID.",
	'PluginException:InvalidPath' => "%s is een ongeldig plugin-pad.",
	'PluginException:InvalidManifest' => 'Ongeldig manifestbestand voor plugin: %s',
	'PluginException:InvalidPlugin' => '%s is een ongeldige plugin.',
	'PluginException:InvalidPlugin:Details' => '%s is een ongeldige plugin: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin kan niet worden geïnitieerd met null. Je moet een GUID, een plugin-ID of een pad opgeven.',
	'ElggPlugin:MissingID' => 'Plugin-ID ontbreekt (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Ontbrekend ElggPluginPackage voor plugin-ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Het bestand %s is vereist, maar kan niet gevonden worden.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'De directorynaam van de plugin moet hernoemd worden naar "%s" om overeen te komen met de ID in het manifest.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Het manifest bevat een ongeldig afhankelijkheidtype "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Het manifest bevat een ongeldig aanbodtype "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => '100%
Ongeldig %s afhankelijkheid "%s" in plugin %s. Let op: plugins kunnen niet conflicteren met, of afhankelijk zijn van iets dat ze zelf bieden!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Error' => 'Plugin fout',
	'ElggPlugin:Error:ID' => 'Fout in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Fout in plugin pad "%s"',
	'ElggPlugin:Error:Unknown' => 'Ongedefinieerde plugin fout',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Kan %s niet laden voor plugin %s (guid: %s) in %s. Controleer de rechten!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Kan de views-map niet openen van plugin %s (guid: %s) in %s. Controleer de rechten!',
	'ElggPlugin:Exception:NoID' => 'Geen ID voor plugin-guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Package kan niet worden geladen',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest ontbreekt of is ongeldig',
	'PluginException:NoPluginName' => "De pluginnaam kon niet worden gevonden",
	'PluginException:ParserError' => 'Fout tijdens het lezen van de manifest met API-versie %s in plugin %s',
	'PluginException:NoAvailableParser' => 'Kan geen parser vinden voor manifest API-versie %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Ontbrekend verplicht '%s' attribuut in manifest van plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s is een ongeldige plugin. Hij is daarom uitgeschakeld.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Vereist',
	'ElggPlugin:Dependencies:Suggests' => 'Adviseert',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflicteert',
	'ElggPlugin:Dependencies:Conflicted' => 'Geconflicteerd',
	'ElggPlugin:Dependencies:Provides' => 'Biedt',
	'ElggPlugin:Dependencies:Priority' => 'Prioriteit',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg-versie',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP-versie',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP-module: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Instelling van PHP-ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Na %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Voor %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s is niet geïnstalleerd',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Ontbreekt',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Er zijn plugins die afhankelijk zijn van %s. Je moet eerst de volgende plugins uitschakelen voordat je deze kunt uitschakelen: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items gevonden zonder bovenliggende menu items om ze aan te linken',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] gevonden met een ontbrekend bovenliggend menu item [%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Dubbele registratie gevonden voor menu item [%s]',

	'RegistrationException:EmptyPassword' => 'De wachtwoordvelden mogen niet leeg zijn!',
	'RegistrationException:PasswordMismatch' => 'Wachtwoorden moeten gelijk zijn',
	'LoginException:BannedUser' => 'Je account is geblokkeerd. Je kunt je daarom niet aanmelden.',
	'LoginException:UsernameFailure' => 'We konden je niet aanmelden. Controleer je gebruikersnaam.',
	'LoginException:PasswordFailure' => 'We konden je niet aanmelden. Controleer je wachtwoord.',
	'LoginException:AccountLocked' => 'Je account is geblokkeerd wegens te veel mislukte aanmeldpogingen.',
	'LoginException:ChangePasswordFailure' => 'Huidige wachtwoord incorrect.',
	'LoginException:Unknown' => 'We konden je niet aanmelden vanwege een onbekende fout.',

	'UserFetchFailureException' => 'Kan voor user_guid [%s] niet de rechten nakijken omdat de gebruiker niet bestaat.',

	'PageNotFoundException' => 'De pagina die je opvraagt bestaat niet, of je hebt onvoldoende rechten om deze te mogen bekijken.',
	'EntityNotFoundException' => 'De inhoud is verwijderd of je hebt geen rechten om die te mogen bekijken.',
	'EntityPermissionsException' => 'Je hebt onvoldoende rechten om deze actie uit te mogen voeren',
	'GatekeeperException' => 'Je hebt niet de juiste rechten om deze pagina te zien.',
	'BadRequestException' => 'Het verzoek is onjuist',
	'ValidationException' => 'De opgestuurde data voldoet niet aan de vereisten, controleer de invoer',
	'LogicException:InterfaceNotImplemented' => '%smoet %s implementeren',

	'deprecatedfunction' => 'Waarschuwing: Deze code gebruikt de niet meer gebruikte functies \'%s\' en is niet compatibel met deze versie van Elgg. ',

	'pageownerunavailable' => 'Waarschuwing: De pagina-eigenaar %d is niet toegankelijk!',
	'viewfailure' => 'Er is een interne fout in de view %s',
	'view:missing_param' => "De vereiste parameter '%s' mist in de weergave %s",
	'changebookmark' => 'Wijzig je favoriet/bladwijzer voor deze pagina',
	'noaccess' => 'De inhoud is verwijderd, is ongeldig of je hebt geen rechten om die te mogen bekijken.',
	'error:missing_data' => 'Er missen enkele gegevens in je verzoek',
	'save:fail' => 'Er ging iets mis bij het opslaan van je gegevens',
	'save:success' => 'Je gegevens zijn opgeslagen',

	'forward:error' => 'Onze excuses. Er is een fout opgetreden terwijl we je wilden doorsturen naar een andere site.',

	'error:default:title' => 'Oeps...',
	'error:default:content' => 'Oeps... er ging iets mis.',
	'error:400:title' => 'Het verzoek is onjuist',
	'error:400:content' => 'Sorry, Het verzoek is onjuist of onvolledig',
	'error:403:title' => 'Verboden',
	'error:403:content' => 'Sorry. Je hebt geen toestemming om de opgevraagde pagina te bezoeken.',
	'error:404:title' => 'Pagina niet gevonden',
	'error:404:content' => 'Sorry. We konden de pagina die je opvroeg niet vinden.',

	'upload:error:ini_size' => 'Het bestand dat je wilt uploaden is te groot.',
	'upload:error:form_size' => 'Het bestand dat je wilt uploaden is te groot.',
	'upload:error:partial' => 'Het bestand is niet volledig geüploadet.',
	'upload:error:no_file' => 'Je hebt geen bestand geselecteerd',
	'upload:error:no_tmp_dir' => 'Het geüploade bestand kan niet opgeslagen worden.',
	'upload:error:cant_write' => 'Het geüploade bestand kan niet opgeslagen worden.',
	'upload:error:extension' => 'Het geüploade bestand kan niet opgeslagen worden.',
	'upload:error:unknown' => 'De bestandsupload is helaas mislukt.',

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

	'name' => "Weergavenaam",
	'email' => "E-mailadres",
	'username' => "Gebruikersnaam",
	'loginusername' => "Gebruikersnaam of e-mailadres",
	'password' => "Wachtwoord",
	'passwordagain' => "Wachtwoord (nogmaals, voor de zekerheid)",
	'admin_option' => "Wil je deze gebruiker sitebeheerder maken? De gebruiker heeft dan alle rechten op de site.",
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */

	'access:label:private' => "Privé",
	'access:label:logged_in' => "Aangemelde gebruikers",
	'access:label:public' => "Publiekelijk",
	'access:label:logged_out' => "Afgemelde gebruikers",
	'access:label:friends' => "Vrienden",
	'access' => "Toegang",
	'access:overridenotice' => "De inhoud van deze groep is alléén toegankelijk voor leden.",
	'access:limited:label' => "Gelimiteerd",
	'access:help' => "Het toegangsniveau",
	'access:read' => "Toegang",
	'access:write' => "Schrijftoegang",
	'access:admin_only' => "Alleen voor beheerders",
	'access:missing_name' => "Ontbrekend naam voor toegangsniveau",
	'access:comments:change' => "Deze reacties zijn op dit moment zichtbaar voor een beperkte groep gebruikers. Bedenk goed met wie je dit wilt delen.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Je dashboard biedt je de mogelijkheid om activiteiten en inhoud die belangrijk is voor jou is te volgen.",

	'widgets:add' => 'Voeg widgets toe',
	'widgets:add:description' => "Klik op een widgetknop om de widget aan jouw pagina toe te voegen.",
	'widgets:position:fixed' => '(Vaste positie op pagina)',
	'widget:unavailable' => 'Je hebt deze widget al toegevoegd',
	'widget:numbertodisplay' => 'Aantal items om weer te geven',

	'widget:delete' => 'Verwijder %s',
	'widget:edit' => 'Pas de widget aan jouw wensen aan',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "De widget is succesvol opgeslagen.",
	'widgets:save:failure' => "Er ging iets mis tijdens het opslaan van je widget. Probeer het nog een keer.",
	'widgets:add:success' => "De widget is toegevoegd.",
	'widgets:add:failure' => "De widget kon niet worden toegevoegd.",
	'widgets:move:failure' => "De nieuwe widgetpositie kon niet worden opgeslagen.",
	'widgets:remove:failure' => "De widget kan niet worden verwijderd",
	'widgets:not_configured' => "Deze widget is nog niet geconfigureerd",
	
/**
 * Groups
 */

	'group' => "Groep",
	'item:group' => "Groepen",
	'collection:group' => 'Groepen',
	'item:group:group' => "Groep",
	'collection:group:group' => 'Groepen',
	'groups:tool_gatekeeper' => "De gevraagde functionaliteit is op dit moment niet ingeschakeld in deze groep.",

/**
 * Users
 */

	'user' => "Gebruiker",
	'item:user' => "Gebruikers",
	'collection:user' => 'Gebruikers',
	'item:user:user' => 'Gebruiker',
	'collection:user:user' => 'Gebruikers',

	'friends' => "Vrienden",
	'collection:friends' => 'Vrienden %s',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "Je hebt geen rechten om de avatar van deze gebruiker te bewerken.",
	'avatar:create' => 'Maak je avatar',
	'avatar:edit' => 'Bewerk avatar',
	'avatar:upload' => 'Upload een nieuwe avatar',
	'avatar:current' => 'Huidige avatar',
	'avatar:remove' => 'Verwijder je avatar en gebruik het standaard icoon',
	'avatar:crop:title' => 'Avatar bijsnijden',
	'avatar:upload:instructions' => "Je avatar wordt weergegeven op verschillende plaatsen op de site. Je kunt je avatar zo vaak als je wilt vervangen. (Ondersteunde bestandsformaten: GIF, JPG of PNG)",
	'avatar:create:instructions' => 'Hieronder kun je de avatar aanpassen door hem in een vierkant bij te snijden. Een voorbeeld zal hiernaast verschijnen. Zodra je tevreden bent met het voorbeeld klik je op de knop \'Maak avatar\'. Deze aangepaste versie zal worden gebruikt op verschillende plaatsen op de site en is zichtbaar voor andere gebruikers.',
	'avatar:upload:success' => 'Avatar succesvol geüpload',
	'avatar:upload:fail' => 'Upload van avatar mislukt',
	'avatar:resize:fail' => 'Schalen van de avatar mislukt',
	'avatar:crop:success' => 'Bijsnijden van de avatar succesvol',
	'avatar:crop:fail' => 'Bijsnijden van de avatar mislukt',
	'avatar:remove:success' => 'Avatar succesvol verwijderd',
	'avatar:remove:fail' => 'Avatar verwijderen is mislukt',
	
	'action:user:validate:already' => "%s was al gevalideerd",
	'action:user:validate:success' => "%s is gevalideerd",
	'action:user:validate:error' => "Een fout is opgetreden tijdens het valideren van %s",

/**
 * Feeds
 */
	'feed:rss' => 'Abonneer je op de RSS-feed',
	'feed:rss:title' => 'RSS feed voor deze pagina',
/**
 * Links
 */
	'link:view' => 'Bekijk link',
	'link:view:all' => 'Bekijk alles',


/**
 * River
 */
	'river' => "River",
	'river:user:friend' => "%s is nu bevriend met %s",
	'river:update:user:avatar' => '%s heeft een nieuwe avatar',
	'river:noaccess' => 'Je hebt geen toegang tot dit item.',
	'river:posted:generic' => '%s plaatste',
	'riveritem:single:user' => 'een gebruiker',
	'riveritem:plural:user' => 'sommige gebruikers',
	'river:ingroup' => 'in de groep %s',
	'river:none' => 'Geen activiteit',
	'river:update' => 'Update van %s',
	'river:delete' => 'Verwijder deze activiteit',
	'river:delete:success' => 'Activity item has been deleted',
	'river:delete:fail' => 'Activity item could not be deleted',
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Ongeldige gebruiker',
	'activity:owner' => 'Bekijk activiteit',

	

/**
 * Notifications
 */
	'notifications:usersettings' => "Notificatie-instellingen",
	'notification:method:email' => 'E-mail',

	'notifications:usersettings:save:ok' => "Je notificatie-instellingen zijn succesvol opgeslagen.",
	'notifications:usersettings:save:fail' => "Er is een fout opgetreden tijdens het opslaan van je notificatie-instellingen.",

	'notification:subject' => 'Notificatie over \'%s\'',
	'notification:body' => 'Bekijk de nieuwe activiteit op %s',

/**
 * Search
 */

	'search' => "Zoeken",
	'searchtitle' => "Zoeken: %s",
	'users:searchtitle' => "Zoeken naar gebruikers: %s",
	'groups:searchtitle' => "Zoeken naar groepen: %s",
	'advancedsearchtitle' => "%s gevonden met %s",
	'notfound' => "Geen resultaten gevonden.",
	'next' => "Volgende",
	'previous' => "Vorige",

	'viewtype:change' => "Wijzig de wijze van weergave",
	'viewtype:list' => "Lijstweergave",
	'viewtype:gallery' => "Galerij",

	'tag:search:startblurb' => "Items gevonden met '%s':",

	'user:search:startblurb' => "Gebruikers gevonden met '%s':",
	'user:search:finishblurb' => "Klik hier om meer te zien!",

	'group:search:startblurb' => "Groepen met '%s':",
	'group:search:finishblurb' => "Klik hier om meer te zien! ",
	'search:go' => 'Ga',
	'userpicker:only_friends' => 'Alleen vrienden',

/**
 * Account
 */

	'account' => "Account",
	'settings' => "Instellingen",
	'tools' => "Tools",
	'settings:edit' => 'Bewerk instellingen',

	'register' => "Registreer",
	'registerok' => "Je hebt je succesvol geregistreerd op %s.",
	'registerbad' => "Je registratie is niet gelukt vanwege een onbekende fout.",
	'registerdisabled' => "Je kunt je op dit moment niet registreren.",
	'register:fields' => 'Alle velden zijn verplicht',

	'registration:noname' => 'Weergavenaam is verplicht',
	'registration:notemail' => 'Het opgegeven e-mailadres lijkt niet te kloppen. Het e-mailadres moet het format aaa@bbbb.ccc hebben!',
	'registration:userexists' => 'Deze gebruikersnaam bestaat al.',
	'registration:usernametooshort' => 'Je gebruikersnaam moet minimaal %u karakters lang zijn.',
	'registration:usernametoolong' => 'Je gebruikersnaam is te lang. Je kunt maximaal %u karakters gebruiken.',
	'registration:passwordtooshort' => 'Het wachtwoord moet minimaal %u karakters lang zijn.',
	'registration:dupeemail' => 'Dit e-mailadres is al geregistreerd.',
	'registration:invalidchars' => 'Sorry, je gebruikersnaam bevat het volgende ongeldige karakter: %s 
De volgende karakters zijn niet toegestaan: %s',
	'registration:emailnotvalid' => 'Sorry, het opgegeven e-mailadres is ongeldig op dit systeem',
	'registration:passwordnotvalid' => 'Sorry, het opgegeven wachtwoord is ongeldig op dit systeem',
	'registration:usernamenotvalid' => 'Sorry, de opgegeven gebruikersnaam is ongeldig op dit systeem',

	'adduser' => "Gebruiker toevoegen",
	'adduser:ok' => "Nieuwe gebruiker is succesvol aangemaakt.",
	'adduser:bad' => "De nieuwe gebruiker kon niet worden aangemaakt.",

	'user:set:name' => "Instellingen van accountnaam",
	'user:name:label' => "Mijn weergavenaam",
	'user:name:success' => "Je weergavenaam is succesvol gewijzigd.",
	'user:name:fail' => "Er is een fout opgetreden tijdens het wijzigen van je weergavenaam.",
	'user:username:success' => "Je gebruikersnaam is succesvol gewijzigd.",
	'user:username:fail' => "Je gebruikersnaam kon niet worden gewijzigd",

	'user:set:password' => "Accountwachtwoord",
	'user:current_password:label' => 'Huidige wachtwoord',
	'user:password:label' => "Je nieuwe wachtwoord",
	'user:password2:label' => "Nogmaals je nieuwe wachtwoord",
	'user:password:success' => "Wachtwoord gewijzigd",
	'user:password:fail' => "Er is een fout opgetreden tijdens het wijzigen van je wachtwoord.",
	'user:password:fail:notsame' => "De twee wachtwoorden komen niet overeen!",
	'user:password:fail:tooshort' => "Het wachtwoord is te kort!",
	'user:password:fail:incorrect_current_password' => 'Het opgegeven huidige wachtwoord is onjuist.',
	'user:changepassword:unknown_user' => 'Ongeldige gebruiker',
	'user:changepassword:change_password_confirm' => 'Hiermee wijzig je je wachtwoord.',

	'user:set:language' => "Taalinstelling",
	'user:language:label' => "Jouw taal",
	'user:language:success' => "Je taalinstelling is gewijzigd.",
	'user:language:fail' => "Er is een fout opgetreden tijdens het wijzigen van je taalinstelling.",

	'user:username:notfound' => 'Gebruikersnaam %s niet gevonden.',
	'user:username:help' => 'Houd er rekening mee dat een wijziging van de gebruikersnaam alle dynamische links mbt je account worden gewijzigd',

	'user:password:lost' => 'Wachtwoord vergeten',
	'user:password:hash_missing' => 'We moeten je vragen om je wachtwoord te wijzigen. De veiligheid van de wachtwoorden is verbeterd, echter konden we niet all accounts migrereren.',
	'user:password:changereq:success' => 'De aanvraag voor een nieuw wachtwoord is gelukt. Er wordt een e-mail verstuurd.',
	'user:password:changereq:fail' => 'Er kan geen nieuw wachtwoord aangevraagd worden.',

	'user:password:text' => 'Om een nieuw wachtwoord aan te vragen vul je hieronder je gebruikersnaam of e-mailadres In. Klik daarna op de knop \'Aanvragen\'.',

	'user:persistent' => 'Onthoud mij',

	'walled_garden:home' => 'Home',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Beheer',
	'menu:page:header:configure' => 'Configureer',
	'menu:page:header:develop' => 'Ontwikkel',
	'menu:page:header:information' => 'Informatie',
	'menu:page:header:default' => 'Andere',

	'admin:view_site' => 'Bekijk website',
	'admin:loggedin' => 'Aangemeld als %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Je instellingen zijn opgeslagen.",
	'admin:configuration:fail' => "Je instellingen zijn niet opgeslagen.",
	'admin:configuration:dataroot:relative_path' => 'Kan \'%s\' niet als datamap opslaan, omdat het geen absoluut pad is.',
	'admin:configuration:default_limit' => 'Het aantal items per pagina moet minstens 1 zijn.',

	'admin:unknown_section' => 'Ongeldige beheersectie.',

	'admin' => "Beheer",
	'admin:description' => "Het beheerpaneel maakt het mogelijk het hele systeem te beheren: van gebruikersbeheer tot hoe plugins zich gedragen. Kies een optie om te beginnen.",

	'admin:statistics' => 'Statistieken',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Laatste Cron Jobs',
	'admin:cron:period' => 'Cron periode',
	'admin:cron:friendly' => 'Laatst afgerond',
	'admin:cron:date' => 'Datum en tijd',
	'admin:cron:msg' => 'Bericht',
	'admin:cron:started' => 'Cron jobs voor "%s" zijn gestart om %s',
	'admin:cron:started:actual' => 'Cron interval "%s" is gestart op %s',
	'admin:cron:complete' => 'Cron jobs voor "%s" zijn afgerond om %s',

	'admin:appearance' => 'Uiterlijk',
	'admin:administer_utilities' => 'Hulpmiddelen',
	'admin:develop_utilities' => 'Hulpmiddelen',
	'admin:configure_utilities' => 'Hulpmiddelen',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Gebruikers",
	'admin:users:online' => 'Op dit moment online',
	'admin:users:newest' => 'Nieuwste',
	'admin:users:admins' => 'Beheerders',
	'admin:users:add' => 'Nieuwe gebruiker',
	'admin:users:description' => "Dit beheerpaneel maakt het mogelijk om gebruikersinstellingen te wijzigen. Kies hieronder een optie om te beginnen.",
	'admin:users:adduser:label' => "Klik hier om een nieuwe gebruiker toe te voegen.",
	'admin:users:opt:linktext' => "Configureer gebruikers.",
	'admin:users:opt:description' => "Configureer gebruikers- en accountinformatie",
	'admin:users:find' => 'Zoek',
	'admin:users:unvalidated' => 'Ongevalideerd',
	'admin:users:unvalidated:no_results' => 'Geen ongevalideerde gebruikers',
	'admin:users:unvalidated:registered' => 'Geregistreerd %s',
	
	'admin:configure_utilities:maintenance' => 'Onderhoudsmodus',
	'admin:upgrades' => 'Upgrades',
	'admin:upgrades:finished' => 'Afgerond',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Start time',
	'admin:upgrades:db:end_time' => 'End time',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Nog uit te voeren upgrades',
	'admin:upgrades:menu:completed' => 'Afgeronde upgrades',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Start deze upgrade',
	'admin:upgrades:run' => 'Start alle upgrades',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s bestaat niet of is geen geldig type van een ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner voor de upgrade %s (%s) kon niet worden geïnstantieerd',
	'admin:upgrades:completed' => 'Upgrade "%s" is afgerond om %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" is afgerond om %s, maar bevatte %s fouten',
	'admin:upgrades:failed' => 'Upgrade "%s" is mislukt',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" is herstart',

	'admin:settings' => 'Instellingen',
	'admin:settings:basic' => 'Basisinstellingen',
	'admin:settings:advanced' => 'Geavanceerde instellingen',
	'admin:site:description' => "Via dit beheerpaneel kun je de algemene instellingen van de site beheren. Kies een optie om te beginnen.",
	'admin:site:opt:linktext' => "Configureer site",
	'admin:settings:in_settings_file' => 'Deze instelling configureer je in settings.php',

	'site_secret:current_strength' => 'Sterkte van de sleutel',
	'site_secret:strength:weak' => "Zwak",
	'site_secret:strength_msg:weak' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren!",
	'site_secret:strength:moderate' => "Middelmatig",
	'site_secret:strength_msg:moderate' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren, zodat je de meeste veiligheid voor je site hebt.",
	'site_secret:strength:strong' => "Sterk",
	'site_secret:strength_msg:strong' => "Jouw 'sitegeheim' is sterk genoeg. Je hoeft het niet opnieuw te genereren.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Online gebruikers',
	'admin:widget:online_users:help' => 'Toont een lijst met gebruikers die nu op de site zijn',
	'admin:widget:new_users' => 'Nieuwe gebruikers',
	'admin:widget:new_users:help' => 'Toon de nieuwste gebruikers',
	'admin:widget:banned_users' => 'Gebande gebruikers',
	'admin:widget:banned_users:help' => 'Toon de gebande gebruikers',
	'admin:widget:content_stats' => 'Inhoud statistieken',
	'admin:widget:content_stats:help' => 'Blijf op de hoogte van de inhoud die door de gebruikers is gemaakt',
	'admin:widget:cron_status' => 'Cron status',
	'admin:widget:cron_status:help' => 'Toont de status van de laatste afronding van de cron jobs',
	'admin:statistics:numentities' => 'Inhoud statistieken',
	'admin:statistics:numentities:type' => 'Content Type',
	'admin:statistics:numentities:number' => 'Nummer',
	'admin:statistics:numentities:searchable' => 'Doorzoekbare entiteiten',
	'admin:statistics:numentities:other' => 'Andere entiteiten',

	'admin:widget:admin_welcome' => 'Welkom',
	'admin:widget:admin_welcome:help' => "Een korte introductie op het beheerdeel van Elgg",
	'admin:widget:admin_welcome:intro' =>
'Welkom in Elgg! Op dit moment kijk je naar het beheerdersdashboard. Dit is makkelijk om te zien wat er op je site gebeurt.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigatie door het beheer gedeelte is mogelijk door het menu aan de rechterkant. Dit is georganiseerd in drie secties:
	<dl>
		<dt>Beheer</dt><dd>Basis taken zoals het beheren van gebruikers, bekijken van gerapporteerde content en het activeren van plugins</dd>
		<dt>Configureer</dt><dd>Incidentele taken zoals het wijzigen van de site naam, of het configureren van plugin instellingen</dd>
		<dt>Informatie</dt><dd>Informatie over je website zoals content statistieken</dd>
		<dt>Ontwikkel</dt><dd>Voor ontwikkelaars welke bezig zijn met het ontwikkelen van een plugin of een theme. (Vereist de developer plugin)</dd>
</dl>
",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br>Controleer de links onderaan de pagina voor meer informatie en bedankt voor het gebruik van Elgg!',

	'admin:widget:control_panel' => 'Configuratiescherm',
	'admin:widget:control_panel:help' => "Biedt snelle toegang tot veelgebruikte opties",

	'admin:cache:flush' => 'Wis de caches',
	'admin:cache:flushed' => "De sitecache is gewist",

	'admin:footer:faq' => 'Veelgestelde vragen voor beheerders',
	'admin:footer:manual' => 'Beheerdershandleiding',
	'admin:footer:community_forums' => 'Elgg communityforums',
	'admin:footer:blog' => 'Elgg blog',

	'admin:plugins:category:all' => 'Alle plugins',
	'admin:plugins:category:active' => 'Actieve plugins',
	'admin:plugins:category:inactive' => 'Uitgeschakelde plugins',
	'admin:plugins:category:admin' => 'Beheer',
	'admin:plugins:category:bundled' => 'Meegeleverd',
	'admin:plugins:category:nonbundled' => 'Niet meegeleverd',
	'admin:plugins:category:content' => 'Inhoud',
	'admin:plugins:category:development' => 'Ontwikkelaars',
	'admin:plugins:category:enhancement' => 'Uitbreidingen',
	'admin:plugins:category:api' => 'Services/API',
	'admin:plugins:category:communication' => 'Communicatie',
	'admin:plugins:category:security' => 'Beveiliging en spam',
	'admin:plugins:category:social' => 'Sociaal',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Themes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Hulpmiddelen',

	'admin:plugins:markdown:unknown_plugin' => 'Onbekende plugin.',
	'admin:plugins:markdown:unknown_file' => 'Onbekend bestand.',

	'admin:notices:delete_all' => 'Sluit alle %s meldingen',
	'admin:notices:could_not_delete' => 'Kon melding niet verwijderen',
	'item:object:admin_notice' => 'Bericht voor sitebeheerder',
	'collection:object:admin_notice' => 'Berichten voor beheerder',

	'admin:options' => 'Beheeropties',

	'admin:security' => 'Beveiliging',
	'admin:security:settings' => 'Instellingen',
	'admin:security:settings:description' => 'Op deze pagina kun je enkele veiligheidskeuzes maken. Lees de instellingen zorgvuldig.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:notifications' => 'Notificaties',
	'admin:security:settings:label:site_secret' => 'Site Secret',
	
	'admin:security:settings:notify_admins' => 'Stuur alle beheerders een bericht indien er een beheerder wordt toegevoegd of verwijderd',
	'admin:security:settings:notify_admins:help' => 'Dit stuurt een notificatie naar alle beheerders dat een gebruiker beheerdersrechten heeft gekregen of verloren.',
	
	'admin:security:settings:notify_user_admin' => 'Stuur de gebruiker een notificatie indien de beheerdersrol aan hem/haar is toegekend',
	'admin:security:settings:notify_user_admin:help' => 'Dit zorgt voor een notificatie naar de gebruiker indien hij/zij beheerdersrechten heeft gekregen.',
	
	'admin:security:settings:notify_user_ban' => 'Stuur een bericht naar de gebruiker als het account wordt ge(de)blokkeerd',
	'admin:security:settings:notify_user_ban:help' => 'Dit zorgt voor een notificatie naar de gebruiker indien hij/zij is ge(de)blokkeerd.',
	
	'admin:security:settings:protect_upgrade' => 'Bescherm upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'Dit beperkt de toegang tot upgrade.php tot beheerders en een ieder die een geldig token heeft.',
	'admin:security:settings:protect_upgrade:token' => 'Indien je upgrade.php als afgemelde gebruiker, of als een gewone gebruiker, wilt gebruiken, gebruik dan de volgende URL:',
	
	'admin:security:settings:protect_cron' => 'Bescherm /cron URLs',
	'admin:security:settings:protect_cron:help' => 'Dit beperkt de toegang tot /cron URLs. Enkel indien een valide token aanwezig is zal de cron worden uitgevoerd.',
	'admin:security:settings:protect_cron:token' => 'Om de /cron URLs te kunnen gebruiken, moeten de volgende tokens worden gebruikt. Houdt er rekening mee dat elke interval zijn eigen token heeft.',
	'admin:security:settings:protect_cron:toggle' => 'Toon/verberg cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Schakel autocomplete uit voor wachtwoord velden',
	'admin:security:settings:disable_password_autocomplete:help' => 'Gegevens die je invoert bij deze velden zullen door de browser worden gecached. Een aanvaller die toegang heeft tot de browser van het slachtoffer kan deze informatie stelen. Dit is met name belangrijk indien de website wordt gebruikt in een publiekelijke plek zoals internetcafés of het vliegveld. Indien je de autocomplete uitschakeld dan kunnen wachtwoord managers mogelijk niet meer functioneren. De ondersteuning voor de autocomplete functionaliteit kan per browser verschillen.',
	
	'admin:security:settings:email_require_password' => 'Vereis een wachtwoord om je emailadres te wijzigen',
	'admin:security:settings:email_require_password:help' => 'Wanneer een gebruiker zijn/haar emailadres wenst te wijziging, dan moet ook het huidige wachtwoord worden ingevoerd.',

	'admin:security:settings:session_bound_entity_icons' => 'Sessie gebonden entity iconen',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity iconen zijn standaard sessie gebonden. Dit houdt in dat de URLs naar de iconen informatie bevatten over de huidige sessie. Indien iconen sessiegebonden zijn, zijn ze niet deelbaar tussen sessies en dus meer afgeschermd. Het bijeffect is dat de iconen alleen voor de huidige sessie cachebaar zijn.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg gebruikt een sleutel om tokens te genereren voor verschillende doeleinden.',
	'admin:security:settings:site_secret:regenerate' => "Regenereer site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Let op: Het regenereren van het site secret kan ongemak met zich meebrengen voor sommige gebruikers. Denk hierbij aan het 'onthoud mij' cookie, email validatie verzoeken of uitnodigingscodes.",
	
	'admin:site:secret:regenerated' => "Het site secret is geregenereerd",
	'admin:site:secret:prevented' => "Het genereren van een nieuw sitegeheim code werd geblokeerd",
	
	'admin:notification:make_admin:admin:subject' => 'Er is een nieuwe site beheerder toegevoegd aan %s',
	'admin:notification:make_admin:admin:body' => 'Beste %s,

%s heeft %s toegevoegd als een site beheerder van %s.

Om het profiel van de nieuwe site beheerder te bekijken, klik hier:
%s

Om naar de website te gaan, klik hier
%s',
	
	'admin:notification:make_admin:user:subject' => 'Je bent toegevoegd als site beheerder van %s',
	'admin:notification:make_admin:user:body' => 'Beste %s,

%s heeft je een site beheerder gemaakt van %s.

Om naar de website te gaan, klik hier:
%s',
	'admin:notification:remove_admin:admin:subject' => 'Een site beheerder is verwijderd van %s',
	'admin:notification:remove_admin:admin:body' => 'Beste %s,

%s heeft %s verwijderd als site beheerder van %s.

Om het profiel van de oud beheerder te bekijken, klik hier:
%s

Om naar de website te gaan, klik hier:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'Je bent verwijderd als site beheerder van %s',
	'admin:notification:remove_admin:user:body' => 'Beste %s,

%s heeft je verwijderd als site beheerder van %s.

Om naar de website te gaan, klik hier:
%s',
	'user:notification:ban:subject' => 'Je account op %s is geblokkeerd',
	'user:notification:ban:body' => 'Beste %s,

Je account op %s is geblokkeerd.

Om naar de website te gaan, klik hier:
%s',
	
	'user:notification:unban:subject' => 'Je account op %s is niet meer geblokkeerd',
	'user:notification:unban:body' => 'Beste %s,

je account op %s is niet meer geblokkeerd, Je kunt weer gebruik maken van de website.

Om naar de website te gaan, klik hier:
%s',
	
/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins worden niet geladen, omdat een bestand genaamd "disabled" in de mod-directory gevonden is.',
	'plugins:settings:save:ok' => "De instellingen voor de plugin '%s' zijn succesvol opgeslagen.",
	'plugins:settings:save:fail' => "Er is een fout opgetreden tijdens het opslaan van de instellingen voor de plugin '%s'.",
	'plugins:usersettings:save:ok' => "Gebruikersinstellingen voor de plugin '%s' zijn succesvol opgeslagen.",
	'plugins:usersettings:save:fail' => "Er is een fout opgetreden tijden het opslaan van de gebruikersinstellingen van de plugin '%s'.",
	'item:object:plugin' => 'Plugins',
	'collection:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Alles activeren',
	'admin:plugins:deactivate_all' => 'Alles deactiveren',
	'admin:plugins:activate' => 'Activeren',
	'admin:plugins:deactivate' => 'Deactiveren',
	'admin:plugins:description' => "Via dit beheerpaneel kun je de verschillende tools van de site beheren en configureren.",
	'admin:plugins:opt:linktext' => "Configureer tools",
	'admin:plugins:opt:description' => "Configureer de tools die zijn geïnstalleerd op de site.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Naam",
	'admin:plugins:label:author' => "Auteur",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categorieën',
	'admin:plugins:label:licence' => "Licentie",
	'admin:plugins:label:website' => "Website",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Bestanden",
	'admin:plugins:label:resources' => "Bronnen",
	'admin:plugins:label:screenshots' => "Schermafbeeldingen",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Meld een probleem",
	'admin:plugins:label:donate' => "Doneer",
	'admin:plugins:label:moreinfo' => 'Meer informatie',
	'admin:plugins:label:version' => 'Versie',
	'admin:plugins:label:location' => 'Locatie',
	'admin:plugins:label:contributors' => 'Bijdragers',
	'admin:plugins:label:contributors:name' => 'Naam',
	'admin:plugins:label:contributors:email' => 'E-mailadres',
	'admin:plugins:label:contributors:website' => 'Website',
	'admin:plugins:label:contributors:username' => 'Community-gebruikersnaam',
	'admin:plugins:label:contributors:description' => 'Omschrijving',
	'admin:plugins:label:dependencies' => 'Afhankelijkheden',
	'admin:plugins:label:missing_dependency' => 'Ontbrekende afhankelijkheid [%s]',

	'admin:plugins:warning:unmet_dependencies' => 'Deze plugin heeft onvervulde afhankelijkheden en kan niet worden geactiveerd. Controleer de afhankelijkheden onder \'meer info\'.',
	'admin:plugins:warning:invalid' => '%s is geen geldige plugin voor Elgg. Controleer <a href="http://docs.elgg.org/Invalid_Plugin" target="_blank">de Elgg-documentatie</a> voor handige tips.',
	'admin:plugins:warning:invalid:check_docs' => 'Controleer <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">de Elgg documentatie</a> voor tips met betrekking tot foutopsporing.',
	'admin:plugins:cannot_activate' => 'kan niet activeren',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'De geselecteerde plugin(s) is/zijn al actief',
	'admin:plugins:already:inactive' => 'De geselecteerde plugin(s) is/zijn al inactief',

	'admin:plugins:set_priority:yes' => "%s herordend.",
	'admin:plugins:set_priority:no' => "Herordenen mislukt voor %s.",
	'admin:plugins:set_priority:no_with_msg' => "Herordenen mislukt voor %s. Fout: %s",
	'admin:plugins:deactivate:yes' => "%s uitgeschakeld.",
	'admin:plugins:deactivate:no' => "%s kon niet worden uitgeschakeld.",
	'admin:plugins:deactivate:no_with_msg' => "%s kon niet worden uitgeschakeld. Fout: %s",
	'admin:plugins:activate:yes' => "%s geactiveerd.",
	'admin:plugins:activate:no' => "%s kon niet worden geactiveerd.",
	'admin:plugins:activate:no_with_msg' => "%s kon niet worden uitgeschakeld. Fout: %s",
	'admin:plugins:categories:all' => 'Alle categorieën',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versie %s',
	'admin:plugin_settings' => 'Plugin-instellingen',
	'admin:plugins:warning:unmet_dependencies_active' => 'Deze plugin is geactiveerd maar heeft onvervulde afhankelijkheden. Je kunt problemen ervaren. Bekijk \'meer informatie\' hieronder voor details.',

	'admin:plugins:dependencies:type' => 'Type',
	'admin:plugins:dependencies:name' => 'Naam',
	'admin:plugins:dependencies:expected_value' => 'Geteste waarde',
	'admin:plugins:dependencies:local_value' => 'Echte waarde',
	'admin:plugins:dependencies:comment' => 'Commentaar',

	'admin:statistics:description' => "Dit is een overzicht van onder andere gebruikersstatistieken van de site. Als je meer gedetailleerde informatie nodig hebt, is er een professionele beheerfunctie beschikbaar.",
	'admin:statistics:opt:description' => "Bekijk statistische gegevens over gebruikers en objecten op de site.",
	'admin:statistics:opt:linktext' => "Bekijk statistieken",
	'admin:statistics:label:user' => "Gebruikers statistieken",
	'admin:statistics:label:numentities' => "Entities op de site",
	'admin:statistics:label:numusers' => "Aantal gebruikers",
	'admin:statistics:label:numonline' => "Aantal gebruikers online",
	'admin:statistics:label:onlineusers' => "Online gebruikers",
	'admin:statistics:label:admins'=>"Beheerders",
	'admin:statistics:label:version' => "Elgg-versie",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versie",
	'admin:statistics:label:version:code' => "Code versie",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Toon PHPInfo',
	'admin:server:label:web_server' => 'Webserver',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Locatie van log',
	'admin:server:label:php_version' => 'PHP-versie',
	'admin:server:label:php_ini' => 'Locatie van PHP-.inibestand',
	'admin:server:label:php_log' => 'PHP-log',
	'admin:server:label:mem_avail' => 'Geheugen beschikbaar',
	'admin:server:label:mem_used' => 'Geheugen gebruikt',
	'admin:server:error_log' => "Foutlog van webserver",
	'admin:server:label:post_max_size' => 'Maximale POST-grootte',
	'admin:server:label:upload_max_filesize' => 'Maximale grootte van uploadbestanden',
	'admin:server:warning:post_max_too_small' => '(PS: post_max_size moet groter zijn dan deze waarde om uploads van deze grootte te ondersteunen)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache is niet beschikbaar op deze server, of is nog niet geconfigureerd in de Elgg configuratie.
		Voor verbeterde performance wordt het aangeraden om Memcache (of Redis) in te schakelen en te configureren.
',

	'admin:server:label:redis' => 'Redis',
	'admin:server:redis:inactive' => '
		Redis is niet beschikbaar op deze server, of is nog niet geconfigureerd in de Elgg configuratie.
		Voor verbeterde performance wordt het aangeraden om Redis (of Memcache) in te schakelen en te configureren.
',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => '
		OPcache is niet beschikbaar op deze server, of is nog niet geactiveerd.
		Voor verbeterde performance wordt het aangeraden om OPcache in te schakelen en te configureren.
',
	
	'admin:user:label:search' => "Gebruikers zoeken:",
	'admin:user:label:searchbutton' => "Zoek",

	'admin:user:ban:no' => "Kan gebruiker niet blokkeren",
	'admin:user:ban:yes' => "Gebruiker geblokkeerd.",
	'admin:user:self:ban:no' => "Je kunt jezelf niet blokkeren",
	'admin:user:unban:no' => "Kan gebruiker niet deblokkeren",
	'admin:user:unban:yes' => "Gebruiker gedeblokkeerd.",
	'admin:user:delete:no' => "Kan gebruiker niet verwijderen",
	'admin:user:delete:yes' => "De gebruiker %s is verwijderd",
	'admin:user:self:delete:no' => "Je kunt jezelf niet verwijderen",

	'admin:user:resetpassword:yes' => "Wachtwoord gereset, gebruiker op de hoogte gebracht.",
	'admin:user:resetpassword:no' => "Wachtwoord kon niet worden gereset.",

	'admin:user:makeadmin:yes' => "Gebruiker is nu een beheerder.",
	'admin:user:makeadmin:no' => "Gebruiker kon geen beheerder worden gemaakt.",

	'admin:user:removeadmin:yes' => "Gebruiker is geen beheerder meer.",
	'admin:user:removeadmin:no' => "We konden de beheerrechten van deze gebruiker niet verwijderen.",
	'admin:user:self:removeadmin:no' => "Je kunt jezelf de beheerrechten niet afnemen!",

	'admin:configure_utilities:menu_items' => 'Menu items',
	'admin:menu_items:configure' => 'Configureer items in het hoofdmenu',
	'admin:menu_items:description' => 'Selecteer welke menu-items je als hoofditems wilt zien. Ongebruikte items zullen worden toegevoegd aan "Meer" aan het einde van de lijst.',
	'admin:menu_items:hide_toolbar_entries' => 'Verwijder links uit het menu?',
	'admin:menu_items:saved' => 'Menu-items opgeslagen.',
	'admin:add_menu_item' => 'Voeg een eigen menu-item toe',
	'admin:add_menu_item:description' => 'Vul de weergavenaam en URL in om een eigen menu-item toe te voegen aan het navigatiemenu.',

	'admin:configure_utilities:default_widgets' => 'Standaard widgets',
	'admin:default_widgets:unknown_type' => 'Onbekend widgettype',
	'admin:default_widgets:instructions' => 'Plaats, verwijder, positioneer en configureer de standaard widgets voor de geselecteerde pagina.
Deze wijzigingen hebben alleen effect op nieuwe gebruikers van de website.',

	'admin:robots.txt:instructions' => "Je kunt hieronder de robots.txt van de site bewerken.",
	'admin:robots.txt:plugins' => "Plugins zullen het volgende toevoegen aan de robots.txt:",
	'admin:robots.txt:subdir' => "De robots.txt-tool zal niet werken, omdat Elgg in een submap is geïnstalleerd.",
	'admin:robots.txt:physical' => "De robots.txt tool zal niet werken omdat er een fysiek robots.txt bestand aanwezig is.",

	'admin:maintenance_mode:default_message' => 'De site is offline in verband met onderhoud.',
	'admin:maintenance_mode:instructions' => 'Je kunt de onderhoudsmodus het beste alléén gebruiken als er sprake is van een upgrade, of als je grote veranderingen aan de site wilt aanbrengen.
Wanneer de site in onderhoudsmodus is kunnen alleen sitebeheerders inloggen en de site bekijken!',
	'admin:maintenance_mode:mode_label' => 'Onderhoudsmodus',
	'admin:maintenance_mode:message_label' => 'Bericht dat gebruikers zien als de site in onderhoudsmodus is',
	'admin:maintenance_mode:saved' => 'De instellingen voor onderhoudsmodus zijn opgeslagen',
	'admin:maintenance_mode:indicator_menu_item' => 'De site is in onderhoudsmodus.',
	'admin:login' => 'Login voor sitebeheerder',

/**
 * User settings
 */

	'usersettings:description' => "Het paneel voor gebruikersinstellingen geeft je controle over al je persoonlijke instellingen: van gebruikersmanagement tot hoe plugins zijn geconfigureerd. Kies een optie om te beginnen.",

	'usersettings:statistics' => "Jouw statistieken",
	'usersettings:statistics:opt:description' => "Bekijk statistische gegevens van gebruikers en objecten op je site.",
	'usersettings:statistics:opt:linktext' => "Accountstatistieken",

	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "Jouw instellingen",
	'usersettings:user:opt:description' => "Hier kun je je gebruikersinstellingen configureren.",
	'usersettings:user:opt:linktext' => "Wijzig je instellingen",

	'usersettings:plugins' => "Plugins",
	'usersettings:plugins:opt:description' => "Configureer instellingen (als die er zijn) voor je actieve plugins.",
	'usersettings:plugins:opt:linktext' => "Configureer je plugins",

	'usersettings:plugins:description' => "Dit paneel staat je toe persoonlijke instellingen te maken voor plugins die door de sitebeheerder zijn geïnstalleerd.",
	'usersettings:statistics:label:numentities' => "Jouw inhoud",

	'usersettings:statistics:yourdetails' => "Jouw details",
	'usersettings:statistics:label:name' => "Volledige naam",
	'usersettings:statistics:label:email' => "E-mailadres",
	'usersettings:statistics:label:membersince' => "Lid sinds",
	'usersettings:statistics:label:lastlogin' => "Laatst aangemeld op",

/**
 * Activity river
 */

	'river:all' => 'Alle site-activiteit',
	'river:mine' => 'Mijn activiteit',
	'river:owner' => 'Activiteit van %s',
	'river:friends' => 'Activiteit van vrienden',
	'river:select' => 'Toon %s',
	'river:comments:more' => '+%u meer',
	'river:comments:all' => 'Bekijk alle %u reacties',
	'river:generic_comment' => 'reageerde op %s %s',

/**
 * Icons
 */

	'icon:size' => "Grootte van de avatar",
	'icon:size:topbar' => "Bovenbalk",
	'icon:size:tiny' => "Extra klein",
	'icon:size:small' => "Klein",
	'icon:size:medium' => "Normaal",
	'icon:size:large' => "Groot",
	'icon:size:master' => "Extra groot",
	
	'entity:edit:icon:file:label' => "Upload een nieuw icoon",
	'entity:edit:icon:file:help' => "Laat dit leeg om het huidige icoon te behouden",
	'entity:edit:icon:remove:label' => "Verwijder het icoon",

/**
 * Generic action words
 */

	'save' => "Opslaan",
	'save_go' => "Opslaan en doorgaan naar %s",
	'reset' => 'Wis',
	'publish' => "Publiceer",
	'cancel' => "Annuleren",
	'saving' => "Bezig met opslaan ...",
	'update' => "Wijzig",
	'preview' => "Voorbeeld",
	'edit' => "Bewerk",
	'delete' => "Verwijder",
	'accept' => "Accepteer",
	'reject' => "Afwijzen",
	'decline' => "Afwijzen",
	'approve' => "Toestaan",
	'activate' => "Activeren",
	'deactivate' => "Deactiveren",
	'disapprove' => "Afkeuren",
	'revoke' => "Intrekken",
	'load' => "Laden",
	'upload' => "Upload",
	'download' => "Download",
	'ban' => "Blokkeer",
	'unban' => "Deblokkeer",
	'banned' => "Geblokkeerd",
	'enable' => "Activeren",
	'disable' => "Deactiveren",
	'request' => "Aanvraag",
	'complete' => "Compleet",
	'open' => 'Open',
	'close' => 'Sluiten',
	'hide' => 'Verbergen',
	'show' => 'Tonen',
	'reply' => "Antwoord",
	'more' => 'Meer',
	'more_info' => 'Meer informatie',
	'comments' => 'Reacties',
	'import' => 'Import',
	'export' => 'Export',
	'untitled' => 'Geen titel',
	'help' => 'Help',
	'send' => 'Verstuur',
	'post' => 'Plaats',
	'submit' => 'Verstuur',
	'comment' => 'Reageer',
	'upgrade' => 'Upgrade',
	'sort' => 'Sorteer',
	'filter' => 'Filter',
	'new' => 'Nieuw',
	'add' => 'Voeg toe',
	'create' => 'Aanmaken',
	'remove' => 'Verwijder',
	'revert' => 'Herstel',
	'validate' => 'Valideer',
	'read_more' => 'Lees meer',

	'site' => 'Website',
	'activity' => 'Activiteit',
	'members' => 'Leden',
	'menu' => 'Menu',

	'up' => 'Omhoog',
	'down' => 'Omlaag',
	'top' => 'Boven',
	'bottom' => 'Beneden',
	'right' => 'Rechts',
	'left' => 'Links',
	'back' => 'Terug',

	'invite' => "Uitnodigen",

	'resetpassword' => "Wachtwoord resetten",
	'changepassword' => "Wachtwoord wijzigen",
	'makeadmin' => "Geef sitebeheerderrechten",
	'removeadmin' => "Trek sitebeheerderrechten in",

	'option:yes' => "Ja",
	'option:no' => "Nee",

	'unknown' => 'Onbekend',
	'never' => 'Nooit',

	'active' => 'Actief',
	'total' => 'Totaal',

	'ok' => 'OK',
	'any' => 'Welke dan ook',
	'error' => 'Fout',

	'other' => 'Andere',
	'options' => 'Opties',
	'advanced' => 'Geavanceerd',

	'learnmore' => "Klik hier voor meer informatie.",
	'unknown_error' => 'Onbekende fout',

	'content' => "inhoud",
	'content:latest' => 'Laatste activiteit',
	'content:latest:blurb' => 'Of klik hier om de laatste inhoud van de hele site te bekijken',

	'link:text' => 'bekijk link',

/**
 * Generic questions
 */

	'question:areyousure' => 'Weet je het zeker?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Niet-opgeslagen concept',
	'status:draft' => 'Concept',
	'status:unpublished' => 'Ongepubliceerd',
	'status:published' => 'Gepubliceerd',
	'status:featured' => 'Uitgelicht',
	'status:open' => 'Open',
	'status:closed' => 'Gesloten',

/**
 * Generic sorts
 */

	'sort:newest' => 'Nieuwste',
	'sort:popular' => 'Populair',
	'sort:alpha' => 'Alfabetisch',
	'sort:priority' => 'Prioriteit',

/**
 * Generic data words
 */

	'title' => "Titel",
	'description' => "Omschrijving",
	'tags' => "Tags",
	'all' => "Alle",
	'mine' => "Mijn",

	'by' => 'door',
	'none' => 'geen',

	'annotations' => "Opmerkingen",
	'relationships' => "Relaties",
	'metadata' => "Metadata",
	'tagcloud' => "Tag-cloud",

	'on' => 'Aan',
	'off' => 'Uit',

/**
 * Entity actions
 */

	'edit:this' => 'Bewerk dit',
	'delete:this' => 'Verwijder dit',
	'comment:this' => 'Reageer hierop',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Weet je zeker dat je dit item wilt verwijderen?",
	'deleteconfirm:plural' => "Weet je zeker dat je deze items wilt verwijderen?",
	'fileexists' => "Er is reeds een bestand geüpload. Om het te vervangen selecteer hieronder een nieuw bestand",
	'input:file:upload_limit' => 'De maximale bestandsgrootte is %s',

/**
 * User add
 */

	'useradd:subject' => 'Gebruikersaccount aangemaakt',
	'useradd:body' => '%s,

Er is een gebruikersaccount voor je aangemaakt op %s. Om je aan te melden bezoek:

%s

Om je aan te kunnen melden moet je gebruik maken van de volgende gegevens:

Gebruikersnaam: %s
Wachtwoord: %s

Nadat je bent aangemeld raden we je aan je wachtwoord te wijzigen.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Klik om te verbergen",


/**
 * Messages
 */
	'messages:title:success' => 'Succes',
	'messages:title:error' => 'Fout',
	'messages:title:warning' => 'Waarschuwing',
	'messages:title:help' => 'Help',
	'messages:title:notice' => 'Bericht',

/**
 * Import / export
 */

	'importsuccess' => "Importeren van data was succesvol",
	'importfail' => "OpenDD data importeren is mislukt.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'G:i',

	'friendlytime:justnow' => "zojuist",
	'friendlytime:minutes' => "%s minuten geleden",
	'friendlytime:minutes:singular' => "een minuut geleden",
	'friendlytime:hours' => "%s uren geleden",
	'friendlytime:hours:singular' => "een uur geleden",
	'friendlytime:days' => "%s dagen geleden",
	'friendlytime:days:singular' => "gisteren",
	'friendlytime:date_format' => 'j F Y @ G:i',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "over %s minuten",
	'friendlytime:future:minutes:singular' => "zometeen",
	'friendlytime:future:hours' => "over %s uur",
	'friendlytime:future:hours:singular' => "over een uurtje",
	'friendlytime:future:days' => "over %s dagen",
	'friendlytime:future:days:singular' => "morgen",

	'date:month:01' => '%s januari',
	'date:month:02' => '%s februari',
	'date:month:03' => '%s maart',
	'date:month:04' => '%s april',
	'date:month:05' => '%s mei',
	'date:month:06' => '%s juni',
	'date:month:07' => '%s juli',
	'date:month:08' => '%s augustus',
	'date:month:09' => '%s september',
	'date:month:10' => '%s oktober',
	'date:month:11' => '%s november',
	'date:month:12' => '%s december',

	'date:month:short:01' => '%s jan',
	'date:month:short:02' => '%s feb',
	'date:month:short:03' => '%s mrt',
	'date:month:short:04' => '%s apr',
	'date:month:short:05' => '%s mei',
	'date:month:short:06' => '%s jun',
	'date:month:short:07' => '%s jul',
	'date:month:short:08' => '%s aug',
	'date:month:short:09' => '%s sep',
	'date:month:short:10' => '%s okt',
	'date:month:short:11' => '%s nov',
	'date:month:short:12' => '%s dec',

	'date:weekday:0' => 'zondag',
	'date:weekday:1' => 'maandag',
	'date:weekday:2' => 'dinsdag',
	'date:weekday:3' => 'woensdag',
	'date:weekday:4' => 'donderdag',
	'date:weekday:5' => 'vrijdag',
	'date:weekday:6' => 'zaterdag',

	'date:weekday:short:0' => 'zo',
	'date:weekday:short:1' => 'ma',
	'date:weekday:short:2' => 'di',
	'date:weekday:short:3' => 'wo',
	'date:weekday:short:4' => 'do',
	'date:weekday:short:5' => 'vr',
	'date:weekday:short:6' => 'za',

	'interval:minute' => 'Elke minuut',
	'interval:fiveminute' => 'Elke vijf minuten',
	'interval:fifteenmin' => 'Elke vijftien minuten',
	'interval:halfhour' => 'Elk half uur',
	'interval:hourly' => 'Elk uur',
	'interval:daily' => 'Elke dag',
	'interval:weekly' => 'Elke week',
	'interval:monthly' => 'Elke maand',
	'interval:yearly' => 'Elk jaar',

/**
 * System settings
 */

	'installation:sitename' => "De naam van je site (bijvoorbeeld 'Mijn sociale netwerksite'):",
	'installation:sitedescription' => "Korte omschrijving van je site (optioneel)",
	'installation:sitedescription:help' => "Met de gebundelde plugins zal dit enkel verschijnen in de 'description' meta-tag voor zoekmachine resultaten.",
	'installation:wwwroot' => "De site-URL, gevolgd door een slash:",
	'installation:path' => "Het volledige pad naar de hoofdmap van de site op de schijf, gevolgd door een slash:",
	'installation:dataroot' => "Het volledige pad naar de map waar de uploads worden opgeslagen, gevolgd door een slash:",
	'installation:dataroot:warning' => "Je moet deze map handmatig aanmaken. Je plaatst de map <strong>buiten</strong> de mapstructuur van de Elgg-installatie!",
	'installation:sitepermissions' => "Het standaard toegangsniveau:",
	'installation:language' => "De standaardtaal voor de site:",
	'installation:debug' => "Debug mode geeft extra informatie die gebruikt kan worden om fouten te achterhalen. Let op: dit vertraagt het systeem! Gebruik dit alleen als je problemen ondervindt. ",
	'installation:debug:label' => "Logniveau:",
	'installation:debug:none' => 'Debug-mode uitschakelen (aanbevolen)',
	'installation:debug:error' => 'Alleen kritieke fouten weergeven',
	'installation:debug:warning' => 'Fouten en waarschuwingen weergeven',
	'installation:debug:notice' => 'Log alle fouten, waarschuwingen en mededelingen',
	'installation:debug:info' => 'Log alles',

	// Walled Garden support
	'installation:registration:description' => 'Registratie is standaard ingeschakeld. Je kunt dit uitschakelen als je niet wilt dat gebruikers zichzelf kunnen registreren.',
	'installation:registration:label' => 'Nieuwe gebruikers mogen zich registreren',
	'installation:walled_garden:description' => 'Maak van deze site een privénetwerk. Dit zorgt ervoor dat niet-aangemelde gebruikers niets kunnen zien van deze site, tenzij inhoud die geplaatst wordt specifiek publiekelijk is gedeeld.',
	'installation:walled_garden:label' => 'Alleen aangemelde gebruikers mogen pagina\'s zien.',

	'installation:view' => "Geeft de view op die standaard wordt gebruikt binnen de site, of laat het leeg voor de standaardview. Bij twijfel, laat de standaard staan!",

	'installation:siteemail' => "Site e-mailadres (dit wordt gebruikt voor het verzenden van systeem e-mails):",
	'installation:siteemail:help' => "Waarschuwing: Gebruik geen e-mailadres dat geassocieerd is met andere third-party diensten, zoals een helpdesk systeem, die overweg kan met inkomende emailberichten, aangezien dat een risico bevat dat er onbedoelde privé informatie gedeeld kan worden. Idealiter is een e-mailadres gewenst dat enkel voor deze website gebruikt zal worden.",
	'installation:default_limit' => "Standaard aantal items per pagina",

	'admin:site:access:warning' => "Het wijzigen van de toegangsinstellingen is alleen van toepassing op nieuwe content.",
	'installation:allow_user_default_access:description' => "Als je dit aanvinkt hebben individuele gebruikers de mogelijkheid om hun eigen standaard toegangsniveau in te stellen. Dit kan anders zijn dan de standaardinstelling van de site.",
	'installation:allow_user_default_access:label' => "Gebruikers standaardtoegang toestaan",

	'installation:simplecache:description' => "De simple cache verhoogt de performance door statische content te cachen, waaronder sommige CSS- en Javascriptbestanden. Normaal gezien wil je dit aan hebben staan.",
	'installation:simplecache:label' => "Gebruik simple cache",

	'installation:cache_symlink:description' => "De symbolic link naar de simple cache map staat de webserver toe om statische content te serveren zonder de engine te starten. Dat kan de performance van de server sterk verbeteren",
	'installation:cache_symlink:label' => "Gebruik een symbolic link naar de simple cache directory (aanbevolen)",
	'installation:cache_symlink:warning' => "Symbolic link is aangemaakt. Indien je deze link wilt verwijderen, verwijder de symbolic link directory van de server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Vanwege de server configuratie kan de symbolic link configuratie niet automatisch worden aangemaakt. Kijk naar de documentatie hoe je de link handmatig aan kunt maken.",

	'installation:minify:description' => "De 'simple cache' kan tevens de prestaties verbeteren door JavaScript- en CSS-bestanden te comprimeren. Dit vereist dat 'simple cache' aanstaat. ",
	'installation:minify_js:label' => "JavaScript comprimeren (aangeraden)",
	'installation:minify_css:label' => "CSS comprimeren (aangeraden)",

	'installation:htaccess:needs_upgrade' => "Je moet het bestand .htaccess zodanig wijzigen dat het pad geïnjecteerd wordt in de GET-parameter __elgg_uri (je kunt install/config/htaccess.dist als voorbeeld gebruiken).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg kan niet zelf de rewrite-rules testen. Controleer dat curl werkt en dat er geen IP-restricties zijn die localhost connecties blokkeren.",

	'installation:systemcache:description' => "De systeemcache verlaagt de laadtijd van de Elgg-engine door data te cachen naar bestanden.",
	'installation:systemcache:label' => "Gebruik systeemcache (aanbevolen)",

	'admin:legend:system' => 'Systeem',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Toegang tot content',
	'admin:legend:site_access' => 'Toegang tot de site',
	'admin:legend:debug' => 'Debugging en loggen',
	
	'config:remove_branding:label' => "Verwijder Elgg uitingen",
	'config:remove_branding:help' => "Overal op de website zijn er verschillende links en logo's welke aantonen dat de website is gemaakt middels Elgg. Als je de uitingen verwijderd overweeg om een donatie te doen op https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Schakel RSS feeds uit",
	'config:disable_rss:help' => "Schakel dit uit om de RSS feeds niet meer te promoten",
	'config:friendly_time_number_of_days:label' => "Aantal dagen dat de relatieve tijdsweergave gebruikt mag worden",
	'config:friendly_time_number_of_days:help' => "Je kunt het aantal dagen configureren dat de relatieve tijdsweergave gebruikt wordt. Na het opgegeven aantal dagen zal de relatieve tijdsweergave wijzigen in een normaal datum formaat. Indien dit wordt ingesteld op 0 zal de relatieve tijdsweergave niet gebruikt worden.",
	
	'upgrading' => 'Bijwerken',
	'upgrade:core' => 'Je Elgg-installatie is bijgewerkt',
	'upgrade:unlock' => 'Ontgrendel upgrade',
	'upgrade:unlock:confirm' => "De database is geblokkeerd door een andere upgrade. Meerdere upgrades tegelijkertijd laten lopen is gevaarlijk. Je moet alleen verder gaan als je zeker weet dat er geen andere upgrade draait. Ontgrendelen?",
	'upgrade:terminated' => 'De uprgade is afgebroken door een event handler',
	'upgrade:locked' => "Upgrade is niet mogelijk: er loopt reeds een upgrade. Om de upgrade te ontgrendelen bezoek je de beheersectie.",
	'upgrade:unlock:success' => "Upgrade succesvol ontgrendeld",
	'upgrade:unable_to_upgrade' => 'Upgrade niet mogelijk.',
	'upgrade:unable_to_upgrade_info' => 'De installatie kan niet worden geüpgrade vanwege de aanwezigheid van legacy views in de Elgg core views map. Deze views zijn verouderd en dienen te worden verwijderd om Elgg correct te laten functioneren. 
Indien je geen aanpassingen hebt gemaakt aan de Elgg core kun je de views map verwijderen en vervangen met de inhoud uit de laatste Elgg release welke te downloaden is op <a href="http://elgg.org">elgg.org</a>.<br /><br />

Indien je gedetailleerde informatie nodig hebt, bekijk de <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">Upgrading Elgg documentatie</a>.
Indien de ondersteuning nodig hebt bezoek de <a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (voorheen OAuth Lib) is uitgeschakeld tijdens de upgrade. Activeer deze zelf weer als het nodig is.',
	'upgrade:site_secret_warning:moderate' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren! Zie ook: Configureren &gt; Instellingen &gt; Geavanceerd",
	'upgrade:site_secret_warning:weak' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren! Zie ook: Configureren &gt; Instellingen &gt; Geavanceerd",

	'deprecated:function' => '%s() is vervangen door %s()',

	'admin:pending_upgrades' => 'Er zijn belangrijke upgrades waar je beter nu meteen aandacht aan kunt schenken!',
	'admin:view_upgrades' => 'Bekijk de upgrades.',
	'item:object:elgg_upgrade' => 'Site-upgrades',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'De installatie is up-to-date!',

	'upgrade:item_count' => 'Er zijn <b>%s</b> zaken die geüpgrade moeten worden.',
	'upgrade:warning' => 'Let op: dit kan lang duren, als je een grote site hebt!',
	'upgrade:success_count' => 'Geüpgraded:',
	'upgrade:error_count' => 'Fouten:',
	'upgrade:finished' => 'Upgrade is voltooid.',
	'upgrade:finished_with_errors' => '<p>De upgrade is afgerond, maar er zijn fouten geconstateerd. Ververs de pagina en probeer de upgrade opnieuw uit te voeren.</p></p><br />Als de fouten zich opnieuw voordoen, kijk dan in je serverlog of daar een mogelijke oorzaak te vinden is. Je kunt bijstand krijgen bij het oplossen van de fout op de community van Elgg <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">in de groep \'technisch(e) support\'</a>',
	'upgrade:should_be_skipped' => 'Geen items om te upgraden',
	'upgrade:count_items' => '%d items te upgraden',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Welkom",
	'welcome:user' => 'Welkom %s',

/**
 * Emails
 */

	'email:from' => 'Van',
	'email:to' => 'Aan',
	'email:subject' => 'Titel',
	'email:body' => 'Bericht',

	'email:settings' => "E-mailinstellingen",
	'email:address:label' => "Jouw e-mailadres",
	'email:address:password' => "Wachtwoord",
	'email:address:password:help' => "Om je e-mail adres te kunnen wijzigen moet je je huidige wachtwoord bevestigen.",

	'email:save:success' => "Het nieuwe e-mail adres is opgeslagen, en er wordt verificatie gevraagd.",
	'email:save:fail' => "Je nieuwe e-mailadres kon niet worden opgeslagen.",
	'email:save:fail:password' => "Het opgegeven wachtwoord komt niet overeen met je huidige wachtwoord, je e-mail adres kon niet worden aangepast.",

	'friend:newfriend:subject' => "%s heeft jou toegevoegd als vriend!",
	'friend:newfriend:body' => "%s heeft je toegevoegd als vriend!

Klik hier om naar het profile te gaan:

%1",

	'email:changepassword:subject' => "Wachtwoord gewijzigd!",
	'email:changepassword:body' => "Beste %s,

je wachtwoord is gewijzigd.",

	'email:resetpassword:subject' => "Wachtwoord reset!",
	'email:resetpassword:body' => "Beste %s,

Je wachtwoord is gereset naar: %s",

	'email:changereq:subject' => "Aanvraag om het wachtwoord te wijzigen.",
	'email:changereq:body' => "Beste %s,

Iemand (van het IP adres %s) heeft een nieuw wachtwoord aangevraagd voor dit account.

Indien je dit zelf hebt aangevraagd, klik op onderstaande link. Anders negeer deze e-mail.

%s",

/**
 * user default access
 */

	'default_access:settings' => "Je standaard toegangsniveau",
	'default_access:label' => "Standaardtoegang",
	'user:default_access:success' => "Je nieuwe standaard toegangsniveau is opgeslagen.",
	'user:default_access:failure' => "Je nieuwe standaard toegangsniveau is niet opgeslagen.",

/**
 * Comments
 */

	'comments:count' => "%s reacties",
	'item:object:comment' => 'Reacties',
	'collection:object:comment' => 'Reacties',

	'river:object:default:comment' => '%s reageerde op %s',

	'generic_comments:add' => "Voeg een reactie toe",
	'generic_comments:edit' => "Reactie bewerken",
	'generic_comments:post' => "Plaats reactie",
	'generic_comments:text' => "Reactie",
	'generic_comments:latest' => "Laatste reacties",
	'generic_comment:posted' => "Je reactie is succesvol geplaatst.",
	'generic_comment:updated' => "De reactie is gewijzigd",
	'entity:delete:object:comment:success' => "De reactie is succesvol verwijderd",
	'generic_comment:blank' => "Sorry, je moet wel wat invullen voordat we je reactie kunnen opslaan!",
	'generic_comment:notfound' => "Sorry, we konden de opgegeven reactie niet vinden.",
	'generic_comment:notfound_fallback' => "Sorry, we konden de opgegeven reactie niet vinden, maar we hebben je doorgestuurd naar de pagina waar de reactie is achtergelaten.",
	'generic_comment:failure' => "Er is een fout opgetreden tijdens het opslaan van je reactie. Probeer het nogmaals.",
	'generic_comment:none' => 'Geen reacties',
	'generic_comment:title' => 'Reactie door %s',
	'generic_comment:on' => '%s op %s',
	'generic_comments:latest:posted' => 'plaatste een',

	'generic_comment:notification:owner:subject' => 'Je hebt een nieuwe reactie',
	'generic_comment:notification:owner:summary' => 'Je hebt een nieuwe reactie',
	'generic_comment:notification:owner:body' => "Je hebt een nieuwe reactie gekregen op je item \"%s\" van %s.

%s

Om te reageren of het originele item te bekijken, klik hier:
%s

Om naar het profiel van %s te gaan, klik hier:
%s",
	
	'generic_comment:notification:user:subject' => 'Een nieuwe reactie op: %s',
	'generic_comment:notification:user:summary' => 'Een nieuwe reactie op: %s',
	'generic_comment:notification:user:body' => "Je hebt een nieuwe reactie gekregen op je item \"%s\" van %s.

%s

Om te reageren of het originele item te bekijken, klik hier:
%s

Om naar het profiel van te gaan, klik hier:
%s",

/**
 * Entities
 */

	'byline' => 'Door %s',
	'byline:ingroup' => 'in de groep %s',
	'entity:default:missingsupport:popup' => 'Deze entity kan niet correct worden weergegeven. Dit kan komen doordat er ondersteuning nodig is van een plugin die niet meer is geïnstalleerd.',

	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item niet gevonden',
	'entity:delete:permission_denied' => 'U beschikt niet over de juiste rechten om dit item te verwijderen.',
	'entity:delete:success' => '%s is verwijderd.',
	'entity:delete:fail' => '%s kon niet worden verwijderd.',

	'entity:can_delete:invaliduser' => 'Kan canDelete() voor user_guid [%s] niet nakijken omdat de gebruiker niet bestaat.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Het formulier mist __token en/of __ts veld(en)',
	'actiongatekeeper:tokeninvalid' => "Er was een probleem (token mismatch). Dit betekent waarschijnlijk dat de gebruikte pagina verlopen was. Probeer het nogmaals.",
	'actiongatekeeper:timeerror' => 'De gebruikte pagina is verlopen. Ververs en probeer het nogmaals.',
	'actiongatekeeper:pluginprevents' => 'Sorry. Het formulier kon niet worden verwerkt om onbekende reden.',
	'actiongatekeeper:uploadexceeded' => 'De totale grootte van de ge-uploade bestanden is meer dan is toegestaan door de site beheerder',
	'actiongatekeeper:crosssitelogin' => "Je mag niet inloggen vanaf een ander domein. Ga naar het juiste adres (url) en probeer het nogmaals.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'en, het, dan, maar, hij, zijn, haar, hem, een, niet, ook, ongeveer, nu, dus, wel, toch, is, anders, omgekeerd, maar dus, in plaats daarvan, intussen, derhalve, dit, lijkt, wat, wie, wiens, wie dan ook, wie dan ook',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Kan geen verbinding maken met %s. Je kunt problemen ervaren tijdens het opslaan van content.',
	'js:security:token_refreshed' => 'Verbinding met %s is hersteld!',
	'js:lightbox:current' => "afbeelding %s van %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Aangedreven door Elgg",

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
	"cmn" => "Mandarijn Chinees", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "Duits",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "Engels",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Baskisch (Spanje)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "Frans",
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
	"nl" => "Nederlands",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portugees (Brazilië)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romeens (Roemenië)",
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
	"sr_latin" => "Servisch (Latijn)",
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
	"zh_hans" => "Vereenvoudigd Chinees",
	"zu" => "Zulu",

	"field:required" => 'Vereist',

	"core:upgrade:2017080900:title" => "Wijzig de database codering om multi-byte te ondersteunen",
	"core:upgrade:2017080900:description" => "Wijzigt de database codering naar utf8mb4 om ondersteuning te bieden voor multi-byte karakters zoals emoji's",

	"core:upgrade:2017080950:title" => "Update de standaard beveiligingsinstellingen",
	"core:upgrade:2017080950:description" => "De geïnstalleerde Elgg versie bied een aantal extra beveiligingsinstellingen. Het wordt aangeraden om deze upgrade uit te voeren om de instellingen goed in te stellen. Je kunt de instellingen ten alle tijden wijzigen onder de Beveiligingsinstellingen",

	"core:upgrade:2017121200:title" => "Maakt de vrienden access collections",
	"core:upgrade:2017121200:description" => "Migreer het vrienden toegangsniveau naar een access collection",

	"core:upgrade:2018041800:title" => "Activeer nieuwe plugins",
	"core:upgrade:2018041800:description" => "Bepaalde core functionaliteiten zijn verhuisd naar eigen plugins. Deze upgrade zal deze nieuwe plugin inschakelen om compatibiliteit te bieden aan derde partij plugins welke misschien afhankelijk zijn van deze functionaliteiten.",

	"core:upgrade:2018041801:title" => "Verwijder oude plugin entiteiten",
	"core:upgrade:2018041801:description" => "Verwijder entiteiten welke geassocieerd zijn aan plugins welke zijn verwijderd in Elgg 3.0",
	
	"core:upgrade:2018061401:title" => "Migreer cron logboek",
	"core:upgrade:2018061401:description" => "Migreer de cron logboek bijdrages in de database naar de nieuwe locatie.",
);
