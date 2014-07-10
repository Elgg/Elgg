<?php
return array(
	'twitter_api' => 'Twitter-kirjautuminen',

	'twitter_api:requires_oauth' => 'Tämä liitännäinen vaatii, että OAuth-kirjastoliitännäinen on käytössä.',

	'twitter_api:consumer_key' => 'Asiakasavain',
	'twitter_api:consumer_secret' => 'Asiakassalasana',

	'twitter_api:settings:instructions' => 'Sinun pitää hankkia asiakasavain ja -salasana osoitteesta <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Luo uusi sovellus, valitse sovellustyypiksi "Selain" ja anna oikeuksiksi "Lue ja kirjoita". Yhteysosoite on %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Yhdistää %s-tilisi Twitteriin.",
	'twitter_api:usersettings:request' => "Käyttääksesi toimintoa, <a href=\"%s\">anna lupa</a> sivustolle %s päästä käsiksi Twitter-tiliisi.",
	'twitter_api:usersettings:cannot_revoke' => "Et voi poistaa yhteyttä Twitter-tiliin, koska et ole syöttänyt säköpostiosoitetta tai salasanaa. <a href=\"%s\">Lisää ne nyt</a>.",
	'twitter_api:authorize:error' => 'Twitteriin yhdistäminen epäonnistui.',
	'twitter_api:authorize:success' => 'Yhteys Twitteriin on muodostettu.',

	'twitter_api:usersettings:authorized' => "Olet sallinut %s-sivustolle pääsyn Twitter-tiliisi: @%s.",
	'twitter_api:usersettings:revoke' => 'Klikkaa <a href="%s">tästä</a> peruaksesi yhteyden.',
	'twitter_api:usersettings:site_not_configured' => 'Sivuston ylläpitäjän pitää syöttää Twitteriin liittyvät asetukset ennen kuin sitä voidaan käyttää.',

	'twitter_api:revoke:success' => 'Twitter-yhteys peruttu.',

	'twitter_api:post_to_twitter' => "Lähetä tilapäivitykset Twitteriin?",

	'twitter_api:login' => 'Salli kirjautuminen Twitter-tilin avulla?',
	'twitter_api:new_users' => 'Salli uusien käyttäjien rekisteröityä Twitter-tilin avulla vaikka rekisteröityminen olisi otettu pois käytöstä?',
	'twitter_api:login:success' => 'Olet kirjautunut sisään.',
	'twitter_api:login:error' => 'Twitterin avulla kirjautuminen epäonnistui.',
	'twitter_api:login:email' => "Syötä sähköpostiosoite uudelle %s-tilillesi.",

	'twitter_api:invalid_page' => 'Virhellinen sivu',

	'twitter_api:deprecated_callback_url' => 'Twitterin yhteysosoite €s on muuttunut. Pyydä sivuston ylläpitäjää vaihtamaan osoite.',

	'twitter_api:interstitial:settings' => 'Määrittele asetuksesi',
	'twitter_api:interstitial:description' => 'Olet lähes valmis käyttämään %s-sivustoa! Syötä vielä nämä lisätiedot ennen kuin jatkat. Nämä ovat vapaaehtoisia, mutta niiden avulla voit kirjautua, jos Twitteriin ei saada yhteyttä, tai jos päätät poistaa yhteyden käyttäjätiliesi väliltä.',

	'twitter_api:interstitial:username' => 'Tämä on käyttäjätunnuksesi, jota et voi enää myöhemmin vaihtaa. Jos syötät salasanan, voit käyttää käyttäjätunnustasi tai sähköpostiosoitettasi kirjautumiseen.',

	'twitter_api:interstitial:name' => 'Tämä on nimi, jonka muut sivuston jäsenet näkevät.',

	'twitter_api:interstitial:email' => 'Sähköpostiosoitteesi. Tämä ei näy oletuksena muille käyttäjille.',

	'twitter_api:interstitial:password' => 'Salasana, jonka avulla kirjaudut jos Twitteriin ei saada yhteyttä, tai jos päätät poistaa yhteyden käyttäjätiliesi väliltä.',
	'twitter_api:interstitial:password2' => 'Salasana uudelleen.',

	'twitter_api:interstitial:no_thanks' => 'Ei kiitos',

	'twitter_api:interstitial:no_display_name' => 'Sinun pitää syöttää nimesi.',
	'twitter_api:interstitial:invalid_email' => 'Sinun pitää syöttää kelvollinen salasana tai jättää kenttä tyhjäksi.',
	'twitter_api:interstitial:existing_email' => 'Tämä sähköpostiosoite on jo rekisteröity tällä sivustolla.',
	'twitter_api:interstitial:password_mismatch' => 'Salasanat eivät täsmää.',
	'twitter_api:interstitial:cannot_save' => 'Tietojen tallentaminen epäonnistui.',
	'twitter_api:interstitial:saved' => 'Tiedot tallennettu!',
);
