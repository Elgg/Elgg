<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'item:object:bookmarks' => 'Signets',
	'collection:object:bookmarks' => 'Signets',
	'collection:object:bookmarks:group' => 'Signets du groupe',
	'collection:object:bookmarks:all' => "Tous les signets du site",
	'collection:object:bookmarks:owner' => "Signets de %s",
	'collection:object:bookmarks:friends' => "Signets des contacts",
	'add:object:bookmarks' => "Ajouter un signet",
	'edit:object:bookmarks' => "Modifier le signet",
	'notification:object:bookmarks:create' => "Envoyer une notification quand un signet est créé",
	'notifications:mute:object:bookmarks' => "à propos du signet '%s'",

	'bookmarks:this' => "Mettre cette page en signet",
	'bookmarks:this:group' => "Mettre en signet dans %s",
	'bookmarks:bookmarklet' => "Installer le bookmarklet",
	'bookmarks:bookmarklet:group' => "Installer le bookmarklet du groupe",
	'bookmarks:address' => "Adresse web de la ressource à ajouter à vos signets",
	'bookmarks:none' => 'Aucun signet',

	'bookmarks:notify:summary' => 'Nouveau signet intitulé %s',
	'bookmarks:notify:subject' => 'Nouveau signet : %s',
	'bookmarks:notify:body' => '%s a jouté un nouveau signet : %s

Adresse : %s

%s

Afficher et commenter le signet :
%s',

	'bookmarks:numbertodisplay' => 'Nombre de signets à afficher',

	'river:object:bookmarks:create' => '%s a mis en signet %s',
	'river:object:bookmarks:comment' => '%s a commenté le signet  %s',

	'groups:tool:bookmarks' => 'Activer les signets du groupe',
	
	/**
	 * Widget and bookmarklet
	 */
	'widgets:bookmarks:name' => 'Signets',
	'widgets:bookmarks:description' => "Affiche vos derniers signets.",

	'bookmarks:bookmarklet:description' => "Un \"bookmarklet\" est un type de bouton ou de lien spécial que vous enregistrez dans la barre de liens de votre navigateur. Il vous permet d'enregistrer rapidement dans vos signets tout type de ressource que vous trouvez sur internet. Pour le mettre en place, faites glisser le bouton ci-dessous dans la barre de liens de votre navigateur :",
	'bookmarks:bookmarklet:descriptionie' => "Si vous utilisez Internet Explorer, faites un clic droit sur le bouton et ajoutez-le dans vos favoris, puis dans votre barre de liens.",
	'bookmarks:bookmarklet:description:conclusion' => "Vous pouvez mettre en signet n'importe quelle page à tout moment en cliquant sur le bouton de votre navigateur.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Votre élément a bien été mis en signet.",
	'entity:delete:object:bookmarks:success' => "Ce signet a été supprimé.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Votre signet n'a pas pu être enregistré. Vérifiez que le titre et le lien sont corrects et réessayez.",
);
