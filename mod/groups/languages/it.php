<?php
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
	'groups:delete' => 'Elimina gruppo',
	'groups:membershiprequests' => 'Gestisci richieste di adesione',
	'groups:membershiprequests:pending' => 'Gestisci richieste di adesione (%s)',
	'groups:invitations' => 'Gruppi a cui sei invitato',
	'groups:invitations:pending' => 'Gruppi a cui sei invitato (%s)',

	'groups:icon' => 'Icona del gruppo (lasciare vuoto per tenerla invariata)',
	'groups:name' => 'Nome del gruppo',
	'groups:username' => 'Nome corto del gruppo (visualizzato negli URL, solo caratteri alfanumerici))',
	'groups:description' => 'Descrizione',
	'groups:briefdescription' => 'Breve descrizione',
	'groups:interests' => 'Tag',
	'groups:website' => 'Sito web',
	'groups:members' => 'Membri del gruppo',
	'groups:my_status' => 'Il mio stato',
	'groups:my_status:group_owner' => 'Questo gruppo è tuo',
	'groups:my_status:group_member' => 'Sei in questo gruppo',
	'groups:subscribed' => 'Notifiche gruppo attive',
	'groups:unsubscribed' => 'Notifiche gruppo non attive',

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
	'groups:widget:membership' => 'Gruppi di cui fai parte',
	'groups:widgets:description' => 'Visualizza i gruppi di cui sei membro nel tuo profilo',

	'groups:widget:group_activity:title' => 'Attività del gruppo',
	'groups:widget:group_activity:description' => 'Visualizza le attività in uno dei tuoi gruppi',
	'groups:widget:group_activity:edit:select' => 'Seleziona un gruppo',
	'groups:widget:group_activity:content:noactivity' => 'Nessuna attività in questo gruppo',
	'groups:widget:group_activity:content:noselect' => 'Modifica questo widget per selezionare un gruppo',

	'groups:noaccess' => 'Nessun accesso al gruppo',
	'groups:permissions:error' => 'Non hai le autorizzazioni per questo',
	'groups:ingroup' => 'nel gruppo',
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
	'groups:inviteto' => "Invita amici a '%s'",
	'groups:nofriends' => "Non ti sono rimasti amici che non siano stati invitati a questo gruppo.",
	'groups:nofriendsatall' => 'Non hai amici da invitare!',
	'groups:viagroups' => "tramite i gruppi",
	'groups:group' => "Gruppo",
	'groups:search:tags' => "tag",
	'groups:search:title' => "Cerca gruppi col tag '%s'",
	'groups:search:none' => "Nessun gruppo corrispondente trovato",
	'groups:search_in_group' => "Cerca in questo gruppo",
	'groups:acl' => "Gruppo: %s",

	'discussion:topic:notify:summary' => 'Nuova discussione denominata %s',
	'discussion:topic:notify:subject' => 'Nuova discussione: %s',
	'discussion:topic:notify:body' =>
'%s ha aggiunto una nuova discussione: %s

Titolo: %s

%s

Visualizza e partecipa alla discussione:
%s
',

	'discussion:reply:notify:summary' => 'Nuova risposta nella discussione: %s',
	'discussion:reply:notify:subject' => 'Nuova risposta nella discussione: %s',
	'discussion:reply:notify:body' =>
'%s è intervenuto/a nella discussione %s del gruppo %s:

%s

Visualizza e rispondi:
%s
',

	'groups:activity' => "Attività del gruppo",
	'groups:enableactivity' => 'Abilita attività del gruppo',
	'groups:activity:none' => "Non ci sono ancora attività nel gruppo",

	'groups:notfound' => "Gruppo non trovato",
	'groups:notfound:details' => "Il gruppo richiesto non esiste o non puoi accedervi",

	'groups:requests:none' => 'Non ci sono richieste di iscrizione in sospeso.',

	'groups:invitations:none' => 'Non ci sono inviti in sospeso.',

	'item:object:groupforumtopic' => "Argomenti del forum",
	'item:object:discussion_reply' => "Risposte alla discussione",

	'groupforumtopic:new' => "Nuovo intervento",

	'groups:count' => "gruppi creati",
	'groups:open' => "gruppo aperto",
	'groups:closed' => "gruppo chiuso",
	'groups:member' => "membri",
	'groups:searchtag' => "Cerca gruppo per tag",

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
	'groups:enableforum' => 'Abilita le discussioni di gruppo',
	'groups:lastupdated' => 'Ultimo aggiornamento %s di %s',
	'groups:lastcomment' => 'Ultimo commento %s di %s',

	/**
	 * Group discussion
	 */
	'discussion' => 'Discussione',
	'discussion:add' => 'Aggiungi argomento di discussione',
	'discussion:latest' => 'Ultime discussioni',
	'discussion:group' => 'Discussioni del gruppo',
	'discussion:none' => 'Nessuna discussione',
	'discussion:reply:title' => 'Rispondi via %s',

	'discussion:topic:created' => 'Argomento di discussione creato',
	'discussion:topic:updated' => 'Argomento di discussione aggiornato.',
	'discussion:topic:deleted' => 'Argomento di discussione eliminato.',

	'discussion:topic:notfound' => 'Argomento di discussione non trovato',
	'discussion:error:notsaved' => 'Impossibile salvare questo argomento',
	'discussion:error:missing' => 'Titolo e Testo sono entrambi campi obbligatori',
	'discussion:error:permissions' => 'Permessi insufficienti per eseguire questa azione',
	'discussion:error:notdeleted' => 'Impossibile eliminare questo argomento di discussione',

	'discussion:reply:edit' => 'Modifica risposta',
	'discussion:reply:deleted' => 'Risposta alla discussione eliminata',
	'discussion:reply:error:notfound' => 'Impossibile trovare la risposta alla discussione',
	'discussion:reply:error:notfound_fallback' => "Impossibile trovare la risposta specificata, ma sei stato inoltrato all'argomento di discussione originario.",
	'discussion:reply:error:notdeleted' => 'Impossibile eliminare la risposta alla discussione',

	'discussion:search:title' => 'Risposta all\'argomento: %s',
	
	'admin:groups' => 'Gruppi',

	'reply:this' => 'Rispondi a questo',

	'group:replies' => 'Risposte',
	'groups:forum:created' => 'Creato %s con %d commenti',
	'groups:forum:created:single' => 'Creato %s con %d risposte',
	'groups:forum' => 'Discussione',
	'groups:addtopic' => 'Aggiungi un argomento',
	'groups:forumlatest' => 'Ultima discussione',
	'groups:latestdiscussion' => 'Ultima discussione',
	'groupspost:success' => 'La tua risposta è stata inviata',
	'groupspost:failure' => 'Si è verificato un problema nell\'inviare la tua risposta',
	'groups:alldiscussion' => 'Ultima discussione',
	'groups:edittopic' => 'Modifica argomento',
	'groups:topicmessage' => 'Testo dell\'argomento',
	'groups:topicstatus' => 'Stato dell\'argomento',
	'groups:reply' => 'Invia un commento',
	'groups:topic' => 'Argomento',
	'groups:posts' => 'Risposte',
	'groups:lastperson' => 'Ultima persona',
	'groups:when' => 'Quando',
	'grouptopic:notcreated' => 'Nessun argomento è stato creato.',
	'groups:topicclosed' => 'Chiuso',
	'grouptopic:created' => 'Il tuo argomento è stato creato.',
	'groups:topicsticky' => 'In evidenza',
	'groups:topicisclosed' => 'Questa discussione è chiusa.',
	'groups:topiccloseddesc' => 'Questa discussione è stata chiusa, nuovi commenti non sono ammessi.',
	'grouptopic:error' => 'L\'argomento non può essere creato. Riprovare o contattare l\'amministratore di sistema.',
	'groups:forumpost:edited' => "Risposta modificata.",
	'groups:forumpost:error' => "Si è verificato un problema nel modificare la risposta.",

	'groups:privategroup' => 'Questo gruppo è privato, richiedi di iscriverti.',
	'groups:notitle' => 'I gruppi devono avere un titolo',
	'groups:cantjoin' => 'Non puoi unirti al gruppo',
	'groups:cantleave' => 'Non puoi abbandonare il gruppo',
	'groups:removeuser' => 'Abbandona il gruppo',
	'groups:cantremove' => 'Impossibile rimuovere l\'utente dal gruppo',
	'groups:removed' => '%s è stato rimosso dal gruppo',
	'groups:addedtogroup' => 'L\'utente è stato aggiunto al gruppo',
	'groups:joinrequestnotmade' => 'Impossibile richiedere l\'iscrizione al gruppo',
	'groups:joinrequestmade' => 'Hanno richiesto di iscriversi al gruppo',
	'groups:joined' => 'Ora fai parte del gruppo!',
	'groups:left' => 'Hai abbandonato il gruppo!',
	'groups:notowner' => 'Spiacenti, non sei il proprietario di questo gruppo.',
	'groups:notmember' => 'Spiacenti, non sei membro di questo gruppo.',
	'groups:alreadymember' => 'Sei già membro di questo gruppo!',
	'groups:userinvited' => 'L\'utente è stato invitato.',
	'groups:usernotinvited' => 'L\'utente non può essere invitato.',
	'groups:useralreadyinvited' => 'L\'utente è già stato invitato',
	'groups:invite:subject' => "%s sei stato invitato a unirti a %s!",
	'groups:updated' => "Ultima risposta da %s %s",
	'groups:started' => "Iniziato da %s",
	'groups:joinrequest:remove:check' => 'Sei sicuro di voler annullare questa richiesta di iscrizione?',
	'groups:invite:remove:check' => 'Sei sicuro di voler annullare questo invito?',
	'groups:invite:body' => "Ciao %s,

%s ti ha invitato a far parte del gruppo '%s'. Clicca sotto per vedere il tuo invito:

%s",

	'groups:welcome:subject' => "Benvenuto/a nel gruppo %s!",
	'groups:welcome:body' => "Ciao %s!

Sei ora un membro del gruppo  '%s'! Clicca qui sotto per partecipare alle discussioni!

%s",

	'groups:request:subject' => "%s ha richiesto di unirsi a %s",
	'groups:request:body' => "Ciao %s,

%s ha richiesto di unirsi al gruppo '%s', clicca qui sotto per vedere il suo profilo:

%s

o clicca qui sotto per vedere le richieste di iscrizione al gruppo:

%s",

	/**
	 * Forum river items
	 */

	'river:create:group:default' => '%s ha creato il gruppo %s',
	'river:join:group:default' => '%s si è iscritto al gruppo %s',
	'river:create:object:groupforumtopic' => '%s ha avviato una nuova discussione %s',
	'river:reply:object:groupforumtopic' => '%s è intervenuto nella discussione %s',
	'river:reply:view' => 'visualizza risposta',

	'groups:nowidgets' => 'Nessun widget è stato definito per questo gruppo.',


	'groups:widgets:members:title' => 'Membri del gruppo',
	'groups:widgets:members:description' => 'Elenca i membri di un gruppo.',
	'groups:widgets:members:label:displaynum' => 'Elenca i membri di un gruppo.',
	'groups:widgets:members:label:pleaseedit' => 'Per favore configura questo widget.',

	'groups:widgets:entities:title' => "Oggetti nel gruppo",
	'groups:widgets:entities:description' => "Elenca gli oggetti salvati in questo gruppo",
	'groups:widgets:entities:label:displaynum' => 'Elenca gli oggetti di un gruppo.',
	'groups:widgets:entities:label:pleaseedit' => 'Per favore configura questo widget.',

	'groups:forumtopic:edited' => 'Argomento del forum modificato con successo.',

	'groups:allowhiddengroups' => 'Vuoi permettere gruppi privati (invisibili)?',
	'groups:whocancreate' => 'Chi può creare nuovi gruppi?',

	/**
	 * Action messages
	 */
	'group:deleted' => 'Il gruppo e il suoi contenuti sono stati eliminati',
	'group:notdeleted' => 'Il gruppo non può essere eliminato',

	'group:notfound' => 'Gruppo non trovato',
	'grouppost:deleted' => 'Risposta eliminata',
	'grouppost:notdeleted' => 'La risposta non può essere eliminata',
	'groupstopic:deleted' => 'Argomento eliminato',
	'groupstopic:notdeleted' => 'Argomento non eliminato',
	'grouptopic:blank' => 'Nessun argomento',
	'grouptopic:notfound' => 'Argomento non trovato',
	'grouppost:nopost' => 'Risposta vuota',
	'groups:deletewarning' => "Sicuri di voler eliminare questo gruppo? Questa azione non può essere annullata!",

	'groups:invitekilled' => 'L\'invito è stato eliminato.',
	'groups:joinrequestkilled' => 'La richiesta di iscrizione è stata cancellata.',
	'groups:error:addedtogroup' => "Impossibile aggiungere %s al gruppo",
	'groups:add:alreadymember' => "%s è già membro di questo gruppo",

	/**
	 * ecml
	 */
	'groups:ecml:discussion' => 'Argomenti di discussione del gruppo',
	'groups:ecml:groupprofile' => 'Profili del gruppo',
);
