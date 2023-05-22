<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s veuillez vérifier votre adresse e-mail pour %s !",
	'email:validate:body' => "Avant de pouvoir commencer à utiliser %s, vous devez confirmer qu'il s'agit bien de votre adresse e-mail.

Veuillez vérifier votre adresse e-mail en cliquant sur ce lien :

%s

Si vous ne pouvez pas cliquer sur le lien, copiez et collez-le manuellement dans votre navigateur.",
	'email:confirm:success' => "Vous avez bien confirmé votre adresse e-mail !",
	'email:confirm:fail' => "Votre adresse e-mail n'a pas pu être vérifiée...",

	'uservalidationbyemail:emailsent' => "E-mail envoyé à <em>%s</em>",
	'uservalidationbyemail:registerok' => "Pour activer votre compte, veuillez confirmer votre adresse e-mail en cliquant sur le lien qui vient de vous être envoyé par e-mail.

Si vous ne recevez rien, veuillez vérifier votre dossier Spam ou Courrier Indésirable.",
	'uservalidationbyemail:change_email' => "Renvoyer l'e-mail de vérification",
	'uservalidationbyemail:change_email:info' => "La tentative de connexion a échoué car votre compte n'est pas vérifié. Vous pouvez demander un nouveau lien de vérification ou mettre à jour l'adresse e-mail associée à votre compte.",

	'uservalidationbyemail:admin:resend_validation' => 'Renvoyer l\'e-mail de vérification',
	'uservalidationbyemail:confirm_resend_validation' => 'Renvoyer l\'e-mail de vérification à %s ?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Renvoyer l\'e-mail de vérification aux utilisateurs sélectionnés ?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Impossible de renvoyer la demande de vérification.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Impossible de renvoyer toutes les demandes de vérification aux utilisateurs sélectionnés.',

	'uservalidationbyemail:messages:resent_validation' => 'La demande de vérification a bien été renvoyée.',
	'uservalidationbyemail:messages:resent_validations' => 'Les demandes de vérification ont bien été renvoyées aux utilisateurs sélectionnés.',
);
