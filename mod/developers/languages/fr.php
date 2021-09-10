<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Outils',
	
	// menu
	'admin:develop_tools:sandbox' => 'Bac à sable du thème',
	'admin:develop_tools:inspect' => 'Inspecter',
	'admin:inspect' => 'Inspecter',
	'admin:develop_tools:unit_tests' => 'Tests unitaires',
	'admin:develop_tools:entity_explorer' => 'Explorateur d\'entités',
	'admin:developers' => 'Développeurs',
	'admin:developers:settings' => 'Paramètres',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Contrôlez vos paramètres de développements et de débogage ci-dessous. Certains de ces paramètres sont aussi disponibles sur d\'autres pages d\'administration.',
	'developers:label:simple_cache' => 'Utiliser le cache simple',
	'developers:help:simple_cache' => 'Désactivez ce cache lors des développements, sinon les modifications des fichiers CSS et JavaScript seront ignorées.',
	'developers:label:system_cache' => 'Utiliser le cache système',
	'developers:help:system_cache' => 'Désactivez le cache système lors des développements, sinon les modifications de vos plugins ne seront pas prises en compte.',
	'developers:label:debug_level' => "Niveau de journalisation",
	'developers:help:debug_level' => "Contrôle la quantité d'informations journalisées. Voir elgg_log() pour plus d'informations.",
	'developers:label:display_errors' => 'Afficher les erreurs PHP fatales',
	'developers:help:display_errors' => "Par défaut, le fichier .htaccess de Elgg désactive l'affichage des erreurs fatales.",
	'developers:label:screen_log' => "Afficher à l'écran",
	'developers:help:screen_log' => "Ceci affiche à l'écran les sorties de elgg_log() et de elgg_dump() ainsi que le nombre de requêtes sur la base de données",
	'developers:label:show_strings' => "Afficher les chaînes de traduction brutes",
	'developers:help:show_strings' => "Affiche les chaînes de traduction utilisées par elgg_echo().",
	'developers:label:show_modules' => "Montrer les modules AMD chargés dans la console",
	'developers:help:show_modules' => "Envoie les modules chargés et les valeurs dans votre console JavaScript.",
	'developers:label:wrap_views' => "Envelopper les Vues",
	'developers:help:wrap_views' => "Ceci enveloppe presque toutes les vues avec des commentaires HTML. Pratique pour identifier la vue responsable d'un élément de code HTML particulier.
									Cela peut casser les vues non HTML de l'affichage principal : images, RSS, XML, JSON, etc. Voir developers_wrap_views() pour plus d'informations.",
	'developers:label:log_events' => "Journaliser les événements et les hooks des plugins.",
	'developers:help:log_events' => "Écrit les événements et les hooks des plugins dans le journal. Attention : il y en a beaucoup pour chaque page.",
	'developers:label:show_gear' => "Utiliser %s hors de la zone d'administration",
	'developers:help:show_gear' => "Une icône en bas à droite de l'affichage qui offre aux administrateurs un accès aux paramètres et liens pour développeurs.",
	'developers:label:block_email' => "Bloquer tous les e-mails sortants",
	'developers:help:block_email' => "Vous pouvez bloquer les e-mails sortants vers les simples membres, ou pour tous les utilisateurs",
	'developers:label:forward_email' => "Faire suivre tous les e-mails sortants vers une seule adresse e-mail",
	'developers:help:forward_email' => "Tous les e-mails sortants seront envoyés sur l'adresse e-mail configurée",
	'developers:label:enable_error_log' => "Activer le journal des erreurs",
	'developers:help:enable_error_log' => "Maintenir un journal séparé des erreurs et des messages enregistrés via error_log() sur la base de votre configuration de niveau de journalisation. Le journal peut être affiché via l'interface admin.",

	'developers:label:submit' => "Enregistrer et vider les caches",

	'developers:block_email:forward' => 'Faire suivre tous les e-mails',
	'developers:block_email:users' => 'Seulement les membres',
	'developers:block_email:all' => 'Admins et membres',
	
	'developers:debug:off' => 'Désactivé',
	'developers:debug:error' => 'Erreur',
	'developers:debug:warning' => 'Avertissement',
	'developers:debug:notice' => 'Avis',
	'developers:debug:info' => 'Information',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Affiche des informations sur les entités et effectue des actions basiques dessus.',
	'developers:entity_explorer:guid:label' => 'Saisissez le GUID de l\'entité à inspecter',
	'developers:entity_explorer:info' => 'Informations sur l\'entité',
	'developers:entity_explorer:info:attributes' => 'Attributs',
	'developers:entity_explorer:info:metadata' => 'Métadonnées',
	'developers:entity_explorer:info:relationships' => 'Relations',
	'developers:entity_explorer:info:private_settings' => 'Paramètres privés',
	'developers:entity_explorer:info:owned_acls' => 'Collections d\'accès possédées',
	'developers:entity_explorer:info:acl_memberships' => 'Appartenance aux collections d\'accès',
	'developers:entity_explorer:delete_entity' => 'Supprimer cette entité',
	'developers:entity_explorer:inspect_entity' => 'Inspecter cette entité',
	'developers:entity_explorer:view_entity' => 'Voir cette entité sur le site',
	
	// inspection
	'developers:inspect:help' => 'Inspecter la configuration système du framework Elgg',
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Evénements',
	'developers:inspect:menus' => 'Menus',
	'developers:inspect:pluginhooks' => 'Hooks des plugins',
	'developers:inspect:priority' => 'Priorité',
	'developers:inspect:simplecache' => 'Cache simple',
	'developers:inspect:routes' => 'Routes',
	'developers:inspect:views' => 'Vues',
	'developers:inspect:views:all_filtered' => "<b>Note !</b> Toutes les entrées/sorties des vues sont filtrées par ces hooks de plugin :",
	'developers:inspect:views:input_filtered' => "(entrée filtrée par le hook de plugin : %s)",
	'developers:inspect:views:filtered' => "(filtré par le hook de plugin : %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Contexte',
	'developers:inspect:functions' => 'Fonctions',
	'developers:inspect:file_location' => 'Chemin depuis la racine Elgg ou le contrôleur',
	'developers:inspect:route' => 'Nom de la route',
	'developers:inspect:path' => 'Motif du chemin',
	'developers:inspect:resource' => 'Vue de la ressource',
	'developers:inspect:handler' => 'Gestionnaire (handler)',
	'developers:inspect:controller' => 'Contrôleur',
	'developers:inspect:file' => 'Fichier',
	'developers:inspect:middleware' => 'Fichier',
	'developers:inspect:handler_type' => 'Géré par',
	'developers:inspect:services' => 'Services',
	'developers:inspect:service:name' => 'Nom',
	'developers:inspect:service:path' => 'Définition',
	'developers:inspect:service:class' => 'Classe',

	// event logging
	'developers:request_stats' => "Statistiques des requêtes (n'inclue pas l'événement shutdown)",
	'developers:event_log_msg' => "%s : '%s, %s' dans %s",
	'developers:log_queries' => "Requêtes sur la base de données : %s",
	'developers:boot_cache_rebuilt' => "Le cache de démarrage a été reconstruit pour cette requête",
	'developers:elapsed_time' => "Durée écoulée (s)",

	// theme sandbox
	'theme_sandbox:intro' => 'Introduction',
	'theme_sandbox:breakout' => 'Sortir de l\'iframe',
	'theme_sandbox:buttons' => 'Boutons',
	'theme_sandbox:components' => 'Composants',
	'theme_sandbox:email' => 'E-mail',
	'theme_sandbox:forms' => 'Formulaires',
	'theme_sandbox:grid' => 'Grille',
	'theme_sandbox:icons' => 'Icônes',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Mises en page',
	'theme_sandbox:modules' => 'Modules',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typographie',

	'theme_sandbox:icons:blurb' => 'Utilisez <em>elgg_view_icon($name)</em> pour afficher des icônes. ',
	
	'theme_sandbox:test_email:button' => "Envoyer un e-mail de test",
	'theme_sandbox:test_email:success' => "E-mail de test envoyé à : %s",

	// status messages
	'developers:settings:success' => 'Paramètres enregistrés et caches vidés',

	'developers:amd' => 'AMD',

	'admin:develop_tools:error_log' => 'Journal des erreurs',
	'developers:logs:empty' => 'Le journal des erreurs est vide',
);
