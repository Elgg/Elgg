<?php
return array(
	'item:object:blog' => 'Blogy',
	'collection:object:blog' => 'Blogs',
	'collection:object:blog:all' => 'All site blogs',
	'collection:object:blog:owner' => '%s\'s blogs',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Friends\' blogs',
	'add:object:blog' => 'Add blog post',
	'edit:object:blog' => 'Edit blog post',

	'blog:revisions' => 'Revize',
	'blog:archives' => 'Archivy',

	'groups:tool:blog' => 'Enable group blog',
	'blog:write' => 'Napsat blog',

	// Editing
	'blog:excerpt' => 'Úryvek',
	'blog:body' => 'Text',
	'blog:save_status' => 'Naposledy uloženo:',

	'blog:revision' => 'Revize',
	'blog:auto_saved_revision' => 'Automaticky uložená revize',

	// messages
	'blog:message:saved' => 'Blog byl publikován.',
	'blog:error:cannot_save' => 'Nemohu uložit blog.',
	'blog:error:cannot_auto_save' => 'Nemohu automaticky uložit blog.',
	'blog:error:cannot_write_to_container' => 'Nemáte práva k uložení blogu do skupiny.',
	'blog:messages:warning:draft' => 'Pro tento příspěvek máte neuložený koncept!',
	'blog:edit_revision_notice' => '(Stará verze)',
	'blog:message:deleted_post' => 'Blog byl odstraněn.',
	'blog:error:cannot_delete_post' => 'Nemohu odstranit blog.',
	'blog:none' => 'Žádné blogy.',
	'blog:error:missing:title' => 'Zadejte prosím nadpis blogu!',
	'blog:error:missing:description' => 'Napište prosím text vašeho blogu!',
	'blog:error:cannot_edit_post' => 'Tento příspěvek buď neexistuje nebo nemáte povolení ho upravovat.',
	'blog:error:post_not_found' => 'Nemohu najít požadovaný blog.',
	'blog:error:revision_not_found' => 'Nemohu najít tuto revizi.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Nový blog s názvem %s',
	'blog:notify:subject' => 'Nový blog: %s',
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
	'blog:moreblogs' => 'Více blogů',
	'blog:numbertodisplay' => 'Počet zobrazených blogů',
);
