<?php
return array(
	'install:title' => 'Installation d\'Elgg',
	'install:welcome' => 'Bienvenue',
	'install:requirements' => 'Vérification des pré-requis techniques',
	'install:database' => 'Installation de la base de données',
	'install:settings' => 'Configuration du site',
	'install:admin' => 'Création d\'un compte administrateur',
	'install:complete' => 'Terminé',

	'install:next' => 'Suivant',
	'install:refresh' => 'Rafraîchir',

	'install:welcome:instructions' => "L'installation d'Elgg comporte 6 étapes simples et commence par la lecture de cette page de bienvenue !

Si vous ne l'avez pas déjà fait, lisez les instructions d'installation qui font parties d'Elgg (ou cliquez sur le lien instructions au bas de la page).

Si vous êtes prêt à commencer, cliquez sur le bouton Suivant.",
	'install:requirements:instructions:success' => "Votre serveur a passé la vérification des pré-requis techniques.",
	'install:requirements:instructions:failure' => "Votre serveur n'a pas passé la vérification des pré-requis techniques. Après avoir résolu les points ci-dessous , actualisez cette page. Consultez les liens de dépannage au bas de cette page si vous avez besoin d'aide supplémentaire.",
	'install:requirements:instructions:warning' => "Votre serveur a passé la vérification des pré-requis techniques, mais il y a encore au moins un avertissement. Nous vous recommandons de consulter la page dépannage de l'installation pour plus de détails.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Serveur web',
	'install:require:settings' => 'Fichier paramètres',
	'install:require:database' => 'Base de données',

	'install:check:root' => 'Votre serveur web n\'a pas la permission de créer le fichier ".htaccess" dans le répertoire racine d\'Elgg. Vous avez deux choix : 

		1. Changer les permissions du répertoire racine

		2. Copier le fichier ".htaccess_dist" en le renommant ".htaccess"',

	'install:check:php:version' => 'Elgg a besoin de la version %s ou supérieure de PHP . Ce serveur utilise la version %s.',
	'install:check:php:extension' => 'Elgg a besoin de la version %s de l\'extension PHP.',
	'install:check:php:extension:recommend' => 'Il est recommandé que l\'extension PHP %s soit installée.',
	'install:check:php:open_basedir' => 'La directive "open_basedir" de PHP peut empêcher Elgg d\'enregistrer des fichiers dans son répertoire de données.',
	'install:check:php:safe_mode' => 'Exécuter PHP en mode sans échec n\'est pas conseillé et peut causer des problèmes avec Elgg.',
	'install:check:php:arg_separator' => 'Pour fonctionner, l\'option arg_separator.output doit être fixée à "&", alors que la valeur actuelle sur votre serveur est %s',
	'install:check:php:register_globals' => 'L\'option "Register globals" doit être mise à "off".',
	'install:check:php:session.auto_start' => "Pour fonctionner l'option session.auto_start doit être mise à \"off\". Vous devez soit modifier la configuration de votre serveur ou ajouter cette directive au fichier \".htaccess\" d'Elgg.",

	'install:check:enginedir' => 'Votre serveur web n\'a pas la permission de créer le fichier "settings.php" (paramètres) dans le répertoire "engine" (moteur) d\'Elgg. Vous avez deux choix : 

		1. Changer les permissions du répertoire "engine"

		2. Copier le fichier "settings.example.php" en le renommant "settings.php" et suivre les instructions contenues à l\'intérieur de ce fichier afin de définir les paramètres de la base de données.',
	'install:check:readsettings' => 'Un fichier de paramètres existe dans le répertoire "engine" (moteur), mais le serveur web ne peut pas le lire. Vous pouvez supprimer le fichier ou modifier les autorisations de lecture sur lui.',

	'install:check:php:success' => "Votre serveur PHP remplit tous les pré-requis techniques d'Elgg.",
	'install:check:rewrite:success' => 'Le test des règles de réécriture a été passé avec succès.',
	'install:check:database' => 'Les pré-requis techniques de la base de données sont vérifiés au moment où Elgg utilise la base de données.',

	'install:database:instructions' => "Si vous n'avez pas déjà créé une base de données pour Elgg, le faire maintenant. Ensuite, remplissez les valeurs ci-dessous pour initialiser la base de données d'Elgg.",
	'install:database:error' => 'Il y a eu une erreur pendant la création de la base de données Elgg et l\'installation ne peut pas continuer. Lisez le message ci-dessus et corrigez le ou les problèmes. Si vous avez besoin de plus d\'aide, visitez le lien ci-dessous concernant le dépannage de l\'installation ou postez un message sur les forums de la communauté Elgg.',

	'install:database:label:dbuser' =>  'Nom d\'utilisateur de la base de données',
	'install:database:label:dbpassword' => 'Mot de passe de la base de données',
	'install:database:label:dbname' => 'Nom de la base de données',
	'install:database:label:dbhost' => 'Nom du serveur de la base de données',
	'install:database:label:dbprefix' => 'Préfixe des tables de la base de données',

	'install:database:help:dbuser' => 'Un utilisateur qui a tous les privilèges sur la base de données MySQL que vous avez créée pour Elgg',
	'install:database:help:dbpassword' => 'Mot de passe du compte de l\'utilisateur de la base de données ci-dessus',
	'install:database:help:dbname' => 'Nom de la base de données d\'Elgg',
	'install:database:help:dbhost' => 'Nom du serveur MySQL (habituellement localhost)',
	'install:database:help:dbprefix' => "Le préfixe donné à toutes les tables d'Elgg (habituellement elgg_)",

	'install:settings:instructions' => 'Nous avons besoin d\'informations à propos du site afin de configurer correctement Elgg. Si vous n\'avez pas encore <a href="http://docs.elgg.org/wiki/Installation_fr" target="_blank">créé un répertoire de données</a> pour Elgg, vous devez le faire maintenant.',

	'install:settings:label:sitename' => 'Nom du site',
	'install:settings:label:siteemail' => 'Adresse email du site',
	'install:settings:label:wwwroot' => 'URL du site',
	'install:settings:label:path' => 'Répertoire d\'installation d\'Elgg',
	'install:settings:label:dataroot' => 'Répertoire de données',
	'install:settings:label:language' => 'Langue du site',
	'install:settings:label:siteaccess' => 'Accès par défaut au site',
	'install:label:combo:dataroot' => 'Elgg crée un répertoire de données',

	'install:settings:help:sitename' => 'Nom de votre nouveau site Elgg',
	'install:settings:help:siteemail' => 'Adresse email utilisée par Elgg pour la communication avec les utilisateurs',
	'install:settings:help:wwwroot' => 'L\'adresse du site (Elgg habituellement la devine correctement)',
	'install:settings:help:path' => 'Le répertoire où vous avez mis le code d\'Elgg (Elgg habituellement le devine correctement)',
	'install:settings:help:dataroot' => 'Le répertoire que vous avez créé dans lequel Elgg enregistre les fichiers (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant)',
	'install:settings:help:dataroot:apache' => 'Elgg vous donne la possibilité de créer un répertoire de données ou de renseigner le nom du répertoire que vous avez déjà créé pour stocker les fichiers des utilisateurs (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant)',
	'install:settings:help:language' => 'La langue par défaut du site',
	'install:settings:help:siteaccess' => 'Le niveau d\'accès par défaut aux contenus créés par les nouveaux utilisateurs',

	'install:admin:instructions' => "Il est maintenant temps de créer un compte administrateur.",

	'install:admin:label:displayname' => 'Afficher le nom',
	'install:admin:label:email' => 'Adresse email',
	'install:admin:label:username' => 'Nom d\'utilisateur',
	'install:admin:label:password1' => 'Mot de passe',
	'install:admin:label:password2' => 'Mot de passe à nouveau',

	'install:admin:help:displayname' => 'Le nom qui est affiché sur le site pour ce compte',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nom du compte utilisteur utilisé pour se connecter',
	'install:admin:help:password1' => "Le mot de passe du compte doit avoir au moins une longueur de %u caractères",
	'install:admin:help:password2' => 'Répétez le mot de passe pour confirmer',

	'install:admin:password:mismatch' => 'Les mots de passe doivent correspondre.',
	'install:admin:password:empty' => 'Les mots de passe ne peuvent être vides.',
	'install:admin:password:tooshort' => 'Votre mot de passe était trop court',
	'install:admin:cannot_create' => 'Impossible de créer un compte administrateur.',

	'install:complete:instructions' => 'Votre site Elgg est maintenant prêt à être utilisé. Cliquez sur le bouton ci-dessous pour aller sur votre site.',
	'install:complete:gotosite' => 'Aller sur le site',

	'InstallationException:UnknownStep' => '%s est une étape d\'installation inconnue.',
	'InstallationException:MissingLibrary' => 'Impossible de charger %s',
	'InstallationException:CannotLoadSettings' => 'Elgg n\'a pas pu charger les paramètres du fichier. Celui-ci n\'existe pas ou alors présente des problèmes de permissions. ',

	'install:success:database' => 'La base de données a bien été installée.',
	'install:success:settings' => 'Les paramètres du site ont été enregistrés.',
	'install:success:admin' => 'Le compte administrateur a été créé.',

	'install:error:htaccess' => 'Impossible de créer un fichier .htaccess',
	'install:error:settings' => 'Impossible de créer un fichier paramètres',
	'install:error:databasesettings' => 'Impossible de se connecter à la base de données avec ces paramètres.',
	'install:error:database_prefix' => 'Caractères non valides dans le préfixe de la base de données',
	'install:error:oldmysql' => 'La version MySQL doit être la version 5.0 ou supérieure. Votre serveur utilise la version %s.',
	'install:error:nodatabase' => 'Impossible d\'utiliser la base de données %s. Il se peut qu\'elle n\'existe pas.',
	'install:error:cannotloadtables' => 'Impossible de charger les tables de la base de données',
	'install:error:tables_exist' => 'Il y a déjà des tables dans la base de données d\'Elgg. Vous devez soit supprimer ces tables ou redémarrer l\'installeur et nous allons alors tenter de les utiliser. Pour redémarrer l\'installeur, enlevez "?step=database" dans la barre d\'adresse de votre navigateur et appuyez sur Entrée.',
	'install:error:readsettingsphp' => 'Impossible de lire le fichier engine/settings.example.php',
	'install:error:writesettingphp' => 'Impossible d\'écrire le fichier engine/settings.php',
	'install:error:requiredfield' => '%s est nécessaire',
	'install:error:relative_path' => 'Nous ne pensons pas que "%s" soit un chemin absolu pour votre répertoire de données',
	'install:error:datadirectoryexists' => 'Votre répertoire de données %s n\'existe pas.',
	'install:error:writedatadirectory' => 'Le serveur web ne peut pas écrire dans votre répertoire de données %s.',
	'install:error:locationdatadirectory' => 'Votre répertoire de données %s doit être en dehors du répertoire de votre installation pour des raisons de sécurité.',
	'install:error:emailaddress' => '%s n\'est pas une adresse email valide',
	'install:error:createsite' => 'Impossible de créer le site.',
	'install:error:savesitesettings' => 'Impossible d\'enregistrer les paramètres du site.',
	'install:error:loadadmin' => 'Impossible de lire le compte administrateur.',
	'install:error:adminaccess' => 'Impossible d\'attribuer des privilèges administrateur à ce nouveau compte utilisateur.',
	'install:error:adminlogin' => 'Impossible de se connecter automatiquement à ce nouveau compte administrateur.',
	'install:error:rewrite:apache' => 'Nous pensons que votre serveur utilise un serveur web "Apache".',
	'install:error:rewrite:nginx' => 'Nous pensons que votre serveur utilise un serveur web "Nginx".',
	'install:error:rewrite:lighttpd' => 'Nous pensons que votre serveur utilise un serveur web "Lighttpd".',
	'install:error:rewrite:iis' => 'Nous pensons que votre serveur utilise un serveur web "IIS".',
	'install:error:rewrite:allowoverride' => "Le test de réécriture des adresses a échoué car très probablement l'option AllowOverride pour le répertoire d'Elgg n'est pas configurée à \"All\" (Tous). Cela empêche Apache de traiter le fichier \".htaccess\" qui contient les règles de réécriture.
				\n\nUne cause moins probable est qu'un alias pour votre répertoire Elgg est configuré dans Apache et que vous devez définir le RewriteBase dans votre fichier \".htaccess\". Il y a d'autres instructions dans le fichier \".htaccess\" présent dans votre répertoire d'Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Votre serveur web n\'a pas la permission de créer le fichier ".htaccess" dans le répertoire d\'Elgg. Vous devez copier manuellement le fichier "htaccess_dist" en le renommant ".htaccess" ou changer les permissions de ce répertoire.',
	'install:error:rewrite:htaccess:read_permission' => 'Il y a un fichier ".htaccess" dans le répertoire d\'Elgg, mais votre serveur web n\'a pas la permission de le lire.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Il y a un fichier ".htaccess" dans le répertoire d\'Elgg qui n\'a pas été créé par Elgg. Enlevez le s\'il vous plaît.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Il semble y avoir un vieux fichier ".htaccess" dans le répertoire d\'Elgg. Il ne contient pas la règle de réécriture permettant de tester le serveur Web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Une erreur inconnue s\'est produite lors de la création du fichier ".htaccess". Vous devez copier manuellement, dans le répertoire d\'Elgg, le fichier ".htaccess_dist" en le renommant ".htaccess".',
	'install:error:rewrite:altserver' => 'Le test des règles de réécriture a échoué. Vous devez configurer votre serveur web avec les règles de réécriture d\'Elgg et réessayer.',
	'install:error:rewrite:unknown' => 'Euh... Nous ne pouvons pas comprendre quel type de serveur Web est utilisé sur votre serveur et cela a fait échoué la mise en place des règles de réécriture. Nous ne pouvons pas vous donner de conseil particulier dans ce cas. Veuillez svp vérifier le lien de dépannage.',
	'install:warning:rewrite:unknown' => 'Votre serveur ne supporte pas le test automatique des règles de réécriture. Vous pouvez continuer l\'installation, mais il est possible que vous rencontriez des problèmes avec votre site. Vous pouvez tester manuellement les règles de réécriture en cliquant sur ce lien : <a href="%s" target="_blank">test</a>. Vous verrez le mot "succès" si les redirections fonctionnent.',
    
	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Une erreur irrécupérable a eu lieu et a été enregistrée. Si vous êtes l\'administrateur du site, vérifiez vos paramètres, sinon, veuillez svp contacter l\'administrateur du site en fournissant les informations suivantes : ',
	'DatabaseException:WrongCredentials' => "Elgg n'a pas pu charger la base de données en utilisant les données fournies. Vérifiez le fichier des paramètres. ",
);
