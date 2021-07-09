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
	'ElggPlugin:MissingID' => 'ID för tillägg saknas (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Filen "%s" som krävs saknas.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Manifestet innehåller en ogiltig beroendetyp "%s".',

	'RegistrationException:EmptyPassword' => 'Lösenordsfältet kan inte vara tomt',
	'RegistrationException:PasswordMismatch' => 'Lösenord måste matcha',
	'LoginException:BannedUser' => 'Du har blivit avstängd från den här webbplatsen och kan inte logga in',
	'LoginException:UsernameFailure' => 'Vi kunde inte logga in dig. Vänligen kontrollera ditt användarnamn/e-post och lösenord.',
	'LoginException:PasswordFailure' => 'Vi kunde inte logga in dig. Vänligen kontrollera ditt användarnamn/e-post och lösenord.',
	'LoginException:AccountLocked' => 'Ditt konto har blivit låst efter för många misslyckade inloggningsförsök.',
	'LoginException:ChangePasswordFailure' => 'Misslyckades med nuvarande lösenordskontroll.',
	'LoginException:Unknown' => 'Vi kunde inte logga in dig på grund av ett okänt fel.',
	'LoginException:AdminValidationPending' => "Ditt konto måste godkännas av en administratör på den här webbplatsen, innan du kan använda det. Du kommer att få ett meddelande när ditt konto har validerats.",

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
	'river:subject:invalid_subject' => 'Ogiltig användare',
	'activity:owner' => 'Aktivitet',

/**
 * Relationships
 */
	
	'relationship:default' => "%s relaterar till %s",

/**
 * Notifications
 */
	'notification:method:email' => 'E-post',
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
	'admin:performance:php:open_basedir:not_configured' => 'Inga begränsningar har ställts in',
	
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
	'admin:upgrades:db:start_time' => 'Starttid',
	'admin:upgrades:db:end_time' => 'Sluttid',
	'admin:upgrades:menu:pending' => 'Väntande uppgraderingar',
	'admin:upgrades:menu:completed' => 'Färdiga uppgraderingar',

	'admin:settings' => 'Inställningar',
	'admin:settings:basic' => 'Allmänna Inställningar',
	'admin:settings:advanced' => 'Avancerade Inställningar',
	'admin:settings:users' => 'Användare',
	'admin:site_settings' => "Webbplatsinställningar",
	'site_secret:strength:weak' => "Svagt",
	'site_secret:strength:moderate' => "Moderera",
	'site_secret:strength:strong' => "Starkt",

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
	'admin:statistics:numentities' => 'Innehållsstatistik',
	'admin:statistics:numentities:type' => 'Innehållstyp',
	'admin:statistics:numentities:number' => 'Siffra',

	'admin:widget:admin_welcome' => 'Välkommen',
	'admin:widget:admin_welcome:help' => "En kort introduktion till Elggs Adminstration",
	'admin:widget:admin_welcome:intro' =>
'',
	'admin:widget:admin_welcome:admin_overview' =>
"",

	// argh, this is ugly

	'admin:widget:control_panel' => 'Kontrollpanelen',

	'admin:footer:faq' => 'Administrationens FAQ',
	'admin:footer:manual' => 'Administrationens Manual',
	'admin:footer:community_forums' => 'Elgg Community Forum',
	'admin:footer:blog' => 'Elgg Blogg',

	'admin:plugins:category:all' => 'Alla tillägg',
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

	'admin:security' => 'Säkerhet',
	'admin:security:information' => 'Information',
	'admin:security:information:password_length' => 'Minsta lösenordslängd',
	'admin:security:information:password_length:warning' => "Det rekommenderas att ha minst 6 tecken i ett lösenord.",
	'admin:security:information:username_length' => 'Minsta längden på användarnamn',
	'admin:security:information:username_length:warning' => "Det rekommenderas att ha minst 4 tecken i ett användarnamn.",
	
	'admin:security:settings' => 'Inställningar',
	'admin:security:settings:description' => 'På den här sidan kan du konfigurera några säkerhetsfunktioner. Vänligen läs om inställningarna försiktigt.',
	'admin:security:settings:label:account' => 'Konto',
	'admin:security:settings:label:notifications' => 'Aviseringar',
	
	'admin:security:settings:notify_admins' => 'Meddela alla administratörer på webbplatsen när en admin läggs till eller tas bort',
	
	'admin:security:settings:email_require_password' => 'Kräv lösenord för att byta e-postadress',
	
	'admin:security:settings:email_require_confirmation' => 'Kräver bekräftelse vid ändring av e-postadress',
	'admin:security:settings:email_require_confirmation:help' => 'Den nya e-postadressen behöver bekräftas innan ändringen kan göras. Efter en lyckad ändring, kommer en avisering att skickas till den gamla e-postadressen.',
	
	'admin:security:settings:minusername' => "Minsta längden på användarnamn",
	'admin:security:settings:minusername:help' => "Minsta antalet tecken som krävs i ett användarnamn",
	
	'admin:security:settings:min_password_length' => "Minsta lösenordslängd",
	'admin:security:settings:min_password_length:help' => "Minsta antalet tecken som krävs i ett lösenord",
	
	'admin:security:settings:min_password_lower' => "Minsta antal små bokstäver i ett lösenord",
	
	'admin:security:settings:min_password_upper' => "Minsta antal stora bokstäver i ett lösenord",
	
	'admin:security:settings:min_password_number' => "Minsta antal siffror i ett lösenord",
	
	'admin:security:settings:min_password_special' => "Minsta antal specialtecken i ett lösenord",
	'user:notification:ban:subject' => 'Ditt konto på %s har blivit avstängt',
	
	'user:notification:unban:subject' => 'Ditt konto på %s är inte avstängt längre',
	
	'user:notification:password_change:subject' => 'Ditt lösenord har ändrats!',

/**
 * Plugins
 */
	
	'item:object:plugin' => 'Tillägg',
	'collection:object:plugin' => 'Tillägg',
	
	'plugins:settings:remove:menu:text' => "Radera alla inställningar",

	'admin:plugins' => "Tillägg",
	'admin:plugins:activate_all' => 'Aktivera Alla',
	'admin:plugins:deactivate_all' => 'Avaktivera Alla',
	'admin:plugins:activate' => 'Aktivera',
	'admin:plugins:deactivate' => 'Avaktivera',
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Namn",
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
	'admin:plugins:label:dependencies' => 'Beroenden',
	'admin:plugins:cannot_activate' => 'kan inte aktivera',
	'admin:plugins:cannot_deactivate' => 'kan inte avaktivera',

	'admin:plugins:set_priority:yes' => "Omordnade %s.",
	'admin:plugins:set_priority:no' => "Kunde inte omordna %s.",
	'admin:plugins:deactivate:yes' => "Avaktiverade %s.",
	'admin:plugins:deactivate:no' => "Kunde inte avaktivera %s.",
	'admin:plugins:deactivate:no_with_msg' => "Kunde inte avaktivera %s. Fel: %s",
	'admin:plugins:activate:yes' => "Aktiverade %s.",
	'admin:plugins:activate:no' => "Kunde inte aktivera %s.",
	'admin:plugins:activate:no_with_msg' => "Kunde inte aktivera %s. Fel: %s",
	'admin:plugins:categories:all' => 'Alla kategorier',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:statistics:opt:description' => "Visa statistisk information om användare och objekt på din webbplats.",
	'admin:statistics:label:numusers' => "Antalet användare",
	'admin:statistics:label:numonline' => "Antalet användare online",
	'admin:statistics:label:onlineusers' => "Användare online nu",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg-version",
	'admin:statistics:label:version:release' => "Release",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Krav',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Visa PHPInfo',
	'admin:server:label:web_server' => 'Webbserver',
	'admin:server:label:server' => 'Server',
	'admin:server:label:php_version' => 'PHP-version',

	'admin:server:label:redis' => 'Redis',

	'admin:server:label:opcache' => 'OPcache',
	
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

	'admin:configure_utilities:default_widgets' => 'Standardwidgets',
	'admin:default_widgets:unknown_type' => 'Okänd widgettyp',

	'admin:maintenance_mode:default_message' => 'Den här webbplatsen ligger nere för underhåll',
	'admin:maintenance_mode:mode_label' => 'Underhållningsläge',
	'admin:maintenance_mode:indicator_menu_item' => 'Webbplatsen är i underhållsläge.',

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
	'next' => 'Nästa',
	'previous' => 'Föregående',
	
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
	'installation:sitepermissions' => "Standard åtkomstbehörigheter:",
	'installation:language' => "Standardspråk för din webbplats:",
	'installation:debug' => "Kontrollera hur mycket information som skrivs till serverns logg.",
	'installation:debug:label' => "Logg-nivå:",
	'installation:debug:none' => 'Stäng av loggning (rekommenderas)',
	'installation:debug:error' => 'Logga endast kritiska fel',

	// Walled Garden support
	'installation:adminvalidation:notification:direct' => 'Direkt',
	'installation:default_limit' => "Standard antal objekt per sida",

	'admin:site:access:warning' => "Det här är integritetsinställningarna som föreslås för användare när de skapar nytt innehåll. Ändring av det, ändrar inte åtkomst till innehåll. ",

	'admin:legend:system' => 'System',
	'admin:legend:content' => 'Innehåll',
	
	'config:i18n:allowed_languages' => "Tillåtna språk",
	'config:disable_rss:label' => "Avaktivera RSS-flöden",

	'upgrading' => 'Uppgraderar...',
	'upgrade:error_count' => 'Fel: %s',
	
	// Strings specific for the database guid columns reply upgrade
	
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

	'email:resetpassword:subject' => "Återställning av lösenord!",

	'email:changereq:subject' => "Begär ändring av lösenord.",
	
	'account:email:request:success' => "Din nya e-postadress kommer att sparas efter bekräftelse, vänligen kontrollera inkorgen på '%s' för mer instruktioner.",
	'email:request:email:subject' => "Vänligen bekräfta din e-postadress",
	
	'email:confirm:email:old:subject' => "Din e-postadress ändrades",
	
	'email:confirm:email:new:subject' => "Din e-postadress ändrades",
	'account:validation:pending:content' => "Ditt konto har registrerats! Innan du kan använda ditt konto, måste en administratör för webbplatsen validera ditt konto. Du kommer att få ett mejl när ditt konto är validerat.",
	
	'account:notification:validation:subject' => "Ditt konto på %s har validerats!",

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
	
	'generic_comment:notification:user:summary' => 'En ny kommentar på: %s',

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

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Det misslyckades att kontakta %s. Du kan uppleva problem med att spara innehåll. Vänligen uppdatera den här sidan.',
	'js:security:token_refreshed' => 'Anslutning till %s återställd!',
	'js:lightbox:current' => "bild %s av %s",

/**
 * Diagnostics
 */
	
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
	'cli:plugins:list:error:status' => "%s är inte en giltig status. Tillåtna alternativ är: %s",
	'cli:upgrade:list:completed' => "Färdiga uppgraderingar",
	'cli:upgrade:list:pending' => "Väntande uppgraderingar",
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */
	"cmn" => "", // ISO 639-3
	"da" => "Danska",
	"de" => "Tyska",
	"en" => "Engelska",
	"es" => "Spanska",
	"fi" => "Finska",
	"fr" => "Franska",
	//"in" => "",
	"it" => "Italienska",
	"no" => "Norska",
	"ru" => "Ryska",
	"sv" => "Svenska",
	//"y" => "",

	"field:required" => 'Krävs',
);
