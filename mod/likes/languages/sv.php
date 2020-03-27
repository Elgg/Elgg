<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'annotation:delete:likes:fail' => "Det var problem att ta bort din gilla-markering",
	'annotation:delete:likes:success' => "Din gilla-markering har tagits bort",
	
	'likes:this' => 'gillade detta',
	'likes:deleted' => 'Din gilla-markering har tagits bort',
	'likes:see' => 'Se vem som gillat detta',
	'likes:remove' => 'Ogilla detta',
	'likes:notdeleted' => 'Det var problem att ta bort din gilla-markering',
	'likes:likes' => 'Du gillar nu det här objektet',
	'likes:failure' => 'Det var ett problem att gilla detta objektet ',
	'likes:alreadyliked' => 'Du har redan gillat det här objektet',
	'likes:notfound' => 'Objektet som du försöker gilla kan inte hittas',
	'likes:likethis' => 'Gilla detta',
	'likes:userlikedthis' => '%s gilla-markering',
	'likes:userslikedthis' => '%s gilla-markeringar',
	'likes:river:annotate' => 'gilla-markeringar',
	'likes:delete:confirm' => 'Är du säker att du vill ta bort den här gilla-markeringen?',

	'river:likes' => 'gilla-markeringar %s %s',

	// notifications. yikes.
	'likes:notifications:subject' => '%s gillar ditt inlägg "%s"',
	'likes:notifications:body' =>
'Hej %1$s,

%2$s gillar ditt inlägg "%3$s" på %4$s

Se originalinlägget här:

%5$s

eller visa %2$ss profil här:

%6$s

Thanks,
%4$s',
	'likes:upgrade:2017120700:title' => "Publika Gilla-kommentarer",
	'likes:upgrade:2017120700:description' => "Den här uppdaterar åtkomst-id för gilla-kommentarer till publikt",
	
);
