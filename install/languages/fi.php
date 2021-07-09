<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'install:title' => 'Elggin asennus',
	'install:welcome' => 'Tervetuloa',
	'install:requirements' => 'Vaatimusten tarkistaminen',
	'install:database' => 'Tietokannan asennus',
	'install:settings' => 'Sivuston asetukset',
	'install:admin' => 'Pääkäyttäjän tili',
	'install:complete' => 'Valmis',

	'install:next' => 'Seuraava',
	'install:refresh' => 'Edellinen',
	
	'install:requirements:instructions:success' => "Palvelimesi läpäisi kaikki testit.",
	'install:requirements:instructions:failure' => "Palvelimesi ei läpäissyt kaikkia testejä. Korjaa alla mainitut puutteet, ja päivitä sitten sivu. Sivun alalaidassa on linkkejä, joista voit saada apua ongelmien ratkomiseen.",
	'install:requirements:instructions:warning' => "Palvelimesi läpäisi testit, mutta testit antoivat ainakin yhden varoituksen. On suositeltavaa, että etsit lisätietoja sivun alalaidasta löytyvän Ongelmanratkaisu-linkin kautta.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Web-palvelin',
	'install:require:settings' => 'Asetustiedosto',
	'install:require:database' => 'Tietokanta',

	'install:check:php:version' => 'Elgg vaatii vähintään PHP:n version %s. Palvelimesi käyttää versiota %s.',
	'install:check:php:extension' => 'Elgg vaatii PHP:n laajennoksen: %s.',
	'install:check:php:extension:recommend' => 'PHP: laajennosta %s suositellaan asennettavaksi.',
	'install:check:php:open_basedir' => 'PHP:n open_basedir-asetus saattaa estää Elggiä tallentamasta tiedostoja datahakemistoonsa.',
	'install:check:php:safe_mode' => 'PHP:n ajaminen safe mode -tilassa saattaa aiheuttaa ongelmia, joten sitä ei suositella.',
	'install:check:php:arg_separator' => 'arg_separator.output-asetuksen arvo täytyy olla &, mutta palvelimesi käyttää arvoa %s',
	'install:check:php:register_globals' => 'Register globals -asetus täytyy olla pois päältä.',
	'install:check:php:session.auto_start' => "session.auto_start -asetus täytyy olla pois päältä. Muuta se palvelimesi asetustiedostoon tai lisää se Elggin .htaccess-tiedostoon.",
	'install:check:readsettings' => 'Elggin engine-hakemistossa on asetustiedosto, mutta web-palvelin ei voi lukea sitä. Voit joko poistaa tiedoston tai antaa web-palvelimelle oikeuden lukea se.',

	'install:check:php:success' => "Palvelimesi PHP vastaa kaikkia Elggin tarpeita.",
	'install:check:rewrite:success' => 'Testi pyyntöjen uudelleenohjauksesta onnistui.',
	'install:check:database' => 'Tietokannan vaatimukset tarkistetaan, kun Elgg lataa tietokantansa.',

	'install:database:instructions' => "Jos et ole vielä luonut tietokantaa Elggille, tee se nyt. Syötä tämän jälkeen pyydetyt tiedot.",
	'install:database:error' => 'Elggin tietokannan luomisessa tapahtui virhe, jonka vuoksi asennusta ei voida jatkaa. Lue oheinen viesti, ja korjaa mahdolliset virheet. Sivun alalaidassa on linkkejä, joista voit saada apua ongelmien ratkomiseen.',

	'install:database:label:dbuser' =>  'Tietokannan käyttäjätunnus',
	'install:database:label:dbpassword' => 'Tietokannan salasana',
	'install:database:label:dbname' => 'Tietokannan nimi',
	'install:database:label:dbhost' => 'Tietokannan sijainti',
	'install:database:label:dbprefix' => 'Tietokantataulujen etuliite',
	'install:database:label:timezone' => "Aikavyöhyke",

	'install:database:help:dbuser' => 'Käyttäjä, jolla on täydet oikeudet Elggiä varten luomaasi tietokantaan',
	'install:database:help:dbpassword' => 'Ylle syöttämäsi käyttäjätilin salasana',
	'install:database:help:dbname' => 'Elggiä varten luomasi tietokannan nimi',
	'install:database:help:dbhost' => 'MySQL-palvelimen sijainti (yleensä localhost)',
	'install:database:help:dbprefix' => "Kaikille Elggin tietokantatauluille annettava etuliite (yleensä elgg_)",
	'install:database:help:timezone' => "Aikavyöhyke, jossa sivustoa tullaan käyttämään.",

	'install:settings:label:sitename' => 'Sivuston nimi',
	'install:settings:label:siteemail' => 'Sivuston sähköpostiosoite',
	'install:database:label:wwwroot' => 'Sivuston URL-osoite',
	'install:settings:label:path' => 'Elggin asennushakemisto',
	'install:database:label:dataroot' => 'Datahakemisto',
	'install:settings:label:language' => 'Sivuston oletuskieli',
	'install:settings:label:siteaccess' => 'Sivuston sisältöjen oletuslukuoikeus',
	'install:label:combo:dataroot' => 'Elgg luo datahakemiston',

	'install:settings:help:sitename' => 'Elgg-sivustosi nimi',
	'install:settings:help:siteemail' => 'Sähköpostiosoite, jota käytetään lähettäjänä Elggistä lähetettävissä sähköposteissa',
	'install:database:help:wwwroot' => 'Sivuston osoite (Elgg arvaa tämän yleensä oikein)',
	'install:settings:help:path' => 'Hakemisto, johon sijoitit Elggin lähdekoodin (Elgg arvaa tämän yleensä oikein)',
	'install:database:help:dataroot' => 'Hakemisto, jonka loit Elggiin lisättäviä tiedostoja varten (hakemiston kirjoitusoikeudet tarkistetaan, kun siirryt seuraavaan vaiheeseen). Tämän täytyy olla absoluuttinen polku.',
	'install:settings:help:dataroot:apache' => 'Voit joko antaa Elggin luoda datahakemiston, tai voit syöttää hakemiston, jonka olet jo luonut (hakemiston kirjoitusoikeudet tarkistetaan, kun siirryt seuraavaan vaiheeseen)',
	'install:settings:help:language' => 'Sivuston käyttöliittymässä oletuksen käytettävä kieli',
	'install:settings:help:siteaccess' => 'Sivustolle luotaville sisällöille oletuksena annettava lukuoikeus',

	'install:admin:instructions' => "Nyt on aika luoda käyttäjätili sivuston ylläpitäjälle.",

	'install:admin:label:displayname' => 'Nimi',
	'install:admin:label:email' => 'Sähköpostiosoite',
	'install:admin:label:username' => 'Käyttäjätunnus',
	'install:admin:label:password1' => 'Salasana',
	'install:admin:label:password2' => 'Salasana uudelleen',

	'install:admin:help:displayname' => 'Nimi, jonka muut sivuston käyttäjät näkevät',
	'install:admin:help:username' => 'Käyttäjätunnus, jota käytetään kirjautumiseen',
	'install:admin:help:password1' => "Salasanan tulee olla vähintään %u merkkiä pitkä",
	'install:admin:help:password2' => 'Kirjoita salasana uudelleen varmistaaksesi, että siihen ei tullut kirjoitusvirhettä',

	'install:admin:password:mismatch' => 'Salasanat eivät täsmää.',
	'install:admin:password:empty' => 'Salasana ei voi olla tyhjä.',
	'install:admin:password:tooshort' => 'Syöttämäsi salasana oli liian lyhyt',
	'install:admin:cannot_create' => 'Käyttäjätilin luominen epäonnitui.',

	'install:complete:instructions' => 'Elgg-sivustosi on nyt käyttövalmis. Napsauta alla olevaa painiketta siirtyäksesi sivustolle.',
	'install:complete:gotosite' => 'Mene sivustolle',

	'InstallationException:CannotLoadSettings' => 'Elgg ei voinut ladata asetustiedostoa. Tiedosto joko puuttuu, tai sillä on väärät tiedosto-oikeudet.',

	'install:success:database' => 'Tietokanta on asennettu.',
	'install:success:settings' => 'Sivuston asetukset on tallennettu.',
	'install:success:admin' => 'Pääkäyttäjän tili on luotu.',

	'install:error:htaccess' => 'Ei voida luoda .htaccess-tiedostoa',
	'install:error:settings' => 'Ei voida luoda asetustiedostoa',
	'install:error:databasesettings' => 'Tietokantaan yhdistäminen ei onnistunut annetuilla tiedoilla.',
	'install:error:database_prefix' => 'Tietokantataulujen etuliite sisältää virheellisiä merkkejä',
	'install:error:oldmysql2' => 'MySQL-versio pitää olla vähintään 5.5.3. Palvelimesi käyttää versiota %s.',
	'install:error:nodatabase' => 'Tietokantaan %s ei saada yhteyttä.',
	'install:error:cannotloadtables' => 'Tietokantataulujen lataaminen ei onnistu',
	'install:error:tables_exist' => 'Tietokannassa on jo olemassa Elggin tauluja. Sinun pitää joko poistaa taulut tai aloittaa asennus uudelleen, jolloin Elgg voi yrittää ottaa taulut käyttöön. Aloittaaksesi asennuksen uudelleen, poista \'?step=database\' selaimesi osoiteriviltä ja siirry kyseiseen osoitteeseen.',
	'install:error:readsettingsphp' => 'Tiedoston /elgg-config/settings.example.php lukeminen epäonnistui',
	'install:error:writesettingphp' => 'Tiedostoon /elgg-config/settings.php kirjoittaminen epäonnistui',
	'install:error:requiredfield' => '%s on pakollinen kenttä',
	'install:error:relative_path' => 'Datahakemistolle syöttämäsi sijainti "%s" ei ole absoluuttinen polku',
	'install:error:datadirectoryexists' => 'Datahakemistoa %s ei ole olemassa.',
	'install:error:writedatadirectory' => 'Web-palvelimellasi ei ole kirjoitusoikeuksia hakemistoon %s.',
	'install:error:locationdatadirectory' => 'Syötit datahakemiston sijainniksi %s. Tietoturvan vuoksi hakemisto ei saa olla Elggin asennushakemiston alla.',
	'install:error:emailaddress' => '%s on virheellinen sähköpostiosoite',
	'install:error:createsite' => 'Sivuston luominen epäonnistui.',
	'install:error:savesitesettings' => 'Sivuston asetusten tallentaminen epäonnistui',
	'install:error:loadadmin' => 'Pääkäyttäjän luominen epäonnistui.',
	'install:error:adminaccess' => 'Käyttäjätilille ei voitu antaa pääkäyttäjän oikeuksia.',
	'install:error:adminlogin' => 'Automaattinen kirjautuminen pääkäyttäjätiliin epäonnistui.',
	'install:error:rewrite:apache' => 'Vaikuttaa siltä, että palvelimellasi on käytössä Apache web-palvelin.',
	'install:error:rewrite:nginx' => 'Vaikuttaa siltä, että palvelimellasi on käytössä Nginx web-palvelin.',
	'install:error:rewrite:lighttpd' => 'Vaikuttaa siltä, että palvelimellasi on käytössä Lighttpd web-palvelin.',
	'install:error:rewrite:iis' => 'Vaikuttaa siltä, että palvelimellasi on käytössä IIS web-palvelin.',
	'install:error:rewrite:htaccess:write_permission' => 'Web-palvelimella ei ole oikeutta luoda .htaccess-tiedostoa Elggin juurihakemistoon. Joko tiedosto install/config/htaccess.dist pitää kopioida manuaalisesti Elggin juureen ja nimetä muotoon .htaccess, tai web-palvelimelle pitää myöntää kirjoitusoikeus juurihakemistoon.',
	'install:error:rewrite:htaccess:read_permission' => 'Elggin asennushakemistossa on .htaccess-tiedosto, mutta web-palvelimellasi ei ole siihen lukuoikeuksia.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Elggin asennushakemistossa on ylimääräinen Elggiin liittymätön .htaccess-tiedosto, joka pitää poistaa.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Elggin asennushakemistossa on vanhentunut .htacess-tiedosto. Se ei sisällä polkujen uudelleenohjauksen testaamiseen vaadittavia määrityksiä.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Tuntematon virhe esti luomasta .htaccess-tiedostoa. Tiedosto install/config/htaccess.dist pitää kopioida manuaalisesti Elggin juureen ja nimetä muotoon .htaccess.',
	'install:error:rewrite:altserver' => 'Polkujen uudelleenohjauksen testaaminen epäonnistui. Sinun pitää konfiguroida palvelimellesi Elggin vaatimat uudelleenohjaukseen liittyvät säännöt.',
	'install:error:rewrite:unknown' => 'Polkujen uudelleenohjauksen testaaminen epäonnistui. Emme saaneet selvitettyä käyttämääsi web-palvelinta, joten emme pysty tarjoamaan ratkaisua ongelmaan. Voit yrittää etsiä apua sivun alalaidasta löytyvien linkkien kautta.',
	'install:warning:rewrite:unknown' => 'Palvelimesi ei tue polkujen uudelleenohjaamisen automaattista testaamista, ja selaimesi ei tue sen testaamista JavaScriptin avulla. You can continue the installation, but you may experience problems with your site. Voit testata uudelleenohjausta tästä linkistä: <a href="%s" target="_blank">Testaa</a>.',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Tapahtui virhe. Jos olet sivuston ylläpitäjä, tarkista asetustiedosto. Muussa tapauksessa ota yhteys sivuston ylläpitoon, ja toimita oheiset tiedot:',
	'DatabaseException:WrongCredentials' => "Elgg ei saanut yhteyttä tietokantaan. Tarkista asetustiedosto.",
);
