<?php
	/**
	 * translation by RONNEL Jérémy
	 * jeremy.ronnel@elbee.fr
	 * 
	 * Modified by Laurent Grangeau
	 * laurent.grangeau@gmail.com
	 */
     
	$french = array(

		/**
		 * Sites
		 */
	
			'item:site' => "Sites",
	
		/**
		 * Sessions
		 */
			
			'login' => "Connexion",
			'loginok' => "Vous êtes désormais connecté.",
			'loginerror' => "Nous n'avons pas pu vous connecter. Vérifiez votre nom d'utilisateur et votre mot de passe avant de réessayer.",
	
			'logout' => "Déconnexion",
			'logoutok' => "Vous avez été déconnecté.",
			'logouterror' => "Nous n'avons pas pu vous connecter. Essayez de nouveau.",
	
		/**
		 * Errors
		 */
			'exception:title' => "Bienvenue sur Elgg.",
	
			'InstallationException:CantCreateSite' => "Impossibilité de créer le site Elgg %s à l'adresse %s",
		
			'actionundefined' => "L'action demandée (%s) n'est pas définie par le système.",
			'actionloggedout' => "Désolé, vous devez être connecté pour exécuter cette action.",
	
			'notfound' => "La ressource demandée n'a pas été trouvée, ou peut être vous n'avez pas les autorisations nécessaire pour y accéder.",
			
			'SecurityException:Codeblock' => "Accès non autorisé pour la création de block.",
			'DatabaseException:WrongCredentials' => "Elgg n'a pas pu se connecter à la base de données %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Elgg n'a pas pu se sélectionner '%s', vérifiez qu'elle soit créée et que vous y avez accès.",
			'SecurityException:FunctionDenied' => "Accès non autorisé à la fonction '%s'.",
			'DatabaseException:DBSetupIssues' => "Le système a retourné ces problèmes: ",
			'DatabaseException:ScriptNotFound' => "Elgg n'a pas pu atteindre le script de données à %s.",
			
			'IOException:FailedToLoadGUID' => "Chargement échoué de %s avec le GUID:%d",
			'InvalidParameterException:NonElggObject' => "Types incompatibles, objet de type non-Elgg vers un constructeur d'objet Elgg !",
			'InvalidParameterException:UnrecognisedValue' => "Valeur de type non reconnu passée en argument.",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d n'est pas valide %s",
			
			'PluginException:MisconfiguredPlugin' => "%s est un plugin non configuré.",
			
			'InvalidParameterException:NonElggUser' => "Types incompatibles, utilisateur de type non-Elgg vers un constructeur d'utilisateur Elgg !",
			
			'InvalidParameterException:NonElggSite' => "Types incompatibles, site de type non-Elgg vers un constructeur de site Elgg !",
			
			'InvalidParameterException:NonElggGroup' => "Types incompatibles, groupe de type non-Elgg vers un constructeur de groupe Elgg !",
	
			'IOException:UnableToSaveNew' => "Impossible de sauvegarder nouveau %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID non spécifié durant l'export, ceci ne devrait pas se produire.",
			'InvalidParameterException:NonArrayReturnValue' => "La fonction de sérialisation de l'entité a retourné une valeur dont le type n'est pas un tableau.",
			
			'ConfigurationException:NoCachePath' => "Le chemin du cache est vide!",
			'IOException:NotDirectory' => "%s n'est pas un répertoire.",
			
			'IOException:BaseEntitySaveFailed' => "Impossibilité de sauver les informations de bases du nouvel objet!",
			'InvalidParameterException:UnexpectedODDClass' => "import() a passé un argument qui n'est pas du type ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "Le type d'entité doit être renseigné.",
			
			'ClassException:ClassnameNotClass' => "%s n'est pas %s.",
			'ClassNotFoundException:MissingClass' => "La classe '%s' n'a pas été trouvée, le plugin serait-il manquant?",
			'InstallationException:TypeNotSupported' => "Le type %s n'est pas supporté. Il y a une erreur dans votre installation, le plus souvent causé par une mise à jour non-complète.",

			'ImportException:ImportFailed' => "Impossible d'importer l'élément %d",
			'ImportException:ProblemSaving' => "Une erreur est survenue en sauvant %s",
			'ImportException:NoGUID' => "La nouvelle entité a été créée mais n'a pas de GUID, ceci ne devrait pas se produire.",
			
			'ImportException:GUIDNotFound' => "L'entité '%d' n'a pas été trouvée.",
			'ImportException:ProblemUpdatingMeta' => "Il y a eu un problème lors de la mise à jour de '%s' pour l'entité '%d'",
			
			'ExportException:NoSuchEntity' => "Aucune entité avec le GUID:%d", 
			
			'ImportException:NoODDElements' => "Aucun élément OpenDD n'a été trouvé dans les données importées, l'importation a échoué.",
			'ImportException:NotAllImported' => "Tous les éléments n'ont pas été importé.",
			
			'InvalidParameterException:UnrecognisedFileMode' => "Mode de fichier non-reconnu : '%s'",
			'InvalidParameterException:MissingOwner' => "Tous les fichiers doivent avoir un propriétaire",
			'IOException:CouldNotMake' => "Impossible de faire %s",
			'IOException:MissingFileName' => "Vous devez spécifier un nom avant d'ouvrir un fichier.",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "Fichier stockés non trouvé ou classes non sauvegardée avec le fichier!",
			'NotificationException:NoNotificationMethod' => "Aucune méthode de notification spécifiée.",
			'NotificationException:NoHandlerFound' => "Aucune fonction trouvée pour '%s' ou elle ne peut être appelé.",
			'NotificationException:ErrorNotifyingGuid' => "Une erreur s'est produite lors de la notification %d",
			'NotificationException:NoEmailAddress' => "Impossible de trouver une adresse email pour GUID:%d",
			'NotificationException:MissingParameter' => "Un argument obligatoire a été omis, '%s'",
			
			'DatabaseException:WhereSetNonQuery' => "La requête where ne contient pas de WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "Des champs sont manquants sur la requête de sélection.",
			'DatabaseException:UnspecifiedQueryType' => "Type de requête non-reconnue ou non-spécifiée.",
			'DatabaseException:NoTablesSpecified' => "Aucune table spécifiée pour la requête.",
			'DatabaseException:NoACL' => "Pas de liste d'accès fourni pour la requête",
			
			'InvalidParameterException:NoEntityFound' => "Aucune entité trouvée, soit elle est inexistante, soit vous n'y avez pas accès.",
			
			'InvalidParameterException:GUIDNotFound' => "GUID : %s n'a pas été trouvé ou vous n'y avez pas accès.",
			'InvalidParameterException:IdNotExistForGUID' => "Désolé, '%s' n'existe pas pour guid : %d",
			'InvalidParameterException:CanNotExportType' => "Désolé, je ne sais pas comment exporter '%s'",
			'InvalidParameterException:NoDataFound' => "Aucune donnée trouvée.",
			'InvalidParameterException:DoesNotBelong' => "N'appartient pas à l'entité.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "N'appartient pas ou aucune référence à l'entité.",
			'InvalidParameterException:MissingParameter' => "Argument manquant, il faut fournir un GUID.",
			
			'SecurityException:APIAccessDenied' => "Désolé, l'accès API a été désactivé par l'administrateur.",
			'SecurityException:NoAuthMethods' => "Aucune méthode d'authentification n'a été trouvé pour cette requête API.",
			'APIException:ApiResultUnknown' => "Les résultats de API sont de types inconnus, ceci ne devrait pas se produire.", 
			
			'ConfigurationException:NoSiteID' => "L'identifiant du site n'a pas été spécifiée.",
			'InvalidParameterException:UnrecognisedMethod' => "Appel à la méthode '%s' non-reconnu",
			'APIException:MissingParameterInMethod' => "Argument %s manquant pour la méthode %s",
			'APIException:ParameterNotArray' => "%s n'est semble t-il pas un tableau.",
			'APIException:UnrecognisedTypeCast' => "Type %s non reconnu pour la variable '%s' pour la fonction '%s'",
			'APIException:InvalidParameter' => "Paramètre invalide pour '%s' pour la fonction '%s'.",
			'APIException:FunctionParseError' => "%s(%s) a une erreur d'analyse.",
			'APIException:FunctionNoReturn' => "%s(%s) ne retourne aucune valeur.",
			'SecurityException:AuthTokenExpired' => "Le jeton d'authentification est manquant, invalide ou expiré.",
			'CallException:InvalidCallMethod' => "%s doit être appelé en utilisant '%s'",
			'APIException:MethodCallNotImplemented' => "L'appel à la méthode '%s' n'a pas été implémenté.",
			'APIException:AlgorithmNotSupported' => "L'algorithme '%s' n'est pas supporté ou a été désactivé.",
			'ConfigurationException:CacheDirNotSet' => "Le répertoire de cache 'cache_path' n'a pas été renseigné.",
			'APIException:NotGetOrPost' => "La méthode de requête doit être GET ou POST",
			'APIException:MissingAPIKey' => "X-Elgg-apikey manquant dans l'entête HTTP",
			'APIException:MissingHmac' => "X-Elgg-hmac manquant dans l'entête",
			'APIException:MissingHmacAlgo' => "X-Elgg-hmac-algo manquant dans l'entête",
			'APIException:MissingTime' => "X-Elgg-time manquant dans l'entête",
			'APIException:TemporalDrift' => "X-Elgg-time est trop éloigné dans le temps. Epoch a échoué.",
			'APIException:NoQueryString' => "Aucune valeur dans la requête",
			'APIException:MissingPOSTHash' => "X-Elgg-posthash manquant dans l'entête",
			'APIException:MissingPOSTAlgo' => "X-Elgg-posthash_algo manquant dans l'entête",
			'APIException:MissingContentType' => "Le content-type est manquant pour les données postées",
			'SecurityException:InvalidPostHash' => "La signature des données POST est invalide.%s attendu mais %s reçu.",
			'SecurityException:DupePacket' => "La signature du paquet a déjà été envoyée.",
			'SecurityException:InvalidAPIKey' => "API Key invalide ou non-reconnue.",
			'NotImplementedException:CallMethodNotImplemented' => "La méthode '%s' n'est pas supportée actuellement.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "L'appel à la méthode XML-RPC '%s' n'a pas été implémentée.",
			'InvalidParameterException:UnexpectedReturnFormat' => "L'appel à la méthode '%s' a retourné un résultat inattendu.",
			'CallException:NotRPCCall' => "L'appel ne semble pas être un appel XML-RPC valide",
	
			'PluginException:NoPluginName' => "Le nom du plugin n'a pas pu être trouvé",
				
			'ConfigurationException:BadDatabaseVersion' => "La version de la base de données ne correspondant pas au minimum requis par Elgg. Veuillez vous référer à la documentation de Elgg.",
			'ConfigurationException:BadPHPVersion' => "Elgg requiert au minimum PHP 5.2.",
			
			'InstallationException:DatarootNotWritable' => "Le répertoire des données %s n'est pas accessible en écriture.",
			'InstallationException:DatarootUnderPath' => "Le répertoire des données %s doit être en dehors de votre dossier d'installation de Elgg.",
			'InstallationException:DatarootBlank' => "Vous n'avez pas spécifié de dossier pour le stockage des fichiers.",
	
		/**
		 * User details
		 */

			'name' => "Nom à afficher",
			'email' => "Adresse mail",
			'username' => "Nom d'utilisateur",
			'password' => "Mot de passe",
			'passwordagain' => "Confirmation du mot de passe",
			'admin_option' => "Définir cet utilisateur comme administrateur ?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Privé",
			'ACCESS_LOGGED_IN' => "Utilisateurs connectés seulement",
			'ACCESS_PUBLIC' => "Publique",
			'PRIVATE' => "Privé",
			'LOGGED_IN' => "Utilisateurs connectés",
			'PUBLIC' => "Publique",
			'access' => "Accès",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Tableau de bord",
			'dashboard:nowidgets' => "Votre tableau de bord est votre page d'accueil sur le site. Cliquez sur 'Configurer la page' pour ajouter des widgets pour garder un oeil sur le contenu ou votre activité sur le site.",

			'widgets:add' => "Ajouter un widget à votre page",
			'widgets:add:description' => "Choisissez les fonctionnalités à faire apparaître en glissant un élément de <b>la liste de Widgets</b> sur la droite, vers l'une des trois zones ci-dessous. Positionnez-les selon vos désirs.

Pour retirer un widget, glissez le vers <b>la liste de Widgets</b>.",
			'widgets:position:fixed' => "(Position modifiée sur la page)",
	
			'widgets' => "Widgets",
			'widget' => "Widget",
			'item:object:widget' => "Widgets",
			'layout:customise' => "Personnaliser la mise en page",
			'widgets:gallery' => "Liste des widgets",
			'widgets:leftcolumn' => "Côté gauche",
			'widgets:fixed' => "Position modifiée",
			'widgets:middlecolumn' => "Milieu",
			'widgets:rightcolumn' => "Côté droit",
			'widgets:profilebox' => "Boite de profil",
			'widgets:panel:save:success' => "Vos widgets ont été sauvegardés avec succès.",
			'widgets:panel:save:failure' => "Un problème est survenu lors de l'enregistrement de vos widgets. Veuillez recommencer.",
			'widgets:save:success' => "Le widget a été sauvegarder avec succès.",
			'widgets:save:failure' => "Un problème est survenu lors de l'enregistrement de votre widget. Veuillez recommencer.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Groupe", 
			'item:group' => "Groupes",
	
		/**
		 * Profile
		 */
	
			'profile' => "Profil",
			'user' => "Utilisateur",
			'item:user' => "Utilisateurs",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Votre profil",
			'profile:user' => "Le profil de %s",
	
			'profile:edit' => "Edit profile",
			'profile:editicon' => "Uploader une nouvelle image de profil",
			'profile:profilepictureinstructions' => "Un avatar est une image qui sera associée à votre profil. <br /> Vous pouvez la changer autant de fois que vous le souhaitez. (Formats de fichiers acceptés: GIF, JPG ou PNG)",
			'profile:icon' => "Avatar",
			'profile:createicon' => "Créer votre avatar",
			'profile:currentavatar' => "Avatar actuel",
			'profile:createicon:header' => "Avatar",
			'profile:profilepicturecroppingtool' => "Outil de retaillage de l'avatar",
			'profile:createicon:instructions' => "Cliquez, maintenez le clic et glissez pour créer, à l'endroit désiré, à la taille désirée, une miniature de votre avatar.  Un aperçu sera disponible sur la droite de l'image.  Une fois la sélection réalisée, cliquez sur 'Créer la miniature' pour sauver les modifications. Cette miniature sera visualisable par les autres utilisateurs lors de leur navigation sur le site.",
	
			'profile:editdetails' => "Editer mon profil",
			'profile:editicon' => "Editer mon avatar",
	
			'profile:aboutme' => "A propos de moi", 
			'profile:description' => "A propos de moi",
			'profile:briefdescription' => "Brève description",
			'profile:location' => "Localisation géographique",
			'profile:skills' => "Expériences",  
			'profile:interests' => "Centres d'intérêt", 
			'profile:contactemail' => "Adresse email de contact",
			'profile:phone' => "Téléphone",
			'profile:mobile' => "Téléphone mobile",
			'profile:website' => "Site internet",

			'profile:river:update' => "%s a mis à jour son profil",
			'profile:river:iconupdate' => "%s a mis à jour son avatar",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "Votre profil a été sauvegardé.",
			'profile:icon:uploaded' => "Votre avatar a été sauvegardé.",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "Vous n'avez pas la permission de mettre à jour ce profil.",
			'profile:notfound' => "Désolé, le profil recherché n'a pas été trouvé.",
			'profile:cantedit' => "Désolé, vous ne pouvez pas éditer ce profil.",
			'profile:icon:notfound' => "Désolé, une erreur est survenue pendant le chargement de votre avatar.",
	
		/**
		 * Friends
		 */
	
			'friends' => "Amis",
			'friends:yours' => "Vos amis",
			'friends:owned' => "Les amis de %s",
			'friend:add' => "Ajouter en tant qu'ami(e)",
			'friend:remove' => "Supprimer cet(te) ami(e)",
	
			'friends:add:successful' => "%s fait désormais parti(e) de votre liste d'amis.",
			'friends:add:failure' => "%s n'a pas pu être ajouté(e) à votre liste d'amis. Veuillez réessayer.",
	
			'friends:remove:successful' => "%s ne fait plus parti(e) de votre liste d'amis.",
			'friends:remove:failure' => "%s n'a pas pu être retiré(e) de votre liste d'amis. Veuillez réessayer.",
	
			'friends:none' => "Cet utilisateur n'a pas encore d'ami.",
			'friends:none:you' => "Votre liste d'amis est vide! Trouvez des gens sur le site avec les mêmes centres d'intérêts.",
	
			'friends:none:found' => "Aucun ami trouvé.",
	
			'friends:of:none' => "Personne n'a ajouté cet personne sur sa liste d'amis.",
			'friends:of:none:you' => "Personne ne vous a ajouté sur sa liste d'amis. Rendez-vous visible sur la communauté; ajoutez du contenu, mettez votre profil à jour...",
	
			'friends:of' => "Liste d'amis",
			'friends:of:owned' => "Les amis de %s",

			 'friends:num_display' => "Nombre d'amis à afficher",
			 'friends:icon_size' => "Taille d'icône",
			 'friends:tiny' => "toute petite",
			 'friends:small' => "petite",
			 'friends' => "Amis",
			 'friends:of' => "Liste d'amis",
			 'friends:collections' => "Regroupements d'amis",
			 'friends:collections:add' => "Nouveau regroupement d'amis",
			 'friends:addfriends' => "Ajouter des amis",
			 'friends:collectionname' => "Nom du groupe d'amis",
			 'friends:collectionfriends' => "Amis de ce regroupement",
			 'friends:collectionedit' => "Editer ce regroupement",
			 'friends:nocollections' => "Vous n'avez pas encore de regroupement d'amis.",
			 'friends:collectiondeleted' => "Le regroupement a été supprimé.",
			 'friends:collectiondeletefailed' => "Impossible de supprimer le regroupement. Vous n'avez peut être pas les droits nécessaires, sinon il s'agit d'un autre problème.",
			 'friends:collectionadded' => "Le groupe d'amis a été créé avec succès",
			 'friends:nocollectionname' => "Le nom du groupe d'amis est obligatoire.",
		
	        'friends:river:created' => "%s a ajouté le widget amis.",
	        'friends:river:updated' => "%s a mis à jour son widget amis.",
	        'friends:river:delete' => "%s a supprimé son widget amis.",
	        'friends:river:add' => "%s a ajouté un ami.",
	
		/**
		 * Feeds
		 */
			'feed:rss' => "Souscrire au flux RSS",
			'feed:odd' => "Souscrire au flux OpenDD",
	
		/**
		 * River
		 */
			'river' => "River",			
			'river:relationship:friend' => "est désormais ami avec",

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "La configuration du plugin %s a été sauvegardée.",
			'plugins:settings:save:fail' => "Une erreur est survenue lors de la mise à jour du plugin %s.",
			'plugins:usersettings:save:ok' => "La configuration de l'utilisateur pour le plugin %s a été sauvegardée.",
			'plugins:usersettings:save:fail' => "Une erreur est survenue lors de la mise à jour de la configuration utilisateur pour le plugin %s.",
			
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "Configuration des alertes",
			'notifications:methods' => "Veuillez définir les méthodes à permettre.",
	
			'notifications:usersettings:save:ok' => "Votre configuration des alertes a été sauvegardée avec succès.",
			'notifications:usersettings:save:fail' => "Une erreur est survenue lors de la mise à jour de votre configuration des alertes.",
		/**
		 * Search
		 */
	
			'search' => "Rechercher",
			'searchtitle' => "Rechercher : %s",
			'advancedsearchtitle' => "%s résultat(s) trouvé(s) pour %s",
			'notfound' => "Aucun résultat.",
			'next' => "Suivant",
			'previous' => "Précédent",
	
			'viewtype:change' => "Changer le type de liste",
			'viewtype:list' => "Lister les vues",
			'viewtype:gallery' => "Galerie",
	
			'tag:search:startblurb' => "Eléments avec un tag correspondant à '%s':",

			'user:search:startblurb' => "Utilisateurs associés '%s':",
			'user:search:finishblurb' => "Plus de résultats...",
	
		/**
		 * Account
		 */
	
			'account' => "Compte",
			'settings' => "Configuration",
            'tools' => "Outils",
            'tools:yours' => "Vos outils",
			
			'register' => "Inscription",
			'registerok' => "L'inscription de %s a bien été prise en compte. Pour activer votre compte, veuillez cliquer sur le lien se trouvant dans le mail que nous venons de vous envoyer.",
			'registerbad' => "Une erreur est survenue durant l'inscription. Le nom d'utilisateur existe déjà peut-être, vos mots de passe ne coïncident pas, ou peut être que votre nom d'utilisateur ou mot de passe sont trop court.",
			'registerdisabled' => "Les inscriptions ont été suspendues par l'administrateur.",
	
			'registration:notemail' => "L'adresse email fournie n'est pas valide.",
			'registration:userexists' => "Ce nom d'utilisateur existe déjà.",
			'registration:usernametooshort' => "Le nom d'utilisateur doit comporter au minimum 4 lettres.",
			'registration:passwordtooshort' => "Le mot de passe doit comporter au minimum 6 lettres.",
			'registration:dupeemail' => "Cette adresse mail est déjà enregistrée.",
            'registration:invalidchars' => "Votre adresse mail contient des caractères invalides.",
			'registration:emailnotvalid' => "L'adresse email est invalide.",
			'registration:passwordnotvalid' => "Le mot de passe renseigné est invalide",
			'registration:usernamenotvalid' => "Le nom d'utilisateur renseigné est invalide",
	
			'adduser' => "Ajouter un utilisateur",
			'adduser:ok' => "Le nouvel utilisateur a été créé.",
			'adduser:bad' => "Le nouvel utilisateur n'a pas pu être créé.",
	
			'user:set:name' => "Nom du compte utilisateur",
			'user:name:label' => "Votre nom",
			'user:name:success' => "Votre nom a été sauvegardé.",
			'user:name:fail' => "Une erreur s'est produite lors de la sauvegarde du nom.",
	
			'user:set:password' => "Mot de passe du compte utilisateur",
			'user:password:label' => "Votre nouveau mot de passe",
			'user:password2:label' => "Confirmer le nouveau mot de passe",
			'user:password:success' => "Le mot de passe a été sauvegarder.",
			'user:password:fail' => "Une erreur s'est produite lors de la sauvegarde du mot de passe.",
			'user:password:fail:notsame' => "Les mots de passe diffèrent !",
			'user:password:fail:tooshort' => "Le mot de passe est trop court !",
	
			'user:set:language' => "Langue du compte utilisateur",
			'user:language:label' => "Votre langue",
			'user:language:success' => "La langue du compte a été sauvegardée.",
			'user:language:fail' => "Une erreur est survenue lors de la sauvegarde de la langue du compte.",
	
			'user:username:notfound' => "Le nom d'utilisateur %s n'a pas été trouvé.",
	
			'user:password:lost' => "Mot de passe perdu",
			'user:password:resetreq:success' => "Votre nouveau mot de passe vous a été envoyé.",
			'user:password:resetreq:fail' => "Erreur lors de l'envoie d'un nouveau de passe.",
	
			'user:password:text' => "Pour obtenir un nouveau mot de passe, entrez votre nom d'utilisateur ci-dessous. Vous recevrez un lien de vérification pour l'obtention d'un nouveau mot de passe, celui-ci vous sera alors envoyé par email.",
	
		/**
		 * Administration
		 */

			'admin:configuration:success' => "La configuration a été sauvegardée.",
			'admin:configuration:fail' => "Une erreur s'est produite lors de la sauvegarde de la configuration.",
	
			'admin' => "Administration",
			'admin:description' => "Le panneau d'administration vous permet de controler les fonctionnalités et l'aspect du site, Choisissez une option ci-dessous pour continuer.",
			
			'admin:user' => "Gestion des utilisateurs",
			'admin:user:description' => "Ce panneau de configuration vous permet de gérer les inscriptions à votre site. Choisissez une option ci-dessous pour continuer.",
			'admin:user:adduser:label' => "Ajouter un nouvel utilisateur.",
			'admin:user:opt:linktext' => "Configurer les utilisateurs.",
			'admin:user:opt:description' => "Configurer les utilisateurs et leurs informations.",
			
			'admin:site' => "Gestion du site",
			'admin:site:description' => "Ce panneau de configuration vous permet de gérer les paramètres globaux du site. Choisissez une option pour continuer.",
			'admin:site:opt:linktext' => "Configurer le site.",
			'admin:site:opt:description' => "Configurer les paramètres techniques et non-techniques du site.",
			
			'admin:plugins' => "Gestion des outils",
			'admin:plugins:description' => "Ce panneau de configuration vous permet de gérer et de configurer les outils disponibles sur votre site. Choisissez une option pour continuer.",
			'admin:plugins:opt:linktext' => "Configurer l'outil",
			'admin:plugins:opt:description' => "Configurer les outils installés sur le site.",
			'admin:plugins:label:author' => "Auteur",
			'admin:plugins:label:copyright' => "Copyright",
			'admin:plugins:label:licence' => "License",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "Le plugin %s a été désactivé avec succès.",
			'admin:plugins:disable:no' => "Le plugin %s ne peut pas être désactivé.",
			'admin:plugins:enable:yes' => "Le plugin %s a été activé avec succès.",
			'admin:plugins:enable:no' => "Le plugin %s ne peut pas être activé.",
	
			'admin:statistics' => "Statistiques",
			'admin:statistics:description' => "Visualiser les statistiques d'accès à votre site. Pour plus d'informations un outil plus complet est disponible.",
			'admin:statistics:opt:description' => "Visualiser les statistiques relatives aux utilisateurs et objets du site.",
			'admin:statistics:opt:linktext' => "Voir les statistiques...",
			'admin:statistics:label:basic' => "Statistiques basiques du site",
			'admin:statistics:label:numentities' => "Les entités du site",
			'admin:statistics:label:numusers' => "Nombre d'utilisateurs",
			'admin:statistics:label:numonline' => "Nombre d'utilisateurs en ligne",
			'admin:statistics:label:onlineusers' => "Utilisateur en ligne actuellement",
			'admin:statistics:label:version' => "Version de Elgg",
			'admin:statistics:label:version:release' => "Release",
			'admin:statistics:label:version:version' => "Version",
	
			'admin:user:label:search' => "Trouver un utilisateur :",
			'admin:user:label:seachbutton' => "Rechercher", 
	
			'admin:user:ban:no' => "L'utilisateur ne peut pas être banni",
			'admin:user:ban:yes' => "Utilisateur banni !",
			'admin:user:unban:no' => "L'utilisateur ne peut pas être réintégré au site",
			'admin:user:unban:yes' => "Utilisateur réintégré",
			'admin:user:delete:no' => "L'utilisateur ne peut pas être supprimé",
			'admin:user:delete:yes' => "Utilisateur supprimé",
	
			'admin:user:resetpassword:yes' => "Le mot de passe a été réinitialisé.",
			'admin:user:resetpassword:no' => "Le mot de passe ne peut pas être réinitialisé.",
	
			'admin:user:makeadmin:yes' => "L'utilisateur est désormais administrateur.",
			'admin:user:makeadmin:no' => "L'utilisateur ne peut pas devenir administrateur.",
			
		/**
		 * User settings
		 */
			'usersettings:description' => "Le panneau d'administration de compte vous permettra de modifier vos informations, ainsi que le fonctionnement de vos outils. Choisissez une options pour continuer.",
	
			'usersettings:statistics' => "Vos statistiques",
			'usersettings:statistics:opt:description' => "Visualiser les statistiques des utilisateurs et des objets sur votre espace.",
			'usersettings:statistics:opt:linktext' => "Statistiques de votre compte.",
	
			'usersettings:user' => "Vos paramètres",
			'usersettings:user:opt:description' => "Editez vos informations.",
			'usersettings:user:opt:linktext' => "Editer vos informations personnelles",
	
			'usersettings:plugins' => "Outils",
			'usersettings:plugins:opt:description' => "Configurez vos outils.",
			'usersettings:plugins:opt:linktext' => "Configurez vos outils...",
	
			'usersettings:plugins:description' => "Ce panneau de configuration vous permez de mettre à jour les options de vos outils installés par l'administrateur.",
			'usersettings:statistics:label:numentities' => "Vos entités",
	
			'usersettings:statistics:yourdetails' => "Vos informations",
			'usersettings:statistics:label:name' => "Votre nom",
			'usersettings:statistics:label:email' => "Email",
			'usersettings:statistics:label:membersince' => "Membre depuis",
			'usersettings:statistics:label:lastlogin' => "Dernière connexion",
	
			
	
		/**
		 * Generic action words
		 */
	
			'save' => "Enregistrer",
			'cancel' => "Annuler",
			'saving' => "Sauvegarde en cours...",
			'update' => "Mettre à jour",
			'edit' => "Editer",
			'delete' => "Supprimer",
			'load' => "Charger",
			'upload' => "Charger",
			'ban' => "Bannir",
			'unban' => "Réintégrer",
			'enable' => "Activer",
			'disable' => "Désactiver",
			'request' => "Requête",
			'complete' => "Compléter",
	
			'invite' => "Inviter",
	
			'resetpassword' => "Réinitialiser le mot de passe",
			'makeadmin' => "Faire devenir administrateur",
	
			'option:yes' => "Oui",
			'option:no' => "Non",
	
			'unknown' => "Inconnu",
	
			'learnmore' => "Cliquer pour connaître d'avantage.",
	
			'content' => "contenu",
			'content:latest' => "Dernières activités",
			'content:latest:blurb' => "Cliquez ici pour voir les dernières activités sur le site.",
	
		/**
		 * Generic data words
		 */
	
			'title' => "Titre",
			'description' => "Description",
			'tags' => "Tags",
			'spotlight' => "Projecteur sur",
			'all' => "Tous",
	
			'by' => "par",
	
			'annotations' => "Annotations",
			'relationships' => "Relations",
			'metadata' => "Métadonnées",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "Etes vous sur de vouloir supprimer cet élément?",
			'fileexists' => "Un fichier a déjà été uploadé. Pour le remplacer, sélectionnez-le ci-dessous:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "L'import des données a été réalisée avec succès",
			'importfail' => "L'import OpenDD des données a échouée.",
	
		/**
		 * Time
		 */
	
			'friendlytime:justnow' => "à l'instant",
			'friendlytime:minutes' => "il y a %s minutes",
			'friendlytime:minutes:singular' => "il y a une minute",
			'friendlytime:hours' => "il y a %s heures",
			'friendlytime:hours:singular' => "il y a une heure",
			'friendlytime:days' => "il y a %s jours",
			'friendlytime:days:singular' => "hier",
	
		/**
		 * Installation and system settings
		 */
	
			'installation:error:htaccess' => "Elgg requiert un fichier .htaccess à la racine de n'installation. L'écriture automatique du fichier par l'installeur a échoué. 

La création de ce fichier est facile. Copiez-collez le texte ci-dessous dans un fichier texte que vous nommerez .htaccess

",
			'installation:error:settings' => "Elgg requiert un fichier de configuration. Pour continuer :

1. Renommez 'engine/settings.example.php' en 'settings.php' dans le répertoire d'installation de Elgg.

2. Editer le fichier avec le bloc-notes et entrez les informations relatives à votre base de données MySQL. Si vous ne les connaissez pas contacter votre administrateur ou le support technique.

Elgg peut créer ce fichier pour vous, entrez les informations ci-dessous...",
	
			'installation:error:configuration' => "Une fois les corrections de configuration apportées, pressez 'Réessayer'.",
	
			'installation' => "Installation",
			'installation:success' => "La base de données a été créée avec succès.",
			'installation:configuration:success' => "Votre configuration initiale a été sauvegardée. Désormais enregistrer un premier utilisateur; il sera administrateur du système.",
	
			'installation:settings' => "Configuration du système",
			'installation:settings:description' => "Désormais la base de données de Elgg est installée, entrez quelques informations supplémentaires relatives à votre site. Certaines de ces informations sont automatiquement renseignées, <b>veuillez vérifier ces détails.</b>",
	
			'installation:settings:dbwizard:prompt' => "Entrez la configuration de votre base de données ci-dessous:",
			'installation:settings:dbwizard:label:user' => "Utilisateur",
			'installation:settings:dbwizard:label:pass' => "Mot de passe",
			'installation:settings:dbwizard:label:dbname' => "Nom de la base",
			'installation:settings:dbwizard:label:host' => "Serveur hôte (le plus souvent 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Préfixe des tables de données (le plus souvent 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "La création du fichier 'settings.php' a échoué. Copiez-collez le texte ci-dessous dans un fichier texte 'engine/settings.php'.",
	
			'installation:sitename' => "Nom du site (par exemple \"Ma communauté\"):",
			'installation:sitedescription' => "Brève description du site (optionnel)",
			'installation:wwwroot' => "Adresse du site internet suivi de '\' :",
			'installation:path' => "Chemin physique des fichiers sur le serveur suivi de '\' :",
			'installation:dataroot' => "Chemin complet où héberger les fichiers uploadés par les utilisateurs suivi de '\' :",
            'installation:dataroot:warning' => "Vous devez créer ce dossier manuellement. Il doit se trouver en dehors de votre installation de Elgg.",
			'installation:language' => "Langue par défaut du site :",
			'installation:debug' => "Le mode de débogage permet de mettre en évidence certaines erreurs de fonctionnement, cependant il ralenti l'accès au site, il est à utiliser uniquement en cas de problème :",
			'installation:debug:label' => "Activer le mode debug",
			'installation:usage' => "Cette option permet l'envoie de statistiques anonyme vers Curverider.",
			'installation:usage:label' => "Envoyer des statistiques anonymement",
			'installation:view' => "Entrer le nom de la vue qui sera utilisée automatiquement pour l'affichage du site (par exemple : 'mobile'), laissez \"default\" en cas de doute :",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Bienvenue %s",
			'welcome_message' => "Bienvenue dans l'installation de Elgg.",
	
		/**
		 * Emails
		 */
			'email:settings' => "Paramètre email",
			'email:address:label' => "Adresse email",
			
			'email:save:success' => "Nouvelle adresse sauvegardée, un email de validation vous a été envoyé.",
			'email:save:fail' => "Une erreur est survenue lors de la sauvegarde de votre adresse email, veuillez recommencer.",
	
			'email:confirm:success' => "Adresse email confirmée !",
			'email:confirm:fail' => "Votre email n'a pas pu été vérifié...",
	
			'friend:newfriend:subject' => "%s est devenu(e) votre ami(e) !",
			'friend:newfriend:body' => "%s est devenu(e) votre ami(e) !

Pour voir son profil cliquez ici:

	%s

Ne répondez pas à ce mail.",
	
	
			'email:validate:subject' => "%s, veuillez confirmer votre adresse email !",
			'email:validate:body' => "Salut %s,

Veuillez s'il vous plait confirmer votre adresse email en suivant le lien suivant:

%s
",
			'email:validate:success:subject' => "Email validé %s !",
			'email:validate:success:body' => "Salut %s,
			
Féliciations, votre adresse email a été validée, vous pouvez désormais vous connecter sur notre site.

Merci.",
	
	
			'email:resetpassword:subject' => "Mot de passe réinitialisé !",
			'email:resetpassword:body' => "Salut %s,
			
Voici votre nouveau mot de passe : %s",
	
	
			'email:resetreq:subject' => "Mot de passe oublié ?!",
			'email:resetreq:body' => "Salut %s,
			
Une personne (avec l'adresse IP %s) a réclamé un nouveau mot de passe pour votre compte.

S'il s'agit bien de vous, cliquez sur le lien suivant, autrement veuillez ignorer cet email.

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata' => "Données d'entrées manquantes.",
	
		/**
		 * Comments
		 */
	
			'comments:count' => "%s commentaire(s)",
			'generic_comments:add' => "Ajouter un commentaire",
			'generic_comments:text' => "Commentaire",
			'generic_comment:posted' => "Votre commentaire a été sauvegardé.",
			'generic_comment:deleted' => "Votre commentaire a été supprimé.",
			'generic_comment:blank' => "Veuillez remplir le champ texte avant de soumettre votre commentaire.",
			'generic_comment:notfound' => "Impossible de trouver l'élément spécifié.",
			'generic_comment:notdeleted' => "Une erreur est survenue lors de l'enregistrement de votre commentaire.",
			'generic_comment:failure' => "Une erreur est survenue lors de la sauvegarde de votre commentaire, veuillez recommencer.",
	
			'generic_comment:email:subject' => "Vous avez un nouveau commentaire !",
			'generic_comment:email:body' => "Vous avez un nouveau commentaire sur l'objet \"%s\" de %s. Voici son contenu :

			
%s


Pour répondre ou voir le contenu de référence, suivez le lien :

	%s

Pour voir le profil de %s, suivez ce lien :

	%s

Ne répondez pas à ce mail.",
	
		/**
		 * Entities
		 */
			'entity:default:strapline' => "Créé le %s par %s",
			'entity:default:missingsupport:popup' => "Impossible d'afficher le contenu correctement. Le problème réside sans doute dans la disparition d'un plugin supprimé, veuillez contacter l'administrateur du site.",
	
			'entity:delete:success' => "Le contenu %s a été supprimé",
			'entity:delete:fail' => "Une erreur est survenue lors de la suppression de %s. Impossible de supprimer le contenu.",
	
	
		/**
		 * Action gatekeeper
		 */
			'actiongatekeeper:missingfields' => "Il manque les champs __token ou __ts dans le formulaire.",
			'actiongatekeeper:tokeninvalid' => "Les données transmises ne correspondent pas à celles attendues par le serveur.",
			'actiongatekeeper:timeerror' => "Le formulaire a expiré, rafraichissez et recommencez à nouveau.",
			'actiongatekeeper:pluginprevents' => "Une extension a empêchée ce formulaire d'envoyer ses données.",
	
		/**
		 * Languages according to ISO 639-1
		 */
			"aa" => "Afar",
			"ab" => "Abkhazian",
			"af" => "Africain",
			"am" => "Amharic",
			"ar" => "Arabe",
			"as" => "Assamese",
			"ay" => "Aymara",
			"az" => "Azerbaijani",
			"ba" => "Bashkir",
			"be" => "Bielorusse",
			"bg" => "Bulgare",
			"bh" => "Bihari",
			"bi" => "Bislama",
			"bn" => "Bengali; Bangla",
			"bo" => "Tibetin",
			"br" => "Breton",
			"ca" => "Catalan",
			"co" => "Corse",
			"cs" => "Tchèque",
			"cy" => "Welsh",
			"da" => "Danois",
			"de" => "Allemand",
			"dz" => "Bhutani",
			"el" => "Grecques",
			"en" => "Anglais",
			"eo" => "Esperanto",
			"es" => "Espagnol",
			"et" => "Estonian",
			"eu" => "Basque",
			"fa" => "Perse",
			"fi" => "Finnois",
			"fj" => "Fiji",
			"fo" => "Faeroese",
			"fr" => "Français",
			"fy" => "Frisian",
			"ga" => "Irlandais",
			"gd" => "Écossais / Gaélique",
			"gl" => "Gaélique",
			"gn" => "Guarani",
			"gu" => "Gujarati",
			"he" => "Hebreu",
			"ha" => "Hausa",
			"hi" => "Hindi",
			"hr" => "Croate",
			"hu" => "Hongrois",
			"hy" => "Armenien",
			"ia" => "Interlingua",
			"id" => "Indonésien",
			"ie" => "Interlingue",
			"ik" => "Inupiak",
			"in" => "Indonésien",
			"is" => "Islandais",
			"it" => "Italien",
			"iu" => "Inuktitut",
			"iw" => "Hébreu (obsolete)",
			"ja" => "Japonnais",
			"ji" => "Yiddish (obsolete)",
			"jw" => "Javanais",
			"ka" => "Georgien",
			"kk" => "Kazakh",
			"kl" => "Greenlandic",
			"km" => "Cambodgien",
			"kn" => "Kannada",
			"ko" => "Coréen",
			"ks" => "Kashmiri",
			"ku" => "Kurdish",
			"ky" => "Kirghiz",
			"la" => "Latin",
			"ln" => "Lingala",
			"lo" => "Laothian",
			"lt" => "Lithuanian",
			"lv" => "Latvian/Lettish",
			"mg" => "Malagasy",
			"mi" => "Maori",
			"mk" => "Macédonien",
			"ml" => "Malayalam",
			"mn" => "Mongol",
			"mo" => "Moldavian",
			"mr" => "Marathi",
			"ms" => "Malay",
			"mt" => "Maltese",
			"my" => "Burmese",
			"na" => "Nauru",
			"ne" => "Nepali",
			"nl" => "Hollandais",
			"no" => "Norvégien",
			"oc" => "Occitan",
			"om" => "(Afan) Oromo",
			"or" => "Oriya",
			"pa" => "Punjabi",
			"pl" => "Polonais",
			"ps" => "Pashto / Pushto",
			"pt" => "Portuguais",
			"qu" => "Quechua",
			"rm" => "Rhaeto-Romance",
			"rn" => "Kirundi",
			"ro" => "Roumain",
			"ru" => "Russe",
			"rw" => "Kinyarwanda",
			"sa" => "Sanskrit",
			"sd" => "Sindhi",
			"sg" => "Sangro",
			"sh" => "Serbo-Croate",
			"si" => "Singhalese",
			"sk" => "Slovak",
			"sl" => "Slovène",
			"sm" => "Samoan",
			"sn" => "Shona",
			"so" => "Somali",
			"sq" => "Albanian",
			"sr" => "Serbe",
			"ss" => "Siswati",
			"st" => "Sesotho",
			"su" => "Sundanese",
			"sv" => "Suèdois",
			"sw" => "Swahili",
			"ta" => "Tamil",
			"te" => "Tegulu",
			"tg" => "Tajik",
			"th" => "Thaïlandais",
			"ti" => "Tigrinya",
			"tk" => "Turkmen",
			"tl" => "Tagalog",
			"tn" => "Setswana",
			"to" => "Tonga",
			"tr" => "Turque",
			"ts" => "Tsonga",
			"tt" => "Tatar",
			"tw" => "Twi",
			"ug" => "Uigur",
			"uk" => "Ukrainien",
			"ur" => "Urdu",
			"uz" => "Uzbek",
			"vi" => "Vietnamien",
			"vo" => "Volapuk",
			"wo" => "Wolof",
			"xh" => "Xhosa",
			"y" => "Yiddish",
			"yo" => "Yoruba",
			"za" => "Zuang",
			"zh" => "Chinois",
			"zu" => "Zulu",
	);
	
	add_translation("fr",$french);

?>
