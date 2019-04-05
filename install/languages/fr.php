<?php
return array(
	'install:title' => 'Installation d\'Elgg',
	'install:welcome' => 'Bienvenue',
	'install:requirements' => 'Vérification des pré-requis',
	'install:database' => 'Installation de la base de données',
	'install:settings' => 'Configuration du site',
	'install:admin' => 'Création d\'un compte administrateur',
	'install:complete' => 'Terminé',

	'install:next' => 'Suivant',
	'install:refresh' => 'Rafraîchir',

	'install:welcome:instructions' => "L'installation d'Elgg comporte 6 étapes simples et commence par la lecture de cette page de bienvenue !

Si vous ne l'avez pas déjà fait, lisez les instructions d'installation distribuées avec Elgg (ou cliquez sur le lien vers les instructions au bas de la page).

Si vous êtes prêt à commencer, cliquez sur le bouton Suivant.",
	'install:requirements:instructions:success' => "Votre serveur a passé la vérification des pré-requis techniques.",
	'install:requirements:instructions:failure' => "Votre serveur n'a pas passé la vérification des pré-requis techniques. Après avoir résolu les points ci-dessous , actualisez cette page. Consultez les liens de dépannage au bas de cette page si vous avez besoin d'aide supplémentaire.",
	'install:requirements:instructions:warning' => "Votre serveur a passé la vérification des pré-requis techniques, mais il y a encore au moins un avertissement. Nous vous recommandons de consulter la page d'aide à l'installation pour plus de détails.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Serveur web',
	'install:require:settings' => 'Fichier de configuration',
	'install:require:database' => 'Base de données',

	'install:check:root' => 'Votre serveur web n\'a pas la permission de créer le fichier ".htaccess" dans le répertoire racine d\'Elgg. Vous avez deux options :

1. Changer les permissions du répertoire racine

2. Copier le fichier install/config/htaccess.dist vers .htaccess',

	'install:check:php:version' => 'Elgg a besoin de la version %s ou supérieure de PHP . Ce serveur utilise la version %s.',
	'install:check:php:extension' => 'Elgg a besoin de l\'extension PHP %s.',
	'install:check:php:extension:recommend' => 'Il est recommandé que l\'extension PHP %s soit installée.',
	'install:check:php:open_basedir' => 'La directive "open_basedir" de PHP peut empêcher Elgg d\'enregistrer les fichiers dans son répertoire de données.',
	'install:check:php:safe_mode' => 'Exécuter PHP en mode sans échec n\'est pas conseillé et peut poser des problèmes avec Elgg.',
	'install:check:php:arg_separator' => 'Pour fonctionner, l\'option arg_separator.output doit être fixée à "&", alors que la valeur actuelle sur votre serveur est %s',
	'install:check:php:register_globals' => 'L\'option "Register globals" doit être mise à "off".',
	'install:check:php:session.auto_start' => "Pour fonctionner l'option session.auto_start doit être mise à \"off\". Vous devez soit modifier la configuration de votre serveur, soit ajouter cette directive au fichier .htaccess d'Elgg.",

	'install:check:installdir' => 'Votre serveur web n\'a pas la permission de créer le fichier settings.php dans le répertoire d\'installation. Vous avez deux possibilités :

1. Modifier les permissions du dossier elgg-config de votre installation Elgg

2. Copier le fichier %s/settings.example.php dans elgg-config/settings.php et suivre les instructions à l\'intérieur du fichier pour définir les paramètres de la base de données. ',
	'install:check:readsettings' => 'Un fichier de configuration existe déjà dans le répertoire "engine", mais le serveur web ne peut pas le lire. Vous pouvez supprimer le fichier ou modifier ses permissions en lecture.',

	'install:check:php:success' => "Votre serveur PHP remplit tous les pré-requis techniques d'Elgg.",
	'install:check:rewrite:success' => 'Le test des règles de réécriture a réussi.',
	'install:check:database' => 'Les pré-requis de la base de données sont vérifiés quand Elgg chargera la base de données.',

	'install:database:instructions' => "Si vous n'avez pas déjà créé une base de données pour Elgg, faites-le maintenant. Ensuite, remplissez les valeurs ci-dessous pour initialiser la base de données d'Elgg.",
	'install:database:error' => 'Il y a eu une erreur pendant la création de la base de données Elgg et l\'installation ne peut pas continuer. Lisez le message ci-dessus et corrigez tout problème. Si vous avez besoin de plus d\'aide, visitez le lien ci-dessous concernant l\'aide à l\'installation ou postez un message sur les forums de la communauté Elgg.',

	'install:database:label:dbuser' =>  'Nom d\'utilisateur de la base de données',
	'install:database:label:dbpassword' => 'Mot de passe de la base de données',
	'install:database:label:dbname' => 'Nom de la base de données',
	'install:database:label:dbhost' => 'Nom du serveur de la base de données',
	'install:database:label:dbprefix' => 'Préfixe des tables de la base de données',
	'install:database:label:timezone' => "Fuseau horaire",

	'install:database:help:dbuser' => 'Utilisateur qui a tous les droits sur la base de données MySQL que vous avez créée pour Elgg',
	'install:database:help:dbpassword' => 'Mot de passe du compte de l\'utilisateur de la base de données ci-dessus',
	'install:database:help:dbname' => 'Nom de la base de données d\'Elgg',
	'install:database:help:dbhost' => 'Nom du serveur MySQL (habituellement localhost)',
	'install:database:help:dbprefix' => "Le préfixe donné à toutes les tables d'Elgg (habituellement elgg_)",
	'install:database:help:timezone' => "Le fuseau horaire par défaut du site",

	'install:settings:instructions' => 'Nous avons besoin de quelques informations à propos du site afin de configurer Elgg. Si vous n\'avez pas encore <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">créé un répertoire de données</a> pour Elgg, vous devez le faire maintenant.',

	'install:settings:label:sitename' => 'Nom du site',
	'install:settings:label:siteemail' => 'Adresse email du site',
	'install:database:label:wwwroot' => 'URL du site',
	'install:settings:label:path' => 'Répertoire d\'installation d\'Elgg',
	'install:database:label:dataroot' => 'Répertoire de données',
	'install:settings:label:language' => 'Langue du site',
	'install:settings:label:siteaccess' => 'Accès par défaut au site',
	'install:label:combo:dataroot' => 'Elgg crée un répertoire de données',

	'install:settings:help:sitename' => 'Nom de votre nouveau site Elgg',
	'install:settings:help:siteemail' => 'Adresse email utilisée par Elgg pour la communication avec les utilisateurs',
	'install:database:help:wwwroot' => 'L\'adresse du site (Elgg la devine habituellement correctement)',
	'install:settings:help:path' => 'Le répertoire où vous avez mis le code d\'Elgg (Elgg le devine habituellement correctement)',
	'install:database:help:dataroot' => 'Le répertoire que vous avez créé dans lequel Elgg enregistre les fichiers (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant). Doit être un chemin absolu.',
	'install:settings:help:dataroot:apache' => 'Elgg vous donne la possibilité de créer un répertoire de données ou de renseigner le nom du répertoire que vous avez déjà créé pour stocker les fichiers des utilisateurs (les permissions sur ce répertoire seront vérifiées lorsque vous cliquerez sur Suivant)',
	'install:settings:help:language' => 'La langue par défaut du site',
	'install:settings:help:siteaccess' => 'Le niveau d\'accès par défaut aux nouveaux contenus créés par les utilisateurs',

	'install:admin:instructions' => "Il est maintenant temps de créer un compte administrateur.",

	'install:admin:label:displayname' => 'Nom affiché',
	'install:admin:label:email' => 'Adresse email',
	'install:admin:label:username' => 'Nom d\'utilisateur',
	'install:admin:label:password1' => 'Mot de passe',
	'install:admin:label:password2' => 'Mot de passe (confirmer)',

	'install:admin:help:displayname' => 'Le nom qui est affiché sur le site pour ce compte',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'Nom du compte utilisateur utilisé pour se connecter',
	'install:admin:help:password1' => "Le mot de passe du compte doit avoir une longueur d'au moins %u caractères",
	'install:admin:help:password2' => 'Répétez le mot de passe pour confirmer',

	'install:admin:password:mismatch' => 'Les mots de passe doivent correspondre.',
	'install:admin:password:empty' => 'Les mots de passe ne peuvent pas être vides.',
	'install:admin:password:tooshort' => 'Votre mot de passe était trop court',
	'install:admin:cannot_create' => 'Impossible de créer un compte administrateur.',

	'install:complete:instructions' => 'Votre site Elgg est maintenant prêt à être utilisé. Cliquez sur le bouton ci-dessous pour vous rendre sur votre site.',
	'install:complete:gotosite' => 'Aller sur le site',
	'install:complete:admin_notice' => 'Bienvenue sur votre site Elgg ! Pour plus d\'options, lisez le s %s.',
	'install:complete:admin_notice:link_text' => 'pages de paramètres',

	'InstallationException:UnknownStep' => '%s est une étape d\'installation inconnue.',
	'InstallationException:MissingLibrary' => 'Impossible de charger %s',
	'InstallationException:CannotLoadSettings' => 'Elgg n\'a pas pu charger le ficher de configuration. Celui-ci n\'existe pas ou alors il y a un problème de permissions. ',

	'install:success:database' => 'La base de données a bien été installée.',
	'install:success:settings' => 'Les paramètres de configuration du site ont bien été enregistrés.',
	'install:success:admin' => 'Le compte administrateur a été créé.',

	'install:error:htaccess' => 'Impossible de créer un fichier .htaccess',
	'install:error:settings' => 'Impossible de créer le fichier de configuration',
	'install:error:settings_mismatch' => 'La valeur du fichier de configuration pour "%s" ne correspond pas aux  $params fournis.',
	'install:error:databasesettings' => 'Impossible de se connecter à la base de données avec ces paramètres de configuration.',
	'install:error:database_prefix' => 'Caractères non valides dans le préfixe de la base de données',
	'install:error:oldmysql2' => 'MySQL doit être en version 5.5.3 ou supérieure. Votre serveur utilise la version %s.',
	'install:error:nodatabase' => 'Impossible d\'utiliser la base de données %s. Il se peut qu\'elle n\'existe pas.',
	'install:error:cannotloadtables' => 'Impossible de charger les tables de la base de données',
	'install:error:tables_exist' => 'Il y a déjà des tables dans la base de données d\'Elgg. Vous pouvez soit supprimer ces tables, soit redémarrer l\'installeur qui va tenter de les utiliser. Pour redémarrer l\'installeur, enlevez "?step=database" dans la barre d\'adresse de votre navigateur et appuyez sur Entrée.',
	'install:error:readsettingsphp' => 'Impossible de lire le fichier /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'Impossible d\'écrire dans le fichier /elgg-config/settings.php',
	'install:error:requiredfield' => '%s est nécessaire',
	'install:error:relative_path' => 'Nous ne pensons pas que "%s" soit un chemin absolu pour votre répertoire de données',
	'install:error:datadirectoryexists' => 'Votre répertoire de données %s n\'existe pas.',
	'install:error:writedatadirectory' => 'Le serveur web ne peut pas écrire dans votre répertoire de données %s.',
	'install:error:locationdatadirectory' => 'Pour des raisons de sécurité, le répertoire de données %s doit être en dehors du répertoire de l\'installation.',
	'install:error:emailaddress' => '%s n\'est pas une adresse email valide',
	'install:error:createsite' => 'Impossible de créer le site.',
	'install:error:savesitesettings' => 'Impossible d\'enregistrer les paramètres du site.',
	'install:error:loadadmin' => 'Impossible de charger le compte administrateur.',
	'install:error:adminaccess' => 'Impossible d\'attribuer les privilèges administrateur au nouveau compte utilisateur.',
	'install:error:adminlogin' => 'Impossible de connecter automatiquement le nouveau compte administrateur.',
	'install:error:rewrite:apache' => 'Nous pensons que votre serveur utilise un serveur web Apache.',
	'install:error:rewrite:nginx' => 'Nous pensons que votre serveur utilise un serveur web Nginx.',
	'install:error:rewrite:lighttpd' => 'Nous pensons que votre serveur utilise un serveur web Lighttpd.',
	'install:error:rewrite:iis' => 'Nous pensons que votre serveur utilise un serveur web IIS.',
	'install:error:rewrite:allowoverride' => "Le test de réécriture des adresses a échoué, et la cause la plus probable est que l'option AllowOverride n'est pas définie à All pour le répertoire d'Elgg. Cela empêche Apache de traiter le fichier \".htaccess\" qui contient les règles de réécriture.
\n\nUne cause moins probable est qu'Apache est configuré avec un alias pour votre répertoire Elgg et que vous devez alors définir le RewriteBase dans votre fichier .htaccess. Il y a d'autres instructions dans le fichier \".htaccess\" de votre répertoire d'Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Votre serveur web n\'a pas la permission de créer le fichier .htaccess dans le répertoire d\'Elgg. Vous devez copier manuellement le fichier install/config/htaccess.dist et le renommer en .htaccess ou modifier les permissions du répertoire.',
	'install:error:rewrite:htaccess:read_permission' => 'Il y a un fichier .htaccess dans le répertoire d\'Elgg, mais votre serveur web n\'a pas la permission de le lire.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Il y a un fichier .htaccess dans le répertoire d\'Elgg qui n\'a pas été créé par Elgg. Veuillez l\'enlever.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Il semble y avoir un vieux fichier .htaccess dans le répertoire d\'Elgg. Il ne contient pas la règle de réécriture permettant de tester le serveur Web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Une erreur inconnue s\'est produite lors de la création du fichier .htaccess. Vous devez copier manuellement install/config/htaccess.dist dans le répertoire d\'Elgg et le renommer en .htaccess.',
	'install:error:rewrite:altserver' => 'Le test des règles de réécriture a échoué. Vous devez configurer votre serveur web avec les règles de réécriture d\'Elgg et réessayer.',
	'install:error:rewrite:unknown' => 'Euh... Nous ne pouvons pas comprendre quel type de serveur Web est utilisé sur votre serveur et cela a fait échouer la mise en place des règles de réécriture. Nous ne pouvons pas vous donner de conseil particulier dans ce cas. Veuillez SVP vérifier le lien de dépannage.',
	'install:warning:rewrite:unknown' => 'Votre serveur ne supporte pas le test automatique des règles de réécriture. Vous pouvez continuer l\'installation, mais il est possible que vous rencontriez des problèmes avec votre site. Vous pouvez tester manuellement les règles de réécriture en cliquant sur ce lien : <a href="%s" target="_blank">test</a>. Vous verrez le mot "succès" si les redirections fonctionnent.',
	'install:error:wwwroot' => '%s n\'est pas une URL valide',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Une erreur irrécupérable s\'est produite et a été enregistrée. Si vous êtes l\'administrateur du site, vérifiez le fichier de configuration, sinon, veuillez SVP contacter l\'administrateur du site en fournissant les informations suivantes : ',
	'DatabaseException:WrongCredentials' => "Elgg n'a pas pu se connecter à la base de données en utilisant les données fournies. Vérifiez le fichier de configuration. ",
);
