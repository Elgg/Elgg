<?php
return array(
	'item:object:blog' => 'Blog',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'All site blogs',
	'collection:object:blog:owner' => '%s\'s blogs',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Add blog post',
	'edit:object:blog' => 'Edit blog post',

	'blog:revisions' => 'Revisioni',
	'blog:archives' => 'Archivi',

	'groups:tool:blog' => 'Enable group blog',
	'blog:write' => 'Scrivi un articolo',

	// Editing
	'blog:excerpt' => 'Estratto',
	'blog:body' => 'Corpo',
	'blog:save_status' => 'Ultimo salvataggio:',

	'blog:revision' => 'Revisione',
	'blog:auto_saved_revision' => 'Revisione salvata automaticamente',

	// messages
	'blog:message:saved' => 'Articolo salvato.',
	'blog:error:cannot_save' => 'Impossibile salvare l\'articolo.',
	'blog:error:cannot_auto_save' => 'Impossibile salvare automaticamente l\'articolo.',
	'blog:error:cannot_write_to_container' => 'Privilegi insufficienti per salvare l\'articolo sul gruppo.',
	'blog:messages:warning:draft' => 'C\'è una bozza non salvata di questo articolo!',
	'blog:edit_revision_notice' => '(Vecchia versione)',
	'blog:message:deleted_post' => 'Articolo eliminato.',
	'blog:error:cannot_delete_post' => 'Impossibile eliminare l\'articolo.',
	'blog:none' => 'Nessun articolo.',
	'blog:error:missing:title' => 'Si prega di inserire un titolo per l\'articolo.',
	'blog:error:missing:description' => 'Si prega di inserire i contenuti dell\'articolo.',
	'blog:error:cannot_edit_post' => 'Questo articolo potrebbe non esistere oppure non hai il permesso di modificarlo.',
	'blog:error:post_not_found' => 'Impossibile trovare l\'articolo specificato.',
	'blog:error:revision_not_found' => 'Impossibile trovare questa revisione.',

	// river
	'river:object:blog:create' => '%sha pubblicato un articolo nel blog %s',
	'river:object:blog:comment' => '%s ha commentato il blog %s',

	// notifications
	'blog:notify:summary' => 'Nuovo articolo intitolato %s',
	'blog:notify:subject' => 'Nuovo articolo: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Display your latest blog posts',
	'blog:moreblogs' => 'Altri articoli',
	'blog:numbertodisplay' => 'Numero di articoli da visualizzare',
);
