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

/**
 * Sessions
 */

	'login' => "Kirjaudu",
	'loginok' => "Olet kirjautunut sisään.",
	'login:empty' => "Syötä käyttäjätunnus/sähköposti ja salasana.",
	'login:baduser' => "Tiliisi kirjautuminen on estetty.",
	'auth:nopams' => "Sisäinen virhe. Toimintoa käyttäjän sisäänkirjaamiseen ei ole asennettuna.",

	'logout' => "Kirjaudu ulos",
	'logoutok' => "Olet kirjautunut ulos.",
	'logouterror' => "Uloskirjautuminen epäonnistui. Yritä uudelleen.",
	'session_expired' => "Sessiosi on vanhentunut. <a href='javascript:location.reload(true)'>Lataa sivu uudelleen</a> kirjautuaksesi sisään.",
	'session_changed_user' => "Olet kirjautunut toisena käyttäjänä, joten sinun täytyy <a href='javascript:location.reload(true)'>ladata sivu uudelleen</a>.",

	'loggedinrequired' => "Tämän sivun näkyminen edellyttää, että olet kirjautuneena sisään.",
	'adminrequired' => "Tämän sivun näkyminen edellyttää ylläpitäjän oikeuksia.",
	'membershiprequired' => "Sinun pitää olla ryhmän jäsen nähdäksesi tämän sivun.",
	'limited_access' => "Sinulla ei ole oikeuksia tämän sivun tarkasteluun.",
	'invalid_request_signature' => "Osoite, johon koitat päästä käsiksi, on virheellinen tai vanhentunut.",

/**
 * Errors
 */

	'exception:title' => "Virhe.",
	'exception:contact_admin' => 'Tapahtui virhe. Ota yhteys sivuston ylläpitäjään ja toimita seuraavat tiedot:',

	'actionnotfound' => "Tiedostoa, johon toiminto %s viittaa, ei löytynyt.",
	'actionunauthorized' => 'Sinulla ei ole oikeuksia tämän toiminnon suorittamiseen',

	'ajax:error' => 'AJAX-kutsun yhteydessä tapahtui odottamaton virhe. Yhteys palvelimeen saattaa olla katkennut.',
	'ajax:not_is_xhr' => 'AJAX-näkymiin ei ole mahdollista päästä käsiksi suoraan',

	'PluginException:CannotStart' => '%s (guid: %s) ei käynnisty. Syy: %s',
	'PluginException:InvalidID' => "%s on virheellinen pluginin ID.",
	'PluginException:InvalidPath' => "%s on virheellinen pluginipolku.",
	'ElggPlugin:MissingID' => 'Plugin-ID puuttuu (guid %s)',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Ei voida lisätä toiminnallisuutta %s pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Ei voida avata näkymähakemistoa pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:InvalidAndDeactivated' => '%s on virheellinen liitännäinen, joten se poistettiin käytöstä.',
	'ElggPlugin:activate:BadConfigFormat' => 'Tiedoston "elgg-plugin.php" sisältö on virheellinen.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Luettiin konfiguraatio tiedostosta "elgg-plugin.php".',

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

	'UserFetchFailureException' => 'Oikeuksien tarkistaminen käyttäjälle GUID [%s] epäonnistui, koska käyttäjää ei ole olemassa.',
	'BadRequestException' => 'Virheellinen pyyntö',

	'viewfailure' => 'Näkymässä %s ilmeni sisäinen virhe.',
	'changebookmark' => 'Kirjanmerkin polku on vanhentunut. Ole hyvä ja vaihda kirjanmerkkisi tälle sivulle',
	'error:missing_data' => 'Pyynnössäsi oli puutteellisia tietoja',
	'save:fail' => 'Tallentaminen epäonnistui',
	'save:success' => 'Tiedot tallennettu',

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
	'access:label:friends' => "Ystävät",
	'access' => "Pääsy",
	'access:limited:label' => "Rajoitettu",
	'access:help' => "Pääsytaso",
	'access:read' => "Lukuoikeus",
	'access:write' => "Kirjoitusoikeus",
	'access:admin_only' => "Vain ylläpitäjät",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Kojelauta",
	'dashboard:nowidgets' => "Kojelauta on henkilökohtainen työpöytäsi sivustolla. Klikkaa \"Lisää vimpaimia\" lisätäksesi kojelaudalle vimpaimia, joiden avulla voit seurata sivuston sisältöä ja toimintaa.",

	'widgets:add' => 'Vimpaimet',
	'widgets:add:description' => "Klikkaa alla olevia vimpaimia lisätäksesi ne sivulle.",
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
	
/**
 * Groups
 */

	'group' => "Ryhmä",
	'item:group' => "Ryhmät",
	'collection:group' => 'Ryhmät',
	'item:group:group' => "Ryhmät",
	'collection:group:group' => 'Ryhmät',

/**
 * Users
 */

	'user' => "Käyttäjä",
	'item:user' => "Käyttäjät",
	'collection:user' => 'Käyttäjät',
	'item:user:user' => 'Käyttäjät',
	'collection:user:user' => 'Käyttäjät',

	'friends' => "Ystävät",

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

/**
 * Feeds
 */
	'feed:rss' => 'Tilaa sivu syötteenä',
/**
 * Links
 */
	'link:view' => 'Näytä linkki',
	'link:view:all' => 'Näytä kaikki',


/**
 * River
 */
	'river' => "Toimintalista",
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
	'river:subject:invalid_subject' => 'Virheellinen käyttäjä',
	'activity:owner' => 'Näytä toiminta',

/**
 * Relationships
 */

/**
 * Notifications
 */
	'notification:method:email' => 'Sähköposti',
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

	'viewtype:change' => "Vaihda listatyyppi",
	'viewtype:list' => "Listanäkymä",
	'viewtype:gallery' => "Gallerianäkymä",
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
	'registration:notemail' => 'Syötit virheellisen sähköpostiosoitteen.',
	'registration:userexists' => 'Syöttämäsi käyttäjätunnus on jo olemassa',
	'registration:usernametooshort' => 'Käyttäjätunnuksen tulee olla vähintään %u merkin pituinen.',
	'registration:usernametoolong' => 'Käyttäjätunnuksesi on liian pitkä. Siinä voi olla korkeintaan %u merkkiä.',
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

	'user:password:lost' => 'Unohtunut salasana',
	'user:password:changereq:success' => 'Pyydettiin uutta salasanaa, sähköposti lähetetty',
	'user:password:changereq:fail' => 'Uuden salasanan pyytäminen epäonnistui.',

	'user:password:text' => 'Syötä käyttäjätunnuksesi tai sähköpostiosoitteesi. Saat sähköpostiisi linkin, jonka kautta pääset syöttämään uuden salasanan.',

	'user:persistent' => 'Muista minut',

/**
 * Password requirements
 */
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Hallinnointi',
	'menu:page:header:configure' => 'Asetukset',
	'menu:page:header:develop' => 'Kehittäjän työkalut',
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
	'admin:description' => "Hallintapaneelilla voit vaikuttaa kaikkiin sivustoa koskeviin asetuksiin. Valitse vaihtoehto alta aloittaaksesi.",
	
	'admin:statistics' => 'Tilastotiedot',
	'admin:server' => 'Palvelin',
	'admin:cron:record' => 'Viimeisimmät Cron-ajot',
	'admin:cron:period' => 'Cron-aikaväli',
	'admin:cron:friendly' => 'Suoritettu viimeksi',
	'admin:cron:date' => 'Päivä ja aika',
	'admin:cron:msg' => 'Viesti',
	'admin:cron:started' => '%s ajettava Cron aloitettu %s',
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
	'admin:users:add' => 'Lisää uusi käyttäjä',
	'admin:users:description' => "Tämän hallintapaneelin avulla voit vaikuttaa sivustosi käyttäjäasetuksiin. Valitse vaihtoehto alta aloittaaksesi.",
	'admin:users:adduser:label' => "Klikkaa tästä lisätäksesi uuden käyttäjän...",
	'admin:users:opt:linktext' => "Hallinnoi käyttäjiä...",
	'admin:users:opt:description' => "Säädä käyttäjä ja tiliasetuksia. ",
	'admin:users:find' => 'Etsi',
	'admin:users:unvalidated' => 'Vahvistamattomat käyttäjät',
	'admin:users:unvalidated:no_results' => 'Ei vahvistamattomia käyttäjiä.',
	
	'admin:configure_utilities:maintenance' => 'Ylläpitotila',
	'admin:upgrades' => 'Päivitykset',

	'admin:settings' => 'Asetukset',
	'admin:settings:basic' => 'Perusasetukset',
	'admin:settings:advanced' => 'Lisäasetukset',
	'admin:settings:users' => 'Käyttäjät',
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

	'admin:widget:admin_welcome' => 'Tervetuloa',
	'admin:widget:admin_welcome:help' => "Lyhyt perehdytys Elggin hallintapaneeliin",
	'admin:widget:admin_welcome:intro' => 'Tervetuloa Elggiin! Olet juuri nyt ylläpitäjän kojelaudalla, joka on kätevä työkalu sivuston toiminnan seuraamiseen.',

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Tutustu myös sivun alaosassa oleviin linkkeihin, ja kiitos kun käytät Elggiä!',

	'admin:widget:control_panel' => 'Hallintapaneeli',
	'admin:widget:control_panel:help' => "Tarjoaa helpon pääsyn yleisimpiin toimintoihin",

	'admin:cache:flush' => 'Tyhjennä välimuistit',
	'admin:cache:flushed' => "Välimuistit on tyhjennetty",

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
	'admin:notices:could_not_delete' => 'Ilmoituksen poistaminen epäonnistui.',
	'item:object:admin_notice' => 'Ylläpidon ilmoitus',

	'admin:options' => 'Hallintavalikko',
	
	'admin:security:settings' => 'Asetukset',
	'admin:security:settings:label:account' => 'Tili',
	'admin:security:settings:label:notifications' => 'Ilmoitukset',

/**
 * Plugins
 */

	'plugins:disabled' => 'Liitännäiset ovat poissa käytöstä, sillä mod-hakemistossa on "disabled"-niminen tiedosto.',
	'plugins:settings:save:ok' => "Päivitettiin asetukset liitännäiselle %s.",
	'plugins:settings:save:fail' => "Asetusten päivittäminen liitännäiselle %s epäonnistui.",
	'plugins:usersettings:save:ok' => "Päivitettiin käyttöasetukset liitännäiselle %s.",
	'plugins:usersettings:save:fail' => "Käyttöasetusten päivittäminen liitännäiselle %s epäonnistui.",
	
	'item:object:plugin' => 'Liitännäiset',
	'collection:object:plugin' => 'Liitännäiset',

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
	'admin:plugins:label:dependencies' => 'Riippuvuudet',

	'admin:plugins:warning:unmet_dependencies' => 'Tällä liitännäisellä on puuttuvia riippuvuuksia, joten sitä ei voida aktivoida. Tarkista riippuvuudet lisätiedoista.',
	'admin:plugins:warning:invalid' => 'Pluginissa on virhe: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Voit yrittää etsiä apua <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elggin dokumentaatiosta</a>.',
	'admin:plugins:cannot_activate' => 'ei voi aktivoida',
	'admin:plugins:cannot_deactivate' => 'ei voida aktivoida',
	'admin:plugins:already:active' => 'Valitut liitännäiset ovat jo aktiivisia',
	'admin:plugins:already:inactive' => 'Valitut liitännäiset ovat jo deaktivoituja',

	'admin:plugins:set_priority:yes' => "Vaihdettiin liitännäisen \"%s\" prioriteettia.",
	'admin:plugins:set_priority:no' => "Liitännäisen \"%s\" uudelleenjärjestäminen epäonnistui.",
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

	'admin:statistics:description' => "Tämä näyttää tilastoja sivustostasi. Tarkempien tietojen selvittämiseen voit käyttää erillistä hallintatyökalua.",
	'admin:statistics:opt:description' => "Näyttää tilastoja sivustosi käyttäjistä ja sisällöistä.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:numentities' => "Sivustolla olevat kohteet",
	'admin:statistics:label:numusers' => "Käyttäjien määrä",
	'admin:statistics:label:numonline' => "Tällä hetkellä kirjautuneena",
	'admin:statistics:label:onlineusers' => "Tällä hetkellä kirjautuneena",
	'admin:statistics:label:admins'=>"Ylläpitäjät",
	'admin:statistics:label:version' => "Elgg-versio",
	'admin:statistics:label:version:release' => "Julkaisu",
	'admin:statistics:label:version:version' => "Versio",
	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Web-palvelin',
	'admin:server:label:server' => 'Palvelin',
	'admin:server:label:log_location' => 'Lokin sijainti',
	'admin:server:label:php_version' => 'PHP-versio',
	'admin:server:label:php_ini' => 'PHP:n ini-tiedoton sijainti',
	'admin:server:label:php_log' => 'PHP:n loki',
	'admin:server:label:mem_avail' => 'Käytettävissä oleva muisti',
	'admin:server:label:mem_used' => 'Käytetty muisti',
	'admin:server:error_log' => "Web-palvelimen virheloki",
	'admin:server:label:post_max_size' => 'POST-datan maksimikoko',
	'admin:server:label:upload_max_filesize' => 'Palvelimelle lähetettävien tiedostojen maksimikoko',
	'admin:server:warning:post_max_too_small' => '(Huom: post_max_size pitää olla isompi kuin tämä.)',
	
	'admin:server:requirements:php_extension' => "PHP-laajennos: %s",
	
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
	'admin:menu_items:configure' => 'Määrittele päänavigaation linkit',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Navigaatiolinkki tallettiin.',
	'admin:add_menu_item' => 'Lisää navigaatiolinkki',
	'admin:add_menu_item:description' => 'Syötä kohteen nimi ja osoite lisätäksesi uuden linkin päänavigaatioon.',
	'admin:default_widgets:unknown_type' => 'Tuntematon vimpaintyyppi',

	'admin:robots.txt:instructions' => "Muokkaa sivuston robots.txt-tiedostoa",
	'admin:robots.txt:plugins' => "Liitännäisten robots.txt-tiedostoon lisäämät säännöt:",
	'admin:robots.txt:subdir' => "Tämä robots.txt-työkalu ei toimi, koska Elgg on asennettu alihakemistoon",
	'admin:robots.txt:physical' => "Hallintapaneelin robots.txt-toimintoa ei voi käyttää, koska asennushakemistossa on jo robots.txt -tiedosto",

	'admin:maintenance_mode:default_message' => 'Sivusto on väliaikaisesti poissa käytöstä huoltokatkoksen vuoksi',
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

/**
 * Generic action words
 */

	'save' => "Tallenna",
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
	'next' => 'Seuraava',
	'previous' => 'Edellinen',
	
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
	'status:active' => 'Aktiivista',

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

/**
 * User add
 */

	'useradd:subject' => 'Käyttäjätili luotu',

/**
 * Messages
 */
	'messages:title:error' => 'Virheet',
	'messages:title:warning' => 'Varoitukset',
	'messages:title:help' => 'Ohje',
	'messages:title:notice' => 'Huomautukset',
	'messages:title:info' => 'Info',

/**
 * Time
 */
	'input:date_format:datepicker' => '', // jQuery UI datepicker format

	'friendlytime:justnow' => "juuri nyt",
	'friendlytime:minutes' => "%s minuuttia sitten",
	'friendlytime:minutes:singular' => "hetki sitten",
	'friendlytime:hours' => "%s tuntia sitten",
	'friendlytime:hours:singular' => "tunti sitten",
	'friendlytime:days' => "%s päivää sitten",
	'friendlytime:days:singular' => "eilen",
	'friendlytime:date_format' => 'j F Y @ g:ia',

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
	'installation:walled_garden:description' => 'Rajoita sivusto yksityiseksi. Rekisteröitymättömät käyttäjät voivat nähdä vain sisällöt, jotka on erikseen määritetty julkisiksi.',
	'installation:walled_garden:label' => 'Rajoita sivusto vain kirjautuneille käyttäjille',

	'installation:view' => "Syötä näkymä, jota käytetään sivustosi etusivuna. Jätä kenttä tyhjäksi käyttääksesi oletusnäkymää (jos et ole varma, jätä tämä oletukseksi):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
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

	'upgrading' => 'Päivitetään...',
	'upgrade:core' => 'Elgg päivitetty.',
	'upgrade:unlock' => 'Avaa päivityksen lukitus',
	'upgrade:unlock:confirm' => "Aiemmin käynnistetty päivitys on lukinnut tietokannan. Useiden päivitysten ajaminen samanaikaisesti on vaarallista, joten avaa lukitus vain jos tiedät, että käynnissä ei ole päivitystä. Avataanko lukitus?",
	'upgrade:locked' => "Päivitystä ei voida aloittaa, koska käynnissä on jo toinen päivitys. Voit avata lukituksen hallintapaneelista.",
	'upgrade:unlock:success' => "Avattiin päivityksen lukitus.",

	'admin:pending_upgrades' => 'Sivustolla on odottavia päivityksiä, jotka vaativat välitöntä huomiotasi.',
	'admin:view_upgrades' => 'Siirry päivityksiin tästä.',
	'item:object:elgg_upgrade' => 'Sivuston päivitykset',
	'admin:upgrades:none' => 'Sivustosi on ajan tasalla!',

	'upgrade:success_count' => 'Päivitetyt kohteet:',
	'upgrade:finished' => 'Päivitys on valmis',
	'upgrade:finished_with_errors' => '<p>Päivityksen aikana ilmeni virheitä. Päivitä sivu ja yritä ajaa päivitys uudelleen.<br /></p><p>Jos virheet toistuvat, yritä selvittää niiden syy palvelimen virhelokeista. Tarvittaessa voit pyytää apua Elggin yhteisön <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">teknisen tuen ryhmästä</a>.</p>',
	
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
	'email:address:password' => "Salasana",

	'email:save:success' => "Uusi sähköpostiosoite tallennettu.",
	'email:save:fail' => "Sähköpostiosoitteen vaihtaminen epäonnistui.",

	'friend:newfriend:subject' => "%s on tehnyt sinusta ystävänsä!",

	'email:changepassword:subject' => "Salasana nollattu!",

	'email:resetpassword:subject' => "Salasana nollattu!",

	'email:changereq:subject' => "Anomus salasanan vaihtamiseksi.",

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
	'generic_comment:failure' => "Kommentin tallentamisessa tapahtui odottamaton virhe.",
	'generic_comment:none' => 'Ei kommentteja',
	'generic_comment:title' => 'Kommentti käyttäjältä %s',
	'generic_comment:on' => '%s kohteessa %s',
	'generic_comments:latest:posted' => 'posted a',

/**
 * Entities
 */

	'byline' => 'Käyttäjältä %s',
	'byline:ingroup' => 'ryhmässä %s',
	
	'entity:delete:item' => 'Kohde',
	'entity:delete:item_not_found' => 'Kohdetta ei löytynyt.',
	'entity:delete:permission_denied' => 'Sinulla ei ole oikeuksia tämän kohteen poistamiseen.',
	'entity:delete:success' => 'Poistettiin %s',
	'entity:delete:fail' => 'Kohteen %s poistaminen epäonnistui',

/**
 * Annotations
 */
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Lomakkeesta puuttuu __token tai __ts -kentät',
	'actiongatekeeper:tokeninvalid' => "Käyttämäsi sivu oli vanhentunut. Yritä uudelleen.",
	'actiongatekeeper:timeerror' => 'Käyttämäsi sivu on vanhentunut. Päivitä sivu ja yritä uudelleen.',
	'actiongatekeeper:pluginprevents' => 'Tietojen lähettäminen epäonnistui tuntemattoman ongelma vuoksi.',
	'actiongatekeeper:uploadexceeded' => 'Lähettämäsi datan koko ylittää sivuston ylläpitäjän määrittämän maksimirajan',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Yhteyden saaminen osoitteeseen %s epäonnistui. Kohteiden tallentamisessa saattaa ilmetä ongelmia. Ole hyvä ja päivitä sivu.',
	'js:lightbox:current' => "%s/%s",

/**
 * Diagnostics
 */
	
/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",
	
/**
 * Cli commands
 */
	
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
);
