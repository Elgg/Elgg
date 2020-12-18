<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'annotation:delete:likes:fail' => "A fost o problemă la îndepărtarea aprecierii tale",
	'annotation:delete:likes:success' => "Aprecierea ta a fost îndepărtată",
	
	'likes:this' => 'au apreciat asta',
	'likes:deleted' => 'Aprecierea ta a fost îndepărtată',
	'likes:see' => 'Vezi cine a apreciat asta',
	'likes:remove' => 'Nu mai aprecia asta',
	'likes:notdeleted' => 'A fost o problemă la îndepărtarea aprecierii tale',
	'likes:likes' => 'Apreciezi acum acest element',
	'likes:failure' => 'A fost o problemă la aprecierea acestui element',
	'likes:alreadyliked' => 'Ai apreciat deja acest element',
	'likes:notfound' => 'Elementul pe care încerci să-l apreciezi nu poate fi găsit',
	'likes:likethis' => 'Apreciază asta',
	'likes:userlikedthis' => '%s apreciere',
	'likes:userslikedthis' => '%s aprecieri',
	'likes:river:annotate' => 'aprecieri',
	'likes:delete:confirm' => 'Sigur dorești să ștergi această apreciere?',

	'river:likes' => 'aprecieri %s %s',

	// notifications. yikes.
	'likes:notifications:subject' => '%s ți-a apreciat postarea "%s"',
	'likes:notifications:body' =>
'Salutare %1$s,

%2$s ți-a apreciat postarea "%3$s" de pe %4$s

Vezi postarea ta originală de aici:

%5$s

sau vezi profilul utilizatorului %2$s de aici:

%6$s

Mulțumim,
%4$s',
	'likes:upgrade:2017120700:title' => "Adnotări Publice de Aprecieri",
	'likes:upgrade:2017120700:description' => "Acest lucru actualizează ID-ul de acces al adnotărilor de aprecieri către public",
	
);
