<?php
return array(
	'twitter_api' => 'Services Twitter',

	'twitter_api:requires_oauth' => 'Les Services Twitter nécessitent l\'activation du plugin des bibliothèques OAuth pour pouvoir être activés.',

	'twitter_api:consumer_key' => 'Clé client',
	'twitter_api:consumer_secret' => 'Secret du client',

	'twitter_api:settings:instructions' => 'Vous devez obtenir une clé client et le code secret à partir de <a href="https://twitter.com/oauth_clients" target="_blank">Twitter</a>Twitter</a>. Complétez le formulaire de création d\'une nouvelle application. Sélectionnez "Navigateur" ("Browser") comme type d\'application et "Lecture et Ecriture" ("Read and Write") comme type d\'accès. L\'URL de callback est %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Lier votre compte %s avec Twitter.",
	'twitter_api:usersettings:request' => "Vous devez d'abord <a href=\"%s\">autoriser</a> %s à accéder à votre compte Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Vous ne pouvez pas enlever le lien entre votre compte et Twitter parce que vous n'avez pas fournit d'adresse mail ou de mot de passe. <a href=\"%s\">Indiquez-les maintenant</a>.",
	'twitter_api:authorize:error' => 'Impossible d\'autoriser Twitter.',
	'twitter_api:authorize:success' => 'L\'accès à Twitter a été autorisé.',

	'twitter_api:usersettings:authorized' => "Vous avez autorisé %s à accéder à votre compte Twitter : @%s.",
	'twitter_api:usersettings:revoke' => '<a href="%s">Cliquez ici</a> pour révoquer l\'accès.',
	'twitter_api:usersettings:site_not_configured' => 'Un administrateur doit d\'abord configurer Twitter avant qu\'il puisse être utilisé.',

	'twitter_api:revoke:success' => 'L\'accès à Twitter a été révoqué.',

	'twitter_api:post_to_twitter' => "Envoyer les messages du Fil des utilisateurs vers Twitter?",

	'twitter_api:login' => 'Permettre aux utilisateurs de connecter avec Twitter?',
	'twitter_api:new_users' => 'Permettre aux nouveaux utilisateurs de s\'enregistrer en utilisant leur compte Twitter, même si l\'enregistrement manuel est désactivé ?',
	'twitter_api:login:success' => 'Connexion réussie.',
	'twitter_api:login:error' => 'Impossible de se connecter avec Twitter.',
	'twitter_api:login:email' => "Vous devez entrer une adresse email valide pour votre nouveau compte %s.",

	'twitter_api:invalid_page' => 'Page invalide',

	'twitter_api:deprecated_callback_url' => 'L\'URL de callback de l\'API Twitter a été modifiée pour %s. Merci de demander à votre administrateur de la modifier.',

	'twitter_api:interstitial:settings' => 'Configurez vos paramètres',
	'twitter_api:interstitial:description' => 'Vous êtes presque prêt à utiliser %s ! Nous avons besoin de quelques informations supplémentaires avant que vous pussiez continuer. Elles sont facultatives, mais elles vous permettront de vous connecter si Twitter ne fonctionne pas ou si vous décidez de rompre le lien avec votre compte Twitter.',

	'twitter_api:interstitial:username' => 'Votre identifiant. Il ne peut être changé. Si vous définissez un mot de passe, vous pourrez utiliser votre identifiant ou votre adresse mail pour vous connecter.',

	'twitter_api:interstitial:name' => 'Le nom que les autres membres verront lorsqu\'ils interagiront avec vous.',

	'twitter_api:interstitial:email' => 'Votre adresse email. Par défaut les autres membres ne peuvent pas la voir.',

	'twitter_api:interstitial:password' => 'Un mot de passe pour se connecter si Twitter ne fonctionne pas ou si vous décidez de rompre le lien avec votre compte Twitter.',
	'twitter_api:interstitial:password2' => 'Le même mot de passe (confirmation)',

	'twitter_api:interstitial:no_thanks' => 'Non merci',

	'twitter_api:interstitial:no_display_name' => 'Vous devez avoir un nom à afficher.',
	'twitter_api:interstitial:invalid_email' => 'Vous devez entrer une adresse mail valide ou rien.',
	'twitter_api:interstitial:existing_email' => 'Cette adresse mail est déjà enregistrée sur le site.',
	'twitter_api:interstitial:password_mismatch' => 'Vos mots de passe ne correspondent pas.',
	'twitter_api:interstitial:cannot_save' => 'Impossible d\'enregistrer les informations du compte.',
	'twitter_api:interstitial:saved' => 'Informations du compte enregistrées !',
);
