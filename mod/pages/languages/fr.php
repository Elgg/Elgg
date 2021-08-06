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

	'item:object:page' => 'Page',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "Toutes les pages du site",
	'collection:object:page:owner' => "Pages de %s",
	'collection:object:page:friends' => "Pages des contacts",
	'collection:object:page:group' => "Pages du groupe",
	'add:object:page' => "Ajouter une page",
	'edit:object:page' => "Modifier cette page",
	'notification:object:page:create' => "Envoyer une notification quand une page est créée",
	'notifications:mute:object:page' => "à propos de la page '%s'",

	'groups:tool:pages' => 'Activer les pages du groupe',
	
	'annotation:delete:page:success' => 'La révision de la page a bien été supprimée.',
	'annotation:delete:page:fail' => 'La révision de la page n\'a pas pu être supprimée.',

	'pages:history' => "Historique",
	'pages:revision' => "Révision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Nouvelle page intitulée %s',
	'pages:notify:subject' => "Une nouvelle page : %s",
	'pages:notify:body' => '%s a créé une page : %s

%s

Voir et commenter la page :
%s',

	'pages:more' => 'Plus de pages',
	'pages:none' => 'Aucune page créé pour l\'instant',

	/**
	* River
	**/

	'river:object:page:create' => '%s a créé la page %s',
	'river:object:page:update' => '%s a mis à jour la page %s',
	'river:object:page:comment' => '%s a commenté la page %s',
	
	/**
	 * Form fields
	 */

	'pages:title' => 'Titre de la page',
	'pages:description' => 'Contenu de la page',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Page parente',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Vous ne pouvez pas modifier cette page',
	'pages:saved' => 'Page enregistrée',
	'pages:notsaved' => 'La page n\'a pas pu être enregistrée',
	'pages:error:no_title' => 'Vous devez donner un titre à cette page.',
	'entity:delete:object:page:success' => 'La page a bien été supprimée.',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Révision créée le %s par %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Nombre de pages à afficher',
	'widgets:pages:name' => 'Pages',
	'widgets:pages:description' => "Voici la liste de vos pages.",

	'pages:newchild' => "Créer une sous-page",
);
