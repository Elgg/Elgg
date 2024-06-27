<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Outils',
	
	// menu
	'admin:develop_tools:inspect' => 'Inspecter',
	'admin:inspect' => 'Inspecter',
	'admin:develop_tools:unit_tests' => 'Tests unitaires',
	'admin:develop_tools:entity_explorer' => 'Explorateur d\'entités',
	'admin:developers' => 'Développeurs',
	'admin:developers:settings' => 'Paramètres',
	'menu:entity_explorer:header' => 'Explorateur d\'entités',
	'menu:developers_inspect_viewtype:header' => 'Inspecter les types de vues',

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
	'developers:show_strings:default' => "Traduction normale",
	'developers:show_strings:key_append' => "Clef de traduction ajoutée",
	'developers:show_strings:key_only' => "Afficher uniquement la clef de traduction",
	'developers:label:show_strings' => "Afficher les chaînes de traduction brutes",
	'developers:help:show_strings' => "Affiche les chaînes de traduction utilisées par elgg_echo().",
	'developers:label:wrap_views' => "Envelopper les Vues",
	'developers:help:wrap_views' => "Ceci enveloppe presque toutes les vues avec des commentaires HTML. Pratique pour identifier la vue responsable d'un bloc HTML particulier.
									Ceci peut casser les vues non HTML dans le type de vue par défaut.",
	'developers:label:log_events' => "Journaliser les événements",
	'developers:help:log_events' => "Écrit les événements dans le journal. Attention : il y en a beaucoup pour chaque page.",
	'developers:label:block_email' => "Bloquer tous les e-mails sortants",
	'developers:help:block_email' => "Vous pouvez bloquer les e-mails expédiés aux utilisateurs standards, ou pour tous les utilisateurs",
	'developers:label:forward_email' => "Faire suivre tous les e-mails sortants vers une seule adresse e-mail",
	'developers:help:forward_email' => "Tous les e-mails sortants seront envoyés sur l'adresse e-mail configurée",
	'developers:label:enable_error_log' => "Activer le journal des erreurs",
	'developers:help:enable_error_log' => "Maintient un journal séparé des erreurs et des messages enregistrés via error_log() sur la base de votre configuration du niveau de journalisation. Le journal peut être affiché via l'interface admin.",

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
	'developers:entity_explorer:info:attributes' => 'Attributs',
	'developers:entity_explorer:info:metadata' => 'Métadonnées',
	'developers:entity_explorer:info:relationships' => 'Relations',
	'developers:entity_explorer:info:owned_acls' => 'Collections d\'accès possédées',
	'developers:entity_explorer:info:acl_memberships' => 'Appartenance aux collections d\'accès',
	'developers:entity_explorer:delete_entity' => 'Supprimer cette entité',
	'developers:entity_explorer:inspect_entity' => 'Inspecter cette entité',
	'developers:entity_explorer:view_entity' => 'Voir cette entité sur le site',
	
	// inspection
	'developers:inspect:actions' => 'Actions',
	'developers:inspect:events' => 'Événements',
	'developers:inspect:menus' => 'Menus',
	'developers:inspect:priority' => 'Priorité',
	'developers:inspect:seeders' => 'Semeurs',
	'developers:inspect:simplecache' => 'Cache simple',
	'developers:inspect:routes' => 'Routes',
	'developers:inspect:views' => 'Vues',
	'developers:inspect:views:all_filtered' => "<b>Note !</b> Toutes les entrées/sorties des vues sont filtrées par ces Événements :",
	'developers:inspect:views:input_filtered' => "(entrée filtrée par le gestionnaire d'événement : %s)",
	'developers:inspect:views:filtered' => "(filtré par le gestionnaire d'événement : %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Contexte',
	'developers:inspect:functions' => 'Fonctions',
	'developers:inspect:file_location' => 'Chemin depuis la racine Elgg ou le contrôleur',
	'developers:inspect:route' => 'Nom de la route',
	'developers:inspect:path' => 'Motif du chemin',
	'developers:inspect:resource' => 'Vue de la ressource',
	'developers:inspect:handler' => 'Gestionnaire',
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

	'admin:develop_tools:error_log' => 'Journal des erreurs',
	'developers:logs:empty' => 'Le journal des erreurs est vide',
);
