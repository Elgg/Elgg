<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'Fichier',
	'item:object:file:application' => 'Application',
	'item:object:file:archive' => 'Archive',
	'item:object:file:excel' => 'Excel',
	'item:object:file:image' => 'Image',
	'item:object:file:music' => 'Musique',
	'item:object:file:openoffice' => 'OpenOffice',
	'item:object:file:pdf' => 'PDF',
	'item:object:file:ppt' => 'PowerPoint',
	'item:object:file:text' => 'Texte',
	'item:object:file:vcard' => 'vCard',
	'item:object:file:video' => 'Vidéo',
	'item:object:file:word' => 'Word',
	
	'file:upgrade:2022092801:title' => 'Déplacer des fichiers',
	'file:upgrade:2022092801:description' => 'Déplace les fichiers téléchargés qui utilisent le plugin "file" vers le dossier de l\'entité du fichier au lieu du dossier de l\'entité propriétaire.',
	
	'collection:object:file' => 'Fichiers',
	'collection:object:file:all' => "Tous les fichiers du site",
	'collection:object:file:owner' => "Fichiers de %s",
	'collection:object:file:friends' => "Fichiers des contacts",
	'collection:object:file:group' => "Fichiers du groupe",
	'add:object:file' => "Envoyer un fichier",
	'edit:object:file' => "Modifier le fichier",
	'notification:object:file:create' => "Envoyer une notification lorsqu'un fichier est créé",
	'notifications:mute:object:file' => "à propos du fichier '%s'",

	'file:more' => "Plus de fichiers",
	'file:list' => "vue liste",

	'file:num_files' => "Nombre de fichiers à afficher",
	'file:replace' => 'Remplacer le contenu du fichier (laisser vide pour ne pas changer le fichier)',
	'file:list:title' => "de %s %s %s",

	'file:file' => "Fichier",

	'file:list:list' => 'Afficher sous forme de liste',
	'file:list:gallery' => 'Afficher sous forme de galerie',

	'file:type:' => 'Fichiers',
	'file:type:all' => "Tous les fichiers",
	'file:type:video' => "Vidéos",
	'file:type:document' => "Documents",
	'file:type:audio' => "Audio",
	'file:type:image' => "Images",
	'file:type:general' => "Autres types de fichiers",

	'file:user:type:video' => "Vidéos de %s",
	'file:user:type:document' => "Documents de %s",
	'file:user:type:audio' => "Enregistrements audio de %s",
	'file:user:type:image' => "Images de %s",
	'file:user:type:general' => "Autres types de fichiers de %s",

	'file:friends:type:video' => "Vidéos des contacts",
	'file:friends:type:document' => "Documents des contacts",
	'file:friends:type:audio' => "Enregistrements audio des contacts",
	'file:friends:type:image' => "Images des contacts",
	'file:friends:type:general' => "Autres types de fichiers des contacts",

	'widgets:filerepo:name' => "Widget Fichiers",
	'widgets:filerepo:description' => "Affiche une liste de vos derniers fichiers",

	'groups:tool:file' => 'Activer les fichiers du groupe',
	'groups:tool:file:description' => 'Autoriser les membres du groupe à partager des fichiers dans ce groupe.',

	'river:object:file:create' => '%s a publié le fichier %s',
	'river:object:file:comment' => '%s a commenté le fichier %s',

	'file:notify:summary' => 'Nouveau fichier intitulé %s',
	'file:notify:subject' => 'Nouveau fichier : %s',
	'file:notify:body' => '%s a chargé un nouveau fichier : %s

%s

Afficher et commenter le fichier :
%s',
	
	'notification:mentions:object:file:subject' => '%s vous a mentionné dans un fichier',

	/**
	 * Status messages
	 */

	'file:saved' => "Le fichier a bien été enregistré.",
	'entity:delete:object:file:success' => "Le fichier a bien été supprimé.",

	/**
	 * Error messages
	 */

	'file:none' => "Aucun fichier.",
	'file:uploadfailed' => "Désolé, votre fichier n'a pas pu être enregistré.",
	'file:noaccess' => "Vous n'avez pas la permission de modifier ce fichier",
	'file:cannotload' => "Une erreur s'est produite lors de l'envoi du fichier",
);
