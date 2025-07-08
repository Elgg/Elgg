<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Ιστολόγιο',
	'collection:object:blog' => 'Ιστολόγια',
	
	'collection:object:blog:all' => 'Όλα τα ιστολόγια',
	'collection:object:blog:owner' => 'Ιστολόγια χρήστη %s',
	'collection:object:blog:group' => 'Ιστολόγια ομάδων',
	'collection:object:blog:friends' => 'Ιστολόγια φίλων',
	'add:object:blog' => 'Προσθήκη ανάρτησης',
	'edit:object:blog' => 'Επεξεργασία ανάρτησης',

	'blog:revisions' => 'Αναθεωρήσεις',
	'blog:archives' => 'Αρχεία',

	'groups:tool:blog' => 'Ενεργοποίηση ιστολογίου ομάδας',

	// Editing
	'blog:excerpt' => 'Aπόσπασμα',
	'blog:body' => 'Κείμενο',
	'blog:save_status' => 'Τελευταία αποθήκευση:',

	'blog:revision' => 'Αναθεώρηση',
	
	// messages
	'blog:message:saved' => 'Η ανάρτηση αποθηκεύτηκε.',
	'blog:error:cannot_save' => 'Αδύνατη η αποθήκευση της ανάρτησης',
	'blog:error:cannot_write_to_container' => 'Μη έγκυρη πρόσβαση για αποθήκευση της ανάρτησης στην ομάδα',
	'blog:edit_revision_notice' => '(Παλιά έκδοση)',
	'blog:none' => 'Δεν υπάρχουν αναρτήσεις', // @todo remove in Elgg 7.0
	'blog:error:missing:title' => 'Παρακαλούμε εισάγετε τίτλο της ανάρτησης!',
	'blog:error:missing:description' => 'Παρακαλούμε εισάγετε κείμενο της ανάρτησης!',
	'blog:error:post_not_found' => 'Δεν είναι δυνατή η εύρεση της ανάρτησης',
	'blog:error:revision_not_found' => 'Αδύνατη εύρεση της αναθέωρησης',

	// river

	// notifications
	'blog:notify:summary' => 'Νέα ανάρτηση με τίτλο %s',
	'blog:notify:subject' => 'Νέα ανάρτηση: %s',

	// widget
	'widgets:blog:description' => 'Προβολή πρόσφατων αναρτήσεων σας',
	'blog:moreblogs' => 'Περισσότερες αναρτήσεις',
	'blog:numbertodisplay' => 'Αριθμός αναρτήσεων για εμφάνιση',
);
