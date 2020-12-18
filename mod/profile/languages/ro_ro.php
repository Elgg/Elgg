<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'profile' => 'Profil',
	'profile:notfound' => 'Scuze. Nu am putut găsi profilul cerut.',
	'profile:upgrade:2017040700:title' => 'Migrează schema câmpurilor de profil',
	'profile:upgrade:2017040700:description' => 'Această migrare transformă câmpurile de profil din date meta în adnotații cu fiecare nume
prefixat cu "profil:". <strong>Notă:</strong> Dacă ai câmpuri de profil "inactive" pe care le dorești migrate, recreează aceste câmpuri
și reîncarcă această pagină pentru a te asigura că vor fi migrate.',
	
	'admin:configure_utilities:profile_fields' => 'Editează Câmpurile de Profil',
	
	'profile:edit' => 'Editează profilul',
	'profile:aboutme' => "Despre mine",
	'profile:description' => "Despre mine",
	'profile:briefdescription' => "Descriere scurtă",
	'profile:location' => "Locație",
	'profile:skills' => "Aptitudini",
	'profile:interests' => "Interese",
	'profile:contactemail' => "Email de contact",
	'profile:phone' => "Telefon",
	'profile:mobile' => "Mobil",
	'profile:website' => "Site web",
	'profile:twitter' => "Nume utilizator Twitter",
	'profile:saved' => "Profilul tău a fost salvat cu succes.",

	'profile:field:text' => 'Text scurt',
	'profile:field:longtext' => 'Zonă mare de text',
	'profile:field:tags' => 'Etichete',
	'profile:field:url' => 'Adresă web',
	'profile:field:email' => 'Adresă de email',
	'profile:field:tel' => 'Telefon',
	'profile:field:location' => 'Locație',
	'profile:field:date' => 'Dată',
	'profile:field:datetime-local' => 'Timp Dată',
	'profile:field:month' => 'Lună',
	'profile:field:week' => 'Săptămână',
	'profile:field:color' => 'Culoare',

	'profile:edit:default' => 'Editează câmpurile de profil',
	'profile:label' => "Eticheta profilului",
	'profile:type' => "Tipul profilului",
	'profile:editdefault:delete:fail' => 'Îndepărtarea câmpului de profil a eșuat',
	'profile:editdefault:delete:success' => 'Câmp de profil șters',
	'profile:defaultprofile:reset' => 'Câmpurile de profil au fost resetate la starea inițială de sistem',
	'profile:resetdefault' => 'Resetează câmpurile de profil la starea inițială de sistem',
	'profile:resetdefault:confirm' => 'Sigur dorești să ștergi câmpurile tale personalizate de profil?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own.
Click the 'Add' button and give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags).
To re-order the fields drag on the handle next to the field label.
To edit a field label - click on the edit icon.

At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'Câmp nou de profil adăugat',
	'profile:editdefault:fail' => 'Profilul inițial nu a putut fi salvat',
	'profile:noaccess' => "Nu ai permisiunea de a edita acest profil.",
	'profile:invalid_email' => '%s trebuie să fie o adresă de email validă.',
);
