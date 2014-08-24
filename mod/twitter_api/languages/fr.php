<?php
return array(
	'twitter_api' => 'Services Twitter',

	'twitter_api:requires_oauth' => 'Twitter Services nécessitent les bibliothèques OAuth plugin pour être activés.',

	'twitter_api:consumer_key' => 'Clé client',
	'twitter_api:consumer_secret' => 'Secret du client',

	'twitter_api:settings:instructions' => 'Vous devez obtenir une clé client et le code secret à partir de <a href="https://twitter.com/oauth_clients" target="_blank">Twitter</a> . La plupart des champs sont explicites, la principale donnée dont vous aurez besoin est l\'url de retour qui prend la forme http://[VotreSite]/action/twitterlogin/return - [VotreSite] est l\'url de votre réseau Elgg.',

	'twitter_api:usersettings:description' => "Lier votre compte %s avec Twitter.",
	'twitter_api:usersettings:request' => "Vous devez d'abord <a href=\"%s\">autoriser</a> %s pour accéder à votre compte Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Vous ne pouvez pas enlever le lien entre votre compte et Twitter parce que vous n'avez pas fournit d'adresse mail ou de mot de passe. <a href=\"%s\">Donnez les maintenant</a>.",
	'twitter_api:authorize:error' => 'Impossible d\'autoriser Twitter.',
	'twitter_api:authorize:success' => 'L\'accès à Twitter a été autorisé.',

	'twitter_api:usersettings:authorized' => "Vous avez autorisé %s à accéder à votre compte Twitter : @%s.",
	'twitter_api:usersettings:revoke' => 'Cliquez <a href="%s">ici</a> pour révoquer l\'accès.',
	'twitter_api:usersettings:site_not_configured' => 'Un administrateur doit d\'abord configurer Twitter avant qu\'il puisse être utiliser.',

	'twitter_api:revoke:success' => 'L\'accès à Twitter a été révoqué.',

	'twitter_api:post_to_twitter' => "Envoyer les messages du microblog des utilisateurs à Twitter?",

	'twitter_api:login' => 'Permettre aux utilisateurs de connecter avec Twitter?',
	'twitter_api:new_users' => 'Permet aux nouveaux utilisateurs de s\'inscrire en utilisant leur compte Twitter, même si l\'enregistrement manuel est désactivé ?',
	'twitter_api:login:success' => 'Vous êtes connecté',
	'twitter_api:login:error' => 'Impossible de se connecter à Twitter.',
	'twitter_api:login:email' => "Vous devez entrer une adresse email valide pour votre nouveau compte %s.",

	'twitter_api:invalid_page' => 'Page invalide',

	'twitter_api:deprecated_callback_url' => 'L\'URL de retour de l\'API Twitter est modifié comme suit %s. Merci de demandez à votre administrateur de la changer.',

	'twitter_api:interstitial:settings' => 'Configurer vos paramètres',
	'twitter_api:interstitial:description' => 'Vous êtes presque prêt à utiliser %s ! Nous avons besoin de quelques détails supplémentaires avant que vous pussiez continuer. Ils sont facultatifs, mais ils vous permettrons de vous connecter si Twitter ne fonctionne pas ou si vous décidez de rompre le lien des comptes.',

	'twitter_api:interstitial:username' => 'Voici votre nom utilisateur (login). Il ne peut être changé. Si vous donnez un mot de passe, vous pouvez utiliser le nom d\'utilisateur ou votre adresse mail pour vous connecter.',

	'twitter_api:interstitial:name' => 'Voici le nom public que vous verrez quand on interagira avec vous.',

	'twitter_api:interstitial:email' => 'Votre adresse mail. Les utilisateurs ne peuvent la voir par défaut.',

	'twitter_api:interstitial:password' => 'Une mot de passe pour se connecter si Twitter ne fonctionne pas ou si vous décidez de rompre le lien des comptes.',
	'twitter_api:interstitial:password2' => 'Même mot de passe à nouveau.',

	'twitter_api:interstitial:no_thanks' => 'Non merci',

	'twitter_api:interstitial:no_display_name' => 'Vous devez avoir un nom à afficher.',
	'twitter_api:interstitial:invalid_email' => 'Vous devez entrer une adresse mail valide ou rien.',
	'twitter_api:interstitial:existing_email' => 'Cette adresse mail est déjà enregistrée sur le site.',
	'twitter_api:interstitial:password_mismatch' => 'Vos mots de passe ne sont pas les mêmes.',
	'twitter_api:interstitial:cannot_save' => 'Impossible de  sauvegarder les détails du compte.',
	'twitter_api:interstitial:saved' => 'Détails du compte sauvegardés !',
);
