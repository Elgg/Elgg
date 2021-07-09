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

	'item:site:site' => 'Ilmoitukset',
	'collection:site:site' => 'Sivustot',
	'index:content' => '<p>Welcome to your Elgg site.</p><p><strong>Tip:</strong> Many sites use the <code>activity</code> plugin to place a site activity stream on this page.</p>',

/**
 * Sessions
 */

	'login' => "Kirjaudu",
	'loginok' => "Olet kirjautunut sisään.",
	'loginerror' => "Kirjautuminen epäonnistui. Tarkista kirjautumistietosi ja yritä uudelleen.",
	'login:empty' => "Syötä käyttäjätunnus/sähköposti ja salasana.",
	'login:baduser' => "Tiliisi kirjautuminen on estetty.",
	'auth:nopams' => "Sisäinen virhe. Toimintoa käyttäjän sisäänkirjaamiseen ei ole asennettuna.",

	'logout' => "Kirjaudu ulos",
	'logoutok' => "Olet kirjautunut ulos.",
	'logouterror' => "Uloskirjautuminen epäonnistui. Yritä uudelleen.",
	'session_expired' => "Sessiosi on vanhentunut. <a href='javascript:location.reload(true)'>Lataa sivu uudelleen</a> kirjautuaksesi sisään.",
	'session_changed_user' => "Olet kirjautunut toisena käyttäjänä, joten sinun täytyy <a href='javascript:location.reload(true)'>ladata sivu uudelleen</a>.",

	'loggedinrequired' => "Tämän sivun näkyminen edellyttää, että olet kirjautuneena sisään.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "Tämän sivun näkyminen edellyttää ylläpitäjän oikeuksia.",
	'membershiprequired' => "Sinun pitää olla ryhmän jäsen nähdäksesi tämän sivun.",
	'limited_access' => "Sinulla ei ole oikeuksia tämän sivun tarkasteluun.",
	'invalid_request_signature' => "Osoite, johon koitat päästä käsiksi, on virheellinen tai vanhentunut.",

/**
 * Errors
 */

	'exception:title' => "Virhe.",
	'exception:contact_admin' => 'Tapahtui virhe. Ota yhteys sivuston ylläpitäjään ja toimita seuraavat tiedot:',

	'actionundefined' => "Haluttua toimintoa (%s) ei ole määritelty järjestelmässä.",
	'actionnotfound' => "Tiedostoa, johon toiminto %s viittaa, ei löytynyt.",
	'actionloggedout' => "Tämän toiminnon suorittaminen edellyttää, että olet kirjautuneena sisään.",
	'actionunauthorized' => 'Sinulla ei ole oikeuksia tämän toiminnon suorittamiseen',

	'ajax:error' => 'AJAX-kutsun yhteydessä tapahtui odottamaton virhe. Yhteys palvelimeen saattaa olla katkennut.',
	'ajax:not_is_xhr' => 'AJAX-näkymiin ei ole mahdollista päästä käsiksi suoraan',

	'PluginException:MisconfiguredPlugin' => "Liitännäisessä %s (guid %s) on havaittu virhe, joten se on poistettu käytöstä. Voit yrittää etsiä lisätietoja Elggin dokumentaatiosta (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) ei käynnisty. Syy: %s',
	'PluginException:InvalidID' => "%s on virheellinen pluginin ID.",
	'PluginException:InvalidPath' => "%s on virheellinen pluginipolku.",
	'PluginException:InvalidManifest' => 'Pluginin %s infotiedosto (manifest) on virheellinen',
	'PluginException:InvalidPlugin' => '%s on virheellinen plugini.',
	'PluginException:InvalidPlugin:Details' => '%s ei ole kelvollinen plugini: %s',
	'PluginException:NullInstantiated' => 'ElggPluginista ei voi luoda ilmentymää null-arvolla. Syötä GUID, plugin id tai polku.',
	'ElggPlugin:MissingID' => 'Plugin-ID puuttuu (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'ElggPluginPackage pluginille %s (guid %s) puuttuu',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Tiedosto %s puuttuu.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Tämän pluginin hakemisto täytyy nimetä muotoon "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Manifestissa on virheellinen riippuvuustyyppi "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Manifestissa on virheellinen "tarjoaa" tyyppi "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Virheellinen %s riippuvuus "%s" pluginissa %s. Pluginit eivät voi vaatia toimintoa tai olla konfliktissa toiminnon kanssa, jonka ne itse tarjoavat!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Konfliktissa pluginin %s kanssa.',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Tiedosto "elgg-plugin.php" löytyi, mutta sitä ei pystytä lukemaan,',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Error:ID' => 'Error in plugin "%s"',
	'ElggPlugin:Error:Path' => 'Error in plugin path "%s"',
	'ElggPlugin:Error:Unknown' => 'Undefined plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Ei voida lisätä toiminnallisuutta %s pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Ei voida avata näkymähakemistoa pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:NoID' => 'Ei löydetty ID:tä pluginille guid %s!',
	'ElggPlugin:Exception:InvalidPackage' => 'Package cannot be loaded',
	'ElggPlugin:Exception:InvalidManifest' => 'Plugin manifest is missing or invalid',
	'PluginException:NoPluginName' => "Liitännäisen nimeä ei löytynyt",
	'PluginException:ParserError' => 'Virhe yrittäessä käsitellä infotiedostoa API-versiolla %s pluginissa %s.',
	'PluginException:NoAvailableParser' => 'Ei löydetty käsittelijää infotiedoston API-versiolle %s pluginissa %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Pakollinen '%s' attribuutti puuttuu pluginin %s infotiedostosta.",
	'ElggPlugin:InvalidAndDeactivated' => '%s on virheellinen liitännäinen, joten se poistettiin käytöstä.',
	'ElggPlugin:activate:BadConfigFormat' => 'Tiedoston "elgg-plugin.php" sisältö on virheellinen.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Luettiin konfiguraatio tiedostosta "elgg-plugin.php".',

	'ElggPlugin:Dependencies:Requires' => 'Vaatimus',
	'ElggPlugin:Dependencies:Suggests' => 'Suositus',
	'ElggPlugin:Dependencies:Conflicts' => 'Yhteensopimattomuus',
	'ElggPlugin:Dependencies:Conflicted' => 'Yhteensopimattomuus',
	'ElggPlugin:Dependencies:Provides' => 'Tarjoaa',
	'ElggPlugin:Dependencies:Priority' => 'Prioriteetti',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg-versio',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP-versio',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP-laajennos: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini -asetus: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugini: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Liitännäisen %s jälkeen',
	'ElggPlugin:Dependencies:Priority:Before' => 'Ennen liitännäistä %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s ei ole asennettuna',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Puuttuu',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Käytössä on liitännäisiä, jotka ovat riippuvaisia liitännäisestä %s. Ennen kuin voit deaktivoida sen, sinun pitää deaktivoida seuraavat liitännäiset: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Valikkolinkiltä puuttuu parent',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Valikkolinkille [%s] määritetty parent [%s] puuttuu',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Valikkolinkki [%s] on rekisteröity kahdesti',

	'RegistrationException:EmptyPassword' => 'Salasanakenttä ei voi olla tyhjä',
	'RegistrationException:PasswordMismatch' => 'Salasanojen on täsmättävä',
	'LoginException:BannedUser' => 'Sinut on bannattu tällä sivustolla, etkä voi kirjautua sisään',
	'LoginException:UsernameFailure' => 'Kirjautuminen epäonnistui. Tarkista tunnus/sähköposti ja salasana.',
	'LoginException:PasswordFailure' => 'Kirjautuminen epäonnistui. Tarkista tunnus/sähköposti ja salasana.',
	'LoginException:AccountLocked' => 'Tilisi on lukittu liian monen epäonnistuneen kirjautumisyrityksen vuoksi.',
	'LoginException:ChangePasswordFailure' => 'Syöttämäsi salasana ei vastannut nykyistä salasanaasi.',
	'LoginException:Unknown' => 'Kirjautuminen epäonnistui tuntemattoman virheen takia.',
	'LoginException:AdminValidationPending' => "Your account needs to be validated by a site administrator before you can use it. You'll be notified when your account is validated.",
	'LoginException:DisabledUser' => "Your account has been disabled. You're not allowed to login.",

	'UserFetchFailureException' => 'Oikeuksien tarkistaminen käyttäjälle GUID [%s] epäonnistui, koska käyttäjää ei ole olemassa.',

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Virheellinen pyyntö',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "The provided password is doesn't meet the character requirements",
	'Security:InvalidPasswordLengthException' => "The provided password doesn't meet the minimal length requirement of %s characters",

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Varoitus: Sivun omistajaa %d ei pystytä näyttämään!',
	'viewfailure' => 'Näkymässä %s ilmeni sisäinen virhe.',
	'view:missing_param' => "Parametri '%s' puuttuu näkymästä %s",
	'changebookmark' => 'Kirjanmerkin polku on vanhentunut. Ole hyvä ja vaihda kirjanmerkkisi tälle sivulle',
	'noaccess' => 'Kohde on poistettu tai sinulla ei ole oikeuksia sen tarkastelemiseen.',
	'error:missing_data' => 'Pyynnössäsi oli puutteellisia tietoja',
	'save:fail' => 'Tallentaminen epäonnistui',
	'save:success' => 'Tiedot tallennettu',

	'forward:error' => 'Sorry. An error occurred while redirecting to you to another site.',

	'error:default:title' => 'Hups...',
	'error:default:content' => 'Hups... jotain meni pieleen.',
	'error:400:title' => 'Virheellinen pyyntö',
	'error:400:content' => 'Pyyntö on virheellinen tai puutteellinen',
	'error:403:title' => 'Pääsy kielletty',
	'error:403:content' => 'Sinulla ei ole oikeuksia nähdä pyydettyä sivua',
	'error:404:title' => 'Sivua ei löydy',
	'error:404:content' => 'Hakemaasi sivua ei löydy.',

	'upload:error:ini_size' => 'Tiedosto, jota yritit lisätä, on liian suuri.',
	'upload:error:form_size' => 'Tiedosto, jota yritit lisätä, on liian suuri.',
	'upload:error:partial' => 'Tiedoston lisääminen epäonnistui.',
	'upload:error:no_file' => 'Et valinnut tiedostoa.',
	'upload:error:no_tmp_dir' => 'Tiedoston lisääminen ei onnistu.',
	'upload:error:cant_write' => 'Tiedoston lisääminen ei onnistu.',
	'upload:error:extension' => 'Tiedoston lisääminen ei onnistu.',
	'upload:error:unknown' => 'Tiedoston lisääminen epäonnistui.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Ylläpitäjä',
	'table_columns:fromView:banned' => 'Bannattu',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Kuvaus',
	'table_columns:fromView:link' => 'Nimi/Otsikko',
	'table_columns:fromView:icon' => 'Kuvake',
	'table_columns:fromView:item' => 'Kohde',
	'table_columns:fromView:language' => 'Kieli',
	'table_columns:fromView:owner' => 'Omistaja',
	'table_columns:fromView:time_created' => 'Luomisaika',
	'table_columns:fromView:time_updated' => 'Päivitysaika',
	'table_columns:fromView:user' => 'Käyttäjä',

	'table_columns:fromProperty:description' => 'Kuvaus',
	'table_columns:fromProperty:email' => 'Sähköposti',
	'table_columns:fromProperty:name' => 'Nimi',
	'table_columns:fromProperty:type' => 'Tyyppi',
	'table_columns:fromProperty:username' => 'Käyttäjätunnus',

	'table_columns:fromMethod:getSubtype' => 'Alatyyppi',
	'table_columns:fromMethod:getDisplayName' => 'Nimi/Otsikko',
	'table_columns:fromMethod:getMimeType' => 'MIME-tyyppi',
	'table_columns:fromMethod:getSimpleType' => 'Tyyppi',

/**
 * User details
 */

	'name' => "Nimi",
	'email' => "Sähköpostiosoite",
	'username' => "Käyttäjätunnus",
	'loginusername' => "Tunnus tai sähköposti",
	'password' => "Salasana",
	'passwordagain' => "Salasana (uudelleen)",
	'admin_option' => "Tee tästä käyttäjästä ylläpitäjä?",
	'autogen_password_option' => "Luo salasana automaattisesti?",

/**
 * Access
 */

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Ystävät",
	'access' => "Pääsy",
	'access:overridenotice' => "Huom: Vain ryhmän jäsenillä on pääsy tähän ryhmään lisättyihin sisältöihin.",
	'access:limited:label' => "Rajoitettu",
	'access:help' => "Pääsytaso",
	'access:read' => "Lukuoikeus",
	'access:write' => "Kirjoitusoikeus",
	'access:admin_only' => "Vain ylläpitäjät",
	'access:missing_name' => "Tuntematon lukuoikeus",
	'access:comments:change' => "Tässä sisällössä on kommentteja. Ota huomioon, että vaihtaessasi sisällön pääsytasoa, myös kommenttien pääsytaso muuttuu.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Kojelauta",
	'dashboard:nowidgets' => "Kojelauta on henkilökohtainen työpöytäsi sivustolla. Klikkaa \"Lisää vimpaimia\" lisätäksesi kojelaudalle vimpaimia, joiden avulla voit seurata sivuston sisältöä ja toimintaa.",

	'widgets:add' => 'Vimpaimet',
	'widgets:add:description' => "Klikkaa alla olevia vimpaimia lisätäksesi ne sivulle.",
	'widgets:position:fixed' => '(Lukittu sijainti sivulla)',
	'widget:unavailable' => 'Olet jo lisännyt tämän vimpaimen',
	'widget:numbertodisplay' => 'Näytettävien kohteiden määrä',

	'widget:delete' => 'Poista %s',
	'widget:edit' => 'Muokkaa vimpainta',

	'widgets' => "Vimpaimet",
	'widget' => "Vimpain",
	'item:object:widget' => "Vimpaimet",
	'collection:object:widget' => 'Vimpaimet',
	'widgets:save:success' => "Vimpain tallennettu onnistuneesti.",
	'widgets:save:failure' => "Vimpaimen tallentaminen epäonnistui.",
	'widgets:add:success' => "Vimpain lisättiin onnituneesti.",
	'widgets:add:failure' => "Vimpaimen lisääminen epäonnistui.",
	'widgets:move:failure' => "Vimpaimen uuden sijainnin tallentaminen epäonnistui.",
	'widgets:remove:failure' => "Vimpaimen poistaminen epäonnistui.",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Ryhmä",
	'item:group' => "Ryhmät",
	'collection:group' => 'Ryhmät',
	'item:group:group' => "Ryhmät",
	'collection:group:group' => 'Ryhmät',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "Käyttäjä",
	'item:user' => "Käyttäjät",
	'collection:user' => 'Käyttäjät',
	'item:user:user' => 'Käyttäjät',
	'collection:user:user' => 'Käyttäjät',

	'friends' => "Ystävät",
	'collection:friends' => 'Friends\' %s',

	'avatar' => 'Profiilikuva',
	'avatar:noaccess' => "Sinulla ei ole oikeuksia muokata tämän käyttäjän profiilikuvaa",
	'avatar:create' => 'Rajaa profiilikuva',
	'avatar:edit' => 'Muokkaa profiilikuvaa',
	'avatar:upload' => 'Vaihda profiilikuva',
	'avatar:current' => 'Nykyinen profiilikuva',
	'avatar:remove' => 'Poista profiilikuva',
	'avatar:crop:title' => 'Profiilikuvan rajaustyökalu',
	'avatar:upload:instructions' => "Profiilikuvasi on kuva, jota käytetään ympäri sivustoa. Voit vaihtaa sen niin usein kun haluat. (Sallitut tiedostomuodot ovat: GIF, JPG ja PNG)",
	'avatar:create:instructions' => 'Rajaa hiirellä neliö alapuolella olevaan kuvaan määritelläksesi kuvan rajauksen. Esikatselu rajatusta kuvasta näkyy oikealla. Kun olet tyytyväinen esikatselukuvaan, klikkaa "Rajaa profiilikuva"-painiketta, jolloin rajatusta alueesta luodaan uusi profiilikuvasi.',
	'avatar:upload:success' => 'Profiilikuva ladattiin onnistuneesti',
	'avatar:upload:fail' => 'Profiilikuvan lataaminen epäonnistui',
	'avatar:resize:fail' => 'Profiilikuvan koon muuttaminen epäonnistui',
	'avatar:crop:success' => 'Profiilikuva rajattiin onnistuneesti',
	'avatar:crop:fail' => 'Profiilikuvan rajaaminen epäonnistui',
	'avatar:remove:success' => 'Profiilikuva poistettu',
	'avatar:remove:fail' => 'Profiilikuvan poistaminen epäonnistui.',
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

/**
 * Feeds
 */
	'feed:rss' => 'Tilaa sivu syötteenä',
	'feed:rss:title' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'Näytä linkki',
	'link:view:all' => 'Näytä kaikki',


/**
 * River
 */
	'river' => "Toimintalista",
	'river:user:friend' => "%s is now a friend with %s",
	'river:update:user:avatar' => 'Käyttäjä %s päivitti profiilikuvansa',
	'river:noaccess' => 'Sinulla ei ole oikeuksia tämän kohteen näkemiseen.',
	'river:posted:generic' => '%s lähetti',
	'riveritem:single:user' => 'a user',
	'riveritem:plural:user' => 'some users',
	'river:ingroup' => 'ryhmässä %s',
	'river:none' => 'Ei uutta toimintaa',
	'river:update' => 'Päivitys kohteelle %s',
	'river:delete' => 'Poista tämä kohde listasta',
	'river:delete:success' => 'Kohde poistettu',
	'river:delete:fail' => 'Kohteen poistaminen epäonnistui',
	'river:delete:lack_permission' => 'Sinulla ei ole oikeuksia poistaa tätä kohdetta',
	'river:can_delete:invaliduser' => 'Ei pystytty tarkistamaan oikeuksia poistamiseen, koska käyttäjää ID:llä [%s] ei löytynyt.',
	'river:subject:invalid_subject' => 'Virheellinen käyttäjä',
	'activity:owner' => 'Näytä toiminta',

/**
 * Relationships
 */
	
	'relationship:default' => "%s relates to %s",

/**
 * Notifications
 */
	'notifications:usersettings' => "Ilmoitusasetukset",
	'notification:method:email' => 'Sähköposti',

	'notifications:usersettings:save:ok' => "Ilmoitusasetukset tallennettu.",
	'notifications:usersettings:save:fail' => "Ilmoitusasetusten tallentaminen epäonnistui.",

	'notification:subject' => 'Ilmoitus kohteesta %s',
	'notification:body' => 'Pääset tarkastelemaan kohdetta tästä: %s',

/**
 * Search
 */

	'search' => "Haku",
	'searchtitle' => "Haku: %s",
	'users:searchtitle' => "Etsitään käyttäjiä: %s",
	'groups:searchtitle' => "Etsitään ryhmiä: %s",
	'advancedsearchtitle' => "%s jotka täsmäävät hakuun %s",
	'notfound' => "Ei hakutuloksia.",
	'next' => "Seuraava",
	'previous' => "Edellinen",

	'viewtype:change' => "Vaihda listatyyppi",
	'viewtype:list' => "Listanäkymä",
	'viewtype:gallery' => "Gallerianäkymä",

	'tag:search:startblurb' => "Kohteet, joiden täsmäävät tagiin '%s':",

	'user:search:startblurb' => "Käyttäjät, jotka täsmäävät hakuun '%s':",
	'user:search:finishblurb' => "Näytä lisää hakutuloksia.",

	'group:search:startblurb' => "Ryhmät, jotka täsmäävät hakuun '%s':",
	'group:search:finishblurb' => "Näytä lisää hakutuloksia.",
	'search:go' => 'Etsi',
	'userpicker:only_friends' => 'Näytä vain ystävät',

/**
 * Account
 */

	'account' => "Tili",
	'settings' => "Asetukset",
	'tools' => "Työkalut",
	'settings:edit' => 'Muokkaa asetuksia',

	'register' => "Rekisteröidy",
	'registerok' => "Olet onnistuneesti rekisteröitynyt sivustolle %s.",
	'registerbad' => "Rekisteröitymisesi epäonnistui tuntemattoman virheen takia.",
	'registerdisabled' => "Sivuston ylläpitäjä on ottanut rekisteröitymisen pois käytöstä",
	'register:fields' => 'Kaikki kentät ovat pakollisia',

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'Syötit virheellisen sähköpostiosoitteen.',
	'registration:userexists' => 'Syöttämäsi käyttäjätunnus on jo olemassa',
	'registration:usernametooshort' => 'Käyttäjätunnuksen tulee olla vähintään %u merkin pituinen.',
	'registration:usernametoolong' => 'Käyttäjätunnuksesi on liian pitkä. Siinä voi olla korkeintaan %u merkkiä.',
	'registration:passwordtooshort' => 'Salasanan tulee olla vähintään %u merkin pituinen.',
	'registration:dupeemail' => 'Tämä sähköpostiosoite on jo rekisteröity.',
	'registration:invalidchars' => 'Käyttätunnuksessai on seuraavia kiellettyvä merkkejä: %s. Tunnuksessa ei voi olla mitään näistä merkeistä: %s',
	'registration:emailnotvalid' => 'Syöttämäsi sähköpostiosoite ei ole kelvollinen',
	'registration:passwordnotvalid' => 'Syöttämäsi salasana ei ole kelvollinen',
	'registration:usernamenotvalid' => 'Syöttämäsi käyttäjätunnus ei ole kelvollinen',

	'adduser' => "Lisää käyttäjä",
	'adduser:ok' => "Olet lisännyt uuden käyttäjän.",
	'adduser:bad' => "Uuden käyttäjän luonti epäonnistui.",

	'user:set:name' => "Tilin nimiasetukset",
	'user:name:label' => "Nimi",
	'user:name:success' => "Nimi vaihdettu.",
	'user:name:fail' => "Nimen vaihtaminen epäonnistui.",
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

	'user:set:password' => "Tilin salasana",
	'user:current_password:label' => 'Nykyinen salasana',
	'user:password:label' => "Uusi salasana",
	'user:password2:label' => "Uusi salasana uudelleen",
	'user:password:success' => "Salasana vaihdettu",
	'user:password:fail' => "Salasanan vaihtaminen epäonnistui.",
	'user:password:fail:notsame' => "Salasanat eivät täsmää!",
	'user:password:fail:tooshort' => "Salasana on liian lyhyt!",
	'user:password:fail:incorrect_current_password' => 'Salasana ei vastannut nykyistä salsanaasi.',
	'user:changepassword:unknown_user' => 'Virheellinen käyttäjä.',
	'user:changepassword:change_password_confirm' => 'Syötä uusi salasana.',

	'user:set:language' => "Kieliasetukset",
	'user:language:label' => "Kieli",
	'user:language:success' => "Kieliasetus päivitetty.",
	'user:language:fail' => "Kieliasetuksen tallentaminen epäonnistui.",

	'user:username:notfound' => 'Käyttäjätunnusta %s ei löytynyt.',
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'Unohtunut salasana',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => 'Pyydettiin uutta salasanaa, sähköposti lähetetty',
	'user:password:changereq:fail' => 'Uuden salasanan pyytäminen epäonnistui.',

	'user:password:text' => 'Syötä käyttäjätunnuksesi tai sähköpostiosoitteesi. Saat sähköpostiisi linkin, jonka kautta pääset syöttämään uuden salasanan.',

	'user:persistent' => 'Muista minut',

	'walled_garden:home' => 'Home',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "The password needs to be at least %s characters.",
	'password:requirements:lower' => "The password needs to have at least %s lower case characters.",
	'password:requirements:no_lower' => "The password shouldn't contain any lower case characters.",
	'password:requirements:upper' => "The password needs to have at least %s upper case characters.",
	'password:requirements:no_upper' => "The password shouldn't contain any upper case characters.",
	'password:requirements:number' => "The password needs to have at least %s number characters.",
	'password:requirements:no_number' => "The password shouldn't contain any number characters.",
	'password:requirements:special' => "The password needs to have at least %s special characters.",
	'password:requirements:no_special' => "The password shouldn't contain any special characters.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Hallinnointi',
	'menu:page:header:configure' => 'Asetukset',
	'menu:page:header:develop' => 'Kehittäjän työkalut',
	'menu:page:header:information' => 'Information',
	'menu:page:header:default' => 'Muut',

	'admin:view_site' => 'Siirry sivustolle',
	'admin:loggedin' => 'Olet kirjautuneena käyttäjänä %s',
	'admin:menu' => 'Valikko',

	'admin:configuration:success' => "Asetukset tallennettiin.",
	'admin:configuration:fail' => "Asetusten tallentaminen epäonnistui.",
	'admin:configuration:dataroot:relative_path' => 'Sijaintia "%s" ei voida asettaa datahakemistoksi, koska se ei ole absoluuttinen polku.',
	'admin:configuration:default_limit' => 'Kohteiden lukumäärän pitää olla vähintään 1',

	'admin:unknown_section' => 'Virheellinen admin-osio.',

	'admin' => "Hallinta",
	'admin:header:release' => "Elgg release: %s",
	'admin:description' => "Hallintapaneelilla voit vaikuttaa kaikkiin sivustoa koskeviin asetuksiin. Valitse vaihtoehto alta aloittaaksesi.",

	'admin:performance' => 'Performance',
	'admin:performance:label:generic' => 'Generic',
	'admin:performance:generic:description' => 'Below is a list of performance suggestions / values which could help in tuning your website',
	'admin:performance:simplecache' => 'Simplecache',
	'admin:performance:simplecache:settings:warning' => "It's recommended you configure the simplecache setting in the settings.php.
Configuring simplecache in the settings.php file improves caching performance.
It allows Elgg to skip connecting to the database when serving cached JavaScript and CSS files",
	'admin:performance:systemcache' => 'Systemcache',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'The mod_cache module provides HTTP-aware caching schemes. This means that the files will be cached according
to an instruction specifying how long a page can be considered "fresh".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'No limitations have been set',
	'admin:performance:php:open_basedir:warning' => 'A small amount of open_basedir limitations are in effect, this could impact performance.',
	'admin:performance:php:open_basedir:error' => 'A large amount of open_basedir limitations are in effect, this will probably impact performance.',
	'admin:performance:php:open_basedir:generic' => 'With open_basedir every file access will be checked against the list of limitations. Since Elgg has a lot of
file access this will negatively impact performance. Also PHPs opcache can no longer cache file paths in memory and has to resolve this upon every access.',
	
	'admin:statistics' => 'Tilastotiedot',
	'admin:server' => 'Palvelin',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Viimeisimmät Cron-ajot',
	'admin:cron:period' => 'Cron-aikaväli',
	'admin:cron:friendly' => 'Suoritettu viimeksi',
	'admin:cron:date' => 'Päivä ja aika',
	'admin:cron:msg' => 'Viesti',
	'admin:cron:started' => '%s ajettava Cron aloitettu %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
	'admin:cron:complete' => '%s ajettava Cron suoritettu %s',

	'admin:appearance' => 'Ulkoasu',
	'admin:administer_utilities' => 'Apuohjelmat',
	'admin:develop_utilities' => 'Apuohjelmat',
	'admin:configure_utilities' => 'Apuohjelmat',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Käyttäjät",
	'admin:users:online' => 'Tällä hetkellä kirjautuneena',
	'admin:users:newest' => 'Uusimmat',
	'admin:users:admins' => 'Ylläpitäjät',
	'admin:users:searchuser' => 'Search user to make them admin',
	'admin:users:existingadmins' => 'List of existing admins',
	'admin:users:add' => 'Lisää uusi käyttäjä',
	'admin:users:description' => "Tämän hallintapaneelin avulla voit vaikuttaa sivustosi käyttäjäasetuksiin. Valitse vaihtoehto alta aloittaaksesi.",
	'admin:users:adduser:label' => "Klikkaa tästä lisätäksesi uuden käyttäjän...",
	'admin:users:opt:linktext' => "Hallinnoi käyttäjiä...",
	'admin:users:opt:description' => "Säädä käyttäjä ja tiliasetuksia. ",
	'admin:users:find' => 'Etsi',
	'admin:users:unvalidated' => 'Vahvistamattomat käyttäjät',
	'admin:users:unvalidated:no_results' => 'Ei vahvistamattomia käyttäjiä.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	'admin:users:unvalidated:change_email' => 'Change e-mail address',
	'admin:users:unvalidated:change_email:user' => 'Change e-mail address for: %s',
	
	'admin:configure_utilities:maintenance' => 'Ylläpitotila',
	'admin:upgrades' => 'Päivitykset',
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
	'admin:upgrades:run' => 'Run upgrades now',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s does not exist or not a valid instance of ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner for the upgrade %s (%s) could not be instantiated',
	'admin:upgrades:completed' => 'Upgrade "%s" completed at %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" completed at %s but encountered %s errors',
	'admin:upgrades:failed' => 'Upgrade "%s" failed',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'Asetukset',
	'admin:settings:basic' => 'Perusasetukset',
	'admin:settings:i18n' => 'Internationalization',
	'admin:settings:advanced' => 'Lisäasetukset',
	'admin:settings:users' => 'Käyttäjät',
	'admin:site_settings' => "Site Settings",
	'admin:site:description' => "Tämän hallintapaneelin avulla voit vaikuttaa sivustosi yleisiin asetuksiin. Valitse vaihtoehto alta aloittaaksesi.",
	'admin:site:opt:linktext' => "Hallinnoi sivuston asetuksia...",
	'admin:settings:in_settings_file' => 'Tämä asetus on määritetty asetustiedostossa (settings.php)',

	'site_secret:current_strength' => 'Salausavaimen vahvuus',
	'site_secret:strength:weak' => "Heikko",
	'site_secret:strength_msg:weak' => "Suosittelemme, että uusit sivuston salausavaimen.",
	'site_secret:strength:moderate' => "Keskiverto",
	'site_secret:strength_msg:moderate' => "Suosittelemme, että uusit salausavaimen taataksesi sivuston tietoturvallisen käytön.",
	'site_secret:strength:strong' => "Vahva",
	'site_secret:strength_msg:strong' => "Salausavain on riittävän vahva. Sitä ei tarvitse uusia.",

	'admin:dashboard' => 'Kojelauta',
	'admin:widget:online_users' => 'Kirjautuneet käyttäjät',
	'admin:widget:online_users:help' => 'Listaa kirjautuneet käyttäjät',
	'admin:widget:new_users' => 'Uudet käyttäjät',
	'admin:widget:new_users:help' => 'Listaa uusimmat käyttäjät',
	'admin:widget:banned_users' => 'Bannatut käyttäjät',
	'admin:widget:banned_users:help' => 'Listaa bannatut käyttäjät',
	'admin:widget:content_stats' => 'Sisältötilastot',
	'admin:widget:content_stats:help' => 'Näyttää sivustolle luotujen sisältöjen määrän',
	'admin:widget:cron_status' => 'Cron-seuranta',
	'admin:widget:cron_status:help' => 'Näyttää listan Cron-ajojen viimeisimmistä ajankohdista',
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Tervetuloa',
	'admin:widget:admin_welcome:help' => "Lyhyt perehdytys Elggin hallintapaneeliin",
	'admin:widget:admin_welcome:intro' =>
'Tervetuloa Elggiin! Olet juuri nyt ylläpitäjän kojelaudalla, joka on kätevä työkalu sivuston toiminnan seuraamiseen.',

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
	'admin:widget:admin_welcome:outro' => '<br />Tutustu myös sivun alaosassa oleviin linkkeihin, ja kiitos kun käytät Elggiä!',

	'admin:widget:control_panel' => 'Hallintapaneeli',
	'admin:widget:control_panel:help' => "Tarjoaa helpon pääsyn yleisimpiin toimintoihin",

	'admin:cache:flush' => 'Tyhjennä välimuistit',
	'admin:cache:flushed' => "Välimuistit on tyhjennetty",
	'admin:cache:invalidate' => 'Invalidate the caches',
	'admin:cache:invalidated' => "The site's caches have been invalidated",
	'admin:cache:clear' => 'Clear the caches',
	'admin:cache:cleared' => "The site's caches have been cleared",
	'admin:cache:purge' => 'Purge the caches',
	'admin:cache:purged' => "The site's caches have been purged",

	'admin:footer:faq' => 'Hallinnan FAQ',
	'admin:footer:manual' => 'Hallinnan ohjekirja',
	'admin:footer:community_forums' => 'Elgg-yhteisön keskustelualue',
	'admin:footer:blog' => 'Elggin virallinen blogi',

	'admin:plugins:category:all' => 'Kaikki',
	'admin:plugins:category:active' => 'Aktiiviset',
	'admin:plugins:category:inactive' => 'Inaktiiviset',
	'admin:plugins:category:admin' => 'Ylläpito',
	'admin:plugins:category:bundled' => 'Oletuspakettiin kuuluvat',
	'admin:plugins:category:nonbundled' => 'Oletuspakettiin kuulumattomat',
	'admin:plugins:category:content' => 'Sisältö',
	'admin:plugins:category:development' => 'Kehitystyökalut',
	'admin:plugins:category:enhancement' => 'Parannukset',
	'admin:plugins:category:api' => 'API',
	'admin:plugins:category:communication' => 'Kommunikointi',
	'admin:plugins:category:security' => 'Tietoturva ja roskaposti',
	'admin:plugins:category:social' => 'Sosiaaliset',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Teemat',
	'admin:plugins:category:widget' => 'Vimpaimet',
	'admin:plugins:category:utility' => 'Apuohjelmat',

	'admin:plugins:markdown:unknown_plugin' => 'Tuntematon liitännäinen.',
	'admin:plugins:markdown:unknown_file' => 'Tuntematon tiedosto.',

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => 'Ilmoituksen poistaminen epäonnistui.',
	'item:object:admin_notice' => 'Ylläpidon ilmoitus',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Hallintavalikko',

	'admin:security' => 'Security',
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
	'admin:security:information:password_length' => 'Minimal password length',
	'admin:security:information:password_length:warning' => "It's recommended to have a minimal password length of at least 6 characters.",
	'admin:security:information:username_length' => 'Minimal username length',
	'admin:security:information:username_length:warning' => "It's recommended to have a minimal username length of at least 4 characters.",
	'admin:security:information:php:session_gc' => "PHP session cleanup",
	'admin:security:information:php:session_gc:chance' => "Cleanup chance: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Session lifetime %s seconds",
	'admin:security:information:php:session_gc:error' => "It's recommended to set 'session.gc_probability' and 'session.gc_divisor' in your PHP settings, this will cleanup
expired sessions from your database and not allow users to reuse old sessions.",
	'admin:security:information:htaccess:hardening' => ".htaccess file access hardening",
	'admin:security:information:htaccess:hardening:help' => "In the .htaccess file access to certain files can be blocked to increase security on your site. For more information look in your .htaccess file.",
	
	'admin:security:settings' => 'Asetukset',
	'admin:security:settings:description' => 'On this page you can configure some security features. Please read the settings carefully.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:account' => 'Tili',
	'admin:security:settings:label:notifications' => 'Ilmoitukset',
	'admin:security:settings:label:site_secret' => 'Site secret',
	
	'admin:security:settings:notify_admins' => 'Notify all site administrators when an admin is added or removed',
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
	
	'admin:security:settings:email_require_password' => 'Require password to change email address',
	'admin:security:settings:email_require_password:help' => 'When the user wishes to change their email address, require that they provide their current password.',
	
	'admin:security:settings:email_require_confirmation' => 'Require confirmation on email address change',
	'admin:security:settings:email_require_confirmation:help' => 'The new e-mail address needs to be confirmed before the change is in effect. After a successfull change a notification is send to the old e-mail address.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:security:settings:minusername' => "Minimal username length",
	'admin:security:settings:minusername:help' => "Minimal number of characters required in a username",
	
	'admin:security:settings:min_password_length' => "Minimal password length",
	'admin:security:settings:min_password_length:help' => "Minimal number of characters required in a password",
	
	'admin:security:settings:min_password_lower' => "Minimal number of lower case characters in a password",
	'admin:security:settings:min_password_lower:help' => "Configure the minimal number of lower case (a-z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_upper' => "Minimal number of upper case characters in a password",
	'admin:security:settings:min_password_upper:help' => "Configure the minimal number of upper case (A-Z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_number' => "Minimal number of number characters in a password",
	'admin:security:settings:min_password_number:help' => "Configure the minimal number of number (0-9) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_special' => "Minimal number of special characters in a password",
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
	'user:notification:ban:subject' => 'Your account on %s was banned',
	'user:notification:ban:body' => 'Hi %s,

Your account on %s was banned.

To go to the site, click here:
%s',
	
	'user:notification:unban:subject' => 'Your account on %s is no longer banned',
	'user:notification:unban:body' => 'Hi %s,

Your account on %s is no longer banned. You can use the site again.

To go to the site, click here:
%s',
	
	'user:notification:password_change:subject' => 'Your password has been changed!',
	'user:notification:password_change:body' => "Hi %s,

Your password on '%s' has been changed! If you made this change than you're all set.

If you didn't make this change, please reset your password here:
%s

Or contact a site administrator:
%s",
	
	'admin:notification:unvalidated_users:subject' => "Users awaiting approval on %s",
	'admin:notification:unvalidated_users:body' => "Hi %s,

%d users of '%s' are awaiting approval by an administrator.

See the full list of users here:
%s",

/**
 * Plugins
 */

	'plugins:disabled' => 'Liitännäiset ovat poissa käytöstä, sillä mod-hakemistossa on "disabled"-niminen tiedosto.',
	'plugins:settings:save:ok' => "Päivitettiin asetukset liitännäiselle %s.",
	'plugins:settings:save:fail' => "Asetusten päivittäminen liitännäiselle %s epäonnistui.",
	'plugins:settings:remove:ok' => "All settings for the %s plugin have been removed",
	'plugins:settings:remove:fail' => "An error occured while removing all settings for the plugin %s",
	'plugins:usersettings:save:ok' => "Päivitettiin käyttöasetukset liitännäiselle %s.",
	'plugins:usersettings:save:fail' => "Käyttöasetusten päivittäminen liitännäiselle %s epäonnistui.",
	
	'item:object:plugin' => 'Liitännäiset',
	'collection:object:plugin' => 'Liitännäiset',
	
	'plugins:settings:remove:menu:text' => "Remove all settings",
	'plugins:settings:remove:menu:confirm' => "Are you sure you wish to remove all settings, including user settings from this plugin?",

	'admin:plugins' => "Liitännäiset",
	'admin:plugins:activate_all' => 'Aktivoi kaikki',
	'admin:plugins:deactivate_all' => 'Deaktivoi kaikki',
	'admin:plugins:activate' => 'Aktivoi',
	'admin:plugins:deactivate' => 'Deaktivoi',
	'admin:plugins:description' => "Täällä voit määrittää sivustolla käytössä olevat työkalut sekä niiden asetukset.",
	'admin:plugins:opt:linktext' => "Konfiguroi työkalut...",
	'admin:plugins:opt:description' => "Konfiguroi järjestelmään asennetut työkalut. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nimi",
	'admin:plugins:label:author' => "Tekijä",
	'admin:plugins:label:copyright' => "Tekijänoikeus",
	'admin:plugins:label:categories' => 'Kategoriat',
	'admin:plugins:label:licence' => "Lisenssi",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Tiedostot",
	'admin:plugins:label:resources' => "Resurssit",
	'admin:plugins:label:screenshots' => "Kuvankaappaukset",
	'admin:plugins:label:repository' => "Lähdekoodi",
	'admin:plugins:label:bugtracker' => "Ilmoita virheestä",
	'admin:plugins:label:donate' => "Lahjoitukset",
	'admin:plugins:label:moreinfo' => 'lisätiedot',
	'admin:plugins:label:version' => 'Versio',
	'admin:plugins:label:location' => 'Sijainti',
	'admin:plugins:label:priority' => 'Tärkeysjärjestys',
	'admin:plugins:label:contributors' => 'Tekemiseen osallistuneet',
	'admin:plugins:label:contributors:name' => 'Nimi',
	'admin:plugins:label:contributors:email' => 'Sähköposti',
	'admin:plugins:label:contributors:website' => 'Nettisivu',
	'admin:plugins:label:contributors:username' => 'Yhteisön käyttäjätunnus',
	'admin:plugins:label:contributors:description' => 'Kuvaus',
	'admin:plugins:label:dependencies' => 'Riippuvuudet',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'Tällä liitännäisellä on puuttuvia riippuvuuksia, joten sitä ei voida aktivoida. Tarkista riippuvuudet lisätiedoista.',
	'admin:plugins:warning:invalid' => 'Pluginissa on virhe: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Voit yrittää etsiä apua <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elggin dokumentaatiosta</a>.',
	'admin:plugins:cannot_activate' => 'ei voi aktivoida',
	'admin:plugins:cannot_deactivate' => 'ei voida aktivoida',
	'admin:plugins:already:active' => 'Valitut liitännäiset ovat jo aktiivisia',
	'admin:plugins:already:inactive' => 'Valitut liitännäiset ovat jo deaktivoituja',

	'admin:plugins:set_priority:yes' => "Vaihdettiin liitännäisen \"%s\" prioriteettia.",
	'admin:plugins:set_priority:no' => "Liitännäisen \"%s\" uudelleenjärjestäminen epäonnistui.",
	'admin:plugins:set_priority:no_with_msg' => "Liitättäisen \"%s\" uudelleenjärjestäminen epäonnistui. Virheilmoitus: %s",
	'admin:plugins:deactivate:yes' => "Liitännäinen \"%s\" otettiin pois käytöstä.",
	'admin:plugins:deactivate:no' => "Liitännäisen \"%s\" deaktivointi epäonnistui.",
	'admin:plugins:deactivate:no_with_msg' => "Liitännäisen \"%s\" deaktivointi epäonnistui. Virheilmoitus: %s",
	'admin:plugins:activate:yes' => "Otettiin käyttöön liitännäinen \"%s\".",
	'admin:plugins:activate:no' => "Liitännäisen \"%s\" aktivoiminen epäonnistui.",
	'admin:plugins:activate:no_with_msg' => "Liitännäisen \"%s\" aktivoiminen epäonnistui. Virheilmoitus: %s",
	'admin:plugins:categories:all' => 'Kaikki kategoriat',
	'admin:plugins:plugin_website' => 'Liitännäisen kotisivu',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versio %s',
	'admin:plugin_settings' => 'Liitännäisen asetukset',
	'admin:plugins:warning:unmet_dependencies_active' => 'Tällä liitännäisellä on puuttuvia riippuvuuksia, mikä voi aiheuttaa ongelmia. Valitse "lisätiedot" nähdäksesi yksityiskohdat.',

	'admin:plugins:dependencies:type' => 'Tyyppi',
	'admin:plugins:dependencies:name' => 'Nimi',
	'admin:plugins:dependencies:expected_value' => 'Vähimmäisvaatimus',
	'admin:plugins:dependencies:local_value' => 'Sivustolla käytössä',
	'admin:plugins:dependencies:comment' => 'Tulos',

	'admin:statistics:description' => "Tämä näyttää tilastoja sivustostasi. Tarkempien tietojen selvittämiseen voit käyttää erillistä hallintatyökalua.",
	'admin:statistics:opt:description' => "Näyttää tilastoja sivustosi käyttäjistä ja sisällöistä.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Sivustolla olevat kohteet",
	'admin:statistics:label:numusers' => "Käyttäjien määrä",
	'admin:statistics:label:numonline' => "Tällä hetkellä kirjautuneena",
	'admin:statistics:label:onlineusers' => "Tällä hetkellä kirjautuneena",
	'admin:statistics:label:admins'=>"Ylläpitäjät",
	'admin:statistics:label:version' => "Elgg-versio",
	'admin:statistics:label:version:release' => "Julkaisu",
	'admin:statistics:label:version:version' => "Versio",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Requirements',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
	'admin:server:label:web_server' => 'Web-palvelin',
	'admin:server:label:server' => 'Palvelin',
	'admin:server:label:log_location' => 'Lokin sijainti',
	'admin:server:label:php_version' => 'PHP-versio',
	'admin:server:label:php_version:required' => 'Elgg requires a minimal PHP version of 7.1',
	'admin:server:label:php_ini' => 'PHP:n ini-tiedoton sijainti',
	'admin:server:label:php_log' => 'PHP:n loki',
	'admin:server:label:mem_avail' => 'Käytettävissä oleva muisti',
	'admin:server:label:mem_used' => 'Käytetty muisti',
	'admin:server:error_log' => "Web-palvelimen virheloki",
	'admin:server:label:post_max_size' => 'POST-datan maksimikoko',
	'admin:server:label:upload_max_filesize' => 'Palvelimelle lähetettävien tiedostojen maksimikoko',
	'admin:server:warning:post_max_too_small' => '(Huom: post_max_size pitää olla isompi kuin tämä.)',
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
	
	'admin:server:requirements:php_extension' => "PHP-laajennos: %s",
	'admin:server:requirements:php_extension:required' => "This PHP extension is required for the correct operation of Elgg",
	'admin:server:requirements:php_extension:recommended' => "This PHP extension is recommended for the optimal operation of Elgg",
	'admin:server:requirements:rewrite' => ".htaccess rewrite rules",
	'admin:server:requirements:rewrite:fail' => "Check your .htaccess for the correct rewrite rules",
	
	'admin:server:requirements:database:server' => "Database server",
	'admin:server:requirements:database:server:required' => "Elgg requires MySQL v5.5.3 or higher for its database",
	'admin:server:requirements:database:client' => "Database client",
	'admin:server:requirements:database:client:required' => "Elgg requires pdo_mysql to connect to the database server",
	
	'admin:user:label:search' => "Find users:",
	'admin:user:label:searchbutton' => "Search",

	'admin:user:ban:no' => "Käyttäjän bannaaminen epäonnistui",
	'admin:user:ban:yes' => "Käyttäjä bannattu.",
	'admin:user:self:ban:no' => "Et voi bannata itseäsi",
	'admin:user:unban:no' => "Bannin peruminen epäonnistui",
	'admin:user:unban:yes' => "Peruttiin bannaus.",
	'admin:user:delete:no' => "Käyttäjää ei voida poistaa",
	'admin:user:delete:yes' => "Käyttäjä %s poistettiin",
	'admin:user:self:delete:no' => "Et voi poistaa omaa tilisäsi",

	'admin:user:resetpassword:yes' => "Salasana nollattu, ja käyttäjälle lähetetty ilmoitus.",
	'admin:user:resetpassword:no' => "Salasanan nollaaminen epäonnistui.",

	'admin:user:makeadmin:yes' => "Käyttäjällä on nyt ylläpito-oikeudet.",
	'admin:user:makeadmin:no' => "Käyttäjästä ei voitu tehdä ylläpitäjää.",

	'admin:user:removeadmin:yes' => "Käyttäjän ylläpito-oikeudet poistettu.",
	'admin:user:removeadmin:no' => "Ylläpito-oikeuksien poistaminen epäonnistui.",
	'admin:user:self:removeadmin:no' => "Et voi poistaa omia ylläpito-oikeuksiasi.",

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Määrittele päänavigaation linkit',
	'admin:menu_items:description' => 'Select the order of site menu items. Unconfigured items will be added to the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Navigaatiolinkki tallettiin.',
	'admin:add_menu_item' => 'Lisää navigaatiolinkki',
	'admin:add_menu_item:description' => 'Syötä kohteen nimi ja osoite lisätäksesi uuden linkin päänavigaatioon.',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Tuntematon vimpaintyyppi',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Muokkaa sivuston robots.txt-tiedostoa",
	'admin:robots.txt:plugins' => "Liitännäisten robots.txt-tiedostoon lisäämät säännöt:",
	'admin:robots.txt:subdir' => "Tämä robots.txt-työkalu ei toimi, koska Elgg on asennettu alihakemistoon",
	'admin:robots.txt:physical' => "Hallintapaneelin robots.txt-toimintoa ei voi käyttää, koska asennushakemistossa on jo robots.txt -tiedosto",

	'admin:maintenance_mode:default_message' => 'Sivusto on väliaikaisesti poissa käytöstä huoltokatkoksen vuoksi',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Ylläpitotila',
	'admin:maintenance_mode:message_label' => 'Käyttäjille näytettävä viesti',
	'admin:maintenance_mode:saved' => 'Huoltotilan asetukset tallennettu',
	'admin:maintenance_mode:indicator_menu_item' => 'Sivusto on huoltotilassa.',
	'admin:login' => 'Ylläpitäjien kirjautuminen',

/**
 * User settings
 */

	'usersettings:description' => "Käyttäjäasetuksista voit määritellä kaikki henkilökohtaiset asetuksesi. Valitse vaihtoehto alapuolelta aloittaaksesi.",

	'usersettings:statistics' => "Tilastotietosi",
	'usersettings:statistics:opt:description' => "Katsele tilastotietoja käyttäjistä ja sivuston objekteista.",
	'usersettings:statistics:opt:linktext' => "Tilin tilastotiedot",

	'usersettings:statistics:login_history' => "Kirjautumishistoria",
	'usersettings:statistics:login_history:date' => "Päivämäärä",
	'usersettings:statistics:login_history:ip' => "IP-osoite",

	'usersettings:user' => "Käyttäjän %s asetukset",
	'usersettings:user:opt:description' => "Voit muuttaa käyttäjäkohtaisia asetuksia.",
	'usersettings:user:opt:linktext' => "Muuta asetuksiasi",

	'usersettings:plugins' => "Työkalut",
	'usersettings:plugins:opt:description' => "Määrittele työkalujesi asetukset.",
	'usersettings:plugins:opt:linktext' => "Konfiguroi työkalusi",

	'usersettings:plugins:description' => "Tämän paneelin avulla voit muokata omia asetuksiasi eri työkaluissa.",
	'usersettings:statistics:label:numentities' => "Kohteiden määrä",

	'usersettings:statistics:yourdetails' => "Yksityiskohdat",
	'usersettings:statistics:label:name' => "Koko nimi",
	'usersettings:statistics:label:email' => "Sähköpostiosoite",
	'usersettings:statistics:label:membersince' => "Jäsenenä alkaen",
	'usersettings:statistics:label:lastlogin' => "Viimeisin kirjautuminen",

/**
 * Activity river
 */

	'river:all' => 'Kaikki sivuston toiminta',
	'river:mine' => 'Oma toimintani',
	'river:owner' => 'Käyttäjän %s toiminta',
	'river:friends' => 'Ystävien toiminta',
	'river:select' => 'Näytä %s',
	'river:comments:more' => '+%u lisää',
	'river:comments:all' => 'Kaikki %u kommenttia',
	'river:generic_comment' => 'kommentoi %s %s',

/**
 * Icons
 */

	'icon:size' => "Ikonin koko",
	'icon:size:topbar' => "Yläpalkki",
	'icon:size:tiny' => "Pikkuruinen",
	'icon:size:small' => "Pieni",
	'icon:size:medium' => "Keskikokoinen",
	'icon:size:large' => "Suuri",
	'icon:size:master' => "Erittäin suuri",
	
	'entity:edit:icon:crop_messages:generic' => "The selected image doesn't meet the recommended image dimensions. This could result in low quality icons.",
	'entity:edit:icon:crop_messages:width' => "It's recommended to use an image with a minimal width of at least %dpx.",
	'entity:edit:icon:crop_messages:height' => "It's recommended to use an image with a minimal height of at least %dpx.",
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Tallenna",
	'save_go' => "Save, and go to %s",
	'reset' => 'Resetöi',
	'publish' => "Julkaise",
	'cancel' => "Peruuta",
	'saving' => "Tallennetaan ...",
	'update' => "Päivitä",
	'preview' => "Esikatselu",
	'edit' => "Muokkaa",
	'delete' => "Poista",
	'accept' => "Hyväksy",
	'reject' => "Hylkää",
	'decline' => "Kieltäydy",
	'approve' => "Hyväksy",
	'activate' => "Aktivoi",
	'deactivate' => "Deaktivoi",
	'disapprove' => "Hylkää",
	'revoke' => "Kumoa",
	'load' => "Lataa",
	'upload' => "Lähetä",
	'download' => "Lataa",
	'ban' => "Bannaa",
	'unban' => "Poista bannaus",
	'banned' => "Bannattu",
	'enable' => "Ota käyttöön",
	'disable' => "Poista käytöstä",
	'request' => "Lähetä",
	'complete' => "Valmis",
	'open' => 'Avaa',
	'close' => 'Sulje',
	'hide' => 'Piilota',
	'show' => 'Näytä',
	'reply' => "Vastaa",
	'more' => 'Lisää',
	'more_info' => 'Lisätietoja	',
	'comments' => 'Kommentit',
	'import' => 'Importtaa',
	'export' => 'Exporttaa',
	'untitled' => 'Nimeton',
	'help' => 'Ohje',
	'send' => 'Lähetä',
	'post' => 'Lähetä',
	'submit' => 'Tallenna',
	'comment' => 'Kommentoi',
	'upgrade' => 'Päivitä Elgg',
	'sort' => 'Järjestä',
	'filter' => 'Suodata',
	'new' => 'Uusi',
	'add' => 'Lisää',
	'create' => 'Luo',
	'remove' => 'Poista',
	'revert' => 'Palauta',
	'validate' => 'Vahvista',
	'read_more' => 'Read more',

	'site' => 'Sivusto',
	'activity' => 'Toiminta',
	'members' => 'Jäsenet',
	'menu' => 'Valikko',

	'up' => 'Ylös',
	'down' => 'Alas',
	'top' => 'Ylimmäiseksi',
	'bottom' => 'Alimmaiseksi',
	'right' => 'Oikealle',
	'left' => 'Vasemmalle',
	'back' => 'Takaisin',

	'invite' => "Kutsu",

	'resetpassword' => "Nollaa salasana",
	'changepassword' => "Vaihda salasana",
	'makeadmin' => "Anna ylläpito-oikeudet",
	'removeadmin' => "Poista ylläpito-oikeudet",

	'option:yes' => "Kyllä",
	'option:no' => "Ei",

	'unknown' => 'Tuntematon',
	'never' => 'Ei koskaan',

	'active' => 'Aktiivista',
	'total' => 'Yhteensä',
	'unvalidated' => 'Vahvistamattomat käyttäjät',
	
	'ok' => 'OK',
	'any' => 'Mikä tahansa',
	'error' => 'Virhe',

	'other' => 'Muu',
	'options' => 'Asetukset',
	'advanced' => 'Lisäasetukset',

	'learnmore' => "Klikkaa tästä lukeaksesi lisää.",
	'unknown_error' => 'Tuntematon virhe',

	'content' => "content",
	'content:latest' => 'Viimeisin toiminta',
	'content:latest:blurb' => 'Näet viimeisimmän toiminnan myös tästä.',
	
	'list:out_of_bounds' => "You have reached a part of the list without any content, however there is content available.",
	'list:out_of_bounds:link' => "Go back to the first page of this listing.",

	'link:text' => 'näytä linkki',

/**
 * Generic questions
 */

	'question:areyousure' => 'Oletko varma?',

/**
 * Status
 */

	'status' => 'Tila',
	'status:unsaved_draft' => 'Tallentamaton luonnos',
	'status:draft' => 'Luonnos',
	'status:unpublished' => 'Julkaisematon',
	'status:published' => 'Julkaistu',
	'status:featured' => 'Featured',
	'status:open' => 'Avoin',
	'status:closed' => 'Suljettu',
	'status:enabled' => 'Enabled',
	'status:disabled' => 'Disabled',
	'status:unavailable' => 'Unavailable',
	'status:active' => 'Aktiivista',
	'status:inactive' => 'Inactive',

/**
 * Generic sorts
 */

	'sort:newest' => 'Uusimmat',
	'sort:popular' => 'Suosituimmat',
	'sort:alpha' => 'Aakkosjärjestys',
	'sort:priority' => 'Tärkeysjärjestys',

/**
 * Generic data words
 */

	'title' => "Otsikko",
	'description' => "Kuvaus",
	'tags' => "Tagit",
	'all' => "Kaikki",
	'mine' => "Omasi",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "Huomiot",
	'relationships' => "Suhteet",
	'metadata' => "Metadata",
	'tagcloud' => "Tagipilvi",

	'on' => 'On',
	'off' => 'Off',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%sK",
	'number_counter:view:million' => "%sM",
	'number_counter:view:billion' => "%sB",
	'number_counter:view:trillion' => "%sT",

/**
 * Entity actions
 */

	'edit:this' => 'Muokkaa',
	'delete:this' => 'Poista',
	'comment:this' => 'Kommentoi',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Haluatko varmasti poistaa tämän kohteen?",
	'deleteconfirm:plural' => "Haluatko varmasti poistaa nämä kohteet?",
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'Käyttäjätili luotu',
	'useradd:body' => '%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "click to dismiss",


/**
 * Messages
 */
	'messages:title:success' => 'Success',
	'messages:title:error' => 'Virheet',
	'messages:title:warning' => 'Varoitukset',
	'messages:title:help' => 'Ohje',
	'messages:title:notice' => 'Huomautukset',
	'messages:title:info' => 'Info',

/**
 * Import / export
 */

	'importsuccess' => "Import of data was successful",
	'importfail' => "OpenDD import of data failed.",

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "juuri nyt",
	'friendlytime:minutes' => "%s minuuttia sitten",
	'friendlytime:minutes:singular' => "hetki sitten",
	'friendlytime:hours' => "%s tuntia sitten",
	'friendlytime:hours:singular' => "tunti sitten",
	'friendlytime:days' => "%s päivää sitten",
	'friendlytime:days:singular' => "eilen",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "%s minuutin kuluttua",
	'friendlytime:future:minutes:singular' => "minuutin kuluttua",
	'friendlytime:future:hours' => "%s tunnin kuluttua",
	'friendlytime:future:hours:singular' => "tunnin kuluttua",
	'friendlytime:future:days' => "%s päivän kuluttua",
	'friendlytime:future:days:singular' => "huomenna",

	'date:month:01' => '%s tammikuu',
	'date:month:02' => '%s helmikuu',
	'date:month:03' => '%s maaliskuu',
	'date:month:04' => '%s huhtikuu',
	'date:month:05' => '%s toukokuu',
	'date:month:06' => '%s kesäkuu',
	'date:month:07' => '%s heinäkuu',
	'date:month:08' => '%s elokuu',
	'date:month:09' => '%s syyskuu',
	'date:month:10' => '%s lokakuu',
	'date:month:11' => '%s marraskuu',
	'date:month:12' => '%s joulukuu',

	'date:month:short:01' => 'Tammi %s',
	'date:month:short:02' => 'Helmi %s',
	'date:month:short:03' => 'Maalis %s',
	'date:month:short:04' => 'Huhti %s',
	'date:month:short:05' => 'Touko %s',
	'date:month:short:06' => 'Kesä %s',
	'date:month:short:07' => 'Heinä %s',
	'date:month:short:08' => 'Elo %s',
	'date:month:short:09' => 'Syys %s',
	'date:month:short:10' => 'Loka %s',
	'date:month:short:11' => 'Marras %s',
	'date:month:short:12' => 'Joulu %s',

	'date:weekday:0' => 'Sunnuntai',
	'date:weekday:1' => 'Maanantai',
	'date:weekday:2' => 'Tiistai',
	'date:weekday:3' => 'Keskiviikko',
	'date:weekday:4' => 'Torstai',
	'date:weekday:5' => 'Perjantai',
	'date:weekday:6' => 'Lauantai',

	'date:weekday:short:0' => 'Su',
	'date:weekday:short:1' => 'Ma',
	'date:weekday:short:2' => 'Ti',
	'date:weekday:short:3' => 'Ke',
	'date:weekday:short:4' => 'To',
	'date:weekday:short:5' => 'Pe',
	'date:weekday:short:6' => 'La',

	'interval:minute' => 'Minuutin välein',
	'interval:fiveminute' => 'Viiden minuutin välein',
	'interval:fifteenmin' => 'Viidentoista minuutin välein',
	'interval:halfhour' => 'Puolen tunnin välein',
	'interval:hourly' => 'Tunnin välein',
	'interval:daily' => 'Kerran päivässä',
	'interval:weekly' => 'Viikoittain',
	'interval:monthly' => 'Kuukausittain',
	'interval:yearly' => 'Vuosittain',

/**
 * System settings
 */

	'installation:sitename' => "Sivuston nimi:",
	'installation:sitedescription' => "Sivuston kuvaus (vapaaehtoinen):",
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
	'installation:wwwroot' => "Sivuston URL:",
	'installation:path' => "Polku Elgg-asennukseen:",
	'installation:dataroot' => "Polku datahakemistoon:",
	'installation:dataroot:warning' => "Sinun pitää luoda tämä hakemisto manuaalisesti. Sen tulee olla eri hakemistossa kuin Elgg-asennuksesi.",
	'installation:sitepermissions' => "Oletusoikeudet:",
	'installation:language' => "Sivuston oletuskieli:",
	'installation:debug' => "Määritä palvelimen lokiin tallennettavat tiedot.",
	'installation:debug:label' => "Lokin tarkkuus:",
	'installation:debug:none' => 'Ota virheenjäljitysmoodi pois käytöstä (suositus)',
	'installation:debug:error' => 'Kirjaa vain kriittiset virheet',
	'installation:debug:warning' => 'Kirjaa virheet ja varoitukset',
	'installation:debug:notice' => 'Kirjaa kaikki virheet, varoitukset ja huomautukset',
	'installation:debug:info' => 'Kirjaa kaikki',

	// Walled Garden support
	'installation:registration:description' => 'Oletuksena sivustolle voi vapaasti rekisteröityä uusia käyttäjiä. Ota tämä pois päältä, jos et halua, että käyttäjät voivat rekisteröityä omatoimisesti.',
	'installation:registration:label' => 'Salli käyttäjien vapaa rekisteröityminen',
	'installation:adminvalidation:description' => 'If enabled, newly registered users require manual validation by an administrator before they can use the site.',
	'installation:adminvalidation:label' => 'New users require manual validation by an administrator',
	'installation:adminvalidation:notification:description' => 'When enabled, site administrators will get a notification that there are pending user validations. An administrator can disable the notification on their personal settings page.',
	'installation:adminvalidation:notification:label' => 'Notify administrators of pending user validations',
	'installation:adminvalidation:notification:direct' => 'Direct',
	'installation:walled_garden:description' => 'Rajoita sivusto yksityiseksi. Rekisteröitymättömät käyttäjät voivat nähdä vain sisällöt, jotka on erikseen määritetty julkisiksi.',
	'installation:walled_garden:label' => 'Rajoita sivusto vain kirjautuneille käyttäjille',

	'installation:view' => "Syötä näkymä, jota käytetään sivustosi etusivuna. Jätä kenttä tyhjäksi käyttääksesi oletusnäkymää (jos et ole varma, jätä tämä oletukseksi):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Listauksissa näytettävien kohteiden oletusmäärä",

	'admin:site:access:warning' => "Tämä määrittää oletusasetuksen, kun ollaan luomassa uutta sisältöä. Tämän asetuksen muuttaminen ei vaikuta jo olemassa oleviin sisältöihin.",
	'installation:allow_user_default_access:description' => "Jos tämä on valittuna, käyttäjät voivat määrittää oman oletuspääsytasonsa, joka yliajaa järjestelmän oletustason.",
	'installation:allow_user_default_access:label' => "Salli käyttäjille oma oletuspääsytaso",

	'installation:simplecache:description' => "Yksinkertainen välimuisti nopeuttaa sivustoa tallentamalla muistiin staattista sisältöä kuten CSS ja JavaScript-tiedostoja.",
	'installation:simplecache:label' => "Käytä yksinkertaista välimuistia (suositus)",

	'installation:cache_symlink:description' => "Symbolinen linkki sallii palvelimen tarjota staattisia resursseja suoraan levyltä sen sijaan, että ne tarjottaisiin Elggin kautta. Tämä parantaa huomattavasti suorituskykyä ja vähentää palvelimen kuormaa.",
	'installation:cache_symlink:label' => "Käytä symbolista linkkiä välimuistin sisältävään hakemistoon (suositus).",
	'installation:cache_symlink:warning' => "Symbolinen linkki on lisätty. Sen voi tarvittaessa ottaa pois käytöstä poistamalla linkin palvelimelta.",
	'installation:cache_symlink:paths' => 'Symbolisen linkin tulee linkittää polku <i>%s</i> hakemistoon <i>%s</i>',
	'installation:cache_symlink:error' => "Käytössäsi oleva palvelin ei salli symbolisen linkin luomista automaattisesti. Lue dokumentaatiosta, miten voit luoda linkin manuaalisesti.",

	'installation:minify:description' => "Välimuisti voi parantaa suorituskykyä myös pakkamalla JavaScript- and CSS-tiedostot. (Tämä vaatii, että välimuisti on käytössä.)",
	'installation:minify_js:label' => "Pakkaa JavaScript (suositus)",
	'installation:minify_css:label' => "Pakkaa CSS (suositus)",

	'installation:htaccess:needs_upgrade' => "Sivuston .htaccess-tiedosto pitää päivittää syöttämään polku __elgg_uri-nimiseen GET-parametriin. (Katso ohjeet tiedostosta install/config/htaccess.dist.)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg ei saa testattua rewrite-sääntöjä. Tarkista, että curl toimii oikein ja että palvelimelle ei ole määritetty IP-rajoituksia, jotka estävät localhost-yhteydet.",

	'installation:systemcache:description' => "Välimuisti vähentää liitännäisten latausaikaa tallentamalla muistiin niiden käytössä olevien näkymien sijainnit.",
	'installation:systemcache:label' => "Käytä välimuistia (suositus)",

	'admin:legend:system' => 'Järjestelmä',
	'admin:legend:caching' => 'Välimuisti',
	'admin:legend:content' => 'Sisältö',
	'admin:legend:content_access' => 'Pääsy sisältöihin',
	'admin:legend:site_access' => 'Pääsy sivustolle',
	'admin:legend:debug' => 'Lokit ja virheidenjäljitys',
	
	'config:i18n:allowed_languages' => "Allowed languages",
	'config:i18n:allowed_languages:help' => "Only allowed languages can be used by users. English and the site language are always allowed.",
	'config:users:can_change_username' => "Allow users to change their username",
	'config:users:can_change_username:help' => "If not allowed only admins can change a users username",
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	'config:content:comment_box_collapses' => "The comment box collapses after the first comment on content",
	'config:content:comment_box_collapses:help' => "This only applies if the comments list is sorted latest first",
	'config:content:comments_latest_first' => "The comments should be listed with the latest comment first",
	'config:content:comments_latest_first:help' => "This controls the default behaviour of the listing of comments on a content detail page. If disabled this will also move the comment box to the end of the comments list",
	
	'upgrading' => 'Päivitetään...',
	'upgrade:core' => 'Elgg päivitetty.',
	'upgrade:unlock' => 'Avaa päivityksen lukitus',
	'upgrade:unlock:confirm' => "Aiemmin käynnistetty päivitys on lukinnut tietokannan. Useiden päivitysten ajaminen samanaikaisesti on vaarallista, joten avaa lukitus vain jos tiedät, että käynnissä ei ole päivitystä. Avataanko lukitus?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Päivitystä ei voida aloittaa, koska käynnissä on jo toinen päivitys. Voit avata lukituksen hallintapaneelista.",
	'upgrade:unlock:success' => "Avattiin päivityksen lukitus.",
	'upgrade:unable_to_upgrade' => 'Päivityksen lukituksen avaaminen epäonnistui.',
	'upgrade:unable_to_upgrade_info' => 'This installation cannot be upgraded because legacy views
were detected in the Elgg core views directory. These views have been deprecated and need to be
removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
simply delete the views directory and replace it with the one from the latest
package of Elgg downloaded from <a href="https://elgg.org">elgg.org</a>.<br /><br />

If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
Upgrading Elgg documentation</a>. If you require assistance, please post to the
<a href="https://elgg.org/discussion/all">Community Support Forums</a>.',

	'update:oauth_api:deactivated' => 'OAuth API (aiemmin "OAuth Lib") otettiin pois käytöstä päivityksen aikana. Ota se käyttöön manuaalisesti, mikäli sille on tarvetta.',
	'upgrade:site_secret_warning:moderate' => "On suositeltavaa, että uusit sivuston salausavaimen parantaaksesi tietoturvaa. Lisätietoja löydät kohdasta: Asetukset > Lisäasetukset",
	'upgrade:site_secret_warning:weak' => "Sivustollasi on käytössä liian heikko salausavain, mikä saattaa heikentää tietoturvaa. Uudista salausavain kohdasta: Asetukset > Lisäasetukset",

	'deprecated:function' => '%s() on korvattu funktiolla %s()',

	'admin:pending_upgrades' => 'Sivustolla on odottavia päivityksiä, jotka vaativat välitöntä huomiotasi.',
	'admin:view_upgrades' => 'Siirry päivityksiin tästä.',
	'item:object:elgg_upgrade' => 'Sivuston päivitykset',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Sivustosi on ajan tasalla!',

	'upgrade:item_count' => 'Sivustolla on <b>%s</b> kohdetta, jotka vaativat päivityksen.',
	'upgrade:warning' => '<b>Varoitus:</b> Tämä päivitys saattaa viedä huomattavasti aikaa.',
	'upgrade:success_count' => 'Päivitetyt kohteet:',
	'upgrade:error_count' => 'Virheet:',
	'upgrade:finished' => 'Päivitys on valmis',
	'upgrade:finished_with_errors' => '<p>Päivityksen aikana ilmeni virheitä. Päivitä sivu ja yritä ajaa päivitys uudelleen.<br /></p><p>Jos virheet toistuvat, yritä selvittää niiden syy palvelimen virhelokeista. Tarvittaessa voit pyytää apua Elggin yhteisön <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">teknisen tuen ryhmästä</a>.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Tervetuloa",
	'welcome:user' => 'Tervetuloa %s',

/**
 * Emails
 */

	'email:from' => 'From',
	'email:to' => 'To',
	'email:subject' => 'Otsikko',
	'email:body' => 'Viesti',

	'email:settings' => "Sähköpostiasetukset",
	'email:address:label' => "Sähköpostiosoite",
	'email:address:help:confirm' => "Pending e-mail address change to '%s', please check the inbox for instructions.",
	'email:address:password' => "Salasana",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "Uusi sähköpostiosoite tallennettu.",
	'email:save:fail' => "Sähköpostiosoitteen vaihtaminen epäonnistui.",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s on tehnyt sinusta ystävänsä!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Salasana nollattu!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Salasana nollattu!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Anomus salasanan vaihtamiseksi.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",
	
	'account:email:request:success' => "Your new e-mail address will be saved after confirmation, please check the inbox of '%s' for more instructions.",
	'email:request:email:subject' => "Please confirm your e-mail address",
	'email:request:email:body' => "Hi %s,

You requested to change your e-mail address on '%s'.
If you didn't request this change, you can ignore this email.

In order to confirm the e-mail address change, please click this link:
%s

Please note this link is only valid for 1 hour.",
	
	'account:email:request:error:no_new_email' => "No e-mail address change pending",
	
	'email:confirm:email:old:subject' => "You're e-mail address was changed",
	'email:confirm:email:old:body' => "Hi %s,

Your e-mail address on '%s' was changed.
From now on you'll receive notifications on '%s'.

If you didn't request this change, please contact a site administrator.
%s",
	
	'email:confirm:email:new:subject' => "You're e-mail address was changed",
	'email:confirm:email:new:body' => "Hi %s,

Your e-mail address on '%s' was changed.
From now on you'll receive notifications on this e-mail address.

If you didn't request this change, please contact a site administrator.
%s",

	'account:email:admin:validation_notification' => "Notify me when there are users requiring validation by an administrator",
	'account:email:admin:validation_notification:help' => "Because of the site settings, newly registered users require manual validation by an administrator. With this setting you can disable notifications about pending validation requests.",
	
	'account:validation:pending:title' => "Account validation pending",
	'account:validation:pending:content' => "Your account has been registered successfully! However before you can use you account a site administrator needs to validate you account. You'll receive an e-mail when you account is validated.",
	
	'account:notification:validation:subject' => "Your account on %s has been validated!",
	'account:notification:validation:body' => "Hi %s,

Your account on '%s' has been validated. You can now use your account.

To go the the website, click here:
%s",

/**
 * user default access
 */

	'default_access:settings' => "Oletuspääsyoikeudet",
	'default_access:label' => "Oletuspääsyoikeus",
	'user:default_access:success' => "Uusi oletusoikeustasosi tallennettu.",
	'user:default_access:failure' => "Oletusoikeuden tallentaminen epäonnistui.",

/**
 * Comments
 */

	'comments:count' => "%s kommenttia",
	'item:object:comment' => 'Kommentit',
	'collection:object:comment' => 'Kommentit',

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Kommentoi",
	'generic_comments:edit' => "Muokkaa",
	'generic_comments:post' => "Lähetä kommentti",
	'generic_comments:text' => "Kommentti",
	'generic_comments:latest' => "Viimeisimmät kommentit",
	'generic_comment:posted' => "Kommentti lisätty.",
	'generic_comment:updated' => "Kommentti päivitetty.",
	'entity:delete:object:comment:success' => "Kommentti poistettu.",
	'generic_comment:blank' => "Kommenttiin täytyy kirjoittaa jotain ennen kuin se voidaan tallentaa.",
	'generic_comment:notfound' => "Etsimääsi kommenttia ei löytynyt.",
	'generic_comment:notfound_fallback' => "Etsimääsi kommenttia ei löytynyt, mutta ohjasimme sinut sivulle, jonne kommentti oli jätetty.",
	'generic_comment:failure' => "Kommentin tallentamisessa tapahtui odottamaton virhe.",
	'generic_comment:none' => 'Ei kommentteja',
	'generic_comment:title' => 'Kommentti käyttäjältä %s',
	'generic_comment:on' => '%s kohteessa %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:notification:owner:subject' => 'You have a new comment!',
	'generic_comment:notification:owner:summary' => 'You have a new comment!',
	'generic_comment:notification:owner:body' => "You have a new comment on your item \"%s\" from %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",
	
	'generic_comment:notification:user:subject' => 'A new comment on: %s',
	'generic_comment:notification:user:summary' => 'A new comment on: %s',
	'generic_comment:notification:user:body' => "A new comment was made on \"%s\" by %s. It reads:

%s

To reply or view the original item, click here:
%s

To view %s's profile, click here:
%s",

/**
 * Entities
 */

	'byline' => 'Käyttäjältä %s',
	'byline:ingroup' => 'ryhmässä %s',
	'entity:default:missingsupport:popup' => 'Tätä kohdetta ei voida näyttää oikein. Tämä voi johtua puuttuvasta liitännäisestä.',

	'entity:delete:item' => 'Kohde',
	'entity:delete:item_not_found' => 'Kohdetta ei löytynyt.',
	'entity:delete:permission_denied' => 'Sinulla ei ole oikeuksia tämän kohteen poistamiseen.',
	'entity:delete:success' => 'Poistettiin %s',
	'entity:delete:fail' => 'Kohteen %s poistaminen epäonnistui',

	'entity:can_delete:invaliduser' => 'Käyttäjälle GUID [%s] ei voida tehdä canDelete()-tarkistusta, koska käyttäjää ei ole olemassa.',

/**
 * Annotations
 */
	
	'annotation:delete:fail' => "An error occured while removing the annotation",
	'annotation:delete:success' => "The annotation was removed successfully",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Lomakkeesta puuttuu __token tai __ts -kentät',
	'actiongatekeeper:tokeninvalid' => "Käyttämäsi sivu oli vanhentunut. Yritä uudelleen.",
	'actiongatekeeper:timeerror' => 'Käyttämäsi sivu on vanhentunut. Päivitä sivu ja yritä uudelleen.',
	'actiongatekeeper:pluginprevents' => 'Tietojen lähettäminen epäonnistui tuntemattoman ongelma vuoksi.',
	'actiongatekeeper:uploadexceeded' => 'Lähettämäsi datan koko ylittää sivuston ylläpitäjän määrittämän maksimirajan',
	'actiongatekeeper:crosssitelogin' => "Eri domainista kirjautuminen ei ole sallittua. Yritä uudelleen.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'ja, silloin, mutta, hän, hänen, yksi, ei, myös, noin, nyt, sillä, silti, yhä, niin ikään, muutoin, sen takia, päinvastoin, mieluummin, näin ollen, lisäksi, joka tapauksessa, sijaan, sillä aikaa, sen mukaisesti, tämä, näyttää, mikä, jonka, kuka tahansa, ketä tahansa',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tagit',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Yhteyden saaminen osoitteeseen %s epäonnistui. Kohteiden tallentamisessa saattaa ilmetä ongelmia. Ole hyvä ja päivitä sivu.',
	'js:security:token_refreshed' => 'Yhteys osoitteeseen %s palautettu!',
	'js:lightbox:current' => "%s/%s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",
	
/**
 * Cli commands
 */
	'cli:login:error:unknown' => "Unable to login as %s",
	'cli:login:success:log' => "Logged in as %s [guid: %s]",
	'cli:response:output' => "Response:",
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
	'cli:plugins:list:error:status' => "%s is not a valid status. Allowed options are: %s",
	
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
	'cli:upgrade:list:completed' => "Completed upgrades",
	'cli:upgrade:list:pending' => "Pending upgrades",
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

	"field:required" => 'Pakollinen',

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
