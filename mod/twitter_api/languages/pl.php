<?php
return array(
	'twitter_api' => 'Usługi Twittera',

	'twitter_api:requires_oauth' => 'Usługi Twittera wymagają włączonego pluginu dostarczającego bibliotekę OAuth.',

	'twitter_api:consumer_key' => 'Klucz konsumenta',
	'twitter_api:consumer_secret' => 'Sekretny klucz konsumenta',

	'twitter_api:settings:instructions' => 'Musisz uzyskać klucz (ang. consumer key) na stronie <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Wypełnij zgłoszenie nowej aplikacji. Wybierz "Browser" jako typ aplikacji oraz "Read & Write" jako rodzaj dostępu. Url wywołania zwrotnego (ang. callback) to %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Połącz swoje %s konto z Twitterem.",
	'twitter_api:usersettings:request' => "Musisz się najpierw <a href=\"%s\">uwierzytelnić</a> %s aby mieć dostęp to konta na Twitterze.",
	'twitter_api:usersettings:cannot_revoke' => "Nie można rozłączyć twojego konta na Twitterze, ponieważ nie wprowadziłeś adresu e-mail ani hasła. <a href=\"%s\">Wprowadź je teraz</a>.",
	'twitter_api:authorize:error' => 'Nie powiodła się aautoryzacja na Twitterze.',
	'twitter_api:authorize:success' => 'Autoryzowano dostęp do Twittera.',

	'twitter_api:usersettings:authorized' => "Autoryzowałeś dostęp %s do twojego konta na Twitterze: @%s.",
	'twitter_api:usersettings:revoke' => 'Kliknij <a href="%s">tutaj</a> aby cofnąć dostęp.',
	'twitter_api:usersettings:site_not_configured' => 'Administrator musi skonfigurować Twittera, zanim będzie można go użyć.',

	'twitter_api:revoke:success' => 'Cofnięto dostęp do Twittera.',

	'twitter_api:post_to_twitter' => "Wysyłać  wpisy mikrobloga na Twitter'a?",

	'twitter_api:login' => 'Zezwolić użytkownikom na logowanie poprzez Twitter\'a?',
	'twitter_api:new_users' => 'Czy pozwolić na rejestrację przy użyciu kont na Twitterze, nawet gdy rejestracja jest wyłączona?',
	'twitter_api:login:success' => 'Zostałeś zalogowany.',
	'twitter_api:login:error' => 'Nie powiodło się logowanie na Twitterze.',
	'twitter_api:login:email' => "Musisz podać poprawny adres e-mail do twojego nowego %s konta.",

	'twitter_api:invalid_page' => 'Błędna strona',

	'twitter_api:deprecated_callback_url' => 'Adres zwrotny URL został zmieniony w API Twittera na %s. Poproś administratora o zmianę.',

	'twitter_api:interstitial:settings' => 'Zmień swoje ustawienia',
	'twitter_api:interstitial:description' => 'Już prawie wszystko gotowe aby używać %s! Potrzebujemy jeszcze tylko kilku dodatkowych informacji. Są one opcjonalne, ale pozwolą na logowanie się w przypadku jeśli Twitter będzie niedostępny lub zdecydujesz się rozłączyć konta.',

	'twitter_api:interstitial:username' => 'To twój login. Nie może zostać zmieniony. Jeśli ustawisz hasło, będziesz mógł użyć loginu lub adresu e-mail aby się zalogować.',

	'twitter_api:interstitial:name' => 'To jest nazwa, którą inne osoby zobaczą wchodząc z Tobą w interakcje.',

	'twitter_api:interstitial:email' => 'Twój adres e-mail. Inni użytkownicy domyślnie go nie zobaczą.',

	'twitter_api:interstitial:password' => 'Hasło na wypadek niedostępności Twittera lub rozłączenia kont.',
	'twitter_api:interstitial:password2' => 'To samo hasło, ponownie.',

	'twitter_api:interstitial:no_thanks' => 'Nie, dziękuję',

	'twitter_api:interstitial:no_display_name' => 'Musisz posiadać nazwę do wyświetlania.',
	'twitter_api:interstitial:invalid_email' => 'Musisz podać poprawny adres e-mail lub pozostawić to pole pustym.',
	'twitter_api:interstitial:existing_email' => 'Ten adres e-mail jest już zarejestrowany na tej stronie.',
	'twitter_api:interstitial:password_mismatch' => 'Twoje hasła do siebie nie pasują.',
	'twitter_api:interstitial:cannot_save' => 'Nie udało się zapisać szczegółów konta.',
	'twitter_api:interstitial:saved' => 'Szczegóły konta zostały zapisane!',
);
