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
	
	'groups' => "Gruppi",
	'groups:owned' => "Gruppi creati da te",
	'groups:owned:user' => 'Gruppi creati da %s',
	'groups:yours' => "I gruppi a cui partecipi",
	'groups:user' => "Gruppi a cui partecipa %s",
	'groups:all' => "Tutti i gruppi",
	'groups:add' => "Crea un nuovo gruppo",
	'groups:edit' => "Modifica gruppo",
	'groups:membershiprequests' => 'Gestisci richieste di adesione',
	'groups:membershiprequests:pending' => 'Gestisci richieste di adesione (%s)',
	'groups:invitations' => 'Gruppi a cui sei invitato',
	'groups:invitations:pending' => 'Gruppi a cui sei invitato (%s)',

	'groups:icon' => 'Icona del gruppo (lasciare vuoto per tenerla invariata)',
	'groups:name' => 'Nome del gruppo',
	'groups:description' => 'Descrizione',
	'groups:briefdescription' => 'Breve descrizione',
	'groups:interests' => 'Tag',
	'groups:website' => 'Sito web',
	'groups:members' => 'Membri del gruppo',

	'groups:members_count' => '%s membri',

	'groups:members:title' => 'Membri di %s',
	'groups:members:more' => "Visualizza tutti i membri",
	'groups:membership' => "Autorizzazioni dei membri del gruppo",
	'groups:content_access_mode' => "Accessibilità dei contenuti del gruppo",
	'groups:content_access_mode:warning' => "Attenzione: cambiare questa impostazione non cambierà i permessi di accesso dei contenuti di gruppo già esistenti.",
	'groups:content_access_mode:unrestricted' => "Liberi - L'accesso dipende dalle impostazioni di accesso a livello di contenuto",
	'groups:content_access_mode:membersonly' => "Solo membri - Solo i membri del gruppo possono accedere ai contenuti di gruppo",
	'groups:access' => "Autorizzazioni di accesso",
	'groups:owner' => "Responsabile",
	'groups:owner:warning' => "Attenzione: con questa modifica non sarai più il responsabile di questo gruppo.",
	'groups:widget:num_display' => 'Numero di gruppi da visualizzare',
	'widgets:a_users_groups:name' => 'Gruppi di cui fai parte',
	'widgets:a_users_groups:description' => 'Visualizza i gruppi di cui sei membro nel tuo profilo',

	'groups:noaccess' => 'Nessun accesso al gruppo',
	'groups:cantcreate' => 'Non puoi creare un gruppo. Funzione riservata agli amministratori',
	'groups:cantedit' => 'Non puoi modificare questo gruppo',
	'groups:saved' => 'Gruppo salvato',
	'groups:save_error' => 'Il gruppo non può essere salvato',
	'groups:featured' => 'Gruppi in evidenza',
	'groups:makeunfeatured' => 'Non in evidenza',
	'groups:makefeatured' => 'Porta in evidenza',
	'groups:featuredon' => '%s è ora un gruppo in evidenza',
	'groups:unfeatured' => '%s è stato rimosso dai gruppi in evidenza',
	'groups:featured_error' => 'Gruppo non valido.',
	'groups:nofeatured' => 'Nessun gruppo in evidenza',
	'groups:joinrequest' => 'Chiedi di iscriverti al gruppo',
	'groups:join' => 'Unisciti al gruppo',
	'groups:leave' => 'Abbandona il gruppo',
	'groups:invite' => 'Invita amici',
	'groups:invite:title' => 'Invita amici a questo gruppo',
	'groups:invite:friends:help' => 'Cerca un amico usando il nome o il nome utente e selezionalo dall\'elenco',
	'groups:invite:resend' => 'Reinvia gli inviti agli utenti già invitati',

	'groups:nofriendsatall' => 'Non hai amici da invitare!',
	'groups:group' => "Gruppo",
	'groups:search:title' => "Cerca gruppi col tag '%s'",
	'groups:search:none' => "Nessun gruppo corrispondente trovato",
	'groups:search_in_group' => "Cerca in questo gruppo",
	'groups:acl' => "Gruppo: %s",
	'groups:acl:in_context' => 'Membri del gruppo',

	'groups:notfound' => "Gruppo non trovato",
	
	'groups:requests:none' => 'Non ci sono richieste di iscrizione in sospeso.',

	'groups:invitations:none' => 'Non ci sono inviti in sospeso.',

	'groups:open' => "gruppo aperto",
	'groups:closed' => "gruppo chiuso",
	'groups:member' => "membri",
	'groups:search' => "Cerca gruppi",

	'groups:more' => 'Altri gruppi',
	'groups:none' => 'Nessun gruppo',

	/**
	 * Access
	 */
	'groups:access:private' => 'Chiuso - Gli utenti devono essere invitati',
	'groups:access:public' => 'Aperto - Tutti gli utenti possono partecipare',
	'groups:access:group' => 'Solo membri del gruppo',
	'groups:closedgroup' => "Le iscrizioni a questo gruppo sono chiuse.",
	'groups:closedgroup:request' => 'Per richiedere l\'iscrizione al gruppo clicca sul relativo collegamento.',
	'groups:closedgroup:membersonly' => "L'iscrizione a questo gruppo è chiusa e i suoi contenuti sono accessibili solo dai membri.",
	'groups:opengroup:membersonly' => "I contenuti di questo gruppo sono accessibili solo dai suoi membri",
	'groups:opengroup:membersonly:join' => 'Per iscriversi al gruppo clicca su "Iscriviti al gruppo"',
	'groups:visibility' => 'Chi può vedere questo gruppo?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Gruppi',

	'groups:notitle' => 'I gruppi devono avere un titolo',
	'groups:cantjoin' => 'Non puoi unirti al gruppo',
	'groups:cantleave' => 'Non puoi abbandonare il gruppo',
	'groups:removeuser' => 'Abbandona il gruppo',
	'groups:cantremove' => 'Impossibile rimuovere l\'utente dal gruppo',
	'groups:removed' => '%s è stato rimosso dal gruppo',
	'groups:addedtogroup' => 'L\'utente è stato aggiunto al gruppo',
	'groups:joinrequestnotmade' => 'Impossibile richiedere l\'iscrizione al gruppo',
	'groups:joinrequestmade' => 'Hanno richiesto di iscriversi al gruppo',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Hai aderito',
	'groups:button:owned' => 'Di tua proprietà',
	'groups:joined' => 'Ora fai parte del gruppo!',
	'groups:left' => 'Hai abbandonato il gruppo!',
	'groups:userinvited' => 'L\'utente è stato invitato.',
	'groups:usernotinvited' => 'L\'utente non può essere invitato.',
	'groups:useralreadyinvited' => 'L\'utente è già stato invitato',
	'groups:invite:subject' => "%s sei stato invitato a unirti a %s!",
	'groups:joinrequest:remove:check' => 'Sei sicuro di voler annullare questa richiesta di iscrizione?',
	'groups:invite:remove:check' => 'Sei sicuro di voler annullare questo invito?',

	'groups:welcome:subject' => "Benvenuto/a nel gruppo %s!",

	'groups:request:subject' => "%s ha richiesto di unirsi a %s",

	'river:group:create' => '%s ha creato il gruppo %s',
	'river:group:join' => '%s ha aderito al gruppo %s',

	'groups:allowhiddengroups' => 'Vuoi permettere gruppi privati (invisibili)?',
	'groups:whocancreate' => 'Chi può creare nuovi gruppi?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'L\'invito è stato eliminato.',
	'groups:joinrequestkilled' => 'La richiesta di iscrizione è stata cancellata.',
	'groups:error:addedtogroup' => "Impossibile aggiungere %s al gruppo",
	'groups:add:alreadymember' => "%s è già membro di questo gruppo",
	
	// Notification settings
);
