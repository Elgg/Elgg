<?php
return array(
	// menu
	'admin:develop_tools' => 'Outils',
	'admin:develop_tools:sandbox' => 'Thème bac à sable',
	'admin:develop_tools:inspect' => 'Inspecter',
	'admin:develop_tools:unit_tests' => 'Tests unitaires',
	'admin:developers' => 'Les développeurs',
	'admin:developers:settings' => 'Paramètres développeurs',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Pour vos dévelopements et déboguage, contrôlez les paramètres ci-dessous. Certains de ces paramètres sont aussi disponibles sur d\'autres pages d\'administration.',
	'developers:label:simple_cache' => 'Utiliser le cache simple',
	'developers:help:simple_cache' => 'Désactiver le fichier cache lors des développements. Autrement, les mises à jours des fichiers CSS et JavaScript seront ignorés.',
	'developers:label:system_cache' => 'Utiliser le cache système',
	'developers:help:system_cache' => 'Désactiver le fichier cache lors des développements. Autrement, les changements concernant vos plugins ne seront pas pris en compte.',
	'developers:label:debug_level' => "Niveau de suivi des traces",
	'developers:help:debug_level' => "Contrôle la quantité d'informations enregistrées. Voir elgg_log() pour plus d'informations.",
	'developers:label:display_errors' => 'Affichage des erreurs PHP fatales',
	'developers:help:display_errors' => "Par défaut, le fichier .htaccess d'Elgg supprime l'affichage des erreurs fatales.",
	'developers:label:screen_log' => "Journal à l'écran",
	'developers:help:screen_log' => "Affiche les sorties elgg_log() et elgg_dump() sur la page web.",
	'developers:label:show_strings' => "Montrer les chaînes de traduction brutes",
	'developers:help:show_strings' => "Affiche les chaînes de traduction utilisées par elgg_echo().",
	'developers:label:wrap_views' => "Vues contractées",
	'developers:help:wrap_views' => "Cela regroupe presque toutes les vues avec les commentaires en HTML. C'est utile pour trouver la vue permettant de créer un code HTML particulier. 
⇥⇥⇥⇥⇥⇥⇥⇥⇥Cela peut casser les vues non HTML de l'affichage principal. Voir developers_wrap_views() pour plus de détails. ",
	'developers:label:log_events' => "Journaux des évènements et interceptions des plugins (hooks).",
	'developers:help:log_events' => "Ecrit les événements et les interceptions plugins (hooks) dans le journal. Attention: il y en a beaucoup par page.",

	'developers:debug:off' => 'Arrêt',
	'developers:debug:error' => 'Erreur',
	'developers:debug:warning' => 'Avertissement',
	'developers:debug:notice' => 'Avis',
	'developers:debug:info' => 'Information',
	
	// inspection
	'developers:inspect:help' => 'Inspecter la configuration système d\'Elgg.',

	// event logging
	'developers:event_log_msg' => "%s : '%s, %s' dans %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Introduction',
	'theme_sandbox:breakout' => 'Sortir de l\'iframe',
	'theme_sandbox:buttons' => 'Boutons',
	'theme_sandbox:components' => 'Composants',
	'theme_sandbox:forms' => 'Formulaires',
	'theme_sandbox:grid' => 'Grille',
	'theme_sandbox:icons' => 'Icônes',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Mises en page',
	'theme_sandbox:modules' => 'Modules',
	'theme_sandbox:navigation' => 'Navigation',
	'theme_sandbox:typography' => 'Typographie',

	'theme_sandbox:icons:blurb' => 'Utiliser <em>elgg_view_icon($name)</em> ou la classe elgg-icon-$name pour afficher les icônes. ',

	// unit tests
	'developers:unit_tests:description' => 'Elgg a des tests unitaires et d\'intégration pour détecter des bugs dans les classes et fonctions de son coeur.',
	'developers:unit_tests:warning' => 'Attention : Ne pas exécuter ces tests sur un site en Production. Ils peuvent corrompre votre base de données.',
	'developers:unit_tests:run' => 'Exécuter',

	// status messages
	'developers:settings:success' => 'Paramètres sauvegardés',
);
