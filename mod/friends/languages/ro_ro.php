<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s a cerut prietenia cu %s",
	'relationship:friendrequest:pending' => "%s dorește să-ți fie prieten/ă",
	'relationship:friendrequest:sent' => "Ai cerut prietenia cu %s",
	
	// plugin settings
	'friends:settings:request:description' => "Implicit orice utilizator poate fi prieten/ă cu orice alt utilizator, e ca și urmărirea activității altor utilizatori.
După activarea cererilor de prietenie, atunci când utilizatorul A dorește să fie prieten/ă cu utilizatorul B, utilizatorul B trebuie să aprobe cererea. După aprobare, utilizatorul A va fi prieten/ă cu utilizatorul B și utilizatorul B va fi prieten/ă cu utilizatorul A.",
	'friends:settings:request:label' => "Activează cererile de prietenie",
	'friends:settings:request:help' => "Utilizatorii trebuie să aprobe cererea de prietenie iar prieteniile vor deveni bidirecționale",
	
	'friends:owned' => "Prieteni cu %s",
	'friend:add' => "Adaugă prieten/ă",
	'friend:remove' => "Îndepărtează prietenia",
	'friends:menu:request:status:pending' => "Cerere de prietenie în așteptare",

	'friends:add:successful' => "Ai adăugat cu succes pe %s ca și prieten/ă",
	'friends:add:duplicate' => "Ești deja prieten/ă cu %s",
	'friends:add:failure' => "Nu am putut adăuga pe %s ca prieten/ă",
	'friends:request:successful' => 'O cerere de prietenie a fost trimisă către %s',
	'friends:request:error' => 'O eroare a apărut la procesarea prieteniei tale cu %s',

	'friends:remove:successful' => "Ai îndepărtat cu succes pe %s de la prieteni",
	'friends:remove:no_friend' => "Tu și %s nu sunteți prieteni",
	'friends:remove:failure' => "Nu am putut îndepărta pe %s de la prieteni.",

	'friends:none' => "Nu sunt prieteni momentan.",
	'friends:of:owned' => "Persoane care au făcut pe %s prieten/ă",

	'friends:of' => "Prieteni cu",
	
	'friends:request:pending' => "Cereri de prietenie în așteptare",
	'friends:request:pending:none' => "Nu au fost găsite cereri de prietenie în așteptare.",
	'friends:request:sent' => "Cereri de prietenie trimise",
	'friends:request:sent:none' => "Nu s-au trimis cereri de prietenie.",
	
	'friends:num_display' => "Numărul de prieteni de afișat",
	
	'widgets:friends:name' => "Prieteni",
	'widgets:friends:description' => "Afișează-ți câțiva prieteni.",
	
	'friends:notification:request:subject' => "%s dorește să-ți fie prieten/ă!",
	'friends:notification:request:message' => "%s a cerut să-ți fie prieten/ă pe %s.

Pentru a vedea cererea de prietenie, apasă aici:
%s",
	
	'friends:notification:request:decline:subject' => "%s a respins cererea ta de prietenie",
	
	'friends:notification:request:accept:subject' => "%s a acceptat cererea ta de prietenie",
	
	'friends:action:friendrequest:revoke:fail' => "O eroare a apărut la revocarea cererii de prietenie, te rugăm să încerci din nou",
	'friends:action:friendrequest:revoke:success' => "Cererea de prietenie a fost revocată",
	
	'friends:action:friendrequest:decline:fail' => "O eroare a apărut la respingerea cererii de prietenie, te rugăm să încerci din nou",
	'friends:action:friendrequest:decline:success' => "Cererea de prietenie a fost respinsă",
	
	'friends:action:friendrequest:accept:success' => "Cererea de prietenie a fost acceptată",
	
	// notification settings
	'friends:notification:settings:description' => 'Setările de notificare implicite pentru utilizatorii pe care îi adaugi ca prieteni',
);
