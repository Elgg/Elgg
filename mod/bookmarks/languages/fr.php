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
	'bookmarks:bookmarklet' => "Installer le \"bookmarklet\"",
	'bookmarks:bookmarklet:group' => "Installer le \"bookmarklet\" du groupe",
	'bookmarks:inbox' => "Boîte de réception des signets",
	'bookmarks:with' => "Partager avec",
	'bookmarks:new' => "Un nouveau signet",
	'bookmarks:address' => "Adresse web (URL) de la ressource à ajouter à vos signets",
	'bookmarks:none' => 'Aucun signet',

	'bookmarks:notify:summary' => 'Nouveau signet intitulé %s',
	'bookmarks:notify:subject' => 'Nouveau signet: %s',
	'bookmarks:notify:body' =>
'%s a ajouté un nouveau signet: %s

Adresse: %s

%s

Voir et commenter ce signet: 
%s
',

	'bookmarks:delete:confirm' => "Confirmez-vous vouloir supprimer cette ressource ?",

	'bookmarks:numbertodisplay' => 'Nombre de signets à afficher',

	'bookmarks:shared' => "Mis en signet",
	'bookmarks:visit' => "Visiter la ressource",
	'bookmarks:recent' => "Signets récents",

	'river:create:object:bookmarks' => '%s a mis en signet %s',
	'river:comment:object:bookmarks' => '%s a commenté le signet %s',
	'bookmarks:river:annotate' => 'un commentaire sur ce signet',
	'bookmarks:river:item' => 'un élément',

	'item:object:bookmarks' => 'Signets',

	'bookmarks:group' => 'Signets du groupe',
	'bookmarks:enablebookmarks' => 'Activer les signets du groupe',
	'bookmarks:nogroup' => 'Ce groupe n\'a pas encore de signet',
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Ce widget affiche vos derniers signets.",

	'bookmarks:bookmarklet:description' =>
			"Le \"bookmarklet\" vous permet de partager ce que vous trouvez sur le web avec vos contacts, vos groupes, ou pour vous-même. Pour l'utiliser, glissez simplement le bouton ci-dessous dans la barre de liens de votre navigateur.",

	'bookmarks:bookmarklet:descriptionie' =>
			"Si vous utilisez Internet Explorer, faites un clic droit sur le bouton et ajoutez-le dans vos favoris, puis dans votre barre de liens.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Vous pouvez mettre en signet n'importe quelle page à tout moment en cliquant sur le bouton de votre navigateur.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Votre élément a bien été mis en signet.",
	'bookmarks:delete:success' => "Votre signet a bien été supprimé.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Votre signet n'a pas pu être enregistré. Vérifiez que le titre et le lien sont corrects et réessayez.",
	'bookmarks:save:invalid' => "L’adresse du signet est invalide et ne peut donc pas être enregistrée.",
	'bookmarks:delete:failed' => "Votre signet n'a pas pu être supprimé. Merci de réessayer.",
	'bookmarks:unknown_bookmark' => 'Impossible de trouver le signet spécifié',
);
