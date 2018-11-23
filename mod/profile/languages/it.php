<?php

return array(
	'profile' => 'Profilo',
	'profile:notfound' => 'Spiacenti ma è impossibile trovare il profilo richiesto.',
	'profile:upgrade:2017040700:title' => 'Schema di migrazione dei campi del profilo',
	'profile:upgrade:2017040700:description' => 'This migration converts profile fields from metadata to annotations with each name
prefixed with "profile:". <strong>Note:</strong> If you have "inactive" profile fields you want migrated, re-create those fields
and re-load this page to make sure they get migrated.',
	
	'admin:configure_utilities:profile_fields' => 'Modifica i campi del profilo',
	
	'profile:edit' => 'Modifica il profilo',
	'profile:aboutme' => "Chi sono",
	'profile:description' => "Chi sono",
	'profile:briefdescription' => "Breve descrizione",
	'profile:location' => "Posizione",
	'profile:skills' => "Abilità",
	'profile:interests' => "Interessi",
	'profile:contactemail' => "Email di contatto",
	'profile:phone' => "Telefono",
	'profile:mobile' => "Cellulare",
	'profile:website' => "Sito web",
	'profile:twitter' => "Nome utente Twitter",
	'profile:saved' => "Profilo salvato correttamente",

	'profile:field:text' => 'Breve testo',
	'profile:field:longtext' => 'Area di testo lungo',
	'profile:field:tags' => 'Tag',
	'profile:field:url' => 'Indirizzo web',
	'profile:field:email' => 'Indirizzo email',
	'profile:field:location' => 'Posizione',
	'profile:field:date' => 'Data',

	'profile:edit:default' => 'Modifica i campi del profilo',
	'profile:label' => "Etichetta del profilo",
	'profile:type' => "Tipo di profilo",
	'profile:editdefault:delete:fail' => 'La rimozione del campo del profilo non è stata possibile',
	'profile:editdefault:delete:success' => 'Campo del profilo eliminato',
	'profile:defaultprofile:reset' => 'Campi del profilo reimpostati a quelli predefiniti di sistema',
	'profile:resetdefault' => 'Reimposta i campi del profilo a quelli predefiniti di sistema',
	'profile:resetdefault:confirm' => 'Sicuro/a di voler eliminare i campi del profilo personalizzati?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own.
Click the 'Add' button and give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags).
To re-order the fields drag on the handle next to the field label.
To edit a field label - click on the edit icon.

At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'Aggiunto un nuovo campo del profilo',
	'profile:editdefault:fail' => 'Non è stato possibile salvare il profilo predefinito.',
	'profile:noaccess' => "Non hai i permessi per modificare questo profilo",
	'profile:invalid_email' => '%s deve essere un indirizzo email valido.',
);
