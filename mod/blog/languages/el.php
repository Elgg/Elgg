<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Ιστολόγια',
	'collection:object:blog' => 'Ιστολόγια',
	'collection:object:blog:all' => 'Όλα τα ιστολόγια',
	'collection:object:blog:owner' => 'ιστολόγια του χρήστη %s',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Ιστολόγια φίλων',
	'add:object:blog' => 'Προσθέστε μία νέα ανάρτηση',
	'edit:object:blog' => 'Επεξεργασία ανάρτησης',

	'blog:revisions' => 'Αναθεωρήσεις',
	'blog:archives' => 'Αρχεία',

	'groups:tool:blog' => 'Ενεργοποίηση ιστολογίου ομάδας',
	'blog:write' => 'Γράψτε μία νέα ανάρτηση',

	// Editing
	'blog:excerpt' => 'Aπόσπασμα',
	'blog:body' => 'Κείμενο',
	'blog:save_status' => 'Τελευταία αποθήκευση:',

	'blog:revision' => 'Αναθεώρηση',
	'blog:auto_saved_revision' => 'Αυτόματη αποθήκευση αναθεώρησης',

	// messages
	'blog:message:saved' => 'Η ανάρτηση αποθηκεύτηκε',
	'blog:error:cannot_save' => 'Αδύνατη η αποθήκευση της ανάρτησης',
	'blog:error:cannot_auto_save' => 'Αδύνατη η αυτόματη αποθήκευση της ανάρτησης',
	'blog:error:cannot_write_to_container' => 'Μη έγκυρη πρόσβαση για αποθήκευση της ανάρτησης στην ομάδα',
	'blog:messages:warning:draft' => 'Υπάρχει μη αποθηκευμένο προσχέδιο αυτής της ανάρτησης!',
	'blog:edit_revision_notice' => '(Παλιά έκδοση)',
	'blog:message:deleted_post' => 'Η ανάρτηση διαγράφτηκε',
	'blog:error:cannot_delete_post' => 'Αδύνατη η διαγραφή της ανάρτησης',
	'blog:none' => 'Δεν υπάρχουν αναρτήσεις',
	'blog:error:missing:title' => 'Παρακαλούμε εισάγετε τίτλο της ανάρτησης!',
	'blog:error:missing:description' => 'Παρακαλούμε εισάγετε κείμενο της ανάρτησης!',
	'blog:error:cannot_edit_post' => 'Η ανάρτηση που ζητήσατε δεν υπάρχει ή δεν έχετε δικαιώματα για επεξεργασία της',
	'blog:error:post_not_found' => 'Δεν είναι δυνατή η εύρεση της ανάρτησης',
	'blog:error:revision_not_found' => 'Αδύνατη εύρεση της αναθέωρησης',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => 'Νέα ανάρτηση με τίτλο %s',
	'blog:notify:subject' => 'Νέα ανάρτηση: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Προβολή πρόσφατων αναρτήσεων σας',
	'blog:moreblogs' => 'Περισσότερες αναρτήσεις',
	'blog:numbertodisplay' => 'Αριθμός αναρτήσεων για εμφάνιση',
);
