<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sivustot',

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
	'session_expired' => "Sessiosi on vanhentunut. Päivitä sivu kirjautuaksesi sisään.",

	'loggedinrequired' => "Tämän sivun näkyminen edellyttää, että olet kirjautuneena sisään.",
	'adminrequired' => "Tämän sivun näkyminen edellyttää ylläpitäjän oikeuksia.",
	'membershiprequired' => "Sinun pitää olla ryhmän jäsen nähdäksesi tämän sivun.",
	'limited_access' => "Sinulla ei ole oikeuksia tämän sivun tarkasteluun.",


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
	'ElggPlugin:Exception:CannotIncludeFile' => 'Ei voida lisätä toiminnallisuutta %s pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Ei voida avata näkymähakemistoa pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Ei voida rekisteröidä käännöksiä pluginille %s (guid: %s) sijainnissa %s. Tarkista tiedosto-oikeudet!',
	'ElggPlugin:Exception:NoID' => 'Ei löydetty ID:tä pluginille guid %s!',
	'PluginException:NoPluginName' => "Liitännäisen nimeä ei löytynyt",
	'PluginException:ParserError' => 'Virhe yrittäessä käsitellä infotiedostoa API-versiolla %s pluginissa %s.',
	'PluginException:NoAvailableParser' => 'Ei löydetty käsittelijää infotiedoston API-versiolle %s pluginissa %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Pakollinen '%s' attribuutti puuttuu pluginin %s infotiedostosta.",
	'ElggPlugin:InvalidAndDeactivated' => '%s on virheellinen liitännäinen, joten se poistettiin käytöstä.',

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

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Varoitus: Sivun omistajaa %d ei pystytä näyttämään!',
	'viewfailure' => 'Näkymässä %s ilmeni sisäinen virhe.',
	'view:missing_param' => "Parametri '%s' puuttuu näkymästä %s",
	'changebookmark' => 'Kirjanmerkin polku on vanhentunut. Ole hyvä ja vaihda kirjanmerkkisi tälle sivulle',
	'noaccess' => 'Kohde on poistettu tai sinulla ei ole oikeuksia sen tarkastelemiseen.',
	'error:missing_data' => 'Pyynnössäsi oli puutteellisia tietoja',
	'save:fail' => 'Tallentaminen epäonnistui',
	'save:success' => 'Tiedot tallennettu',

	'error:default:title' => 'Hups...',
	'error:default:content' => 'Hups... jotain meni pieleen.',
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
 * User details
 */

	'name' => "Nimi",
	'email' => "Sähköpostiosoite",
	'username' => "Käyttäjätunnus",
	'loginusername' => "Tunnus tai sähköposti",
	'password' => "Salasana",
	'passwordagain' => "Salasana (uudelleen)",
	'admin_option' => "Tee tästä käyttäjästä ylläpitäjä?",

/**
 * Access
 */

	'PRIVATE' => "Yksityinen",
	'LOGGED_IN' => "Kirjautuneet",
	'PUBLIC' => "Julkinen",
	'LOGGED_OUT' => "Kirjautumattomat käyttäjät",
	'access:friends:label' => "Ystävät",
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
	'widgets:panel:close' => "Sulje vimpainpaneeli",
	'widgets:position:fixed' => '(Lukittu sijainti sivulla)',
	'widget:unavailable' => 'Olet jo lisännyt tämän vimpaimen',
	'widget:numbertodisplay' => 'Näytettävien kohteiden määrä',

	'widget:delete' => 'Poista %s',
	'widget:edit' => 'Muokkaa vimpainta',

	'widgets' => "Vimpaimet",
	'widget' => "Vimpain",
	'item:object:widget' => "Vimpaimet",
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

/**
 * Users
 */

	'user' => "Käyttäjä",
	'item:user' => "Käyttäjät",

/**
 * Friends
 */

	'friends' => "Ystävät",
	'friends:yours' => "Omat ystäväsi",
	'friends:owned' => "Käyttäjän %s ystävät",
	'friend:add' => "Lisää ystäväksi",
	'friend:remove' => "Poista ystävistä",

	'friends:add:successful' => "Olet lisännyt käyttäjän %s ystäväksesi.",
	'friends:add:failure' => "Käyttäjää %s ei voitu lisätä ystäväksi.",

	'friends:remove:successful' => "Olet poistanut käyttäjän %s ystävistäsi.",
	'friends:remove:failure' => "Käyttäjää %s ei voitu poistaa ystävistäsi.",

	'friends:none' => "Tämä käyttäjä ei ole vielä lisännyt ketään ystäväkseen.",
	'friends:none:you' => "Et ole lisännyt vielä ketään ystäväksesi.",

	'friends:none:found' => "Ystäviä ei löydetty.",

	'friends:of:none' => "Kukaan ei ole vielä lisännyt tätä käyttäjää ystäväkseen.",
	'friends:of:none:you' => "Kukaan ei ole vielä lisännyt sinua ystäväkseen. Ala lisätä sisältöä sivustolle, täytä profiiliisi ja anna ihmisten löytää sinut!",

	'friends:of:owned' => "Käyttäjät, joiden ystävänä %s on",

	'friends:of' => "Kenen ystävänä",
	'friends:collections' => "Ystäväkokoelmat",
	'collections:add' => "Uusi kokoelma",
	'friends:collections:add' => "Uusi ystäväkokoelma",
	'friends:addfriends' => "Lisää ystäviä",
	'friends:collectionname' => "Kokoelman nimi",
	'friends:collectionfriends' => "Ystävät kokoelmassa",
	'friends:collectionedit' => "Muokkaa kokoelmaa",
	'friends:nocollections' => "Sinulla ei ole vielä yhtään ystäväkokoelmaa.",
	'friends:collectiondeleted' => "Ystäväkokoelma on poistettu.",
	'friends:collectiondeletefailed' => "Ystäväkokoelmaa ei voitu poistaa. Joko sinulla ei ole oikeuksia tähän, tai sitten tapahtui jokin odottamaton virhe.",
	'friends:collectionadded' => "Ystäväkokoelma luotiin onnistuneesti",
	'friends:nocollectionname' => "Sinun täytyy antaa ystäväkokoelmalle nimi ennen kuin se voidaan luoda.",
	'friends:collections:members' => "Kokoelman jäsenet",
	'friends:collections:edit' => "Muokkaa kokoelmaa",
	'friends:collections:edited' => "Muutokset tallennettiin",
	'friends:collection:edit_failed' => 'Muutoksien tallentaminen eponnistui.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZÅÄÖ',

	'avatar' => 'Profiilikuva',
	'avatar:noaccess' => "Sinulla ei ole oikeuksia muokata tämän käyttäjän profiilikuvaa",
	'avatar:create' => 'Rajaa profiilikuva',
	'avatar:edit' => 'Muokkaa profiilikuvaa',
	'avatar:preview' => 'Esikatselu',
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

	'profile:edit' => 'Muokkaa profiilia',
	'profile:aboutme' => "Kuvaus",
	'profile:description' => "Kuvaus",
	'profile:briefdescription' => "Lyhyt kuvaus",
	'profile:location' => "Sijainti",
	'profile:skills' => "Taidot",
	'profile:interests' => "Kiinnostuksen kohteet",
	'profile:contactemail' => "Sähköpostiosoite",
	'profile:phone' => "Puhelin",
	'profile:mobile' => "Matkapuhelin",
	'profile:website' => "www-sivu",
	'profile:twitter' => "Twitter-tunnus",
	'profile:saved' => "Profiilisi tallennettiin onnistuneesti.",

	'profile:field:text' => 'Lyhyt tekstikenttä',
	'profile:field:longtext' => 'Suuri tekstikenttä',
	'profile:field:tags' => 'Tagit',
	'profile:field:url' => 'WWW-osoite',
	'profile:field:email' => 'Sähköpostiosoite',
	'profile:field:location' => 'Sijainti',
	'profile:field:date' => 'Päivämäärä',

	'admin:appearance:profile_fields' => 'Profiilikentät',
	'profile:edit:default' => 'Muokkaa profiilikenttiä',
	'profile:label' => "Kentän nimi",
	'profile:type' => "Kentän tyyppi",
	'profile:editdefault:delete:fail' => 'Kentän poistaminen epäonnistui',
	'profile:editdefault:delete:success' => 'Oletuskenttä poistettu!',
	'profile:defaultprofile:reset' => 'Käyttäjäprofiilin kenttien oletusasetukset palautettu',
	'profile:resetdefault' => 'Palauta oletusarvoiset profiilikentät',
	'profile:resetdefault:confirm' => 'Haluatko varmasti poistaa itse lisätyt profiilikentät?',
	'profile:explainchangefields' => "Voit korvata olemassa olevat profiilikentät omilla kentillä käyttämällä alla olevaa lomaketta. \n\n Kirjoita ensin uuden profiilikentän otsikko, esimerkiksi 'Suosikkijoukkue'. Valitse seuraavaksi kentän tyyppi (teksti, tagit, url, jne). Voit muuttaa kenttien järjestystä raahaamalla kenttiä nuolikuvakkeesta. Klikkaa kentän nimeä muokataksesi sitä. \n\n Voit milloin tahansa palauttaa profiilin kentät järjestelmän oletusarvoihin. Huomioi kuitenkin, että menetät tällöin kaikki käyttäjien lisäkenttiin syöttämät tiedot.",
	'profile:editdefault:success' => 'Uusi profiilikenttä lisätty onnistuneesti',
	'profile:editdefault:fail' => 'Uuden profiilikentän tallentaminen epäonnistui',
	'profile:field_too_long' => 'Profiilitietojasi ei voida tallentaa, sillä osio "%s" on liian pitkä.',
	'profile:noaccess' => "Sinulla ei ole oikeuksia tämän profiilin muokkaamiseen.",
	'profile:invalid_email' => 'Syötit virheellisen sähköpostiosoitteen.',


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
	'river:friend:user:default' => "%s on nyt käyttäjän %s ystävä",
	'river:update:user:avatar' => 'Käyttäjä %s päivitti profiilikuvansa',
	'river:update:user:profile' => '%s päivitti profiiliaan',
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
	'river:subject:invalid_subject' => 'Virheellinen käyttäjä',
	'activity:owner' => 'Näytä toiminta',

	'river:widget:title' => "Toimintalista",
	'river:widget:description' => "Näytä viimeisin toiminta",
	'river:widget:type' => "Toiminnan tyyppi",
	'river:widgets:friends' => 'Ystävien toiminta',
	'river:widgets:all' => 'Kaikki sivuston toiminta',

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
	'user:changepassword:change_password_confirm' => 'Salasanan nollaus lähettää uuden salasanan sähköpostitse aiemmin rekisteröimääsi sähköpostiosoitteeseen.',

	'user:set:language' => "Kieliasetukset",
	'user:language:label' => "Kieli",
	'user:language:success' => "Kieliasetus päivitetty.",
	'user:language:fail' => "Kieliasetuksen tallentaminen epäonnistui.",

	'user:username:notfound' => 'Käyttäjätunnusta %s ei löytynyt.',

	'user:password:lost' => 'Unohtunut salasana',
	'user:password:changereq:success' => 'Pyydettiin uutta salasanaa, sähköposti lähetetty',
	'user:password:changereq:fail' => 'Uuden salasanan pyytäminen epäonnistui.',

	'user:password:text' => 'Anoaksesi uuden salasanan, syötä alle käyttäjätunnuksesi. Saat sähköpostiisi linkin, jota klikkaamalla sinulle lähetetään uusi salasana.',

	'user:persistent' => 'Muista minut',

	'walled_garden:welcome' => 'Tervetuloa sivustolle',

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

	'admin:statistics' => "Tilastotiedot",
	'admin:statistics:overview' => 'Yhteenveto',
	'admin:statistics:server' => 'Palvelimen tiedot',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Viimeisimmät Cron-ajot',
	'admin:cron:period' => 'Cron-aikaväli',
	'admin:cron:friendly' => 'Suoritettu viimeksi',
	'admin:cron:date' => 'Päivä ja aika',

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

	'admin:administer_utilities:maintenance' => 'Ylläpitotila',
	'admin:upgrades' => 'Päivitykset',

	'admin:settings' => 'Asetukset',
	'admin:settings:basic' => 'Perusasetukset',
	'admin:settings:advanced' => 'Lisäasetukset',
	'admin:site:description' => "Tämän hallintapaneelin avulla voit vaikuttaa sivustosi yleisiin asetuksiin. Valitse vaihtoehto alta aloittaaksesi.",
	'admin:site:opt:linktext' => "Hallinnoi sivuston asetuksia...",
	'admin:settings:in_settings_file' => 'Tämä asetus on määritetty asetustiedostossa (settings.php)',

	'admin:legend:security' => 'Tietoturva',
	'admin:site:secret:intro' => 'Elgg käyttää salausavainta erilaisten tietoturvaa parantavien koodiavainten luomiseen.',
	'admin:site:secret_regenerated' => "Sivustonlaajuinen salausavain on uusittu.",
	'admin:site:secret:regenerate' => "Päivitä salausavain",
	'admin:site:secret:regenerate:help' => "Huom: Avaimen uusiminen kumoaa tähän asti luodut avainkoodit, joita käytetään \"Muista minut\"-toiminnossa, sähköpostien vahvistusviesteissä, kutsukoodeissa, yms. Tämä saattaa aiheuttaa ongelmia joillekin käyttäjille.",
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
	'widget:content_stats:type' => 'Sisältötyyppi',
	'widget:content_stats:number' => 'Lukumäärä',

	'admin:widget:admin_welcome' => 'Tervetuloa',
	'admin:widget:admin_welcome:help' => "Lyhyt perehdytys Elggin hallintapaneeliin",
	'admin:widget:admin_welcome:intro' =>
'Tervetuloa Elggiin! Olet juuri nyt ylläpitäjän kojelaudalla, joka on kätevä työkalu sivuston toiminnan seuraamiseen.',

	'admin:widget:admin_welcome:admin_overview' =>
"Ylläpitopuolen navigaatio löytyy oikella olevasta valikosta. Se on jaettu
kolmeen osioon:
	<dl>
		<dt>Hallinnointi</dt><dd>Käteviä toimintoja kuten listaukset tilastotiedoista, kirjautuneista käyttäjistä sekä sopimattomaksi raportoidusta sisällöstä.</dd>
		<dt>Asetukset</dt><dd>Toiminnot kuten sivuston nimen muuttaminen ja uusien liitännäisten lisääminen.</dd>
		<dt>Kehittäjän työkalut</dt><dd>Henkilöille, jotka kehittävät uusia liitännäisiä tai teemoja. (Vaati developer-pluginin.)</dd>
	</dl>
	",

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

/**
 * Plugins
 */

	'plugins:disabled' => 'Liitännäiset ovat poissa käytöstä, sillä mod-hakemistossa on "disabled"-niminen tiedosto.',
	'plugins:settings:save:ok' => "Päivitettiin asetukset liitännäiselle %s.",
	'plugins:settings:save:fail' => "Asetusten päivittäminen liitännäiselle %s epäonnistui.",
	'plugins:usersettings:save:ok' => "Päivitettiin käyttöasetukset liitännäiselle %s.",
	'plugins:usersettings:save:fail' => "Käyttöasetusten päivittäminen liitännäiselle %s epäonnistui.",
	'item:object:plugin' => 'Liitännäiset',

	'admin:plugins' => "Liitännäiset",
	'admin:plugins:activate_all' => 'Aktivoi kaikki',
	'admin:plugins:deactivate_all' => 'Deaktivoi kaikki',
	'admin:plugins:activate' => 'Aktivoi',
	'admin:plugins:deactivate' => 'Deaktivoi',
	'admin:plugins:description' => "Täällä voit määrittää sivustolla käytössä olevat työkalut sekä niiden asetukset.",
	'admin:plugins:opt:linktext' => "Konfiguroi työkalut...",
	'admin:plugins:opt:description' => "Konfiguroi järjestelmään asennetut työkalut. ",
	'admin:plugins:label:author' => "Tekijä",
	'admin:plugins:label:copyright' => "Tekijänoikeus",
	'admin:plugins:label:categories' => 'Kategoriat',
	'admin:plugins:label:licence' => "Lisenssi",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Lähdekoodi",
	'admin:plugins:label:bugtracker' => "Ilmoita virheestä",
	'admin:plugins:label:donate' => "Lahjoitukset",
	'admin:plugins:label:moreinfo' => 'lisätiedot',
	'admin:plugins:label:version' => 'Versio',
	'admin:plugins:label:location' => 'Sijainti',
	'admin:plugins:label:contributors' => 'Tekemiseen osallistuneet',
	'admin:plugins:label:contributors:name' => 'Nimi',
	'admin:plugins:label:contributors:email' => 'Sähköposti',
	'admin:plugins:label:contributors:website' => 'Nettisivu',
	'admin:plugins:label:contributors:username' => 'Yhteisön käyttäjätunnus',
	'admin:plugins:label:contributors:description' => 'Kuvaus',
	'admin:plugins:label:dependencies' => 'Riippuvuudet',

	'admin:plugins:warning:elgg_version_unknown' => 'Tämä liitännäinen käyttää vanhentunutta manifest-tiedostoa, joka ei sisällä tietoa yhteensopivuudesta eri Elgg-versioiden kanssa. Liitännäinen ei todennäköisesti toimi oikein!',
	'admin:plugins:warning:unmet_dependencies' => 'Tällä liitännäisellä on puuttuvia riippuvuuksia, joten sitä ei voida aktivoida. Tarkista riippuvuudet lisätiedoista.',
	'admin:plugins:warning:invalid' => 'Pluginissa on virhe: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Voit yrittää etsiä apua <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elggin dokumentaatiosta</a>.',
	'admin:plugins:cannot_activate' => 'ei voi aktivoida',

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
	'admin:statistics:label:basic' => "Sivuston perustilastot",
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

	'admin:appearance:menu_items' => 'Päänavigaatio',
	'admin:menu_items:configure' => 'Määrittele päänavigaation linkit',
	'admin:menu_items:description' => 'Valitse päänavigaatiossa näytettävät linkit. Käyttämättömät linkit sijoitetaan listan lopussa olevan "Lisää"-kohdan alle.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Navigaatiolinkki tallettiin.',
	'admin:add_menu_item' => 'Lisää navigaatiolinkki',
	'admin:add_menu_item:description' => 'Syötä kohteen nimi ja osoite lisätäksesi uuden linkin päänavigaatioon.',

	'admin:appearance:default_widgets' => 'Oletusvimpaimet',
	'admin:default_widgets:unknown_type' => 'Tuntematon vimpaintyyppi',
	'admin:default_widgets:instructions' => 'Lisää, poista, asettele ja konfiguroi oletusvimpaimet valitulle vimpainsivulle.
Nämä muutokset vaikuttavat vain sivuston uusiin jäseniin.',

	'admin:robots.txt:instructions' => "Muokkaa sivuston robots.txt-tiedostoa",
	'admin:robots.txt:plugins' => "Liitännäisten robots.txt-tiedostoon lisäämät säännöt:",
	'admin:robots.txt:subdir' => "Tämä robots.txt-työkalu ei toimi, koska Elgg on asennettu alihakemistoon",

	'admin:maintenance_mode:default_message' => 'Sivusto on väliaikaisesti poissa käytöstä huoltokatkoksen vuoksi',
	'admin:maintenance_mode:instructions' => 'Huoltotilaa tulisi käyttää päivitysten ja muiden laajojen muutosten aikana.
		Huoltotilan ollessa päällä, vain ylläpitäjät pääsevät kirjautumaan sivustolle.',
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

	'friends:widget:description' => "Näyttää listan ystävistäsi.",
	'friends:num_display' => "Näytettävien ystävien määrä",
	'friends:icon_size' => "Kuvakkeen koko",
	'friends:tiny' => "pikkuruinen",
	'friends:small' => "pieni",

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
	'fileexists' => "Jätä tämä tyhjäksi, jos haluat säilyttää nykyisen version.",

/**
 * User add
 */

	'useradd:subject' => 'Käyttäjätili luotu',
	'useradd:body' => '
%s,

Sinulle on luotu käyttäjätili sivustolla %s. Kirjautuaksesi sivustolle siirry osoitteeseen:

%s

Ja kirjaudu käyttäen näitä tietoja:

Tunnus: %s
Salasana: %s

Kirjauduttuasi on suositeltavaa, että vaihdat salasanasi.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "click to dismiss",


/**
 * Import / export
 */
		
	'importsuccess' => "Import of data was successful",
	'importfail' => "OpenDD import of data failed.",

/**
 * Time
 */

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

	'date:weekday:0' => 'Sunnuntai',
	'date:weekday:1' => 'Maanantai',
	'date:weekday:2' => 'Tiistai',
	'date:weekday:3' => 'Keskiviikko',
	'date:weekday:4' => 'Torstai',
	'date:weekday:5' => 'Perjantai',
	'date:weekday:6' => 'Lauantai',
	
	'interval:minute' => 'Minuutin välein',
	'interval:fiveminute' => 'Viiden minuutin välein',
	'interval:fifteenmin' => 'Viidentoista minuutin välein',
	'interval:halfhour' => 'Puolen tunnin välein',
	'interval:hourly' => 'Tunnin välein',
	'interval:daily' => 'Kerran päivässä',
	'interval:weekly' => 'Viikoittain',
	'interval:monthly' => 'Kuukausittain',
	'interval:yearly' => 'Vuosittain',
	'interval:reboot' => 'Uudelleenkäynnistyksen yhteydessä',

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

	'installation:httpslogin' => "Pakota käyttäjät kirjautumaan käyttäen salattua yhteyttä. Tämä vaatii, että palvelimellesi on konfiguroitu HTTPS.",
	'installation:httpslogin:label' => "Ota käyttöön HTTPS-kirjautuminen",
	'installation:view' => "Syötä näkymä, jota käytetään sivustosi etusivuna. Jätä kenttä tyhjäksi käyttääksesi oletusnäkymää (jos et ole varma, jätä tämä oletukseksi):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:default_limit' => "Listauksissa näytettävien kohteiden oletusmäärä",

	'admin:site:access:warning' => "Tämä määrittää oletusasetuksen, kun ollaan luomassa uutta sisältöä. Tämän asetuksen muuttaminen ei vaikuta jo olemassa oleviin sisältöihin.",
	'installation:allow_user_default_access:description' => "Jos tämä on valittuna, käyttäjät voivat määrittää oman oletuspääsytasonsa, joka yliajaa järjestelmän oletustason.",
	'installation:allow_user_default_access:label' => "Salli käyttäjille oma oletuspääsytaso",

	'installation:simplecache:description' => "Yksinkertainen välimuisti nopeuttaa sivustoa tallentamalla muistiin staattista sisältöä kuten CSS ja JavaScript-tiedostoja.",
	'installation:simplecache:label' => "Käytä yksinkertaista välimuistia (suositus)",

	'installation:minify:description' => "Välimuisti voi parantaa suorituskykyä myös pakkamalla JavaScript- and CSS-tiedostot. (Tämä vaatii, että välimuisti on käytössä.)",
	'installation:minify_js:label' => "Pakkaa JavaScript (suositus)",
	'installation:minify_css:label' => "Pakkaa CSS (suositus)",

	'installation:htaccess:needs_upgrade' => "Sivuston .htaccess-tiedosto pitää päivittää syöttämään polku __elgg_uri-nimiseen GET-parametriin. (Katso ohjeet tiedostosta install/config/htaccess.dist.)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg ei saa testattua rewrite-sääntöjä. Tarkista, että curl toimii oikein ja että palvelimelle ei ole määritetty IP-rajoituksia, jotka estävät localhost-yhteydet.",
	
	'installation:systemcache:description' => "Välimuisti vähentää liitännäisten latausaikaa tallentamalla muistiin niiden käytössä olevien näkymien sijainnit.",
	'installation:systemcache:label' => "Käytä välimuistia (suositus)",

	'admin:legend:system' => 'Järjestelmä',
	'admin:legend:caching' => 'Välimuisti',
	'admin:legend:content_access' => 'Pääsy sisältöihin',
	'admin:legend:site_access' => 'Pääsy sivustolle',
	'admin:legend:debug' => 'Lokit ja virheidenjäljitys',

	'upgrading' => 'Päivitetään...',
	'upgrade:db' => 'Tietokanta päivitetty.',
	'upgrade:core' => 'Elgg päivitetty.',
	'upgrade:unlock' => 'Avaa päivityksen lukitus',
	'upgrade:unlock:confirm' => "Aiemmin käynnistetty päivitys on lukinnut tietokannan. Useiden päivitysten ajaminen samanaikaisesti on vaarallista, joten avaa lukitus vain jos tiedät, että käynnissä ei ole päivitystä. Avataanko lukitus?",
	'upgrade:locked' => "Päivitystä ei voida aloittaa, koska käynnissä on jo toinen päivitys. Voit avata lukituksen hallintapaneelista.",
	'upgrade:unlock:success' => "Avattiin päivityksen lukitus.",
	'upgrade:unable_to_upgrade' => 'Päivityksen lukituksen avaaminen epäonnistui.',
	'upgrade:unable_to_upgrade_info' =>
		'Sivustoa ei voi päivittää, koska Elggin lähdekoodissa havaittiin vanhentuneita tiedostoja. Kyseiset tiedostot pitää poistaa, jotta Elgg toimii oikein.

Jos et ole tehnyt Elggin lähdekoodiin muutoksia, voit poistaa views-hakemiston kokonaan, ja korvata sen sitten Elggin uuden version mukana tulleella.

Tarvittaessa voit lukea lisäohjeita <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">päivitysdokumentaatiosta</a> tai pyytää apua <a href="http://community.elgg.org/groups/discussion/">tukifoorumilla</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (aiemmin "Twitter Service") otettiin pois käytöstä päivityksen aikana. Ota se käyttöön manuaalisesti, mikäli sille on tarvetta.',
	'update:oauth_api:deactivated' => 'OAuth API (aiemmin "OAuth Lib") otettiin pois käytöstä päivityksen aikana. Ota se käyttöön manuaalisesti, mikäli sille on tarvetta.',
	'upgrade:site_secret_warning:moderate' => "On suositeltavaa, että uusit sivuston salausavaimen parantaaksesi tietoturvaa. Lisätietoja löydät kohdasta: Asetukset > Lisäasetukset",
	'upgrade:site_secret_warning:weak' => "Sivustollasi on käytössä liian heikko salausavain, mikä saattaa heikentää tietoturvaa. Uudista salausavain kohdasta: Asetukset > Lisäasetukset",

	'deprecated:function' => '%s() on korvattu funktiolla %s()',

	'admin:pending_upgrades' => 'Sivustolla on odottavia päivityksiä, jotka vaativat välitöntä huomiotasi.',
	'admin:view_upgrades' => 'Siirry päivityksiin tästä.',
 	'admin:upgrades' => 'Päivitykset',
	'item:object:elgg_upgrade' => 'Sivuston päivitykset',
	'admin:upgrades:none' => 'Sivustosi on ajan tasalla!',

	'upgrade:item_count' => 'Sivustolla on <b>%s</b> kohdetta, jotka vaativat päivityksen.',
	'upgrade:warning' => '<b>Varoitus:</b> Tämä päivitys saattaa viedä huomattavasti aikaa.',
	'upgrade:success_count' => 'Päivitetyt kohteet:',
	'upgrade:error_count' => 'Virheet:',
	'upgrade:river_update_failed' => 'Ei voitu päivittää kohdetta (id %s) sivuston toimintalistaukseen',
	'upgrade:timestamp_update_failed' => 'Luontiajan päivittäminen kohteelle %s epäonnistui',
	'upgrade:finished' => 'Päivitys on valmis',
	'upgrade:finished_with_errors' => '<p>Päivityksen aikana ilmeni virheitä. Päivitä sivu ja yritä ajaa päivitys uudelleen.<br /></p><p>Jos virheet toistuvat, yritä selvittää niiden syy palvelimen virhelokeista. Tarvittaessa voit pyytää apua Elggin yhteisön <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">teknisen tuen ryhmästä</a>.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Kommenttien päivitys',
	'upgrade:comment:create_failed' => 'Kommentin %s päivittäminen epäonnistui.',
	'admin:upgrades:commentaccess' => 'Kommenttien lukuoikeuden päivitys',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Datahakemiston päivitys',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Keskustelujen päivitys',
	'discussion:upgrade:replies:create_failed' => 'Keskusteluviestin %s päivittäminen epäonnistui.',

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

	'email:save:success' => "Uusi sähköpostiosoite tallennettu.",
	'email:save:fail' => "Sähköpostiosoitteen vaihtaminen epäonnistui.",

	'friend:newfriend:subject' => "%s on tehnyt sinusta ystävänsä!",
	'friend:newfriend:body' => "%s on tehnyt sinusta ystävänsä!

Nähdäksesi hänen profiilinsa, siirry tänne:
%s

Tähän viestiin ei voi vastata.",

	'email:changepassword:subject' => "Salasana nollattu!",
	'email:changepassword:body' => "Hei %s,

Salasanasi on nollattu.

Uusi salasanasi on: %s",

	'email:resetpassword:subject' => "Salasana nollattu!",
	'email:resetpassword:body' => "Hei %s,

Salasanasi on nollattu.

Uusi salasanasi on: %s",

	'email:changereq:subject' => "Anomus salasanan vaihtamiseksi.",
	'email:changereq:body' => "Hei %s

Joku (IP-osoitteesta %s) on anonut uutta salasanaa tiliin, johon tämä sähköpostiosoite on liitetty.

Jos anoit uutta salasanaa, klikkaa alla olevaa linkkiä. Muussa tapauksessa voit jättää tämän viestin huomiotta.

%s
",

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

	'river:comment:object:default' => '%s kommentoi kohdetta %s',

	'generic_comments:add' => "Kommentoi",
	'generic_comments:edit' => "Muokkaa",
	'generic_comments:post' => "Lähetä kommentti",
	'generic_comments:text' => "Kommentti",
	'generic_comments:latest' => "Viimeisimmät kommentit",
	'generic_comment:posted' => "Kommentti lisätty.",
	'generic_comment:updated' => "Kommentti päivitetty.",
	'generic_comment:deleted' => "Kommentti poistettu.",
	'generic_comment:blank' => "Kommenttiin täytyy kirjoittaa jotain ennen kuin se voidaan tallentaa.",
	'generic_comment:notfound' => "Etsimääsi kommenttia ei löytynyt.",
	'generic_comment:notfound_fallback' => "Etsimääsi kommenttia ei löytynyt, mutta ohjasimme sinut sivulle, jonne kommentti oli jätetty.",
	'generic_comment:notdeleted' => "Kommentin poistaminen epäonnistui.",
	'generic_comment:failure' => "Kommentin tallentamisessa tapahtui odottamaton virhe.",
	'generic_comment:none' => 'Ei kommentteja',
	'generic_comment:title' => 'Kommentti käyttäjältä %s',
	'generic_comment:on' => '%s kohteessa %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:email:subject' => 'Sinulla on uusi kommentti!',
	'generic_comment:email:body' => "Kohdettasi \"%s\" on kommentoinut %s. Siinä sanotaan


%s


Vastataksesi tai nähdäksesi alkuperäisen viestin, klikkaa tätä:

%s

Nähdäksesi käyttäjän %s profiilin, klikkaa tätä:

%s

Tähän viestiin ei voi vastata.",

/**
 * Entities
 */
	
	'byline' => 'Käyttäjältä %s',
	'entity:default:strapline' => 'Created %s by %s',
	'entity:default:missingsupport:popup' => 'Tätä kohdetta ei voida näyttää oikein. Tämä voi johtua puuttuvasta liitännäisestä.',

	'entity:delete:success' => 'Kohde %s on poistettu',
	'entity:delete:fail' => 'Kohteen %s poistaminen epäonnistui',

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
	"pt_br" => 'Brazilian Portuguese',
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
