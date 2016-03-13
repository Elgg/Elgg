<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Pages",
	'pages:owner' => "Pages de %s",
	'pages:friends' => "Pages des contacts",
	'pages:all' => "Toutes les pages du site",
	'pages:add' => "Ajouter une page",

	'pages:group' => "Pages du groupe",
	'groups:enablepages' => 'Activer les pages du groupe',

	'pages:new' => "Une nouvelle page",
	'pages:edit' => "Modifier cette page",
	'pages:delete' => "Supprimer cette page",
	'pages:history' => "Historique",
	'pages:view' => "Voir la page",
	'pages:revision' => "Révision",
	'pages:current_revision' => "Révision actuelle",
	'pages:revert' => "Rétablir",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Nouvelle page intitulée %s',
	'pages:notify:subject' => "Une nouvelle page: %s",
	'pages:notify:body' =>
'%s a ajouté une nouvelle page: %s

%s

Voir et commenter cette page:
%s
',
	'item:object:page_top' => 'Pages de plus haut niveau',
	'item:object:page' => 'Pages',
	'pages:nogroup' => 'Ce groupe ne comporte encore aucune page',
	'pages:more' => 'Plus de pages',
	'pages:none' => 'Aucune page créé pour l\'instant',

	/**
	* River
	**/

	'river:create:object:page' => '%s a créé une page %s',
	'river:create:object:page_top' => '%s a créé une page %s',
	'river:update:object:page' => '%s a mis à jour la page %s',
	'river:update:object:page_top' => '%s a mis à jour la page %s',
	'river:comment:object:page' => '%s a commenté la page %s',
	'river:comment:object:page_top' => '%s a commenté la page %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Titre de la page',
	'pages:description' => 'Contenu de la page',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Page parente',
	'pages:access_id' => 'Accès en lecture',
	'pages:write_access_id' => 'Accès en écriture',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'Pas d\'accès à cette page',
	'pages:cantedit' => 'Vous ne pouvez pas modifier cette page',
	'pages:saved' => 'Page enregistrée',
	'pages:notsaved' => 'La page n\'a pas pu être enregistrée',
	'pages:error:no_title' => 'Vous devez donner un titre à cette page.',
	'pages:delete:success' => 'Votre page a bien été supprimée.',
	'pages:delete:failure' => 'Votre page n\'a pas pu être supprimée.',
	'pages:revision:delete:success' => 'La version de la page a bien été supprimée.',
	'pages:revision:delete:failure' => 'La version de la page n\'a pas pu être supprimée.',
	'pages:revision:not_found' => 'Impossible de trouver cette révision.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Dernière mise à jour le %s par %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Révision créée le %s par %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Nombre de pages à afficher',
	'pages:widget:description' => "Voici la liste de vos pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Voir la page",
	'pages:label:edit' => "Modifier la page",
	'pages:label:history' => "Historique de la page",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Cette page",
	'pages:sidebar:children' => "Sous-pages",
	'pages:sidebar:parent' => "Parent",

	'pages:newchild' => "Créer une sous-page",
	'pages:backtoparent' => "Retour à \"%s\"",
);
