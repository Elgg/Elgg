<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s vänligen bekräfta din e-postadress för %s!",
	'email:confirm:success' => "Du har bekräftat din e-postadress!",
	'email:confirm:fail' => "Din e-postadress kunde inte verifieras...",

	'uservalidationbyemail:emailsent' => "Mejl skickat till <em>%s</em>",
	'uservalidationbyemail:registerok' => "För att aktivera ditt konto, vänligen bekräfta din e-postadress genom att trycka på länken vi just skickade till dig.",
	'uservalidationbyemail:login:fail' => "Ditt konto är inte validerat så inloggningsförsöket misslyckades. Ett annat valideringsmejl har skickats.",

	'uservalidationbyemail:admin:resend_validation' => 'Återsända validering',
	'uservalidationbyemail:confirm_resend_validation' => 'Återsända valideringsmejl till %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Återsända validering till markerade användare?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Okänd användare',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Det gick inte återsända valideringsbegäran.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Det gick inte återsända alla valideringsbegäranden till markerade användare.',

	'uservalidationbyemail:messages:resent_validation' => 'Valideringsbegäran återsänd.',
	'uservalidationbyemail:messages:resent_validations' => 'Valideringsbegäran återsänd till all markerade användare.',
);
