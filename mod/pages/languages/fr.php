<?php
return array(

	/**
	 * Menu items and titles
	 */

	'pages' => "Pages",
	'pages:owner' => "Pages de %s",
	'pages:friends' => "Pages des amis ",
	'pages:all' => "Toutes les pages du site",
	'pages:add' => "Ajouter une page",

	'pages:group' => "Pages du groupe",
	'groups:enablepages' => 'Autoriser les pages du groupe',

	'pages:new' => "Une nouvelle page",
	'pages:edit' => "Editer cette page",
	'pages:delete' => "Effacer cette page",
	'pages:history' => "Historique",
	'pages:view' => "Voir la page",
	'pages:revision' => "Révision",
	'pages:current_revision' => "Révision actuelle",
	'pages:revert' => "Revenir",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Nouvelle page nommée %s',
	'pages:notify:subject' => "Une nouvelle page: %s",
	'pages:notify:body' =>
'%s a ajouté une nouvelle page: %s

%s

Voir et commenter cette page:
%s
',
	'item:object:page_top' => 'Page de plus haut niveau',
	'item:object:page' => 'Pages',
	'pages:nogroup' => 'Ce groupe ne comporte encore aucune page',
	'pages:more' => 'Plus de pages',
	'pages:none' => 'Aucune page créé pour l\'instant',

	/**
	* River
	**/

	'river:create:object:page' => '%s a créé une page %s',
	'river:create:object:page_top' => '%s a créé une page %s',
	'river:update:object:page' => '%s a mis à jour une page %s',
	'river:update:object:page_top' => '%s a mis à jour une page %s',
	'river:comment:object:page' => '%s a commenté sur une page intitulée %s',
	'river:comment:object:page_top' => '%s a commenté sur une page intitulée %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Titre de la page',
	'pages:description' => 'Texte de la page',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Page Parente',
	'pages:access_id' => 'Accès en lecture',
	'pages:write_access_id' => 'Accès en écriture',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'Pas d\'accès à cette page',
	'pages:cantedit' => 'Vous ne pouvez pas éditer cette page',
	'pages:saved' => 'Page enregistrée',
	'pages:notsaved' => 'La page n\'a pas pu être enregistrée',
	'pages:error:no_title' => 'Vous devez spécifier un titre pour cette page.',
	'pages:delete:success' => 'Votre page a bien été effacée.',
	'pages:delete:failure' => 'Votre page n\'a pas pu être effacée.',
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
	'pages:widget:description' => "Ceci est la liste de vos pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Voir la page",
	'pages:label:edit' => "Editer la page",
	'pages:label:history' => "Historique de la page",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "Cette page",
	'pages:sidebar:children' => "Sous-pages",
	'pages:sidebar:parent' => "Parente",

	'pages:newchild' => "Créer une sous-page",
	'pages:backtoparent' => "Retour à '%s'",
);
