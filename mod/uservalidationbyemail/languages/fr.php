<?php
return array(
	'email:validate:subject' => "%s veuillez SVP confirmer votre adresse email pour %s !",
	'email:validate:body' => "Bonjour %s,

Avant que vous commenciez à utiliser  %s, vous devez confirmer votre adresse email.

Veuillez confirmer votre adresse email en cliquant sur le lien ci-dessous :

%s

Si vous ne pouvez pas cliquer sur le lien, copiez-le et collez -le manuellement dans votre navigateur.

%s
%s",
	'email:confirm:success' => "Vous avez bien confirmé votre adresse email !",
	'email:confirm:fail' => "Votre adresse email n'a pas pu être vérifiée...",

	'uservalidationbyemail:emailsent' => "Email envoyé à <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pour activer votre compte, veuillez confirmer votre adresse email en cliquant sur le lien qui vient de vous être envoyé sur votre adresse email d'inscription.

Si vous ne recevez rien, veuillez vérifier votre dossier Spam ou Courrier Indésirable.",
	'uservalidationbyemail:login:fail' => "Votre compte n'a pas encore été confirmé, c'est pourquoi la tentative de connexion a échoué. Pour vous permettre de continuer, un nouvel email de confirmation vient d'être envoyé à votre adresse email.",

	'uservalidationbyemail:admin:resend_validation' => 'Renvoyer l\'email de confirmation',
	'uservalidationbyemail:confirm_resend_validation' => 'Renvoyer l\'email de confirmation à %s ?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Renvoyer l\'email de confirmation aux utilisateurs sélectionnés ?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossible de renvoyer la demande de validation.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossible de renvoyer toutes les demandes de confirmation aux utilisateurs sélectionnés.',

	'uservalidationbyemail:messages:resent_validation' => 'La demande de confirmation a bien été renvoyée.',
	'uservalidationbyemail:messages:resent_validations' => 'Les demandes de confirmation ont bien été renvoyées aux utilisateurs sélectionnés.'
);
