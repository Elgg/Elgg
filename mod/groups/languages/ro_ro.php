<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	
	'groups' => "Grupuri",
	'groups:owned' => "Grupuri personale",
	'groups:owned:user' => 'Grupuri de %s',
	'groups:yours' => "Grupurile mele",
	'groups:user' => "Grupuri de %s",
	'groups:all' => "Toate grupurile",
	'groups:add' => "Creează un grup nou",
	'groups:edit' => "Editează grupul",
	'groups:edit:profile' => "Profil",
	'groups:edit:access' => "Acces",
	'groups:edit:tools' => "Unelte",
	'groups:edit:settings' => "Setări",
	'groups:membershiprequests' => 'Gestionează cererile de alăturare',
	'groups:membershiprequests:pending' => 'Gestionează cererile de alăturare (%s)',
	'groups:invitedmembers' => "Gestionează invitațiile",
	'groups:invitations' => 'Invitații de grup',
	'groups:invitations:pending' => 'Invitații de grup (%s)',
	
	'relationship:invited' => '%2$s a fost invitat/ă să se alăture pe %1$s',
	'relationship:membership_request' => '%s a cerut să se alăture pe %s',

	'groups:icon' => 'Imaginea grupului (lasă gol fără schimbare)',
	'groups:name' => 'Numele grupului',
	'groups:description' => 'Descriere',
	'groups:briefdescription' => 'Descriere scurtă',
	'groups:interests' => 'Etichete',
	'groups:website' => 'Site web',
	'groups:members' => 'Membri grupului',

	'groups:members_count' => '%s membri',

	'groups:members:title' => 'Membri pe %s',
	'groups:members:more' => "Vezi toți membri",
	'groups:membership' => "Permisiuni apartenență grup",
	'groups:content_access_mode' => "Accesibilitatea conținutului de grup",
	'groups:content_access_mode:warning' => "Atenție: Schimbând această setare nu va schimba și permisiunea de acces a conținutului de grup existent.",
	'groups:content_access_mode:unrestricted' => "Nerestricționat - Accesul depinde de setările la nivel de conținut",
	'groups:content_access_mode:membersonly' => "Numai Membri - Cei care nu sunt membri nu pot accesa conținutul de grup",
	'groups:access' => "Permisiuni de acces",
	'groups:owner' => "Deținător",
	'groups:owner:warning' => "Atenție: dacă schimbi această valoare, nu vei mai fi deținătorul acestui grup.",
	'groups:widget:num_display' => 'Numărul de grupuri pentru afișare',
	'widgets:a_users_groups:name' => 'Apartenența grupului',
	'widgets:a_users_groups:description' => 'Afișează pe profilul tău grupurile din care faci parte',

	'groups:noaccess' => 'Fără acces pe grup',
	'groups:cantcreate' => 'Nu poți crea un grup. Numai administratorii pot.',
	'groups:cantedit' => 'Nu poți edita acest grup',
	'groups:saved' => 'Grupul a fost salvat',
	'groups:save_error' => 'Grupul nu a putut fi salvat',
	'groups:featured' => 'Grupuri promovate',
	'groups:makeunfeatured' => 'Retrogradează',
	'groups:makefeatured' => 'Promovează',
	'groups:featuredon' => '%s este acum un grup promovat.',
	'groups:unfeatured' => '%s a fost îndepărtat din grupurile promovate.',
	'groups:featured_error' => 'Grup invalid.',
	'groups:nofeatured' => 'Nu sunt grupuri promovate',
	'groups:joinrequest' => 'Cere apartenență',
	'groups:join' => 'Alătură-te grupului',
	'groups:leave' => 'Părăsește grupul',
	'groups:invite' => 'Invită prieteni',
	'groups:invite:title' => 'Invită prieteni pe acest grup',
	'groups:invite:friends:help' => 'Caută un prieten după nume sau utilizator și selectează-l din listă',
	'groups:invite:resend' => 'Retrimite invitațiile utilizatorilor deja invitați',
	'groups:invite:member' => 'Deja un membru al acestui grup',
	'groups:invite:invited' => 'Deja invitat/ă pe acest grup',

	'groups:nofriendsatall' => 'Nu ai prieteni de invitat!',
	'groups:group' => "Grup",
	'groups:search:title' => "Caută grupuri după '%s'",
	'groups:search:none' => "Nu s-au găsit grupuri potrivite",
	'groups:search_in_group' => "Caută în acest grup",
	'groups:acl' => "Grup: %s",
	'groups:acl:in_context' => 'Membri grupului',

	'groups:notfound' => "Grupul nu a fost găsit",
	
	'groups:requests:none' => 'Nu există cereri de apartenență curente.',

	'groups:invitations:none' => 'Nu există invitații curente.',

	'groups:open' => "grup deschis",
	'groups:closed' => "grup închis",
	'groups:member' => "membri",
	'groups:search' => "Caută grupuri",

	'groups:more' => 'Mai multe grupuri',
	'groups:none' => 'Nu sunt grupuri',

	/**
	 * Access
	 */
	'groups:access:private' => 'Închis - Utilizatorii trebuie invitați',
	'groups:access:public' => 'Deschis - Orice utilizator se poate alătura',
	'groups:access:group' => 'Numai membri grupului',
	'groups:closedgroup' => "Apartenență la acest grup este închisă.",
	'groups:closedgroup:request' => 'Pentru a cere să fi adăugat/ă, apasă pe link-ul de meniu "Cere apartenență".',
	'groups:closedgroup:membersonly' => "Apartenență acestui grup este închisă iar conținutul acestuia este accesibil numai membrilor.",
	'groups:opengroup:membersonly' => "Conținutul acestui grup este accesibil numai membrilor.",
	'groups:opengroup:membersonly:join' => 'Pentru a deveni un membru, apasă pe link-ul de meniu "Alătură-te grupului".',
	'groups:visibility' => 'Cine poate vedea acest grup?',
	'groups:content_default_access' => 'Accesul implicit al conținutului de grup',
	'groups:content_default_access:help' => 'De aici poți configura accesul implicit al noului conținut din acest grup. Modul conținutului de grup poate preveni opțiunea selectată să ia efect.',
	'groups:content_default_access:not_configured' => 'Nu s-a configurat accesul implicit, lasă asta utilizatorului',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupuri',

	'groups:notitle' => 'Grupurile trebuie să aibă un titlu',
	'groups:cantjoin' => 'Nu se poate alătura grupului',
	'groups:cantleave' => 'Nu s-a putut părăsi grupul',
	'groups:removeuser' => 'Îndepărtează din grup',
	'groups:cantremove' => 'Nu s-a putut îndepărta utilizatorul din grup',
	'groups:removed' => 'S-a îndepărtat cu succes pe %s din grup',
	'groups:addedtogroup' => 'S-a adăugat cu succes utilizatorul pe grup',
	'groups:joinrequestnotmade' => 'Nu s-a putut cere alăturarea pe grup',
	'groups:joinrequestmade' => 'S-a cerut alăturarea pe grup',
	'groups:joinrequest:exists' => 'Ai cerut deja apartenență pentru acest grup',
	'groups:button:joined' => 'Alăturat/ă',
	'groups:button:owned' => 'Deținut/ă',
	'groups:joined' => 'Alăturare cu succes pe grup!',
	'groups:left' => 'Părăsire cu succes de pe grup',
	'groups:userinvited' => 'Utilizatorul a fost invitat.',
	'groups:usernotinvited' => 'Utilizatorul nu a putut fi invitat.',
	'groups:useralreadyinvited' => 'Utilizatorul a fost deja invitat.',
	'groups:invite:subject' => "%s ai fost invitat/ă să te alături pe %s!",
	'groups:joinrequest:remove:check' => 'Sigur dorești să îndepărtezi această cerere de alăturare?',
	'groups:invite:remove:check' => 'Sigur dorești să îndepărtezi această invitație?',
	'groups:invite:body' => "%s te-a invitat să te alături grupului '%s'.

Apasă mai jos pentru a-ți vedea invitațiile:
%s",

	'groups:welcome:subject' => "Bine ai venit pe grupul %s!",
	'groups:welcome:body' => "De acum ești membru al grupului '%s'.

Apasă mai jos pentru a începe să postezi!
%s",

	'groups:request:subject' => "%s a cerut să se alăture pe %s",
	'groups:request:body' => "%s a cerut să se alăture grupului '%s'.

Apasă mai jos pentru a-i vedea profilul:
%s

sau apasă mai jos pentru a vedea cererile de alăturare ale grupului:
%s",

	'river:group:create' => '%s a creat grupul %s',
	'river:group:join' => '%s s-a alăturat grupului %s',

	'groups:allowhiddengroups' => 'Permiți grupuri private (invizibile)?',
	'groups:whocancreate' => 'Cine poate crea grupuri noi?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Invitația a fost ștearsă.',
	'groups:joinrequestkilled' => 'Cererea de alăturare a fost ștearsă.',
	'groups:error:addedtogroup' => "Nu am putut adăuga pe %s în acest grup",
	'groups:add:alreadymember' => "%s este deja un membru al acestui grup",
	
	// Notification settings
	'groups:usersettings:notification:group_join:description' => "Setările de notificare implicite de grup atunci când se alătură pe un grup nou",
	
	'groups:usersettings:notifications:title' => 'Notificări de Grup',
	'groups:usersettings:notifications:description' => 'Pentru a primii notificări atunci când se adaugă conținut nou pe un grup al cărui membru ești, găsește-l mai jos și selectează metodele de notificare pe care dorești să le folosești.',
);
