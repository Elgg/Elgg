<?php
return array(
	'admin:users:unvalidated' => 'Invalidés',
	
	'email:validate:subject' => "%s veuillez svp confirmer votre adresse e-mail %s !",
	'email:validate:body' => "%s,

avant de pouvoir commencer à utiliser %s, vous devez confirmer votre adresse e-mail.

Veuillez confirmer votre adresse e-mail en cliquant sur le lien suivant:

%s

Si vous ne pouvez pas cliquer sur le lien, copiez et collez-le dans votre navigateur manuellement.

%s
%s
",
	'email:confirm:success' => "Vous avez bien validé votre adresse e-mail !",
	'email:confirm:fail' => "Votre adresse e-mail n'a pas pu être vérifiée...",

	'uservalidationbyemail:emailsent' => "E-mail envoyé à <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pour activer votre compte, veuillez confirmer votre adresse e-mail en cliquant sur le lien qui vient de vous être envoyé (si vous ne recevez rien, veuillez vérifier votre dossier Spam ou Courrier Indésirable).",
	'uservalidationbyemail:login:fail' => "Votre compte n'a pas encore été validé, c'est pourquoi la tentative de connexion a échoué. Pour vous permettre de continuer, un nouveau e-mail de validation vient de vous être envoyé.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Pas d\'utilisateurs non-validés.',

	'uservalidationbyemail:admin:unvalidated' => 'Non-validés',
	'uservalidationbyemail:admin:user_created' => '%s enregistré',
	'uservalidationbyemail:admin:resend_validation' => 'Renvoyer la validation',
	'uservalidationbyemail:admin:validate' => 'Valider',
	'uservalidationbyemail:confirm_validate_user' => 'Valider %s ?',
	'uservalidationbyemail:confirm_resend_validation' => 'Renvoyer l\'e-mail de validation à %s ?',
	'uservalidationbyemail:confirm_delete' => 'Supprimer %s ?',
	'uservalidationbyemail:confirm_validate_checked' => 'Valider les utilisateurs sélectionnés ?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Renvoyer la validation aux utilisateurs sélectionnés ?',
	'uservalidationbyemail:confirm_delete_checked' => 'Supprimer les utilisateurs sélectionnés ?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Impossible de valider cet utilisateur.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Impossible de valider chacun des utilisateurs sélectionnés.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Impossible de supprimer cet utilisateur.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Impossible de supprimer chacun des utilisateurs sélectionnés.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossible de renvoyer la demande de validation.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossible de renvoyer chacune des demandes de validation aux utilisateurs sélectionnés.',

	'uservalidationbyemail:messages:validated_user' => 'Cet utilisateur a bien été validé.',
	'uservalidationbyemail:messages:validated_users' => 'Les utilisateurs sélectionnés ont bien été validés.',
	'uservalidationbyemail:messages:deleted_user' => 'Utilisateur supprimé.',
	'uservalidationbyemail:messages:deleted_users' => 'Tout les utilisateurs sélectionnés ont été supprimés.',
	'uservalidationbyemail:messages:resent_validation' => 'La demande de validation a bien été renvoyée.',
	'uservalidationbyemail:messages:resent_validations' => 'Les demandes de validation ont bien été renvoyées aux utilisateurs sélectionnés.'

);
