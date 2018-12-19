<?php
return array(

	/**
	 * Menu items and titles
	 */

	'item:object:page' => 'Pages',
	'collection:object:page' => 'Pages',
	'collection:object:page:all' => "Toutes les pages du site",
	'collection:object:page:owner' => "Pages de %s",
	'collection:object:page:friends' => "Pages des contacts",
	'collection:object:page:group' => "Pages du groupe",
	'add:object:page' => "Ajouter une page",
	'edit:object:page' => "Modifier cette page",

	'groups:tool:pages' => 'Activer les pages du groupe',

	'pages:delete' => "Supprimer cette page",
	'pages:history' => "Historique",
	'pages:view' => "Voir la page",
	'pages:revision' => "Révision",

	'pages:navigation' => "Navigation",

	'pages:notify:summary' => 'Nouvelle page intitulée %s',
	'pages:notify:subject' => "Une nouvelle page: %s",
	'pages:notify:body' =>
'%s a créé une page : %s

%s

Voir et commenter la page:
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
	'pages:access_id' => 'Accès en lecture',
	'pages:write_access_id' => 'Accès en écriture',

	/**
	 * Status and error messages
	 */
	'pages:cantedit' => 'Vous ne pouvez pas modifier cette page',
	'pages:saved' => 'Page enregistrée',
	'pages:notsaved' => 'La page n\'a pas pu être enregistrée',
	'pages:error:no_title' => 'Vous devez donner un titre à cette page.',
	'entity:delete:object:page:success' => 'La page a bien été supprimée.',
	'pages:revision:delete:success' => 'La version de la page a bien été supprimée.',
	'pages:revision:delete:failure' => 'La version de la page n\'a pas pu être supprimée.',

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

	/**
	 * Submenu items
	 */
	'pages:label:view' => "Voir la page",
	'pages:label:edit' => "Modifier la page",
	'pages:label:history' => "Historique de la page",

	'pages:newchild' => "Créer une sous-page",
	
	/**
	 * Upgrades
	 */
	'pages:upgrade:2017110700:title' => "Migrer les entités page_top vers page",
	'pages:upgrade:2017110700:description' => "Change le sous-type de toutes les pages racine vers 'page' et définit les métadonnées pour assurer un listing correct.",
	
	'pages:upgrade:2017110701:title' => "Migrer les entrées page_top de la rivière",
	'pages:upgrade:2017110701:description' => "Modifie le sous-type de tous les éléments de la rivière pour les pages racine vers  'page'.",
);
