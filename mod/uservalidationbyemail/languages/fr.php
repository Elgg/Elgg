<?php
return array(
	'admin:users:unvalidated' => 'Non validés',
	
	'email:validate:subject' => "%s veuillez SVP confirmer votre adresse email pour %s !",
	'email:validate:body' => "%s,

Avant de pouvoir commencer à utiliser %s, vous devez confirmer votre adresse email.

Veuillez confirmer votre adresse email en cliquant sur le lien suivant :
%s

Si vous ne pouvez pas cliquer sur le lien, copiez et collez-le dans votre navigateur manuellement.

%s
%s
",
	'email:confirm:success' => "Vous avez bien confirmé votre adresse email !",
	'email:confirm:fail' => "Votre adresse email n'a pas pu être vérifiée...",

	'uservalidationbyemail:emailsent' => "Email envoyé à <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pour activer votre compte, veuillez confirmer votre adresse email en cliquant sur le lien qui vient de vous être envoyé sur votre adresse email d'inscription.

Si vous ne recevez rien, veuillez vérifier votre dossier Spam ou Courrier Indésirable.",
	'uservalidationbyemail:login:fail' => "Votre compte n'a pas encore été confirmé, c'est pourquoi la tentative de connexion a échoué. Pour vous permettre de continuer, un nouvel email de confirmation vient d'être envoyé à votre adresse email.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Aucun utilisateur en attente de validation.',

	'uservalidationbyemail:admin:unvalidated' => 'Non validés',
	'uservalidationbyemail:admin:user_created' => '%s enregistré',
	'uservalidationbyemail:admin:resend_validation' => 'Renvoyer l\'email de confirmation',
	'uservalidationbyemail:admin:validate' => 'Valider',
	'uservalidationbyemail:confirm_validate_user' => 'Valider %s ?',
	'uservalidationbyemail:confirm_resend_validation' => 'Renvoyer l\'email de confirmation à %s ?',
	'uservalidationbyemail:confirm_delete' => 'Supprimer %s ?',
	'uservalidationbyemail:confirm_validate_checked' => 'Valider les utilisateurs sélectionnés ?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Renvoyer l\'email de confirmation aux utilisateurs sélectionnés ?',
	'uservalidationbyemail:confirm_delete_checked' => 'Supprimer les utilisateurs sélectionnés ?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Impossible de valider cet utilisateur.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Impossible de valider tous les utilisateurs sélectionnés.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Impossible de supprimer cet utilisateur.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Impossible de supprimer tous les utilisateurs sélectionnés.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossible de renvoyer la demande de validation.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossible de renvoyer toutes les demandes de confirmation aux utilisateurs sélectionnés.',

	'uservalidationbyemail:messages:validated_user' => 'L\'utilisateur a bien été validé.',
	'uservalidationbyemail:messages:validated_users' => 'Les utilisateurs sélectionnés ont bien été validés.',
	'uservalidationbyemail:messages:deleted_user' => 'Utilisateur supprimé.',
	'uservalidationbyemail:messages:deleted_users' => 'Tous les utilisateurs sélectionnés ont bien été supprimés.',
	'uservalidationbyemail:messages:resent_validation' => 'La demande de confirmation a bien été renvoyée.',
	'uservalidationbyemail:messages:resent_validations' => 'Les demandes de confirmation ont bien été renvoyées aux utilisateurs sélectionnés.'

);
