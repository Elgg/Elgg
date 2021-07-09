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

	'messages' => "Messages",
	'messages:unreadcount' => "%s non lu(s)",
	'messages:user' => "Boîte de réception de %s",
	'messages:inbox' => "Boîte de réception",
	'messages:sent' => "Messages envoyés",
	'messages:message' => "Message",
	'messages:title' => "Sujet",
	'messages:to:help' => "Saisissez l'identifiant du destinataire",
	'messages:inbox' => "Boîte de réception",
	'messages:sendmessage' => "Envoyer un message",
	'messages:add' => "Nouveau message",
	'messages:sentmessages' => "Messages envoyés",
	'messages:toggle' => 'Inverser la sélection',
	'messages:markread' => 'Marquer comme lu',

	'notification:method:site' => 'Site',

	'messages:error' => 'Un problème est survenu lors de l\'enregistrement de votre message. Veuillez réessayer.',

	'item:object:messages' => 'Message',
	'collection:object:messages' => 'Messages',

	/**
	* Status messages
	*/

	'messages:posted' => "Votre message a bien été envoyé.",
	'messages:success:delete' => 'Les messages ont été supprimés',
	'messages:success:read' => 'Les messages ont été marqués comme lus',
	'messages:error:messages_not_selected' => 'Aucun message sélectionné',

	/**
	* Email messages
	*/

	'messages:email:subject' => 'Vous avez reçu un nouveau message !',
	'messages:email:body' => "Vous avez un nouveau message de %s :

%s

Pour consulter vos messages :
%s

Pour écrire un message à %s :
%s",

	/**
	* Error messages
	*/

	'messages:blank' => "Désolé, vous devez écrire quelque chose dans votre message avant de pouvoir l'enregistrer.",
	'messages:nomessages' => "Aucun message.",
	'messages:user:nonexist' => "Le destinataire n'a pas pu être trouvé dans la base de données des utilisateurs.",
	'messages:user:blank' => "Vous n'avez sélectionné personne à qui envoyer ce message.",
	'messages:user:self' => "Vous ne pouvez pas vous envoyer un message à vous-même.",
	'messages:user:notfriend' => "Vous ne pouvez pas envoyer un message à un membre avec qui vous n'êtes pas en contact.",

	'messages:deleted_sender' => 'Utilisateur effacé',
	
	/**
	* Settings
	*/
	'messages:settings:friends_only:label' => 'Les messages ne peuvent être envoyés qu\'aux contacts',
	'messages:settings:friends_only:help' => 'L\'utilisateur ne pourra pas envoyer de message si le destinataire n\'est pas l\'un de ses contacts',

);
