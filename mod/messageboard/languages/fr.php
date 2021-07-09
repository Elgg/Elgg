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

	'messageboard:board' => "Tableau de messages",
	'messageboard:none' => "Il n'y a encore rien dans ce tableau de messages",
	'messageboard:num_display' => "Nombre de messages à afficher",
	'messageboard:owner' => 'tableau de messages de %s',
	'messageboard:owner_history' => 'publications de %s sur le tableau de messages de %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s a écrit sur le tableau de messages de %s",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Désolé, ce message n'a pas pu être supprimé",
	'annotation:delete:messageboard:success' => "Ce message a bien été supprimé",
	
	'messageboard:posted' => "Votre message a bien été publié sur le tableau de messages.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Vous avez un nouveau message sur le tableau de messages !',
	'messageboard:email:body' => "Vous avez un nouveau commentaire de %s sur votre tableau de messages :

%s

Pour voir les commentaires sur votre tableau de messages :
%s

Pour voir le profil de %s :
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Désolé, vous devez écrire quelque chose dans le corps du message avant de pouvoir l'enregistrer.",

	'messageboard:failure' => "Une erreur imprévue s'est produite lors de l'ajout de votre message. Veuillez réessayer.",

	'widgets:messageboard:name' => "Tableau de messages",
	'widgets:messageboard:description' => "Ceci est un tableau de messages que vous pouvez ajouter sur votre profil, dans lequel les autres utilisateurs peuvent laisser un message.",
);
