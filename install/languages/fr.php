<?php
return array(
	'install:title' => 'Installation d\'Elgg',
	'install:welcome' => 'Bienvenu',
	'install:requirements' => 'Vérification des pré-requis techniques',
	'install:database' => 'Installation base de données',
	'install:settings' => 'Configuration du site',
	'install:admin' => 'Créer un compte administrateur',
	'install:complete' => 'Terminé',

	'install:next' => 'Suivant',
	'install:refresh' => 'Rafraichir',

	'install:welcome:instructions' => "L'installation d'Elgg a 6 étapes simples et commence par la lecture de cette page de bienvenu !

Si vous n'êtes pas déjà prêt, lisez les instructions d'installation qui font partis d'Elgg (ou cliquer sur le lien instructions au bas de la page).

Si vous êtes prêt à commencer, cliquer sur le bouton Suivant.",
	'install:requirements:instructions:success' => "Votre serveur a passé la vérification des pré-requis techniques.",
	'install:requirements:instructions:failure' => "Votre serveur n'a pas passé la vérification des pré-requis techniques. Après avoir résolu les questions ci-dessous , actualisez cette page. Consultez les liens de dépannage au bas de cette page si vous avez besoin d'aide supplémentaire.",
	'install:requirements:instructions:warning' => "Votre serveur a passé la vérification des pré-requis techniques, mais il y a encore au moins un avertissement. Nous vous recommandons de vérifier la page dépannage de l'installation pour plus de détails.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Serveur web',
	'install:require:settings' => 'Fichier paramètres',
	'install:require:database' => 'Base de données',

	'install:check:root' => 'Votre serveur web n\'a pas la permission de créer le fichier ".htaccess" dans le répertoire racine d\'Elgg. Vous avez deux choix : 

		1. Changer les permissions dans le répertoire racine

		2. Copier le fichier ".htaccess_dist" en le renommant ".htaccess"',

	'install:check:php:version' => 'Elgg a besoin de la version %s de PHP ou supérieur. Ce serveur utilise la version %s.',
	'install:check:php:extension' => 'Elgg a besoin de la version %s de l\'extention PHP.',
	'install:check:php:extension:recommend' => 'Il est recommandé que l\'extention PHP %s soit installé.',
	'install:check:php:open_basedir' => 'La directive "open_basedir" de PHP peut empêcher Elgg d\'enregistrer des fichiers dans son répertoire de données.',
	'install:check:php:safe_mode' => 'Exécute PHP en mode sans échec n\'est pas conseillé et peut causer des problèmes avec Elgg.',
	'install:check:php:arg_separator' => 'Pour fonctionner l\'option d\'Elgg arg_separator.output doit être "&", et la valeur sur votre serveur est %s',
	'install:check:php:register_globals' => 'L\'option "Register globals" doit être mis à "off".',
	'install:check:php:session.auto_start' => "Pour fonctionner l'option d'Elgg session.auto_start doit être mise à \"off\". Soit modifier la configuration de votre serveur ou ajouter cette directive au fichier \".htaccess\" d'Elgg.",

	'install:check:enginedir' => 'Votre serveur web n\'a pas la permission de créer le fichier "settings.php" (paramètres) dans le répertoire "engine" (moteur) d\'Elgg. Vous avez deux choix : 

		1. Changer les permissions dans le répertoire "engine"

		2. Copier le fichier "settings.example.php" en le renommant "settings.php" et suivre les instructions à l\'intérieur, pour définir les paramètres de la base de données.',
	'install:check:readsettings' => 'Un fichier de paramètres existe dans le répertoire "engine" (moteur), mais le serveur web ne peut pas le lire. Vous pouvez supprimer le fichier ou modifier les autorisations de lecture sur lui.',

	'install:check:php:success' => "Votre serveur PHP remplis tous les pré-requis techniques d'Elgg.",
	'install:check:rewrite:success' => 'Le test des règles de redirection s\'est fait avec succès.',
	'install:check:database' => 'Les pré-requis techniques des base de données sont vérifiés quand Elgg les lit.',

	'install:database:instructions' => "Si vous n'avez pas déjà créé une base de données pour Elgg, le faire maintenant. Ensuite, remplissez les valeurs ci-dessous pour initialiser la base d'Elgg.",
	'install:database:error' => 'Il y a eu une erreur pendant la création de la base de données Elgg et l\'installation ne peut pas continuer. Lisez le message ci-dessus et corrigez les problèmes. Si vous avez besoin de plus d\'aide, visitez le lien ci-dessous concernant le dépannage de l\'installation ou poster un message sur les forums de la communauté Elgg.',

	'install:database:label:dbuser' =>  'Nom utilisateur de la base de données',
	'install:database:label:dbpassword' => 'Mot de passe de la base de données',
	'install:database:label:dbname' => 'Nom de la base de données',
	'install:database:label:dbhost' => 'Nom du serveur de la base de données',
	'install:database:label:dbprefix' => 'Préfixe des tables de la base de données',

	'install:database:help:dbuser' => 'L\'utilisateur qui a tous les privilèges de la base de données MySQL que vous avez créé pour Elgg',
	'install:database:help:dbpassword' => 'Mot de passe pour le compte d\'utilisateur de la base de données ci-dessus',
	'install:database:help:dbname' => 'Nom de la base de données d\'Elgg',
	'install:database:help:dbhost' => 'Nom du serveur du serveur MySQL (habituellement localhost)',
	'install:database:help:dbprefix' => "Le préfix donné à toutes les tables d'Elgg (habituellement elgg_)",

	'install:settings:instructions' => 'Nous avons besoin d\'informations à propos du site car nous configurons Elgg. Si vous n\'avez pas <a href="http://docs.elgg.org/wiki/Data_directory" target="_blank">crée un répertoire données</a> pour Elgg, vous devez le faire maintenant.',

	'install:settings:label:sitename' => 'Nom du site',
	'install:settings:label:siteemail' => 'Adresse mail du site',
	'install:settings:label:wwwroot' => 'URL du site',
	'install:settings:label:path' => 'Répertoire d\'installation d\'Elgg',
	'install:settings:label:dataroot' => 'Répertoire des données',
	'install:settings:label:language' => 'Langue du site',
	'install:settings:label:siteaccess' => 'Accès au site par défaut',
	'install:label:combo:dataroot' => 'Elgg crée un répertoire de données',

	'install:settings:help:sitename' => 'Nom de votre nouveau site Elgg',
	'install:settings:help:siteemail' => 'Adresse mail utilisé par Elgg pour la communication avec les utilisateurs',
	'install:settings:help:wwwroot' => 'L\'adresse du site (Elgg habituellement le devine correctement)',
	'install:settings:help:path' => 'Le répertoire où vous avez mis le code d\'Elgg (Elgg habituellement le devine correctement)',
	'install:settings:help:dataroot' => 'Le répertoire que vous avez créé pour que Elgg enregistre des fichiers (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant)',
	'install:settings:help:dataroot:apache' => 'Elgg vous donne la possibilité de créer un répertoire de données ou d\'entrer le répertoire que vous avez déjà créé pour stocker les fichiers des utilisateurs (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant)',
	'install:settings:help:language' => 'Le langage par défaut pour le site',
	'install:settings:help:siteaccess' => 'Le niveau d\'accès par défaut aux contenus créés pour les nouveaux utilisateurs',

	'install:admin:instructions' => "Il est maintenant temps de créer un compte administrateur.",

	'install:admin:label:displayname' => 'Affichage le nom',
	'install:admin:label:email' => 'Adresse mail',
	'install:admin:label:username' => 'Nom d\'utilisateur',
	'install:admin:label:password1' => 'Mot de passe',
	'install:admin:label:password2' => 'Mot de passe à nouveau',

	'install:admin:help:displayname' => 'Le nom qui est affiché sur le site pour ce compte',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nom du compte d\'utilisteur utilisé pour se connecter',
	'install:admin:help:password1' => "Le mot de passe du compte doit avoir au moins une longueur de %u caractères",
	'install:admin:help:password2' => 'Retaper le mot de passe pour confirmer',

	'install:admin:password:mismatch' => 'Les mots de passe doivent correspondre.',
	'install:admin:password:empty' => 'Les mots de passe ne peuvent être vide.',
	'install:admin:password:tooshort' => 'Votre mot de passe était trop court',
	'install:admin:cannot_create' => 'Impossible de créer un compte administrateur.',

	'install:complete:instructions' => 'Votre site Elgg est maintenant prêt à être utilisé. Cliquez sur le bouton ci-dessous pour être redirigé vers votre site.',
	'install:complete:gotosite' => 'Aller sur le site site',

	'InstallationException:UnknownStep' => '%s est une étape d\'installation inconnue.',
	'InstallationException:MissingLibrary' => 'Impossible de charger %s',
	'InstallationException:CannotLoadSettings' => 'Elgg n\'a pas pu charger les paramètres du fichier. Celui-ci ne doit pas exister ou bien, présente des problèmes de permissions. ',

	'install:success:database' => 'La base de donnéesa été installé.',
	'install:success:settings' => 'Les paramètres du Site ont été sauvegardés.',
	'install:success:admin' => 'Le compte administrateur a été créé.',

	'install:error:htaccess' => 'Impossible de créé un fichier .htaccess',
	'install:error:settings' => 'Impossible de créé un fichier paramètres',
	'install:error:databasesettings' => 'Impossible de se connecter à la base de données avec ces paramètres.',
	'install:error:database_prefix' => 'Invalid characters in database prefix',
	'install:error:oldmysql' => 'La version MySQL doit être la version 5.0 ou supérieur. Votre serveur utilise la version %s.',
	'install:error:nodatabase' => 'Impossible d\'utiliser la base de données %s. Il se peut qu\'elle n\'existe pas',
	'install:error:cannotloadtables' => 'Impossible de charger les tables de la base de données',
	'install:error:tables_exist' => 'Il y a déjà des tables dans la base de données d\'Elgg, vous devez soit laisser tomber ces tableaux ou redémarrer l\'installateur et nous allons tenter de les utiliser. Pour redémarrer l\'installeur, enlevez "?step=database" partir de la barre d\'adresse URL de votre navigateur et appuyez sur Entrée.',
	'install:error:readsettingsphp' => 'Impossible de lire le fichier engine/settings.example.php',
	'install:error:writesettingphp' => 'Impossible d\'écrire le fichier engine/settings.php',
	'install:error:requiredfield' => '%s est nécessaire',
	'install:error:relative_path' => 'We don\'t think "%s" is an absolute path for your data directory',
	'install:error:datadirectoryexists' => 'Votre répertoire de données %s n\'existe pas.',
	'install:error:writedatadirectory' => 'Le serveur web ne peut pas écrire dans votre répertoire de données %s.',
	'install:error:locationdatadirectory' => 'Votre répertoire de données %s doit être en dehors du répertoire de votre installation pour des raisons de sécurité.',
	'install:error:emailaddress' => '%s n\'est pas une adresse mail valide',
	'install:error:createsite' => 'Impossible de créer le site.',
	'install:error:savesitesettings' => 'Impossible d\'enregistrer les paramètres du site.',
	'install:error:loadadmin' => 'Impossible de lire le compte administrateur.',
	'install:error:adminaccess' => 'Impossible de donner des privilèges administrateurs à ce nouveau compte utilisateur.',
	'install:error:adminlogin' => ' Impossible de se connecter automatiquement à ce nouveau compte administrateur.',
	'install:error:rewrite:apache' => 'Nous pensons que votre serveur exécute le serveur web "Apache".',
	'install:error:rewrite:nginx' => 'Nous pensons que votre serveur exécute le serveur web "Nginx".',
	'install:error:rewrite:lighttpd' => 'Nous pensons que votre serveur exécute le serveur web "Lighttpd".',
	'install:error:rewrite:iis' => 'Nous pensons que votre serveur exécute le serveur web "IIS".',
	'install:error:rewrite:allowoverride' => "Le test de réécrire a échoué car très probablement l'option AllowOverride pour le répertoire d'Elgg n'est pas configuré à \"All\" (Tous). Cela empêche Apache de traiter le fichier \".htaccess\" qui contient les règles de réécriture.
				\n\nUne cause moins probable est qu'un alias pour votre répertoire Elgg est configuré dans Apache et que vous devez définir le RewriteBase dans votre fichier \".htaccess\". Il y a d'autres instructions dans le fichier \".htaccess\". dans votre répertoire d'Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Votre serveur web n\'a pas la permission de créer le fichier ".htaccess". dans le répertoire d\'Elgg. Vous devez copier manuellement le fichier "htaccess_dist" en le renommant ".htaccess". ou changer les permissions dans le répertoire.',
	'install:error:rewrite:htaccess:read_permission' => 'Il y a un fichier ".htaccess" dans le répertoire d\'Elgg, mais votre serveur web n\'a pas la permission de le lire.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Il y a un fichier ".htaccess" dans le répertoire d\'Elgg, qui n\'a pas été créé par Elgg. Enlever le, s\'il vous plaît.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Il semble y avoir un vieux fichier ".htaccess" dans le répertoire d\'Elgg. Il ne contient pas la règle de réécriture permettant de tester le serveur Web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Une erreur inconnue s\'est produite lors de la création du fichier ".htaccess". Dans le répertoire d\'Elgg, Vous devez copier manuellement le fichier ".htaccess_dist" en le renommant ".htaccess".',
	'install:error:rewrite:altserver' => 'Le test des règles de réécriture a échoué. Vous devez configurer votre serveur web avec les règles de réécriture d\'Elgg et réessayer.',
	'install:error:rewrite:unknown' => 'Euh, nous ne pouvons comprendre quel type de serveur Web est utilisé sur votre serveur et cela a fait échoué la mise en place des règles de réécriture. Nous ne pouvons donner aucun conseil particulier. Vérifier le lien de dépannage, s\'il vous plaît.',
	'install:warning:rewrite:unknown' => 'Votre serveur ne supporte pas de test automatique des règles de réécriture. Vous pouvez continuer l\'installation, mais vous pouvez rencontrer des problèmes avec votre site Vous pouvez tester manuellement les règles de réécriture en cliquant sur ce lien : <a href="%s" target="_blank">test</a>. Vous verrez le mot "succès" si les redirections fonctionnent.',
    
	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Une erreur irrécupérable a eu lieu et a été enregistrée. Si vous êtes l\'administrateur du site, vérifiez vos paramètres, sinon, contactez l\'administrateur du site en fournissant les informations suivantes : ',
	'DatabaseException:WrongCredentials' => "Elgg n'a pas pu charger la base de données en utilisant les données fournies. Vérifiez les paramètres. ",
);
