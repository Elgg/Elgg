<?php
return array(
	'twitter_api' => 'Služby Twitter',

	'twitter_api:requires_oauth' => 'Služby Twitter vyžadují aktivovaný doplněk OAuth Libraries.',

	'twitter_api:consumer_key' => 'Zákaznický klíč',
	'twitter_api:consumer_secret' => 'Zákaznické heslo',

	'twitter_api:settings:instructions' => 'Musíte získat zákaznický klíč a heslo z <a href="https://dev.twitter.com/apps/new" target="_blank">Twitteru</a>. Vyplňte novou app aplikaci. Zvolte "Browser" jako typ aplikace a "Read & Write" pro typ přístupu. Callback url je %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Propojí váš %s účet se službou Twitter.",
	'twitter_api:usersettings:request' => "Nejprve musíte <a href=\"%s\">oprávnit</a> %s k přístupu k vašemu Twitter účtu.",
	'twitter_api:usersettings:cannot_revoke' => "Nemůžete rozpojit váš účet se službou Twitter, protože jste nezadali e-mailovou adresu nebo heslo. <a href=\"%s\">Zadejte je nyní</a>.",
	'twitter_api:authorize:error' => 'Ke službě Twitter se není možné přihlásit.',
	'twitter_api:authorize:success' => 'Přístup ke službě Twitter byl povolen.',

	'twitter_api:usersettings:authorized' => "Oprávnil/a jste %s k přístupu k vašemu učtu služby Twitter: @%s.",
	'twitter_api:usersettings:revoke' => 'Klikněte <a href="%s">zde</a> k odebrání přístupu.',
	'twitter_api:usersettings:site_not_configured' => 'Než budete moci využít službu Twitter, správce ji musí nejprve nastavit.',

	'twitter_api:revoke:success' => 'Přístup ke službě Twitter byl odebrán.',

	'twitter_api:post_to_twitter' => "Posílat telegrafní příspěvky uživatele na Twitter?",

	'twitter_api:login' => 'Povolit uživatelům přihlásit se přes Twitter?',
	'twitter_api:new_users' => 'Povolit uživatelům registraci přes účet služby Twitter i když je registrace uživatelů zakázána?',
	'twitter_api:login:success' => 'Byl/a jste přihlášen/a.',
	'twitter_api:login:error' => 'Účtem služby Twitter se není možné přihlásit.',
	'twitter_api:login:email' => "Musíte zadat platnou e-mailovou adresu pro váš nový %s účet.",

	'twitter_api:invalid_page' => 'Neplatná stránka',

	'twitter_api:deprecated_callback_url' => 'Callback URL pro Twitter API se změnila na %s. Požádejte prosím správce o její změnu.',

	'twitter_api:interstitial:settings' => 'Změnit nastavení',
	'twitter_api:interstitial:description' => 'Již jste téměř připraven/a používat %s! Než budete pokračovat, potřebujeme několik dalších detailů. Jsou nepovinné, ale dovolí vám přihlásit se pokud bude Twitter mimo provoz nebo se rozhodnete rozpojit vaše účty.',

	'twitter_api:interstitial:username' => 'Toto je vaše uživatelské jméno. Není možné ho měnit. Pokud si nastavíte heslo, můžete použít uživatelské jméno nebo e-mailovou adresu pro přihlášení.',

	'twitter_api:interstitial:name' => 'Toto je jméno, pod kterým vás ostatní uvidí při vzájemné komunikaci.',

	'twitter_api:interstitial:email' => 'Vaše e-mailová adresa. Uživatelé ji ve výchozím stavu neuvidí.',

	'twitter_api:interstitial:password' => 'Heslo k přihlášení pro případ výpadku Twitteru nebo když se rozhodnete rozpojit účty.',
	'twitter_api:interstitial:password2' => 'Stejné heslo znovu.',

	'twitter_api:interstitial:no_thanks' => 'Ne, díky',

	'twitter_api:interstitial:no_display_name' => 'Musíte mít zobrazované jméno.',
	'twitter_api:interstitial:invalid_email' => 'Musíte zadat platnou e-mailovou adresu nebo nic.',
	'twitter_api:interstitial:existing_email' => 'Tento e-mail je již na těchto stránkách zaregistrován.',
	'twitter_api:interstitial:password_mismatch' => 'Hesla se neshodují.',
	'twitter_api:interstitial:cannot_save' => 'Nemohu uložit data účtu.',
	'twitter_api:interstitial:saved' => 'Data účtu byla uložena!',
);
