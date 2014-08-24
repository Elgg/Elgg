<?php
return array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Signets",
	'bookmarks:add' => "Ajouter un signet",
	'bookmarks:edit' => "Modifier le signet",
	'bookmarks:owner' => "Les signets de %s",
	'bookmarks:friends' => "Signets des contacts",
	'bookmarks:everyone' => "Tous les signets du site",
	'bookmarks:this' => "Mettre cette page en signet",
	'bookmarks:this:group' => "Mettre en signet dans %s",
	'bookmarks:bookmarklet' => "Récupérer le 'bookmarklet'",
	'bookmarks:bookmarklet:group' => "Récupérer le 'bookmarklet' du groupe",
	'bookmarks:inbox' => "Boîte de réception des signets",
	'bookmarks:with' => "Partager avec",
	'bookmarks:new' => "Un nouveau signet",
	'bookmarks:address' => "Adresse de la ressource à ajouter à vos signets",
	'bookmarks:none' => 'Aucun signet',

	'bookmarks:notify:summary' => 'Nouveau signet nommé %s',
	'bookmarks:notify:subject' => 'Nouveau signet: %s',
	'bookmarks:notify:body' =>
'%s a ajouté un nouveau signet: %s

Adresse: %s

%s

Voir et commenter ce signet: 
%s
',

	'bookmarks:delete:confirm' => "Etes-vous sûr(e) de vouloir supprimer cette ressource ?",

	'bookmarks:numbertodisplay' => 'Nombre de signets à afficher',

	'bookmarks:shared' => "Mis en signet",
	'bookmarks:visit' => "Voir la ressource",
	'bookmarks:recent' => "Signets récents",

	'river:create:object:bookmarks' => '%s mis en signet %s',
	'river:comment:object:bookmarks' => '%s a commenté le signet %s',
	'bookmarks:river:annotate' => 'a posté un commentaire sur ce signet',
	'bookmarks:river:item' => 'un élément',

	'item:object:bookmarks' => 'Eléments mis en signets',

	'bookmarks:group' => 'Signets du groupe',
	'bookmarks:enablebookmarks' => 'Activer les signets du groupe',
	'bookmarks:nogroup' => 'Ce groupe n\'a pas encore de signets',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Ce widget affiche vos derniers signets.",

	'bookmarks:bookmarklet:description' =>
			"Le bookmarklet vous permet de partager ce que vous trouvez sur le web avec vos contacts, ou pour vous-même. Pour l'utiliser, glissez simplement le bouton ci-dessous dans votre barre de liens de votre navigateur.",

	'bookmarks:bookmarklet:descriptionie' =>
			"Si vous utilisez Internet Explorer, faites un clic droit sur le bouton et ajoutez le dans vos favoris, puis dans votre barre de liens.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Vous pouvez mettre en signet n'importe quelle page en cliquant sur le bookmarklet.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Votre élément a bien été mis en signet.",
	'bookmarks:delete:success' => "Votre signet a bien été supprimé.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Votre signet n'a pas pu être enregistré. Vérifiez que le titre et le lien sont corrects et réessayez.",
	'bookmarks:save:invalid' => "L’adresse du signet est invalide et ne peut donc pas être sauvegardée.",
	'bookmarks:delete:failed' => "Votre signet n'a pas pu être supprimé. Merci de réessayer.",
	'bookmarks:unknown_bookmark' => 'Impossible de trouver le signet spécifié',
);
