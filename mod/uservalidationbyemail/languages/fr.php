<?php
return array(
	'admin:users:unvalidated' => 'Invalidés',
	
	'email:validate:subject' => "%s veuillez confirmer votre adresse de courriel !",
	'email:validate:body' => "%s,

avant de pouvoir commencer à utiliser %s, vous devez confirmer votre adresse mail.

Veuillez confirmer votre adresse de courriel en cliquant sur le lien suivant:

%s

Si vous ne pouvez pas cliquer sur le lien, copier et coller le dans votre navigateur manuellement.

%s
%s
",
	'email:confirm:success' => "Vous avez validé votre adresse de courriel !",
	'email:confirm:fail' => "Votre adresse de courriel n'a pu être vérifiée...",

	'uservalidationbyemail:emailsent' => "Courriel envoyé à <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pour activer votre compte, veuillez confirmer votre adresse e-mail en cliquant sur le lien qui vient de vous être envoyé (si vous ne recevez rien, veuillez vérifier votre dossier Spam).",
	'uservalidationbyemail:login:fail' => "Votre compte n'est pas validé, par conséquent la tentative de connexion a échoué. Un autre e-mail de validation a été envoyé.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Aucun utilisateurs non-validés.',

	'uservalidationbyemail:admin:unvalidated' => 'Invalidés',
	'uservalidationbyemail:admin:user_created' => '%s enregistré',
	'uservalidationbyemail:admin:resend_validation' => 'Renvoyer la validation',
	'uservalidationbyemail:admin:validate' => 'Valider',
	'uservalidationbyemail:confirm_validate_user' => 'Valider %s ?',
	'uservalidationbyemail:confirm_resend_validation' => 'Renvoyer la validation e-mail à %s ?',
	'uservalidationbyemail:confirm_delete' => 'Supprimer %s ?',
	'uservalidationbyemail:confirm_validate_checked' => 'Valider les utilisateurs cochés ?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Renvoyer la validation aux utilisateurs cochés ?',
	'uservalidationbyemail:confirm_delete_checked' => 'Supprimer les utilisateurs cochés ?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Impossible de valider l\'utilisateur.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Impossible de valider tout les utilisateurs cochés.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Impossible de supprimer l\'utilisateur.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Impossible de supprimer tout les utilisateurs cochés.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossible de renvoyer la demande de validation.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossible de renvoyer toutes les demandes de validation aux utilisateurs cochés.',

	'uservalidationbyemail:messages:validated_user' => 'Utilisateur validé.',
	'uservalidationbyemail:messages:validated_users' => 'Tout les utilisateurs cochés validés.',
	'uservalidationbyemail:messages:deleted_user' => 'Utilisateur supprimé.',
	'uservalidationbyemail:messages:deleted_users' => 'Tout les utilisateurs cochés supprimé.',
	'uservalidationbyemail:messages:resent_validation' => 'Demande de validation renvoyée.',
	'uservalidationbyemail:messages:resent_validations' => 'Demandes de validation renvoyées à tout les utilisateurs cochés.'

);
