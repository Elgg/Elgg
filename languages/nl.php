<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sites',

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
	'session_expired' => "Je sessie is verlopen, en daarom ben je niet meer ingelogd. Herlaad de pagina om in te loggen. ",

	'loggedinrequired' => "Je moet aangemeld zijn om die pagina te kunnen bekijken.",
	'adminrequired' => "Je moet een beheerder zijn om die pagina te kunnen bekijken.",
	'membershiprequired' => "Je moet lid zijn van deze groep om deze pagina te kunnen bekijken.",
	'limited_access' => "Je hebt niet de juiste rechten om deze pagina te zien.",


/**
 * Errors
 */

	'exception:title' => "Fatale fout.",
	'exception:contact_admin' => 'Er is een onherstelbare fout opgetreden en gelogd. Neem contact op met de sitebeheerder met de volgende informatie:',

	'actionundefined' => "De gevraagde actie (%s) is niet gedefinieerd in het systeem.",
	'actionnotfound' => "Het actiebestand voor %s kon niet worden gevonden.",
	'actionloggedout' => "Sorry, je kunt deze actie niet uitvoeren als je bent afgemeld.",
	'actionunauthorized' => 'Je bent niet geautoriseerd om deze actie uit te voeren',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is een verkeerd geconfigureerde plugin. Hij is uitgeschakeld. In de Elgg-wiki kun je mogelijke oorzaken vinden (http://docs.elgg.org/wiki/).",
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
	'ElggPlugin:Exception:CannotIncludeFile' => 'Kan %s niet laden voor plugin %s (guid: %s) in %s. Controleer de rechten!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Kan de views-map niet openen van plugin %s (guid: %s) in %s. Controleer de rechten!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Kan vertalingen voor plugin %s (guid: %s) in %s niet registeren. Controleer de rechten!',
	'ElggPlugin:Exception:NoID' => 'Geen ID voor plugin-guid %s!',
	'PluginException:NoPluginName' => "De pluginnaam kon niet worden gevonden",
	'PluginException:ParserError' => 'Fout tijdens het lezen van de manifest met API-versie %s in plugin %s',
	'PluginException:NoAvailableParser' => 'Kan geen parser vinden voor manifest API-versie %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Ontbrekend verplicht '%s' attribuut in manifest van plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s is een ongeldige plugin. Hij is daarom uitgeschakeld.',

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


	'RegistrationException:EmptyPassword' => 'De wachtwoordvelden mogen niet leeg zijn!',
	'RegistrationException:PasswordMismatch' => 'Wachtwoorden moeten gelijk zijn',
	'LoginException:BannedUser' => 'Je account is geblokkeerd. Je kunt je daarom niet aanmelden.',
	'LoginException:UsernameFailure' => 'We konden je niet aanmelden. Controleer je gebruikersnaam.',
	'LoginException:PasswordFailure' => 'We konden je niet aanmelden. Controleer je wachtwoord.',
	'LoginException:AccountLocked' => 'Je account is geblokkeerd wegens te veel mislukte aanmeldpogingen.',
	'LoginException:ChangePasswordFailure' => 'Huidige wachtwoord incorrect.',
	'LoginException:Unknown' => 'We konden je niet aanmelden vanwege een onbekende fout.',

	'deprecatedfunction' => 'Waarschuwing: Deze code gebruikt de niet meer gebruikte functies \'%s\' en is niet compatibel met deze versie van Elgg. ',

	'pageownerunavailable' => 'Waarschuwing: De pagina-eigenaar %d is niet toegankelijk!',
	'viewfailure' => 'Er is een interne fout in de view %s',
	'view:missing_param' => "De vereiste parameter '%s' mist in de weergave %s",
	'changebookmark' => 'Wijzig je favoriet/bladwijzer voor deze pagina',
	'noaccess' => 'De inhoud is verwijderd, is ongeldig of je hebt geen rechten om die te mogen bekijken.',
	'error:missing_data' => 'Er missen enkele gegevens in je verzoek',
	'save:fail' => 'Er ging iets mis bij het opslaan van je gegevens',
	'save:success' => 'Je gegevens zijn opgeslagen',

	'error:default:title' => 'Oeps...',
	'error:default:content' => 'Oeps... er ging iets mis.',
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
 * User details
 */

	'name' => "Weergavenaam",
	'email' => "E-mailadres",
	'username' => "Gebruikersnaam",
	'loginusername' => "Gebruikersnaam of e-mailadres",
	'password' => "Wachtwoord",
	'passwordagain' => "Wachtwoord (nogmaals, voor de zekerheid)",
	'admin_option' => "Wil je deze gebruiker sitebeheerder maken? De gebruiker heeft dan alle rechten op de site.",

/**
 * Access
 */

	'PRIVATE' => "Privé",
	'LOGGED_IN' => "Aangemelde gebruikers",
	'PUBLIC' => "Publiek",
	'LOGGED_OUT' => "Uitgelogde leden",
	'access:friends:label' => "Vrienden",
	'access' => "Toegang",
	'access:overridenotice' => "De inhoud van deze groep is alléén toegankelijk voor leden.",
	'access:limited:label' => "Gelimiteerd",
	'access:help' => "Het toegangsniveau",
	'access:read' => "Toegang",
	'access:write' => "Schrijftoegang",
	'access:admin_only' => "Alleen voor beheerders",

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
	'widgets:save:success' => "De widget is succesvol opgeslagen.",
	'widgets:save:failure' => "Er ging iets mis tijdens het opslaan van je widget. Probeer het nog een keer.",
	'widgets:add:success' => "De widget is toegevoegd.",
	'widgets:add:failure' => "De widget kon niet worden toegevoegd.",
	'widgets:move:failure' => "De nieuwe widgetpositie kon niet worden opgeslagen.",
	'widgets:remove:failure' => "De widget kan niet worden verwijderd",

/**
 * Groups
 */

	'group' => "Groep",
	'item:group' => "Groepen",

/**
 * Users
 */

	'user' => "Gebruiker",
	'item:user' => "Gebruikers",

/**
 * Friends
 */

	'friends' => "Vrienden",
	'friends:yours' => "Jouw vrienden",
	'friends:owned' => "Vrienden van %s",
	'friend:add' => "Voeg toe als vriend",
	'friend:remove' => "Verwijder vriend",

	'friends:add:successful' => "Je hebt %s succesvol toegevoegd als vriend.",
	'friends:add:failure' => "We konden %s niet toevoegen als vriend. Probeer het nogmaals.",

	'friends:remove:successful' => "Je hebt %s succesvol verwijderd als vriend.",
	'friends:remove:failure' => "We konden %s niet verwijderen als vriend. Probeer het nogmaals.",

	'friends:none' => "Deze gebruiker heeft nog niemand toegevoegd als vriend.",
	'friends:none:you' => "Je hebt nog niemand toegevoegd als vriend!",

	'friends:none:found' => "Geen vrienden gevonden.",

	'friends:of:none' => "Deze gebruiker is nog door niemand toegevoegd als vriend. ",
	'friends:of:none:you' => "Nog niemand heeft jou als vriend toegevoegd. Om beter vindbaar te zijn raden we je aan om je profiel in of aan te vullen, en om content te plaatsen: discussies, blogs etc.",

	'friends:of:owned' => "Mensen die %s als vriend hebben",

	'friends:of' => "Vrienden van",
	'friends:collections' => "Vriendenlijsten",
	'collections:add' => "Nieuwe lijst",
	'friends:collections:add' => "Nieuwe lijst met vrienden",
	'friends:addfriends' => "Voeg vrienden toe",
	'friends:collectionname' => "Lijstnaam",
	'friends:collectionfriends' => "Vrienden in de lijst",
	'friends:collectionedit' => "Bewerk deze lijst",
	'friends:nocollections' => "Je hebt nog geen lijst.",
	'friends:collectiondeleted' => "Je lijst is verwijderd.",
	'friends:collectiondeletefailed' => "We konden je lijst niet verwijderen. Mogelijk heb je geen toegang, of er was een ander probleem.",
	'friends:collectionadded' => "Je lijst werd succesvol aangemaakt",
	'friends:nocollectionname' => "Je moet je lijst een naam geven voordat die opgeslagen kan worden.",
	'friends:collections:members' => "Leden in de lijst",
	'friends:collections:edit' => "Bewerk lijst",
	'friends:collections:edited' => "Lijst bijgewerkt",
	'friends:collection:edit_failed' => 'De lijst kon niet worden opgeslagen.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "Je hebt geen rechten om de avatar van deze gebruiker te bewerken.",
	'avatar:create' => 'Maak je avatar',
	'avatar:edit' => 'Bewerk avatar',
	'avatar:preview' => 'Voorbeeld',
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

	'profile:edit' => 'Bewerk profiel',
	'profile:aboutme' => "Over mij",
	'profile:description' => "Over mij",
	'profile:briefdescription' => "Korte omschrijving",
	'profile:location' => "Locatie",
	'profile:skills' => "Vaardigheden",
	'profile:interests' => "Interesses",
	'profile:contactemail' => "Contact e-mailadres",
	'profile:phone' => "Telefoon",
	'profile:mobile' => "Mobiele telefoon",
	'profile:website' => "Website",
	'profile:twitter' => "Twitter gebruikersnaam",
	'profile:saved' => "Je profiel is succesvol opgeslagen.",

	'profile:field:text' => 'Korte tekst',
	'profile:field:longtext' => 'Groot tekstveld',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Webadres',
	'profile:field:email' => 'E-mailadres',
	'profile:field:location' => 'Locatie',
	'profile:field:date' => 'Datum',

	'admin:appearance:profile_fields' => 'Bewerk profielvelden',
	'profile:edit:default' => 'Bewerk profielvelden',
	'profile:label' => "Profiellabel",
	'profile:type' => "Profieltype",
	'profile:editdefault:delete:fail' => 'Verwijderen van het profielveld is mislukt',
	'profile:editdefault:delete:success' => 'Standaard profielitem verwijderd!',
	'profile:defaultprofile:reset' => 'Standaard systeemprofiel herstellen',
	'profile:resetdefault' => 'Standaardprofiel herstellen',
	'profile:resetdefault:confirm' => 'Weet je zeker dat je de aangepaste profielvelden wilt verwijderen?',
	'profile:explainchangefields' => "Je kunt de bestaande profielvelden vervangen met die van jou. Dit doe je met behulp van onderstaand formulier.

Geef het nieuwe profielveld een label, bijvoorbeeld: 'Favoriete team'. Selecteer vervolgens het veldtype, bijvoorbeeld: tekst, url, tags. Klik daarna op de knop 'Toevoegen'. Om de velden te sorteren sleep je ze met het handvat naast het label. Voor het bewerken van het label klik je op de tekst van het label om het bewerkbaar te maken.

Je kunt op elk moment terugkeren naar het standaardprofiel, maar dan gaat alle informatie die al ingevoerd was in aangepaste velden op de profielpagina's verloren.",
	'profile:editdefault:success' => 'Item succesvol toegevoegd aan het standaardprofiel
',
	'profile:editdefault:fail' => 'Het standaardprofiel kon niet worden opgeslagen',
	'profile:field_too_long' => 'Je profielinformatie kon niet worden opgeslagen omdat de sectie \'%s\' te lang is.',
	'profile:noaccess' => "Je hebt onvoldoende rechten om dit profiel te bewerken",
	'profile:invalid_email' => '%s moet een geldig e-mailadres zijn.',


/**
 * Feeds
 */
	'feed:rss' => 'Abonneer je op de RSS-feed',
/**
 * Links
 */
	'link:view' => 'Bekijk link',
	'link:view:all' => 'Bekijk alles',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s is nu bevriend met %s",
	'river:update:user:avatar' => '%s heeft een nieuwe avatar',
	'river:update:user:profile' => '%s heeft zijn profiel bijgewerkt',
	'river:noaccess' => 'Je hebt geen toegang tot dit item.',
	'river:posted:generic' => '%s plaatste',
	'riveritem:single:user' => 'een gebruiker',
	'riveritem:plural:user' => 'sommige gebruikers',
	'river:ingroup' => 'in de groep %s',
	'river:none' => 'Geen activiteit',
	'river:update' => 'Update van %s',
	'river:delete' => 'Verwijder deze activiteit',
	'river:delete:success' => 'Het river-item is verwijderd',
	'river:delete:fail' => 'Het river-item kon niet worden verwijderd',
	'river:subject:invalid_subject' => 'Ongeldige gebruiker',
	'activity:owner' => 'Bekijk activiteit',

	'river:widget:title' => "Activiteit",
	'river:widget:description' => "Toon de laatste activiteit",
	'river:widget:type' => "Activiteittype",
	'river:widgets:friends' => 'Activiteit van vrienden',
	'river:widgets:all' => 'Alle activiteit op de site',

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

	'user:password:lost' => 'Wachtwoord vergeten',
	'user:password:changereq:success' => 'De aanvraag voor een nieuw wachtwoord is gelukt. Er wordt een e-mail verstuurd.',
	'user:password:changereq:fail' => 'Er kan geen nieuw wachtwoord aangevraagd worden.',

	'user:password:text' => 'Om een nieuw wachtwoord aan te vragen vul je hieronder je gebruikersnaam of e-mailadres In. Klik daarna op de knop \'Aanvragen\'.',

	'user:persistent' => 'Onthoud mij',

	'walled_garden:welcome' => 'Welkom bij',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Beheer',
	'menu:page:header:configure' => 'Configureer',
	'menu:page:header:develop' => 'Ontwikkel',
	'menu:page:header:default' => 'Andere',

	'admin:view_site' => 'Bekijk website',
	'admin:loggedin' => 'Aangemeld als %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Je instellingen zijn opgeslagen.",
	'admin:configuration:fail' => "Je instellingen zijn niet opgeslagen.",
	'admin:configuration:dataroot:relative_path' => 'Kan \'%s\' niet als datamap opslaan, omdat het geen absoluut pad is.',

	'admin:unknown_section' => 'Ongeldige beheersectie.',

	'admin' => "Beheer",
	'admin:description' => "Het beheerpaneel maakt het mogelijk het hele systeem te beheren: van gebruikersbeheer tot hoe plugins zich gedragen. Kies een optie om te beginnen.",

	'admin:statistics' => "Statistieken",
	'admin:statistics:overview' => 'Overzicht',
	'admin:statistics:server' => 'Serverinformatie',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Laatste Cron Jobs',
	'admin:cron:period' => 'Cron periode',
	'admin:cron:friendly' => 'Laatst afgerond',
	'admin:cron:date' => 'Datum en tijd',

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

	'admin:administer_utilities:maintenance' => 'Onderhoudsmodus',
	'admin:upgrades' => 'Upgrades',

	'admin:settings' => 'Instellingen',
	'admin:settings:basic' => 'Basisinstellingen',
	'admin:settings:advanced' => 'Geavanceerde instellingen',
	'admin:site:description' => "Via dit beheerpaneel kun je de algemene instellingen van de site beheren. Kies een optie om te beginnen.",
	'admin:site:opt:linktext' => "Configureer site",
	'admin:settings:in_settings_file' => 'Deze instelling configureer je in settings.php',

	'admin:legend:security' => 'Veiligheid',
	'admin:site:secret:intro' => 'Elgg gebruikt een sleutel om veiligheidstokens voor verschillende doeleinden te genereren. ',
	'admin:site:secret_regenerated' => "Het 'sitegeheim' is opnieuw gegenereerd. ",
	'admin:site:secret:regenerate' => "Genereer het 'sitegeheim' opnieuw",
	'admin:site:secret:regenerate:help' => "Let op: als je het 'sitegeheim' opnieuw laat genereren, dan kunnen sommige gebruikers daar wat hinder van ondervinden. De tokens die in 'Onthoud mij'-cookies, e-mailvalidatie-aanvragen, validatiecodes en meer zit worden dan opnieuw gemaakt.",
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
	'widget:content_stats:type' => 'Type inhoud',
	'widget:content_stats:number' => 'Aantal',

	'admin:widget:admin_welcome' => 'Welkom',
	'admin:widget:admin_welcome:help' => "Een korte introductie op het beheerdeel van Elgg",
	'admin:widget:admin_welcome:intro' =>
'Welkom in Elgg! Op dit moment kijk je naar het beheerdersdashboard. Dit is makkelijk om te zien wat er op je site gebeurt.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigatie door het beheerders gedeelte is mogelijk via de menu's aan e rechterkant. Het is opgedeeld in drie secties:
<dl>
<dt>Beheer</dt><dd>Dagelijkse taken zoals de controle op gemelde inhoud, het controleren wie er online is, en bekijken van statistieken.</dd>
<dt>Configureer</dt><dd>Gelegenheid taken, zoals het instellen van de naam van de site of het activeren van een plugin.</dd>
<dt>Ontwikkel</dt><dd>Voor ontwikkelaars die plugin maken of themes ontwikkelen. (Vereist een ontwikkelaars plugin.)</dd>
</dl>",

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

	'admin:notices:could_not_delete' => 'Kon melding niet verwijderen',
	'item:object:admin_notice' => 'Bericht voor sitebeheerder',

	'admin:options' => 'Beheeropties',

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins worden niet geladen, omdat een bestand genaamd "disabled" in de mod-directory gevonden is.',
	'plugins:settings:save:ok' => "De instellingen voor de plugin '%s' zijn succesvol opgeslagen.",
	'plugins:settings:save:fail' => "Er is een fout opgetreden tijdens het opslaan van de instellingen voor de plugin '%s'.",
	'plugins:usersettings:save:ok' => "Gebruikersinstellingen voor de plugin '%s' zijn succesvol opgeslagen.",
	'plugins:usersettings:save:fail' => "Er is een fout opgetreden tijden het opslaan van de gebruikersinstellingen van de plugin '%s'.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Alles activeren',
	'admin:plugins:deactivate_all' => 'Alles deactiveren',
	'admin:plugins:activate' => 'Activeren',
	'admin:plugins:deactivate' => 'Deactiveren',
	'admin:plugins:description' => "Via dit beheerpaneel kun je de verschillende tools van de site beheren en configureren.",
	'admin:plugins:opt:linktext' => "Configureer tools",
	'admin:plugins:opt:description' => "Configureer de tools die zijn geïnstalleerd op de site.",
	'admin:plugins:label:author' => "Auteur",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categorieën',
	'admin:plugins:label:licence' => "Licentie",
	'admin:plugins:label:website' => "Website",
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

	'admin:plugins:warning:elgg_version_unknown' => 'Deze plugin gebruikt een oud manifestbestand en heeft geen Elggversie gedefinieerd. <strong>Hij zal waarschijnlijk niet werken!</strong>',
	'admin:plugins:warning:unmet_dependencies' => 'Deze plugin heeft onvervulde afhankelijkheden en kan niet worden geactiveerd. Controleer de afhankelijkheden onder \'meer info\'.',
	'admin:plugins:warning:invalid' => '%s is geen geldige plugin voor Elgg. Controleer <a href="http://docs.elgg.org/Invalid_Plugin" target="_blank">de Elgg-documentatie</a> voor handige tips.',
	'admin:plugins:warning:invalid:check_docs' => 'Controleer <a href="http://docs.elgg.org/Invalid_Plugin" target="_blank">de Elgg-documentatie</a> voor tips met betrekking tot foutopsporing.',
	'admin:plugins:cannot_activate' => 'kan niet activeren',

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
	'admin:statistics:label:basic' => "Basis sitestatistieken",
	'admin:statistics:label:numentities' => "Entities op de site",
	'admin:statistics:label:numusers' => "Aantal gebruikers",
	'admin:statistics:label:numonline' => "Aantal gebruikers online",
	'admin:statistics:label:onlineusers' => "Online gebruikers",
	'admin:statistics:label:admins'=>"Beheerders",
	'admin:statistics:label:version' => "Elgg-versie",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Versie",

	'admin:server:label:php' => 'PHP',
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

	'admin:appearance:menu_items' => 'Menu-items',
	'admin:menu_items:configure' => 'Configureer items in het hoofdmenu',
	'admin:menu_items:description' => 'Selecteer welke menu-items je als hoofditems wilt zien. Ongebruikte items zullen worden toegevoegd aan "Meer" aan het einde van de lijst.',
	'admin:menu_items:hide_toolbar_entries' => 'Verwijder links uit het menu?',
	'admin:menu_items:saved' => 'Menu-items opgeslagen.',
	'admin:add_menu_item' => 'Voeg een eigen menu-item toe',
	'admin:add_menu_item:description' => 'Vul de weergavenaam en URL in om een eigen menu-item toe te voegen aan het navigatiemenu.',

	'admin:appearance:default_widgets' => 'Standaardwidgets',
	'admin:default_widgets:unknown_type' => 'Onbekend widgettype',
	'admin:default_widgets:instructions' => 'Plaats, verwijder, positioneer en configureer standaardwidgets voor de geselecteerde widgetpagina. Deze wijzigingen hebben alleen effect op nieuwe gebruikers.',

	'admin:robots.txt:instructions' => "Je kunt hieronder de robots.txt van de site bewerken.",
	'admin:robots.txt:plugins' => "Plugins zullen het volgende toevoegen aan de robots.txt:",
	'admin:robots.txt:subdir' => "De robots.txt-tool zal niet werken, omdat Elgg in een submap is geïnstalleerd.",

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
	'river:generic_comment' => 'reageerde op %s %s',

	'friends:widget:description' => "Toont een aantal van je vrienden.",
	'friends:num_display' => "Aantal vrienden om weer te geven",
	'friends:icon_size' => "Grootte van de avatar",
	'friends:tiny' => "Klein",
	'friends:small' => "Normaal",

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
		
/**
 * Generic action words
 */

	'save' => "Opslaan",
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
	'fileexists' => "Er is reeds een bestand geüpload. Om het te vervangen selecteer het hieronder:",

/**
 * User add
 */

	'useradd:subject' => 'Gebruikersaccount aangemaakt',
	'useradd:body' => '%s,

Een gebruikers account is voor jou aangemaakt op %s. Om je aan te melden, klikt hier:

%s

En meld je aan met de volgende gegevens:

Gebruikersnaam: %s
Wachtwoord: %s

Als je bent aangemeld raden we je aan om je wachtwoord direct te wijzigen.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Klik om te verbergen",


/**
 * Import / export
 */
		
	'importsuccess' => "Importeren van data was succesvol",
	'importfail' => "OpenDD data importeren is mislukt.",

/**
 * Time
 */

	'friendlytime:justnow' => "zojuist",
	'friendlytime:minutes' => "%s minuten geleden",
	'friendlytime:minutes:singular' => "een minuut geleden",
	'friendlytime:hours' => "%s uren geleden",
	'friendlytime:hours:singular' => "een uur geleden",
	'friendlytime:days' => "%s dagen geleden",
	'friendlytime:days:singular' => "gisteren",
	'friendlytime:date_format' => 'j F Y @ G:i',
	
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

	'date:weekday:0' => 'Zondag',
	'date:weekday:1' => 'Maandag',
	'date:weekday:2' => 'Dinsdag',
	'date:weekday:3' => 'Woensdag',
	'date:weekday:4' => 'Donderdag',
	'date:weekday:5' => 'Vrijdag',
	'date:weekday:6' => 'Zaterdag',
	
	'interval:minute' => 'Elke minuut',
	'interval:fiveminute' => 'Elke vijf minuten',
	'interval:fifteenmin' => 'Elke vijftien minuten',
	'interval:halfhour' => 'Elk half uur',
	'interval:hourly' => 'Elk uur',
	'interval:daily' => 'Elke dag',
	'interval:weekly' => 'Elke week',
	'interval:monthly' => 'Elke maand',
	'interval:yearly' => 'Elk jaar',
	'interval:reboot' => 'Bij opnieuw opstarten',

/**
 * System settings
 */

	'installation:sitename' => "De naam van je site (bijvoorbeeld 'Mijn sociale netwerksite'):",
	'installation:sitedescription' => "Korte omschrijving van je site (optioneel)",
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

	'installation:httpslogin' => "Activeer dit om gebruikers via HTTPS aan te melden. Je moet HTTPS op je server hebben geactiveerd (en je moet een SSL-certificaat hebben) om dit te kunnen gebruiken.",
	'installation:httpslogin:label' => "HTTPS-aanmelden inschakelen",
	'installation:view' => "Geeft de view op die standaard wordt gebruikt binnen de site, of laat het leeg voor de standaardview. Bij twijfel, laat de standaard staan!",

	'installation:siteemail' => "Site e-mailadres (dit wordt gebruikt voor het verzenden van systeem e-mails):",

	'admin:site:access:warning' => "Het wijzigen van de toegangsinstellingen is alleen van toepassing op nieuwe content.",
	'installation:allow_user_default_access:description' => "Als je dit aanvinkt hebben individuele gebruikers de mogelijkheid om hun eigen standaard toegangsniveau in te stellen. Dit kan anders zijn dan de standaardinstelling van de site.",
	'installation:allow_user_default_access:label' => "Gebruikers standaardtoegang toestaan",

	'installation:simplecache:description' => "De simple cache verhoogt de performance door statische content te cachen, waaronder sommige CSS- en Javascriptbestanden. Normaal gezien wil je dit aan hebben staan.",
	'installation:simplecache:label' => "Gebruik simple cache",

	'installation:minify:description' => "De 'simple cache' kan tevens de prestaties verbeteren door JavaScript- en CSS-bestanden te comprimeren. Dit vereist dat 'simple cache' aanstaat. ",
	'installation:minify_js:label' => "JavaScript comprimeren (aangeraden)",
	'installation:minify_css:label' => "CSS comprimeren (aangeraden)",

	'installation:htaccess:needs_upgrade' => "Je moet het bestand .htaccess zodanig wijzigen dat het pad geïnjecteerd wordt in de GET-parameter __elgg_uri. Je kunt htaccess_dist als voorbeeld gebruiken.",
	'installation:htaccess:localhost:connectionfailed' => "Elgg kan niet zelf de rewrite-rules testen. Controleer dat curl werkt en dat er geen IP-restricties zijn die localhost connecties blokkeren.",
	
	'installation:systemcache:description' => "De systeemcache verlaagt de laadtijd van de Elgg-engine door data te cachen naar bestanden.",
	'installation:systemcache:label' => "Gebruik systeemcache (aanbevolen)",

	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Toegang tot content',
	'admin:legend:site_access' => 'Toegang tot de site',
	'admin:legend:debug' => 'Debugging en loggen',

	'upgrading' => 'Bijwerken',
	'upgrade:db' => 'Je database is bijgewerkt.',
	'upgrade:core' => 'Je Elgg-installatie is bijgewerkt',
	'upgrade:unlock' => 'Ontgrendel upgrade',
	'upgrade:unlock:confirm' => "De database is geblokkeerd door een andere upgrade. Meerdere upgrades tegelijkertijd laten lopen is gevaarlijk. Je moet alleen verder gaan als je zeker weet dat er geen andere upgrade draait. Ontgrendelen?",
	'upgrade:locked' => "Upgrade is niet mogelijk: er loopt reeds een upgrade. Om de upgrade te ontgrendelen bezoek je de beheersectie.",
	'upgrade:unlock:success' => "Upgrade succesvol ontgrendeld",
	'upgrade:unable_to_upgrade' => 'Upgrade niet mogelijk.',
	'upgrade:unable_to_upgrade_info' =>
		'Deze installatie kan niet worden geüpgrade omdat er \'legacy views\' zijn ontdekt in de Elgg \'core views\' map. Deze views zijn verouderd en moeten worden verwijderd om Elgg correct te laten functioneren. Als je geen wijzigingen hebt gemaakt aan de Elgg-core kun je de map verwijderen en vervangen met de inhoud uit de laatste versie van Elgg, die je kunt vinden op <a href="http://elgg.org" target="_blank">elgg.org</a>.<br><br>

Als je meer gedetailleerde instructie wilt, ga je naar de <a href="http://docs.elgg.org/wiki/Upgrading_Elgg" target="_blank">documentatie \'Upgrading Elgg\'</a>. Als je hulp nodig hebt, plaats je je vraag op de <a href="http://community.elgg.org/pg/groups/discussion/" target="_blank">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (voorheen Twitter Service) is uitgeschakeld tijdens de upgrade. Activeer deze zelf weer als het nodig is.',
	'update:oauth_api:deactivated' => 'OAuth API (voorheen OAuth Lib) is uitgeschakeld tijdens de upgrade. Activeer deze zelf weer als het nodig is.',
	'upgrade:site_secret_warning:moderate' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren! Zie ook: Configureren &gt; Instellingen &gt; Geavanceerd",
	'upgrade:site_secret_warning:weak' => "We raden je ten zeerste aan om je 'sitegeheim' opnieuw te genereren! Zie ook: Configureren &gt; Instellingen &gt; Geavanceerd",

	'deprecated:function' => '%s() is vervangen door %s()',

	'admin:pending_upgrades' => 'Er zijn belangrijke upgrades waar je beter nu meteen aandacht aan kunt schenken!',
	'admin:view_upgrades' => 'Bekijk de upgrades.',
 	'admin:upgrades' => 'Upgrades',
	'item:object:elgg_upgrade' => 'Site-upgrades',
	'admin:upgrades:none' => 'De installatie is up-to-date!',

	'upgrade:item_count' => 'Er zijn <b>%s</b> zaken die geüpgrade moeten worden.',
	'upgrade:warning' => 'Let op: dit kan lang duren, als je een grote site hebt!',
	'upgrade:success_count' => 'Geüpgraded:',
	'upgrade:error_count' => 'Fouten:',
	'upgrade:river_update_failed' => 'Het updaten van de activiteitstroom voor het item met de ID %s is helaas niet gelukt.',
	'upgrade:timestamp_update_failed' => 'Het is helaas niet gelukt om de tijdsdatering voor het item met het ID %s te updaten.',
	'upgrade:finished' => 'Upgrade is voltooid.',
	'upgrade:finished_with_errors' => '<p>De upgrade is afgerond, maar er zijn fouten geconstateerd. Ververs de pagina en probeer de upgrade opnieuw uit te voeren.</p></p><br />Als de fouten zich opnieuw voordoen, kijk dan in je serverlog of daar een mogelijke oorzaak te vinden is. Je kunt bijstand krijgen bij het oplossen van de fout op de community van Elgg <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">in de groep \'technisch(e) support\'</a>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Reacties-upgrade',
	'upgrade:comment:create_failed' => 'Het is helaas niet gelukt om de reactie met ID %s om te zetten naar een entiteit.',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Upgrade van de datamap.',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Upgrade van de discussiereactie',
	'discussion:upgrade:replies:create_failed' => 'Het is helaas niet gelukt om de discussiereactie met ID %s om te zetten naar een entiteit.',

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

	'email:save:success' => "Het nieuwe e-mail adres is opgeslagen, en er wordt verificatie gevraagd.",
	'email:save:fail' => "Je nieuwe e-mailadres kon niet worden opgeslagen.",

	'friend:newfriend:subject' => "%s heeft jou toegevoegd als vriend!",
	'friend:newfriend:body' => "Hallo!

%s heeft jou toegevoegd als vriend.

Om zijn/haar profiel te bekijken klik je hier:

%s

---

Dit is een automatisch aangemaakt bericht. Je kunt hier niet op reageren. ",

	'email:changepassword:subject' => "Wachtwoord gewijzigd!",
	'email:changepassword:body' => "Beste %s,

Je wachtwoord is gewijzigd.",

	'email:resetpassword:subject' => "Wachtwoord reset!",
	'email:resetpassword:body' => "Beste %s,

Je wachtwoord is gereset naar: %s",

	'email:changereq:subject' => "Aanvraag om het wachtwoord te wijzigen.",
	'email:changereq:body' => "Beste %s,

Iemand (van het IP adres %s) heeft een wijziging van het wachtwoord voor dit account aangevraagd.

Indien jij deze persoon bent, klik dan op onderstaande link. In het andere geval kun je deze email negeren.

%s
",

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

	'river:comment:object:default' => '%s reageerde op %s',

	'generic_comments:add' => "Voeg een reactie toe",
	'generic_comments:edit' => "Reactie bewerken",
	'generic_comments:post' => "Plaats reactie",
	'generic_comments:text' => "Reactie",
	'generic_comments:latest' => "Laatste reacties",
	'generic_comment:posted' => "Je reactie is succesvol geplaatst.",
	'generic_comment:updated' => "De reactie is gewijzigd",
	'generic_comment:deleted' => "Je reactie is succesvol verwijderd.",
	'generic_comment:blank' => "Sorry, je moet wel wat invullen voordat we je reactie kunnen opslaan!",
	'generic_comment:notfound' => "Sorry, we konden het opgegeven item niet vinden.",
	'generic_comment:notdeleted' => "Sorry, we konden deze reactie niet verwijderen.",
	'generic_comment:failure' => "Er is een fout opgetreden tijdens het opslaan van je reactie. Probeer het nogmaals.",
	'generic_comment:none' => 'Geen reacties',
	'generic_comment:title' => 'Reactie door %s',
	'generic_comment:on' => '%s op %s',
	'generic_comments:latest:posted' => 'plaatste een',

	'generic_comment:email:subject' => 'Er is een nieuwe reactie!',
	'generic_comment:email:body' => "Hoi!

Er is een nieuwe reactie op je item %s. De reactie die %s gaf is:

%s

Om te antwoorden of het originele item te zien, klik je hier:

%s

Wil je eerst het profiel van %s bekijken? Klik dan hier: %s

---

Dit is een automatisch aangemaakt bericht. Je kunt hier niet op reageren.",

/**
 * Entities
 */
	
	'byline' => 'Door %s',
	'entity:default:strapline' => 'Aangemaakt op %s door %s',
	'entity:default:missingsupport:popup' => 'Deze entity kan niet correct worden weergegeven. Dit kan komen doordat er ondersteuning nodig is van een plugin die niet meer is geïnstalleerd.',

	'entity:delete:success' => 'Entity %s is verwijderd',
	'entity:delete:fail' => 'Entity %s kon niet worden verwijderd',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Het formulier mist __token en/of __ts veld(en)',
	'actiongatekeeper:tokeninvalid' => "Er was een probleem (token mismatch). Dit betekent waarschijnlijk dat de gebruikte pagina verlopen was. Probeer het nogmaals.",
	'actiongatekeeper:timeerror' => 'De gebruikte pagina is verlopen. Ververs en probeer het nogmaals.',
	'actiongatekeeper:pluginprevents' => 'Een extension heeft voorkomen dat het formulier wordt verzonden.',
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
	"pt_br" => 'Braziliaans Portugees',
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
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
	"zu" => "Zulu",
);
