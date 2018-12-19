<?php

return array(
	'profile' => 'Profil',
	'profile:notfound' => 'Désolé, nous n\'avons pas pu trouver le profil demandé.',
	'profile:upgrade:2017040700:title' => 'Migrer le schéma des champs du profil',
	'profile:upgrade:2017040700:description' => 'Cette migration convertit les champs du profil depuis des métadonnées vers des annotations dont le nom est préfixé par "profile:". <strong>Note:</strong> Si vous avez des champs du profil "inactifs" que vous souhaitez migrer, re-créez ces champs
et re-chargez cette page pour vous assurer qu\'ils sont bien migrés.',
	
	'admin:configure_utilities:profile_fields' => 'Modifier les champs du profil',
	
	'profile:edit' => 'Modifier le profil',
	'profile:aboutme' => "A mon propos",
	'profile:description' => "A mon propos",
	'profile:briefdescription' => "Brève description",
	'profile:location' => "Adresse",
	'profile:skills' => "Compétences",
	'profile:interests' => "Centres d'intérêt",
	'profile:contactemail' => "Email de contact",
	'profile:phone' => "Téléphone",
	'profile:mobile' => "Téléphone portable",
	'profile:website' => "Site internet",
	'profile:twitter' => "Identifiant Twitter",
	'profile:saved' => "Votre profil a bien été enregistré.",

	'profile:field:text' => 'Texte court',
	'profile:field:longtext' => 'Texte long',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Adresse web',
	'profile:field:email' => 'Adresse email',
	'profile:field:location' => 'Adresse',
	'profile:field:date' => 'Date',

	'profile:edit:default' => 'Modifier les champs du profil',
	'profile:label' => "Label du profil",
	'profile:type' => "Type de profil",
	'profile:editdefault:delete:fail' => 'La suppression du champ de profil a échoué',
	'profile:editdefault:delete:success' => 'Champ de profil supprimé',
	'profile:defaultprofile:reset' => 'Réinitialisation des champs de profil aux valeurs système par défaut',
	'profile:resetdefault' => 'Réinitialiser les champs de profil aux valeurs système par défaut',
	'profile:resetdefault:confirm' => 'Confirmez-vous vouloir supprimer vos champs de profil personnalisés ?',
	'profile:explainchangefields' => "Vous pouvez remplacer les champs du profil actuels par les vôtres.
Cliquez sur le bouton 'Ajouter' et donnez un label au nouveau champ de profil, par exemple 'Equipe favorite', puis sélectionnez le type de champ (eg. text, url, tags).
Pour ré-organiser les champs, cliquez au niveau de la poignée située à côté du label du champ.
Pour modifier le label d'un champ - cliquez sur l'icône d'édition.

A tout moment vous pouvez revenir au profil par défaut, mais vous perdrez les informations déjà saisies dans les pages de profil.",
	'profile:editdefault:success' => 'Nouveau champ de profil ajouté',
	'profile:editdefault:fail' => 'Le profil par défaut n\'a pas pu être chargé',
	'profile:noaccess' => "Vous n'avez pas la permission de modifier ce profil.",
	'profile:invalid_email' => '%s doit être une adresse email valide',
);
