<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
/**
 * Sites
 */

	'item:site:site' => 'Webbplats',
	'collection:site:site' => 'Webbplatser',
	'index:content' => '<p>Välkommen till Elgg webbplats.</p><p><strong>Tips:</strong> Många webbplatser använder tillägget <code>aktivitet</code> för att placera en webbplats aktivitetsflöde på den här sidan.</p>',

/**
 * Sessions
 */

	'login' => "Logga in",
	'loginok' => "Du har loggats in.",
	'loginerror' => "Vi kunde inte logga in dig. Vänligen, kontrollera dina användaruppgifter och försök igen.",
	'login:empty' => "Användarnamn/e-post och lösenord krävs.",
	'login:baduser' => "Det går inte ladda in ditt användarkonto.",
	'auth:nopams' => "Internt fel. Ingen autentiseringsmetod för användare är installerad. ",

	'logout' => "Logga ut",
	'logoutok' => "Du har loggats ut.",
	'logouterror' => "Vi kunde inte logga ut dig. Vänligen försök igen.",
	'session_expired' => "Din session har löpt ut. Vänligen <a href='javascript:location.reload(true)'>ladda om</a> sidan för att logga in.",
	'session_changed_user' => "Du har loggats in som en annan användare. Du bör <a href='javascript:location.reload(true)'>ladda om</a> sidan.",

	'loggedinrequired' => "Du måste vara inloggad för att visa den efterfrågade sidan.",
	'loggedoutrequired' => "Du måste vara utloggad för att visa den efterfrågade sidan.",
	'adminrequired' => "Du måste vara administratör för att visa den efterfrågade sidan.",
	'membershiprequired' => "Du måste vara medlem i den här gruppen för att visa den efterfrågade sidan.",
	'limited_access' => "Du har inte behörighet att visa den efterfrågade sidan.",
	'invalid_request_signature' => "Sidan som du försöker nå, är ogiltig eller har upphört.",

/**
 * Errors
 */

	'exception:title' => "Allvarligt fel.",
	'exception:contact_admin' => 'Ett oåterkalleligt fel har inträffat och har loggats. Kontakta webbplatsens administratör med följande information:',

	'actionundefined' => "Den begärda handlingen (%s) definierades inte i systemet.",
	'actionnotfound' => "The action file for %s was not found.",
	'actionloggedout' => "Du kan tyvärr inte utföra den här handlingen när du är utloggad.",
	'actionunauthorized' => 'Du är obehörig att utföra den här handlingen',

	'ajax:error' => 'Oväntat fel vid ett anrop till AJAX utfördes. Kanske har anslutningen till servern tappats.',
	'ajax:not_is_xhr' => 'Du kan inte nå AJAX-vyer direkt',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) är ett felkonfigurerat tillägg. Det har avaktiverats. Vänligen sök i Elgg wiki för möjliga orsaker (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) kan inte starta och har avaktiverats.  Orsak: %s',
	'PluginException:InvalidID' => "%s är ett ogiltigt ID för tillägg.",
	'PluginException:InvalidPath' => "%s är en ogiltig sökväg för tillägg.",
	'PluginException:InvalidManifest' => 'Ogiltig manifest-fil för tillägg %s',
	'PluginException:InvalidPlugin' => '%s är inte ett giltigt tillägg.',
	'PluginException:InvalidPlugin:Details' => '%s är inte ett giltigt tillägg: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',
	'ElggPlugin:MissingID' => 'ID för tillägg saknas (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Missing ElggPluginPackage for plugin ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Filen "%s" som krävs saknas.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Namnet på det här tilläggets mapp måste till "%s" för att matcha ID i manifestet.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Manifestet innehåller en ogiltig beroendetyp "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Paketet kan inte laddas',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "The plugin name could not be found",
	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Krävs',
	'ElggPlugin:Dependencies:Suggests' => 'Föreslår',
	'ElggPlugin:Dependencies:Conflicts' => 'Konflikter',
	'ElggPlugin:Dependencies:Conflicted' => 'Conflicted',
	'ElggPlugin:Dependencies:Provides' => 'Erbjuder',
	'ElggPlugin:Dependencies:Priority' => 'Prioritet',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg-version',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP-version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Efter %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Före %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Saknas',

	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items found without parents to link them to',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] found with a missing parent[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Duplicate registration found for menu item [%s]',

	'RegistrationException:EmptyPassword' => 'Lösenordsfältet kan inte vara tomt',
	'RegistrationException:PasswordMismatch' => 'Lösenord måste matcha',
	'LoginException:BannedUser' => 'Du har blivit avstängd från den här webbplatsen och kan inte logga in',
	'LoginException:UsernameFailure' => 'Vi kunde inte logga in dig. Vänligen kontrollera ditt användarnamn/e-post och lösenord.',
	'LoginException:PasswordFailure' => 'Vi kunde inte logga in dig. Vänligen kontrollera ditt användarnamn/e-post och lösenord.',
	'LoginException:AccountLocked' => 'Ditt konto har blivit låst efter för många misslyckade inloggningsförsök.',
	'LoginException:ChangePasswordFailure' => 'Misslyckades med nuvarande lösenordskontroll.',
	'LoginException:Unknown' => 'Vi kunde inte logga in dig på grund av ett okänt fel.',
	'LoginException:AdminValidationPending' => "Ditt konto måste godkännas av en administratör på den här webbplatsen, innan du kan använda det. Du kommer att få ett meddelande när ditt konto har validerats.",
	'LoginException:DisabledUser' => "Your account has been disabled. You're not allowed to login.",

	'UserFetchFailureException' => 'Kan inte kontrollera behörighet för user_guid [%s] eftersom användaren inte existerar.',

	'PageNotFoundException' => 'Sidan du försökte visa finns inte eller så har du inte behörighet att visa den.',
	'EntityNotFoundException' => 'Innehållet som du försökte nå har tagits bort eller så har du inte behörighet att nå det.',
	'EntityPermissionsException' => 'Du har inte tillräcklig behörighet för den här handlingen.',
	'GatekeeperException' => 'Du har inte behörighet att visa sidan du försöker nå',
	'BadRequestException' => 'Fel förfrågan',
	'ValidationException' => 'Skickad data uppfyllde inte kraven, vänligen kontrollera din inmatning.',
	'LogicException:InterfaceNotImplemented' => '%s måste implementera %s',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "Det angivna lösenordet uppfyller inte teckenkraven",
	'Security:InvalidPasswordLengthException' => "Det angivna lösenordet uppfyller inte minsta längd på %s tecken",

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Varning: Sidägaren %d kan inte nås!',
	'viewfailure' => 'Det var ett internt fel i vyn %s',
	'view:missing_param' => "Parametern som krävs '%s' saknas i vyn %s",
	'changebookmark' => 'Vänligen ändra till bokmärke för den här sidan',
	'noaccess' => 'Innehållet du försökte visa har tagits bort eller så har du inte behörighet att visa det.',
	'error:missing_data' => 'Det saknades lite data i din förfrågan',
	'save:fail' => 'Det blev något fel när din data skulle sparas',
	'save:success' => 'Din data har sparats',

	'forward:error' => 'Tyvärr blev det ett fel när du skulle omdirigeras till en annan webbplats.',

	'error:default:title' => 'Oj...',
	'error:default:content' => 'Oj..något blev fel.',
	'error:400:title' => 'Fel förfrågan',
	'error:400:content' => 'Tyvärr, din förfrågan är ogiltig eller ofullständig.',
	'error:403:title' => 'Förbjuden',
	'error:403:content' => 'Tyvärr, har du inte tillåtelse att komma åt den begärda sidan.',
	'error:404:title' => 'Sidan hittades inte',
	'error:404:content' => 'Tyvärr, kunde vi inte hitta sidan du efterfrågade.',

	'upload:error:ini_size' => 'Filen du försökte ladda upp är för stor.',
	'upload:error:form_size' => 'Filen du försökte ladda upp är för stor.',
	'upload:error:partial' => 'Uppladdning av filen slutfördes inte.',
	'upload:error:no_file' => 'Ingen fil valdes.',
	'upload:error:no_tmp_dir' => 'Kan inte spara den uppladdade filen.',
	'upload:error:cant_write' => 'Kan inte spara den uppladdade filen.',
	'upload:error:extension' => 'Kan inte spara den uppladdade filen.',
	'upload:error:unknown' => 'Uppladdning av filen misslyckades.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Avstängd',
	'table_columns:fromView:container' => 'Behållare',
	'table_columns:fromView:excerpt' => 'Beskrivning',
	'table_columns:fromView:link' => 'Namn/Titel',
	'table_columns:fromView:icon' => 'Ikon',
	'table_columns:fromView:item' => 'Objekt',
	'table_columns:fromView:language' => 'Språk',
	'table_columns:fromView:owner' => 'Ägare',
	'table_columns:fromView:time_created' => 'Skapades',
	'table_columns:fromView:time_updated' => 'Uppdaterades',
	'table_columns:fromView:user' => 'Användare',

	'table_columns:fromProperty:description' => 'Beskrivning',
	'table_columns:fromProperty:email' => 'E-post',
	'table_columns:fromProperty:name' => 'Namn',
	'table_columns:fromProperty:type' => 'Typ',
	'table_columns:fromProperty:username' => 'Användarnamn',

	'table_columns:fromMethod:getSubtype' => 'Subtyp',
	'table_columns:fromMethod:getDisplayName' => 'Namn/Titel',
	'table_columns:fromMethod:getMimeType' => 'MIME-typ',
	'table_columns:fromMethod:getSimpleType' => 'Typ',

/**
 * User details
 */

	'name' => "Visningsnamn",
	'email' => "E-postadress",
	'username' => "Användarnamn",
	'loginusername' => "Användarnamn eller E-post",
	'password' => "Lösenord",
	'passwordagain' => "Lösenord (igen för verifikation)",
	'admin_option' => "Göra den här användaren till admin?",
	'autogen_password_option' => "Automatiskt generera ett säkert lösenord?",

/**
 * Access
 */

	'access:label:private' => "Privat",
	'access:label:logged_in' => "Inloggade användare",
	'access:label:public' => "Offentlig",
	'access:label:logged_out' => "Utloggade användare",
	'access:label:friends' => "Vänner",
	'access' => "Vem kan se det här",
	'access:overridenotice' => "Notering: Med anledning av gruppolicy, kommer det här innehållet bara kommas åt av gruppmedlemmar.",
	'access:limited:label' => "Begränsad",
	'access:help' => "Åtkomstnivån",
	'access:read' => "Läsrättighet",
	'access:write' => "Skrivrättighet",
	'access:admin_only' => "Endast administratörer",
	'access:missing_name' => "Saknat nivånamn",
	'access:comments:change' => "Den här diskussionen är för närvarande synlig för en begränsad publik. Tänk dig med vem du delar den med.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Kontrollpanel",
	'dashboard:nowidgets' => "Din kontrollpanel låter dig hålla koll på aktiviteten och innehåll på den här webbplatsen som har betydelse för dig.",

	'widgets:add' => 'Lägg till widgets',
	'widgets:add:description' => "Tryck på vilken widget-knapp som helst nedan, för att lägga till den på sidan.",
	'widgets:position:fixed' => '(Fixed position på sidan)',
	'widget:unavailable' => 'Du har redan lagt till den här widgeten',
	'widget:numbertodisplay' => 'Antal objekt att visa',

	'widget:delete' => 'Ta bort  %s',
	'widget:edit' => 'Skräddarsy den här widgeten',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widget",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "Widgeten sparades med lyckat resultat.",
	'widgets:save:failure' => "Vi kunde inte spara din widget.",
	'widgets:add:success' => "Widgeten lades till med lyckat resultat.",
	'widgets:add:failure' => "Vi kunde inte lägga till din widget.",
	'widgets:move:failure' => "Vi kunde inte lagra den nya widgetens position.",
	'widgets:remove:failure' => "Det gick inte att ta bort den här widgeten",
	'widgets:not_configured' => "Den här widgeten har inte konfigurerats ännu",
	
/**
 * Groups
 */

	'group' => "Grupp",
	'item:group' => "Grupp",
	'collection:group' => 'Grupper',
	'item:group:group' => "Grupp",
	'collection:group:group' => 'Grupper',
	'groups:tool_gatekeeper' => "Den begärda funktionaliteten  är för närvarande inte aktiverad i den här gruppen",

/**
 * Users
 */

	'user' => "Användare",
	'item:user' => "Användare",
	'collection:user' => 'Användare',
	'item:user:user' => 'Användare',
	'collection:user:user' => 'Användare',

	'friends' => "Vänner",
	'collection:friends' => 'Vänners\' %s',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "Du har inte tillåtelse att redigera den här användarens avatar",
	'avatar:create' => 'Skapa din avatar',
	'avatar:edit' => 'Redigera avatar',
	'avatar:upload' => 'Ladda upp en ny avatar',
	'avatar:current' => 'Nuvarande avatar',
	'avatar:remove' => 'Ta bort din avatar och ställ in standardikonen',
	'avatar:crop:title' => 'Beskärningsverktyg för avatar',
	'avatar:upload:instructions' => "Din avatar visas runt om på den här webbplatsen. Du kan 
ändra den hur ofta du vill. (Accepterade fil-format: GIF, JPG eller PNG)",
	'avatar:create:instructions' => 'Tryck och dra en fyrkant nedan för att matcha hur du vill att din avatar blir beskuren. En förhandsvisning kommer att visa sig i en box till höger. När du är nöjd med förhandsvisningen, tryck "Skapa din avatar". Den här beskurna versionen kommer användas runt omkring på webbplatsen som din avatar.',
	'avatar:upload:success' => 'Avatar laddades upp med lyckat resultat',
	'avatar:upload:fail' => 'Uppladdningen av avataren misslyckades',
	'avatar:resize:fail' => 'Ändring av storleken för avataren misslyckades',
	'avatar:crop:success' => 'Beskärningen av avataren lyckades. ',
	'avatar:crop:fail' => 'Beskärningen av avatar misslyckades',
	'avatar:remove:success' => 'Borttagningen av avatar lyckades',
	'avatar:remove:fail' => 'Det misslyckades med att ta bort avataren',
	
	'action:user:validate:already' => "%s var redan validerad",
	'action:user:validate:success' => "%s har validerats",
	'action:user:validate:error' => "Ett fel uppstod under valideringen av %s",

/**
 * Feeds
 */
	'feed:rss' => 'RSS',
	'feed:rss:title' => 'RSS-flöde för den här sidan',
/**
 * Links
 */
	'link:view' => 'visa länk',
	'link:view:all' => 'Visa alla',


/**
 * River
 */
	'river' => "River",
	'river:user:friend' => "%s är nu vän med %s",
	'river:update:user:avatar' => '%s har en ny avatar',
	'river:noaccess' => 'Du har inte behörighet att visa det här objektet.',
	'river:posted:generic' => '%s skickad',
	'riveritem:single:user' => 'en användare',
	'riveritem:plural:user' => 'några användare',
	'river:ingroup' => 'i gruppen %s',
	'river:none' => 'Ingen aktivitet',
	'river:update' => 'Uppdatering för %s',
	'river:delete' => 'Ta bort det här aktivitetsobjektet',
	'river:delete:success' => 'Aktivitetsobjektet har tagits bort',
	'river:delete:fail' => 'Aktivitetsobjektet kunde inte tas bort',
	'river:delete:lack_permission' => 'Du saknar behörighet att ta bort det här aktivitetsobjektet',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Ogiltig användare',
	'activity:owner' => 'Aktivitet',

/**
 * Relationships
 */
	
	'relationship:default' => "%s relaterar till %s",

/**
 * Notifications
 */
	'notifications:usersettings' => "Aviseringsinställningar",
	'notification:method:email' => 'E-post',

	'notifications:usersettings:save:ok' => "Aviseringsinställningar sparades med lyckat resultat.",
	'notifications:usersettings:save:fail' => "Det uppstod ett problem med att spara aviseringsinställningarna.",

	'notification:subject' => 'Avisering om %s',
	'notification:body' => 'Visa den nya aktiviteten på %s',

/**
 * Search
 */

	'search' => "Sök",
	'searchtitle' => "Sök: %s",
	'users:searchtitle' => "Sök efter användare: %s",
	'groups:searchtitle' => "Sök efter grupper: %s",
	'advancedsearchtitle' => "%s med resultat som matchar %s",
	'notfound' => "Inga resultat hittades.",
	'next' => "Nästa",
	'previous' => "Föregående",

	'viewtype:change' => "Ändra listtyp",
	'viewtype:list' => "Listvy",
	'viewtype:gallery' => "Galleri",

	'tag:search:startblurb' => "Objekt med taggar som matchar '%s':",

	'user:search:startblurb' => "Användare som matchar '%s':",
	'user:search:finishblurb' => "För att visa mer, tryck här.",

	'group:search:startblurb' => "Grupper som matchar '%s':",
	'group:search:finishblurb' => "För att visa mer, tryck här.",
	'search:go' => 'Gå',
	'userpicker:only_friends' => 'Bara vänner',

/**
 * Account
 */

	'account' => "Konto",
	'settings' => "Inställningar",
	'tools' => "Verktyg",
	'settings:edit' => 'Redigera inställningar',

	'register' => "Registrera",
	'registerok' => "Du har registrerat dig för %s med lyckat resultat .",
	'registerbad' => "Din registrering misslyckades av ett okänt fel.",
	'registerdisabled' => "Systemadministratören har stängt av registrering",
	'register:fields' => 'Alla fält krävs',

	'registration:noname' => 'Visningsnamn krävs.',
	'registration:notemail' => 'E-postadressen som du angav verkar inte vara en giltig e-postadress.',
	'registration:userexists' => 'Det användarnamnet finns redan.',
	'registration:usernametooshort' => 'Ditt användarnamn måste vara minst %u tecken långt.',
	'registration:usernametoolong' => 'Ditt användarnamn är för långt. Det kan ha maximalt %u tecken.',
	'registration:passwordtooshort' => 'Lösenordet måste vara minst %u tecken långt.',
	'registration:dupeemail' => 'Den här e-postadressen har redan registrerats.',
	'registration:invalidchars' => 'Tyvärr innehåller ditt användarnamn tecknet %s som är ogiltigt. Följande tecken är ogiltiga: %s',
	'registration:emailnotvalid' => 'Tyvärr e-postadressen du fyllde i är ogiltig i det här systemet',
	'registration:passwordnotvalid' => 'Tyvärr lösenordet du fyllde i är ogiltig i det här systemet',
	'registration:usernamenotvalid' => 'Tyvärr användarnamnet du fyllde i är ogiltig i det här systemet',

	'adduser' => "Lägg till användare",
	'adduser:ok' => "Du har lagt till en ny användare med lyckat resultat.",
	'adduser:bad' => "Den nya användaren kunde inte skapas.",

	'user:set:name' => "Inställningar för kontonamn",
	'user:name:label' => "Visningsnamn",
	'user:name:success' => "Visningsnamn ändrades i systemet med lyckat resultat.",
	'user:name:fail' => "Det gick inte ändra visningsnamn i systemet.",
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Det gick inte att ändra användarnamn i systemet.",

	'user:set:password' => "Lösenord för kontot",
	'user:current_password:label' => 'Nuvarande lösenord',
	'user:password:label' => "Nytt lösenord",
	'user:password2:label' => "Nytt lösenord igen",
	'user:password:success' => "Lösenordet ändrades",
	'user:password:fail' => "Det gick inte att ändra ditt lösenord i systemet.",
	'user:password:fail:notsame' => "De två lösenorden stämmer inte överens!",
	'user:password:fail:tooshort' => "Lösenord är för kort!",
	'user:password:fail:incorrect_current_password' => 'Lösenordet du angav är inkorrekt.',
	'user:changepassword:unknown_user' => 'Ogiltig användare.',
	'user:changepassword:change_password_confirm' => 'Det kommer ändra ditt lösenord.',

	'user:set:language' => "Språkinställningar",
	'user:language:label' => "Språk",
	'user:language:success' => "Språkinställningar har uppdaterats.",
	'user:language:fail' => "Språkinställningar kunde inte sparas.",

	'user:username:notfound' => 'Användarnamn %s hittades inte.',
	'user:username:help' => 'Var medveten om att ändring av användarnamn kommer ändra alla dynamiska användarrelaterade länkar',

	'user:password:lost' => 'Glömt lösenord',
	'user:password:hash_missing' => 'Tyvärr måste vi be dig att återställa ditt lösenord. Vi har förbättrat säkerheten för lösenord på den här sidan, men kunde inte migrera alla konton i processen.',
	'user:password:changereq:success' => 'Du har bett om ett nytt lösenord, e-post är skickad',
	'user:password:changereq:fail' => 'Det gick inte att be om en nytt lösenord.',

	'user:password:text' => 'För att be om ett nytt lösenord, ange ditt användarnamn eller e-postadress nedan och tryck på knappen.',

	'user:persistent' => 'Kom ihåg mig',

	'walled_garden:home' => 'Hem',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "Lösenordet måste vara minst %s tecken.",
	'password:requirements:lower' => "Lösenordet måsta ha minst %s små bokstäver.",
	'password:requirements:no_lower' => "Lösenordet borde inte innehålla några små bokstäver.",
	'password:requirements:upper' => "Lösenordet måste ha minst %s stora bokstäver.",
	'password:requirements:no_upper' => "Lösenordet borde inte innehålla några stora bokstäver.",
	'password:requirements:number' => "Lösenordet måste ha minst %s siffra.",
	'password:requirements:no_number' => "Lösenordet borde inte innehålla några siffror.",
	'password:requirements:special' => "Lösenordet måste ha minst %s specialtecken.",
	'password:requirements:no_special' => "Lösenordet borde inte innehålla några specialtecken.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrera',
	'menu:page:header:configure' => 'Konfigurera',
	'menu:page:header:develop' => 'Utveckla',
	'menu:page:header:information' => 'Information',
	'menu:page:header:default' => 'Andra',

	'admin:view_site' => 'Visa webbplats',
	'admin:loggedin' => 'Inloggad som %s',
	'admin:menu' => 'Meny',

	'admin:configuration:success' => "Dina inställningar har sparats.",
	'admin:configuration:fail' => "Dina inställningar kunde inte sparas.",
	'admin:configuration:dataroot:relative_path' => 'Kunde inte ställa in "%s" som datarot eftersom det inte är en absolut sökväg.',
	'admin:configuration:default_limit' => 'Antalet objekt per sida måste vara minst 1.',

	'admin:unknown_section' => 'Ogiltig avdelning för Admin.',

	'admin' => "Administration",
	'admin:header:release' => "Elgg release: %s",
	'admin:description' => "Adminpanelen låter dig kontrollera alla aspekter i systemet, från användarhantering till hur tillägg beter sig. Välj ett alternativ nedan för att börja.",

	'admin:performance' => 'Prestanda',
	'admin:performance:label:generic' => 'Allmänt',
	'admin:performance:generic:description' => 'Nedan finns en lista med prestandaförslag / värden som kan hjälpa till att ställa in din webbplats',
	'admin:performance:simplecache' => 'Simplecache',
	'admin:performance:simplecache:settings:warning' => "It's recommended you configure the simplecache setting in the settings.php.
Configuring simplecache in the settings.php file improves caching performance.
It allows Elgg to skip connecting to the database when serving cached JavaScript and CSS files",
	'admin:performance:systemcache' => 'Systemcache',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'The mod_cache module provides HTTP-aware caching schemes. This means that the files will be cached according
to an instruction specifying how long a page can be considered "fresh".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'Inga begränsningar har ställts in',
	'admin:performance:php:open_basedir:warning' => 'A small amount of open_basedir limitations are in effect, this could impact performance.',
	'admin:performance:php:open_basedir:error' => 'A large amount of open_basedir limitations are in effect, this will probably impact performance.',
	'admin:performance:php:open_basedir:generic' => 'With open_basedir every file access will be checked against the list of limitations. Since Elgg has a lot of
file access this will negatively impact performance. Also PHPs opcache can no longer cache file paths in memory and has to resolve this upon every access.',
	
	'admin:statistics' => 'Statistik',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Senaste Cron-jobben',
	'admin:cron:period' => 'Cron-period',
	'admin:cron:friendly' => 'Senast fullbordad',
	'admin:cron:date' => 'Datum och tid',
	'admin:cron:msg' => 'Meddelande',
	'admin:cron:started' => 'Cron-jobb för "%s" startade vid %s',
	'admin:cron:started:actual' => 'Cron-intervall "%s" startade processa vid %s',
	'admin:cron:complete' => 'Cron-jobb för "%s" fullbordat vid %s',

	'admin:appearance' => 'Utseende',
	'admin:administer_utilities' => 'Verktyg',
	'admin:develop_utilities' => 'Verktyg',
	'admin:configure_utilities' => 'Verktyg',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Användare",
	'admin:users:online' => 'Online nu',
	'admin:users:newest' => 'Nyast',
	'admin:users:admins' => 'Administratörer',
	'admin:users:searchuser' => 'Sök användare för att göra de till admin',
	'admin:users:existingadmins' => 'Lista över existerande admins',
	'admin:users:add' => 'Lägg till Ny Användare',
	'admin:users:description' => "Den här adminpanelen låter dig kontrollera användarinställningar för din webbplats. Välj ett alternativ nedan för att börja.",
	'admin:users:adduser:label' => "Tryck här för att lägga till ny användare...",
	'admin:users:opt:linktext' => "Konfigurera användare...",
	'admin:users:opt:description' => "Konfigurera användare och kontoinformation.",
	'admin:users:find' => 'Hitta',
	'admin:users:unvalidated' => 'Inte validerad',
	'admin:users:unvalidated:no_results' => 'Inga användare att validera.',
	'admin:users:unvalidated:registered' => 'Registrerad: %s',
	'admin:users:unvalidated:change_email' => 'Ändra e-postadress',
	'admin:users:unvalidated:change_email:user' => 'Ändra e-postadress för: %s',
	
	'admin:configure_utilities:maintenance' => 'Underhållningsläge',
	'admin:upgrades' => 'Uppgraderingar',
	'admin:upgrades:finished' => 'Färdig',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Starttid',
	'admin:upgrades:db:end_time' => 'Sluttid',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Väntande uppgraderingar',
	'admin:upgrades:menu:completed' => 'Färdiga uppgraderingar',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Run this upgrade',
	'admin:upgrades:run' => 'Run upgrades now',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s does not exist or not a valid instance of ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner for the upgrade %s (%s) could not be instantiated',
	'admin:upgrades:completed' => 'Upgrade "%s" completed at %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" completed at %s but encountered %s errors',
	'admin:upgrades:failed' => 'Upgrade "%s" failed',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'Inställningar',
	'admin:settings:basic' => 'Allmänna Inställningar',
	'admin:settings:i18n' => 'Internationalization',
	'admin:settings:advanced' => 'Avancerade Inställningar',
	'admin:settings:users' => 'Användare',
	'admin:site_settings' => "Webbplatsinställningar",
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:site:opt:linktext' => "Configure site...",
	'admin:settings:in_settings_file' => 'This setting is configured in settings.php',

	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Svagt",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderera",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Starkt",
	'site_secret:strength_msg:strong' => "Your site secret is sufficiently strong. There is no need to regenerate it.",

	'admin:dashboard' => 'Kontrollpanel',
	'admin:widget:online_users' => 'Online användare',
	'admin:widget:online_users:help' => 'Listar  nuvarande användare på webbplatsen ',
	'admin:widget:new_users' => 'Nya användare',
	'admin:widget:new_users:help' => 'Listar de nyaste användarna',
	'admin:widget:banned_users' => 'Avstängda användare',
	'admin:widget:banned_users:help' => 'Listar de avstängda användarna',
	'admin:widget:content_stats' => 'Innehållsstatistik',
	'admin:widget:content_stats:help' => 'Håll koll på innehållet som skapats av dina användare',
	'admin:widget:cron_status' => 'Cron-status',
	'admin:widget:cron_status:help' => 'Shows the status of the last time cron jobs finished',
	'admin:statistics:numentities' => 'Innehållsstatistik',
	'admin:statistics:numentities:type' => 'Innehållstyp',
	'admin:statistics:numentities:number' => 'Siffra',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Välkommen',
	'admin:widget:admin_welcome:help' => "En kort introduktion till Elggs Adminstration",
	'admin:widget:admin_welcome:intro' =>
'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:registration' => "Registration for new users is currently disabled! You can enabled this on the %s page.",
	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Basic tasks like managing users, monitoring reported content and activating plugins.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or configuring settings of a plugin.</dd>
		<dt>Information</dt><dd>Information about your site like statistics.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Kontrollpanelen',
	'admin:widget:control_panel:help' => "Provides easy access to common controls",

	'admin:cache:flush' => 'Flush the caches',
	'admin:cache:flushed' => "The site's caches have been flushed",
	'admin:cache:invalidate' => 'Invalidate the caches',
	'admin:cache:invalidated' => "The site's caches have been invalidated",
	'admin:cache:clear' => 'Clear the caches',
	'admin:cache:cleared' => "The site's caches have been cleared",
	'admin:cache:purge' => 'Purge the caches',
	'admin:cache:purged' => "The site's caches have been purged",

	'admin:footer:faq' => 'Administrationens FAQ',
	'admin:footer:manual' => 'Administrationens Manual',
	'admin:footer:community_forums' => 'Elgg Community Forum',
	'admin:footer:blog' => 'Elgg Blogg',

	'admin:plugins:category:all' => 'Alla tillägg',
	'admin:plugins:category:active' => 'Active plugins',
	'admin:plugins:category:inactive' => 'Inactive plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Sammanslagna',
	'admin:plugins:category:nonbundled' => 'Inte sammanslagna',
	'admin:plugins:category:content' => 'Innehåll',
	'admin:plugins:category:development' => 'Utveckling',
	'admin:plugins:category:enhancement' => 'Förbättring',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'Kommunikation',
	'admin:plugins:category:security' => 'Säkerhet och Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Teman',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Verktyg',

	'admin:plugins:markdown:unknown_plugin' => 'Okänt tillägg.',
	'admin:plugins:markdown:unknown_file' => 'Okänd fil.',

	'admin:notices:delete_all' => 'Avfärda %s-notiser',
	'admin:notices:could_not_delete' => 'Kunde inte radera notisen.',
	'item:object:admin_notice' => 'Adminnotis',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Admin options',

	'admin:security' => 'Säkerhet',
	'admin:security:information' => 'Information',
	'admin:security:information:description' => 'On this page you can find a list of security recommendations.',
	'admin:security:information:https' => 'Is the website protected by HTTPS',
	'admin:security:information:https:warning' => "It's recommended to protect your website using HTTPS, this helps protect data
(eg. passwords) from being sniffed over the internet connection.",
	'admin:security:information:wwwroot' => 'Website main folder is writable',
	'admin:security:information:wwwroot:error' => "It's recommended that you install Elgg in a folder which isn't writable by your webserver.
Malicious visitors could place unwanted code in your website.",
	'admin:security:information:validate_input' => 'Input validation',
	'admin:security:information:validate_input:error' => "Some plugin has disabled the input validation on your website, this will allow users to
submit potentially harmfull content (eg. cross-site-scripting, etc)",
	'admin:security:information:password_length' => 'Minsta lösenordslängd',
	'admin:security:information:password_length:warning' => "Det rekommenderas att ha minst 6 tecken i ett lösenord.",
	'admin:security:information:username_length' => 'Minsta längden på användarnamn',
	'admin:security:information:username_length:warning' => "Det rekommenderas att ha minst 4 tecken i ett användarnamn.",
	'admin:security:information:php:session_gc' => "PHP session cleanup",
	'admin:security:information:php:session_gc:chance' => "Cleanup chance: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Session lifetime %s seconds",
	'admin:security:information:php:session_gc:error' => "It's recommended to set 'session.gc_probability' and 'session.gc_divisor' in your PHP settings, this will cleanup
expired sessions from your database and not allow users to reuse old sessions.",
	'admin:security:information:htaccess:hardening' => ".htaccess file access hardening",
	'admin:security:information:htaccess:hardening:help' => "In the .htaccess file access to certain files can be blocked to increase security on your site. For more information look in your .htaccess file.",
	
	'admin:security:settings' => 'Inställningar',
	'admin:security:settings:description' => 'På den här sidan kan du konfigurera några säkerhetsfunktioner. Vänligen läs om inställningarna försiktigt.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:account' => 'Konto',
	'admin:security:settings:label:notifications' => 'Aviseringar',
	'admin:security:settings:label:site_secret' => 'Site secret',
	
	'admin:security:settings:notify_admins' => 'Meddela alla administratörer på webbplatsen när en admin läggs till eller tas bort',
	'admin:security:settings:notify_admins:help' => 'This will send out a notification to all site administrators that one of the admins added/removed a site administrator.',
	
	'admin:security:settings:notify_user_admin' => 'Notify the user when the admin role is added or removed',
	'admin:security:settings:notify_user_admin:help' => 'This will send a notification to the user that the admin role was added to/removed from their account.',
	
	'admin:security:settings:notify_user_ban' => 'Notify the user when their account gets (un)banned',
	'admin:security:settings:notify_user_ban:help' => 'This will send a notification to the user that their account was (un)banned.',
	
	'admin:security:settings:notify_user_password' => 'Notify the user when they change their password',
	'admin:security:settings:notify_user_password:help' => 'This will send a notification to the user when they change their password.',
	
	'admin:security:settings:protect_upgrade' => 'Protect upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'This will protect upgrade.php so you require a valid token or you\'ll have to be an administrator.',
	'admin:security:settings:protect_upgrade:token' => 'In order to be able to use the upgrade.php when logged out or as a non admin, the following URL needs to be used:',
	
	'admin:security:settings:protect_cron' => 'Protect the /cron URLs',
	'admin:security:settings:protect_cron:help' => 'This will protect the /cron URLs with a token, only if a valid token is provided will the cron execute.',
	'admin:security:settings:protect_cron:token' => 'In order to be able to use the /cron URLs the following tokens needs to be used. Please note that each interval has its own token.',
	'admin:security:settings:protect_cron:toggle' => 'Show/hide cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Disable autocomplete on password fields',
	'admin:security:settings:disable_password_autocomplete:help' => 'Data entered in these fields will be cached by the browser. An attacker who can access the victim\'s browser could steal this information. This is especially important if the application is commonly used in shared computers such as cyber cafes or airport terminals. If you disable this, password management tools can no longer autofill these fields. The support for the autocomplete attribute can be browser specific.',
	
	'admin:security:settings:email_require_password' => 'Kräv lösenord för att byta e-postadress',
	'admin:security:settings:email_require_password:help' => 'When the user wishes to change their email address, require that they provide their current password.',
	
	'admin:security:settings:email_require_confirmation' => 'Kräver bekräftelse vid ändring av e-postadress',
	'admin:security:settings:email_require_confirmation:help' => 'Den nya e-postadressen behöver bekräftas innan ändringen kan göras. Efter en lyckad ändring, kommer en avisering att skickas till den gamla e-postadressen.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:security:settings:minusername' => "Minsta längden på användarnamn",
	'admin:security:settings:minusername:help' => "Minsta antalet tecken som krävs i ett användarnamn",
	
	'admin:security:settings:min_password_length' => "Minsta lösenordslängd",
	'admin:security:settings:min_password_length:help' => "Minsta antalet tecken som krävs i ett lösenord",
	
	'admin:security:settings:min_password_lower' => "Minsta antal små bokstäver i ett lösenord",
	'admin:security:settings:min_password_lower:help' => "Configure the minimal number of lower case (a-z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_upper' => "Minsta antal stora bokstäver i ett lösenord",
	'admin:security:settings:min_password_upper:help' => "Configure the minimal number of upper case (A-Z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_number' => "Minsta antal siffror i ett lösenord",
	'admin:security:settings:min_password_number:help' => "Configure the minimal number of number (0-9) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_special' => "Minsta antal specialtecken i ett lösenord",
	'admin:security:settings:min_password_special:help' => "Configure the minimal number of special (!@$%^&*()<>,.?/[]{}-=_+) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:site:secret:regenerated' => "Your site secret has been regenerated",
	'admin:site:secret:prevented' => "The regeneration of the site secret was prevented",
	
	'admin:notification:make_admin:admin:subject' => 'A new site administrator was added to %s',
	'admin:notification:make_admin:admin:body' => 'Hi %s,

%s made %s a site administrator of %s.

To view the profile of the new administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:make_admin:user:subject' => 'You were added as a site administator of %s',
	'admin:notification:make_admin:user:body' => 'Hi %s,

%s made you a site administrator of %s.

To go to the site, click here:
%s',
	'admin:notification:remove_admin:admin:subject' => 'A site administrator was removed from %s',
	'admin:notification:remove_admin:admin:body' => 'Hi %s,

%s removed %s as a site administrator of %s.

To view the profile of the old administrator, click here:
%s

To go to the site, click here:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'You were removed as a site administator from %s',
	'admin:notification:remove_admin:user:body' => 'Hi %s,

%s removed you as site administrator of %s.

To go to the site, click here:
%s',
	'user:notification:ban:subject' => 'Ditt konto på %s har blivit avstängt',
	'user:notification:ban:body' => 'Hej %s,

Ditt konto på %s har blivit avstängt.

För att gå till webbplatsen, tryck här:
%s',
	
	'user:notification:unban:subject' => 'Ditt konto på %s är inte avstängt längre',
	'user:notification:unban:body' => 'Hej %s,

Ditt konto på %s är inte längre avstängt. Du kan använda webbplatsen igen.

För att gå till webbplatsen, tryck här:
%s',
	
	'user:notification:password_change:subject' => 'Ditt lösenord har ändrats!',
	'user:notification:password_change:body' => "Hej %s,

Ditt lösenord på '%s' har ändrats! Om du gjorde den här ändringen är allt i sin ordning.

Om du inte gjorde den här ändringen, vänligen återställ ditt lösenord här:
%s

Eller kontakta en sidadminstratör:
%s",
	
	'admin:notification:unvalidated_users:subject' => "Users awaiting approval on %s",
	'admin:notification:unvalidated_users:body' => "Hi %s,

%d users of '%s' are awaiting approval by an administrator.

See the full list of users here:
%s",

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:settings:remove:ok' => "All settings for the %s plugin have been removed",
	'plugins:settings:remove:fail' => "An error occured while removing all settings for the plugin %s",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	
	'item:object:plugin' => 'Tillägg',
	'collection:object:plugin' => 'Tillägg',
	
	'plugins:settings:remove:menu:text' => "Radera alla inställningar",
	'plugins:settings:remove:menu:confirm' => "Are you sure you wish to remove all settings, including user settings from this plugin?",

	'admin:plugins' => "Tillägg",
	'admin:plugins:activate_all' => 'Aktivera Alla',
	'admin:plugins:deactivate_all' => 'Avaktivera Alla',
	'admin:plugins:activate' => 'Aktivera',
	'admin:plugins:deactivate' => 'Avaktivera',
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Namn",
	'admin:plugins:label:author' => "Författare",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Kategorier',
	'admin:plugins:label:licence' => "Licens",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Filer",
	'admin:plugins:label:resources' => "Resurser",
	'admin:plugins:label:screenshots' => "Screenshots",
	'admin:plugins:label:repository' => "Kod",
	'admin:plugins:label:bugtracker' => "Rapportera problem",
	'admin:plugins:label:donate' => "Donera",
	'admin:plugins:label:moreinfo' => 'mer info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Plats',
	'admin:plugins:label:priority' => 'Prioritet',
	'admin:plugins:label:contributors' => 'Medverkande',
	'admin:plugins:label:contributors:name' => 'Namn',
	'admin:plugins:label:contributors:email' => 'E-post',
	'admin:plugins:label:contributors:website' => 'Webbplats',
	'admin:plugins:label:contributors:username' => 'Community användarnamn',
	'admin:plugins:label:contributors:description' => 'Beskrivning',
	'admin:plugins:label:dependencies' => 'Beroenden',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'kan inte aktivera',
	'admin:plugins:cannot_deactivate' => 'kan inte avaktivera',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

	'admin:plugins:set_priority:yes' => "Omordnade %s.",
	'admin:plugins:set_priority:no' => "Kunde inte omordna %s.",
	'admin:plugins:set_priority:no_with_msg' => "Kunde inte omordna %s. Fel: %s",
	'admin:plugins:deactivate:yes' => "Avaktiverade %s.",
	'admin:plugins:deactivate:no' => "Kunde inte avaktivera %s.",
	'admin:plugins:deactivate:no_with_msg' => "Kunde inte avaktivera %s. Fel: %s",
	'admin:plugins:activate:yes' => "Aktiverade %s.",
	'admin:plugins:activate:no' => "Kunde inte aktivera %s.",
	'admin:plugins:activate:no_with_msg' => "Kunde inte aktivera %s. Fel: %s",
	'admin:plugins:categories:all' => 'Alla kategorier',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'Plugin Settings',
	'admin:plugins:warning:unmet_dependencies_active' => 'This plugin is active but has unmet dependencies. You may encounter problems. See "more info" below for details.',

	'admin:plugins:dependencies:type' => 'Typ',
	'admin:plugins:dependencies:name' => 'Namn',
	'admin:plugins:dependencies:expected_value' => 'Förväntat Värde',
	'admin:plugins:dependencies:local_value' => 'Aktuellt värde',
	'admin:plugins:dependencies:comment' => 'Kommentar',

	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "Visa statistisk information om användare och objekt på din webbplats.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Antalet användare",
	'admin:statistics:label:numonline' => "Antalet användare online",
	'admin:statistics:label:onlineusers' => "Användare online nu",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg-version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Database Version",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Krav',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Visa PHPInfo',
	'admin:server:label:web_server' => 'Webbserver',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Log Location',
	'admin:server:label:php_version' => 'PHP-version',
	'admin:server:label:php_version:required' => 'Elgg requires a minimal PHP version of 7.1',
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
		For improved performance, it is recommended that you enable and configure memcache (or redis).
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
	
	'admin:server:requirements:php_extension' => "PHP extension: %s",
	'admin:server:requirements:php_extension:required' => "This PHP extension is required for the correct operation of Elgg",
	'admin:server:requirements:php_extension:recommended' => "This PHP extension is recommended for the optimal operation of Elgg",
	'admin:server:requirements:rewrite' => ".htaccess rewrite rules",
	'admin:server:requirements:rewrite:fail' => "Check your .htaccess for the correct rewrite rules",
	
	'admin:server:requirements:database:server' => "Database server",
	'admin:server:requirements:database:server:required' => "Elgg requires MySQL v5.5.3 or higher for its database",
	'admin:server:requirements:database:client' => "Database client",
	'admin:server:requirements:database:client:required' => "Elgg requires pdo_mysql to connect to the database server",
	
	'admin:user:label:search' => "Hitta användare:",
	'admin:user:label:searchbutton' => "Sök",

	'admin:user:ban:no' => "Kan inte stänga av användare",
	'admin:user:ban:yes' => "Användare avstängd.",
	'admin:user:self:ban:no' => "Du kan inte stänga av dig själv",
	'admin:user:unban:no' => "Kan inte ta bort avstängningen själv",
	'admin:user:unban:yes' => "Användare inte avstängd längre.",
	'admin:user:delete:no' => "Kan inte ta bort användare",
	'admin:user:delete:yes' => "Användaren %s har tagits bort",
	'admin:user:self:delete:no' => "Du kan inte ta bort dig själv",

	'admin:user:resetpassword:yes' => "Återställning av lösenord, användare meddelad.",
	'admin:user:resetpassword:no' => "Lösenord kunde inte återställas.",

	'admin:user:makeadmin:yes' => "Användare är nu en admin.",
	'admin:user:makeadmin:no' => "Vi kunde inte göra den här användaren till en admin.",

	'admin:user:removeadmin:yes' => "Användare är inte längre en admin.",
	'admin:user:removeadmin:no' => "Vi kunde inte ta bort administratörsprivilegier från den här användaren.",
	'admin:user:self:removeadmin:no' => "Du kan inte ta bort dina egna administratörsprivilegier.",

	'admin:configure_utilities:menu_items' => 'Menyobjekt',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select the order of site menu items. Unconfigured items will be added to the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

	'admin:configure_utilities:default_widgets' => 'Standardwidgets',
	'admin:default_widgets:unknown_type' => 'Okänd widgettyp',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Edit this site's robots.txt file below",
	'admin:robots.txt:plugins' => "Plugins are adding the following to the robots.txt file",
	'admin:robots.txt:subdir' => "The robots.txt tool will not work because Elgg is installed in a sub-directory",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'Den här webbplatsen ligger nere för underhåll',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Underhållningsläge',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'Webbplatsen är i underhållsläge.',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "Panelen för användarinställningarna låter dig hålla koll på alla dina personliga inställningar, från användarhantering till hur tillägg beter sig. Välj ett alternativ nedan för att börja.",

	'usersettings:statistics' => "Din statistik",
	'usersettings:statistics:opt:description' => "Visa statistisk information om användare och objekt på din webbplats.",
	'usersettings:statistics:opt:linktext' => "Kontostatistik",

	'usersettings:statistics:login_history' => "Inloggningshistorik",
	'usersettings:statistics:login_history:date' => "Datum",
	'usersettings:statistics:login_history:ip' => "IP-Adress",

	'usersettings:user' => "%ss inställningar",
	'usersettings:user:opt:description' => "Den här låter dig att hålla koll på användarinställningar.",
	'usersettings:user:opt:linktext' => "Ändra dina inställningar",

	'usersettings:plugins' => "Verktyg",
	'usersettings:plugins:opt:description' => "Konfigurera inställningar (om några) för dina aktiva verktyg.",
	'usersettings:plugins:opt:linktext' => "Konfigurera dina verktyg",

	'usersettings:plugins:description' => "Den här panelen låter dig hålla koll på och konfigurera de personliga inställningarna för verktygen som är installerade av din systemadministratör.",
	'usersettings:statistics:label:numentities' => "Ditt innehåll",

	'usersettings:statistics:yourdetails' => "Dina detaljer",
	'usersettings:statistics:label:name' => "Namn",
	'usersettings:statistics:label:email' => "E-post",
	'usersettings:statistics:label:membersince' => "Medlem sedan",
	'usersettings:statistics:label:lastlogin' => "Senast inloggad",

/**
 * Activity river
 */

	'river:all' => 'Hela Webbplatsens Aktivitet',
	'river:mine' => 'Min Aktivitet',
	'river:owner' => 'Aktivitet för %s',
	'river:friends' => 'Vänners Aktivitet',
	'river:select' => 'Visa %s',
	'river:comments:more' => '+%u mer',
	'river:comments:all' => 'Visa alla %u kommentarer',
	'river:generic_comment' => 'kommented på %s %s',

/**
 * Icons
 */

	'icon:size' => "Storlek på ikon",
	'icon:size:topbar' => "Topbar",
	'icon:size:tiny' => "Tiny",
	'icon:size:small' => "Small",
	'icon:size:medium' => "Medium",
	'icon:size:large' => "Large",
	'icon:size:master' => "Extra Large",
	
	'entity:edit:icon:crop_messages:generic' => "Den valda bilden uppfyller inte de rekommenderade dimensionerna. Det här kan resultera i ikoner med låg kvalité.",
	'entity:edit:icon:crop_messages:width' => "Det rekommenderas att använda en bild med en bredd på minst %dpx.",
	'entity:edit:icon:crop_messages:height' => "Det rekommenderas att använda en bild med en höjd på minst %dpx.",
	'entity:edit:icon:file:label' => "Ladda upp en ny ikon",
	'entity:edit:icon:file:help' => "Lämna tomt för att behålla nuvarande ikon.",
	'entity:edit:icon:remove:label' => "Ta bort ikon",

/**
 * Generic action words
 */

	'save' => "Spara",
	'save_go' => "Spara, och gå till %s",
	'reset' => 'Återställ',
	'publish' => "Publicera",
	'cancel' => "Ångra",
	'saving' => "Sparar...",
	'update' => "Uppdatera",
	'preview' => "Förhandsvisa",
	'edit' => "Redigera",
	'delete' => "Ta bort",
	'accept' => "Acceptera",
	'reject' => "Avvisa",
	'decline' => "Neka",
	'approve' => "Godkänna",
	'activate' => "Aktivera",
	'deactivate' => "Avaktivera",
	'disapprove' => "Neka",
	'revoke' => "Återkalla",
	'load' => "Ladda",
	'upload' => "Ladda upp",
	'download' => "Ladda ner",
	'ban' => "Stänga av",
	'unban' => "Ta bort avstängning",
	'banned' => "Avstängd",
	'enable' => "Enable",
	'disable' => "Disable",
	'request' => "Request",
	'complete' => "Färdig",
	'open' => 'Öppna',
	'close' => 'Stänga',
	'hide' => 'Gömma',
	'show' => 'Visa',
	'reply' => "Svara",
	'more' => 'Mer',
	'more_info' => 'Mer info',
	'comments' => 'Kommentarer',
	'import' => 'Importera',
	'export' => 'Exportera',
	'untitled' => 'Utan titel',
	'help' => 'Hjälp',
	'send' => 'Skicka',
	'post' => 'Skicka',
	'submit' => 'Skicka in',
	'comment' => 'Kommentera',
	'upgrade' => 'Uppgradera',
	'sort' => 'Sortera',
	'filter' => 'Filtrera',
	'new' => 'Ny',
	'add' => 'Lägga till',
	'create' => 'Skapa',
	'remove' => 'Ta bort',
	'revert' => 'Återgå',
	'validate' => 'Validera',
	'read_more' => 'Läs mer',

	'site' => 'Webbplats',
	'activity' => 'Aktivitet',
	'members' => 'Medlemmar',
	'menu' => 'Meny',

	'up' => 'Upp',
	'down' => 'Ner',
	'top' => 'Toppen',
	'bottom' => 'Botten',
	'right' => 'Höger',
	'left' => 'Vänster',
	'back' => 'Tillbaka',

	'invite' => "Bjuda in",

	'resetpassword' => "Återställa lösenord",
	'changepassword' => "Ändra lösenord",
	'makeadmin' => "Skapa admin",
	'removeadmin' => "Ta bort admin",

	'option:yes' => "Ja",
	'option:no' => "Nej",

	'unknown' => 'Okänt',
	'never' => 'Aldrig',

	'active' => 'Aktiv',
	'total' => 'Totalt',
	'unvalidated' => 'Inte validerad',
	
	'ok' => 'OK',
	'any' => 'något',
	'error' => 'Fel',

	'other' => 'Andra',
	'options' => 'Alternativ',
	'advanced' => 'Avancerad',

	'learnmore' => "Tryck här för att lära dig mer.",
	'unknown_error' => 'Okänt fel.',

	'content' => "innehåll",
	'content:latest' => 'Senaste aktivitet',
	'content:latest:blurb' => 'Alternativt, tryck här för att visa det senaste innehållet från hela webbplatsen.',
	
	'list:out_of_bounds' => "Du har nått en del av listan utan något innehåll, men det finns tillgängligt innehåll tillgängligt.",
	'list:out_of_bounds:link' => "Gå tillbaka till den första sidan i den här listan.",

	'link:text' => 'visa länk',

/**
 * Generic questions
 */

	'question:areyousure' => 'Är du säker?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Osparat Utkast',
	'status:draft' => 'Utkast',
	'status:unpublished' => 'Opublicerat',
	'status:published' => 'Publicerat',
	'status:featured' => 'Funktioner',
	'status:open' => 'Öppen',
	'status:closed' => 'Stängd',
	'status:enabled' => 'Aktiverad',
	'status:disabled' => 'Avaktiverad',
	'status:unavailable' => 'Otillgänglig',
	'status:active' => 'Aktiv',
	'status:inactive' => 'Inaktiv',

/**
 * Generic sorts
 */

	'sort:newest' => 'Nyast',
	'sort:popular' => 'Populär',
	'sort:alpha' => 'Alfabetisk',
	'sort:priority' => 'Prioritet',

/**
 * Generic data words
 */

	'title' => "Titel",
	'description' => "Beskrivning",
	'tags' => "Taggar",
	'all' => "Alla",
	'mine' => "Min",

	'by' => 'av',
	'none' => 'ingen',

	'annotations' => "Anteckningar",
	'relationships' => "Förhållande",
	'metadata' => "Metadata",
	'tagcloud' => "Etikettmoln",

	'on' => 'På',
	'off' => 'Av',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%sK",
	'number_counter:view:million' => "%sM",
	'number_counter:view:billion' => "%sB",
	'number_counter:view:trillion' => "%sT",

/**
 * Entity actions
 */

	'edit:this' => 'Redigera det här',
	'delete:this' => 'Ta bort det här',
	'comment:this' => 'Kommentera det här',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Är du säker på att du vill ta bort det här objektet?",
	'deleteconfirm:plural' => "Är du säker på att du vill ta bort de här objektet?",
	'fileexists' => "En fil har redan laddats upp. För att ersätta den, välj en ny nedan",
	'input:file:upload_limit' => 'Maximalt tillåten filstorlek är %s',

/**
 * User add
 */

	'useradd:subject' => 'Användarkonto skapat',
	'useradd:body' => '%s,

Ett användarkonto har skapats för dig på %s. För att logga in, besök:

%s

Och logga in med dessa användaruppgifter:

Username: %s
Password: %s

När du har loggat in, rekommenderar vi starkt att du 
ändrar ditt lösenord.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Tryck för att avvisa",


/**
 * Messages
 */
	'messages:title:success' => 'Framgång',
	'messages:title:error' => 'Fel',
	'messages:title:warning' => 'Varning',
	'messages:title:help' => 'Hjälp',
	'messages:title:notice' => 'Notis',
	'messages:title:info' => 'Info',

/**
 * Import / export
 */

	'importsuccess' => "Import av data lyckades",
	'importfail' => "Import av data misslyckades.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "right now",
	'friendlytime:minutes' => "%s minuter sen",
	'friendlytime:minutes:singular' => "en minut sen",
	'friendlytime:hours' => "%s timmar sen",
	'friendlytime:hours:singular' => "en timme sen",
	'friendlytime:days' => "%s dagar sen",
	'friendlytime:days:singular' => "igår",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "i %s minuter",
	'friendlytime:future:minutes:singular' => "om en minut",
	'friendlytime:future:hours' => "om %s timmar",
	'friendlytime:future:hours:singular' => "om en timme",
	'friendlytime:future:days' => "om %s dagar",
	'friendlytime:future:days:singular' => "imorgon",

	'date:month:01' => '%s januari',
	'date:month:02' => '%s februari ',
	'date:month:03' => '%s mars',
	'date:month:04' => '%s april',
	'date:month:05' => '%s maj',
	'date:month:06' => '%s juni',
	'date:month:07' => '%s juli',
	'date:month:08' => '%s augusti',
	'date:month:09' => '%s september',
	'date:month:10' => '%s oktober',
	'date:month:11' => '%s november',
	'date:month:12' => '%s december',

	'date:month:short:01' => '%s jan',
	'date:month:short:02' => '%s feb',
	'date:month:short:03' => '%s mar',
	'date:month:short:04' => '%s apr',
	'date:month:short:05' => '%s maj',
	'date:month:short:06' => '%s jun',
	'date:month:short:07' => '%s jul',
	'date:month:short:08' => '%s aug',
	'date:month:short:09' => '%s sep',
	'date:month:short:10' => '%s okt',
	'date:month:short:11' => '%s nov',
	'date:month:short:12' => '%s dec',

	'date:weekday:0' => 'söndag',
	'date:weekday:1' => 'måndag',
	'date:weekday:2' => 'tisdag',
	'date:weekday:3' => 'onsdag',
	'date:weekday:4' => 'torsdag',
	'date:weekday:5' => 'fredag',
	'date:weekday:6' => 'lördag',

	'date:weekday:short:0' => 'sön',
	'date:weekday:short:1' => 'mån',
	'date:weekday:short:2' => 'tis',
	'date:weekday:short:3' => 'ons',
	'date:weekday:short:4' => 'tor',
	'date:weekday:short:5' => 'fre',
	'date:weekday:short:6' => 'lör',

	'interval:minute' => 'Varje minut',
	'interval:fiveminute' => 'Var femte minut',
	'interval:fifteenmin' => 'Varje kvart',
	'interval:halfhour' => 'Varje halvtimme',
	'interval:hourly' => 'Varje timme',
	'interval:daily' => 'Dagligen',
	'interval:weekly' => 'Veckovis',
	'interval:monthly' => 'Månadsvis',
	'interval:yearly' => 'Årligen',

/**
 * System settings
 */

	'installation:sitename' => "Namnet på din webbplats",
	'installation:sitedescription' => "Kort beskrivning på din webbplats (valfritt):",
	'installation:sitedescription:help' => "Med sammanslagna tillägg visas det här bara i beskrivningens meta tag i resultaten för sökmotorer.",
	'installation:wwwroot' => "Webbplatsens URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path of the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "Standard åtkomstbehörigheter:",
	'installation:language' => "Standardspråk för din webbplats:",
	'installation:debug' => "Kontrollera hur mycket information som skrivs till serverns logg.",
	'installation:debug:label' => "Logg-nivå:",
	'installation:debug:none' => 'Stäng av loggning (rekommenderas)',
	'installation:debug:error' => 'Logga endast kritiska fel',
	'installation:debug:warning' => 'Log errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'If enabled, visitors can create their own user accounts.',
	'installation:registration:label' => 'Allow visitors to register',
	'installation:adminvalidation:description' => 'If enabled, newly registered users require manual validation by an administrator before they can use the site.',
	'installation:adminvalidation:label' => 'New users require manual validation by an administrator',
	'installation:adminvalidation:notification:description' => 'When enabled, site administrators will get a notification that there are pending user validations. An administrator can disable the notification on their personal settings page.',
	'installation:adminvalidation:notification:label' => 'Notify administrators of pending user validations',
	'installation:adminvalidation:notification:direct' => 'Direkt',
	'installation:walled_garden:description' => 'If enabled, logged-out visitors can see only pages marked public (such as login and registration).',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Standard antal objekt per sida",

	'admin:site:access:warning' => "Det här är integritetsinställningarna som föreslås för användare när de skapar nytt innehåll. Ändring av det, ändrar inte åtkomst till innehåll. ",
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

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file (use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "The system cache decreases the loading time of Elgg by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content' => 'Innehåll',
	'admin:legend:content_access' => 'Content Access',
	'admin:legend:site_access' => 'Site Access',
	'admin:legend:debug' => 'Debugging and Logging',
	
	'config:i18n:allowed_languages' => "Tillåtna språk",
	'config:i18n:allowed_languages:help' => "Only allowed languages can be used by users. English and the site language are always allowed.",
	'config:users:can_change_username' => "Allow users to change their username",
	'config:users:can_change_username:help' => "If not allowed only admins can change a users username",
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Avaktivera RSS-flöden",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	'config:content:comment_box_collapses' => "The comment box collapses after the first comment on content",
	'config:content:comment_box_collapses:help' => "This only applies if the comments list is sorted latest first",
	'config:content:comments_latest_first' => "The comments should be listed with the latest comment first",
	'config:content:comments_latest_first:help' => "This controls the default behaviour of the listing of comments on a content detail page. If disabled this will also move the comment box to the end of the comments list",
	
	'upgrading' => 'Uppgraderar...',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",
	'upgrade:unable_to_upgrade' => 'Unable to upgrade.',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",

	'deprecated:function' => '%s() was deprecated by %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrade',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Fel: %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Välkommen",
	'welcome:user' => 'Välkommen %s',

/**
 * Emails
 */

	'email:from' => 'Från',
	'email:to' => 'Till',
	'email:subject' => 'Ämne',
	'email:body' => 'Innehåll',

	'email:settings' => "E-postinställningar",
	'email:address:label' => "E-postadress",
	'email:address:help:confirm' => "Väntande ändring av e-postadress till '%s', vänligen kontrollera inkorgen för instruktioner.",
	'email:address:password' => "Lösenord",
	'email:address:password:help' => "För att kunna ändra din e-postadress, behöver du ange ditt nuvarande lösenord.",

	'email:save:success' => "Ny e-postadress sparad.",
	'email:save:fail' => "Ny e-postadress kunde inte sparas.",
	'email:save:fail:password' => "Lösenordet stämmer inte överens med ditt nuvarande lösenord, kunde inte ändra din e-postadress",

	'friend:newfriend:subject' => "%s har gjort dig till vän!",
	'friend:newfriend:body' => "%s har gjort dig till en vän!

För att visa deras profil, tryck här:

%s",

	'email:changepassword:subject' => "Lösenord ändrat!",
	'email:changepassword:body' => "Hej %s,

Ditt lösenord har ändrats.",

	'email:resetpassword:subject' => "Återställning av lösenord!",
	'email:resetpassword:body' => "Hej %s,

Ditt lösenord har återställts till: %s",

	'email:changereq:subject' => "Begär ändring av lösenord.",
	'email:changereq:body' => "Hej %s,

Någon (från IP-adressen %s) har begärt en ändring av lösenord för det här kontot.

Om du begärde det här, tryck på länken nedan. Annars ignorera det här mejlet.

%s",
	
	'account:email:request:success' => "Din nya e-postadress kommer att sparas efter bekräftelse, vänligen kontrollera inkorgen på '%s' för mer instruktioner.",
	'email:request:email:subject' => "Vänligen bekräfta din e-postadress",
	'email:request:email:body' => "Hej %s,

Du begärde att få ändra din e-postadress på '%s'.
Om du inte begärde den här ändringen, kan du ignorera det här mejlet.

För att bekräfta ändringen av e-postadress, tryck på den här länken:
%s

Notera att den här länken bara är giltig i 1 timme.",
	
	'account:email:request:error:no_new_email' => "No e-mail address change pending",
	
	'email:confirm:email:old:subject' => "Din e-postadress ändrades",
	'email:confirm:email:old:body' => "Hej %s,

Din e-postadress på '%s' ändrades.
Hädanefter kommer du få aviseringar på '%s'.

Om du inte begärde den här ändringen, vänligen kontakta en administratör för webbplatsen.
%s",
	
	'email:confirm:email:new:subject' => "Din e-postadress ändrades",
	'email:confirm:email:new:body' => "Hej %s,

Din e-postadress på '%s' ändrades.
Hädanefter kommer du få aviseringar till den här e-postadressen.

Om du inte begärde den här ändringen, vänligen kontakta en administratör för den här webbplatsen.
%s",

	'account:email:admin:validation_notification' => "Notify me when there are users requiring validation by an administrator",
	'account:email:admin:validation_notification:help' => "Because of the site settings, newly registered users require manual validation by an administrator. With this setting you can disable notifications about pending validation requests.",
	
	'account:validation:pending:title' => "Account validation pending",
	'account:validation:pending:content' => "Ditt konto har registrerats! Innan du kan använda ditt konto, måste en administratör för webbplatsen validera ditt konto. Du kommer att få ett mejl när ditt konto är validerat.",
	
	'account:notification:validation:subject' => "Ditt konto på %s har validerats!",
	'account:notification:validation:body' => "Hej %s,

Ditt konto på '%s' har validerats. Du kan nu använda ditt konto.

För att gå till webbplatsen, tryck här:
%s",

/**
 * user default access
 */

	'default_access:settings' => "Din standard åtkomstnivå",
	'default_access:label' => "Standardåtkomst",
	'user:default_access:success' => "Din nya standard åtkomstnivå sparades.",
	'user:default_access:failure' => "Din nya standard åtkomstnivå kunde inte sparas.",

/**
 * Comments
 */

	'comments:count' => "%s kommenter",
	'item:object:comment' => 'Kommentar',
	'collection:object:comment' => 'Kommentarer',

	'river:object:default:comment' => '%s kommenterade på %s',

	'generic_comments:add' => "Lämna en kommentar",
	'generic_comments:edit' => "Redigera kommentar",
	'generic_comments:post' => "Skicka kommentar",
	'generic_comments:text' => "Kommentar",
	'generic_comments:latest' => "Senaste kommentarer",
	'generic_comment:posted' => "Din kommentar skickades.",
	'generic_comment:updated' => "Kommentaren uppdaterades.",
	'entity:delete:object:comment:success' => "Kommentaren togs bort.",
	'generic_comment:blank' => "Du måste lägga in något i din kommentar, innan vi kan spara det.",
	'generic_comment:notfound' => "Tyvärr kunde vi inte hitta den specificerade kommentaren.",
	'generic_comment:notfound_fallback' => "Tyvärr kunde vi inte hitta den specificerade kommentaren, men vi har vidarebefordrat dig till sidan den lämnades.",
	'generic_comment:failure' => "Ett oväntat fel uppstod när kommentaren skulle sparas.",
	'generic_comment:none' => 'Inga kommentarer',
	'generic_comment:title' => 'Kommentar av %s',
	'generic_comment:on' => '%s på %s',
	'generic_comments:latest:posted' => 'skickade en',

	'generic_comment:notification:owner:subject' => 'Du har en ny kommentar!',
	'generic_comment:notification:owner:summary' => 'Du har en ny kommentar!',
	'generic_comment:notification:owner:body' => "Du har en ny kommentar på ditt inlägg \"%s\" från %s. Det står:

%s

För att svara eller visa originalinlägget, tryck här:
%s

För att visa %ss profil, tryck här:
%s",
	
	'generic_comment:notification:user:subject' => 'En ny kommentar på: %s',
	'generic_comment:notification:user:summary' => 'En ny kommentar på: %s',
	'generic_comment:notification:user:body' => "En ny kommentar gjordes på \"%s\" av %s. Det står:

%s

För att svara eller visa orginalinlägget, tryck här:
%s

För att visa %ss profil, tryck här:
%s",

/**
 * Entities
 */

	'byline' => 'Av %s',
	'byline:ingroup' => 'i gruppen %s',
	'entity:default:missingsupport:popup' => 'Den här enheten kan inte visas korrekt. Det kan bero på att den kräver stöd som erhålles av ett tillägg, som inte längre är installerat.',

	'entity:delete:item' => 'Objekt',
	'entity:delete:item_not_found' => 'Objektet hittades inte.',
	'entity:delete:permission_denied' => 'Du har inte behörighet att ta bort det här objektet.',
	'entity:delete:success' => '%s har tagits bort.',
	'entity:delete:fail' => '%s kunde inte tas bort.',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Annotations
 */
	
	'annotation:delete:fail' => "Ett fel uppstod när anteckningen togs bort.",
	'annotation:delete:success' => "Anteckningen togs bort",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Formuläret saknar fält för __token eller __ts',
	'actiongatekeeper:tokeninvalid' => "Sidan du använde har gått ut. Vänligen försök igen.",
	'actiongatekeeper:timeerror' => 'Sidan du använde har gått ut. Vänligen uppdatera och försök igen.',
	'actiongatekeeper:pluginprevents' => 'Tyvärr, kunde ditt formulär inte lämnas in av en okänd anledning.',
	'actiongatekeeper:uploadexceeded' => 'Storleken på filen (filerna) som laddades upp, översteg gränsen som ställts in av din webbplats administratör',
	'actiongatekeeper:crosssitelogin' => "Tyvärr, är det inte tillåtet att logga in från en annan domän. Vänligen försök igen.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Taggar',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Det misslyckades att kontakta %s. Du kan uppleva problem med att spara innehåll. Vänligen uppdatera den här sidan.',
	'js:security:token_refreshed' => 'Anslutning till %s återställd!',
	'js:lightbox:current' => "bild %s av %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Drivs av Elgg",
	
/**
 * Cli commands
 */
	'cli:login:error:unknown' => "Det går inte logga in som %s",
	'cli:login:success:log' => "Inloggad som %s [guid: %s]",
	'cli:response:output' => "Svar:",
	'cli:option:as' => "Execute the command on behalf of a user with the given username",
	'cli:option:language' => "Execute the command in the given language (eg. en, nl or de)",
	
	'cli:cache:clear:description' => "Clear Elgg caches",
	'cli:cache:invalidate:description' => "Invalidate Elgg caches",
	'cli:cache:purge:description' => "Purge Elgg caches",
	
	'cli:cron:description' => "Execute cron handlers for all or specified interval",
	'cli:cron:option:interval' => "Name of the interval (e.g. hourly)",
	'cli:cron:option:force' => "Force cron commands to run even if they are not yet due",
	'cli:cron:option:time' => "Time of the cron initialization",
	
	'cli:database:seed:description' => "Seeds the database with fake entities",
	'cli:database:seed:option:limit' => "Number of entities to seed",
	'cli:database:seed:option:image_folder' => "Path to a local folder containing images for seeding",
	'cli:database:seed:log:error:faker' => "This is a developer tool currently intended for testing purposes only. Please refrain from using it.",
	'cli:database:seed:log:error:logged_in' => "Database seeding should not be run with a logged in user",
	
	'cli:database:unseed:description' => "Removes seeded fake entities from the database",
	
	'cli:plugins:activate:description' => "Activate plugin(s)",
	'cli:plugins:activate:option:force' => "Resolve conflicts by deactivating conflicting plugins and enabling required ones",
	'cli:plugins:activate:argument:plugins' => "Plugin IDs to be activated",
	'cli:plugins:activate:progress:start' => "Activating plugins",
	
	'cli:plugins:deactivate:description' => "Deactivate plugin(s)",
	'cli:plugins:deactivate:option:force' => "Force deactivation of all dependent plugins",
	'cli:plugins:deactivate:argument:plugins' => "Plugin IDs to be deactivated",
	'cli:plugins:deactivate:progress:start' => "Deactivating plugins",
	
	'cli:plugins:list:description' => "List all plugins installed on the site",
	'cli:plugins:list:option:status' => "Plugin status ( %s )",
	'cli:plugins:list:option:refresh' => "Refresh plugin list with recently installed plugins",
	'cli:plugins:list:error:status' => "%s är inte en giltig status. Tillåtna alternativ är: %s",
	
	'cli:simpletest:description' => "Run simpletest test suite (deprecated)",
	'cli:simpletest:option:config' => "Path to settings file that the Elgg Application should be bootstrapped with",
	'cli:simpletest:option:plugins' => "A list of plugins to enable for testing or 'all' to enable all plugins",
	'cli:simpletest:option:filter' => "Only run tests that match filter pattern",
	'cli:simpletest:error:class' => "You must install your Elgg application using '%s'",
	'cli:simpletest:error:file' => "%s is not a valid simpletest class",
	'cli:simpletest:output:summary' => "Time: %.2f seconds, Memory: %.2fMb",
	
	'cli:upgrade:batch:description' => "Executes one or more upgrades",
	'cli:upgrade:batch:argument:upgrades' => "One or more upgrades (class names) to be executed",
	'cli:upgrade:batch:option:force' => "Run upgrade even if it has been completed before",
	'cli:upgrade:batch:finished' => "Running upgrades finished",
	'cli:upgrade:batch:notfound' => "No upgrade class found for %s",

	'cli:upgrade:list:description' => "Lists all upgrades in the system",
	'cli:upgrade:list:completed' => "Färdiga uppgraderingar",
	'cli:upgrade:list:pending' => "Väntande uppgraderingar",
	'cli:upgrade:list:notfound' => "No upgrades found",
	
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
	"da" => "Danska",
	"de" => "Tyska",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "Engelska",
	"eo" => "Esperanto",
	"es" => "Spanska",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finska",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "Franska",
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
	"it" => "Italienska",
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
	"no" => "Norska",
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
	"ru" => "Ryska",
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
	"sv" => "Svenska",
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

	"field:required" => 'Krävs',

	"core:upgrade:2017080900:title" => "Alter database encoding for multi-byte support",
	"core:upgrade:2017080900:description" => "Alters database and table encoding to utf8mb4, in order to support multi-byte characters such as emoji",

	"core:upgrade:2017080950:title" => "Update default security parameters",
	"core:upgrade:2017080950:description" => "Installed Elgg version introduces additional security parameters. It is recommended that your run this upgrade to configure the defaults. You can later update these parameters in your site settings.",

	"core:upgrade:2017121200:title" => "Create friends access collections",
	"core:upgrade:2017121200:description" => "Migrates the friends access collection to an actual access collection",

	"core:upgrade:2018041800:title" => "Activate new plugins",
	"core:upgrade:2018041800:description" => "Certain core features have been extracted into plugins. This upgrade activates these plugins to maintain compatibility with third-party plugins that maybe dependant on these features",

	"core:upgrade:2018041801:title" => "Delete old plugin entities",
	"core:upgrade:2018041801:description" => "Deletes entities associated with plugins removed in Elgg 3.0",
	
	"core:upgrade:2018061401:title" => "Migrate cron log entries",
	"core:upgrade:2018061401:description" => "Migrate the cron log entries in the database to the new location.",
	
	"core:upgrade:2019071901:title" => "Update default security parameter: Email change confirmation",
	"core:upgrade:2019071901:description" => "Installed Elgg version introduces additional security parameters. It is recommended that your run this upgrade to configure the default. You can later update this parameter in the site security settings.",
);
