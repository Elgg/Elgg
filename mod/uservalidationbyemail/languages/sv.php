<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s vänligen bekräfta din e-postadress för %s!",
	'email:validate:body' => "Hej %s,

Innan du kan börja använda %s, måste du bektäfta din e-postadress.

Vänligen bekräfta din e-postadress genom att trycka på länken nedan:

%s

Om du inte kan trycka på länken, kopiera och klistra in den i webbläsaren manuellt.

%s
%s",
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
	
	'uservalidationbyemail:upgrade:2019090600:title' => 'Spåra användares valideringsstatus för mejl',
	'uservalidationbyemail:upgrade:2019090600:description' => 'Valideringsstatus för mejlen spåras på ett nytt sätt. Se till att alla väntande användare är uppdaterade till den nya spårningen för att fortfarande kräva validering.',
);
