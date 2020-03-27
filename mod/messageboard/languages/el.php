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

	'messageboard:board' => "Πίνακας μηνυμάτων",
	'messageboard:messageboard' => "πίνακας μηνυμάτων",
	'messageboard:none' => "Δεν υπάρχουν μηνύματα ακόμα",
	'messageboard:num_display' => "Αριθμός μηνυμάτων για εμφάνιση",
	'messageboard:user' => "πίνακας μηνυμάτων του χρήστη %s",
	'messageboard:owner' => 'πίνακας μηνυμάτων του χρήστη %s',
	'messageboard:owner_history' => 'οι αναρτήσεις του χρήστη %s στον πίνακα μηνυμάτων του χρήστη %s',

	/**
	 * Message board widget river
	 */
	'river:user:messageboard' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'annotation:delete:messageboard:fail' => "Sorry, we could not delete this message",
	'annotation:delete:messageboard:success' => "You successfully deleted the message",
	
	'messageboard:posted' => "Δημοσιεύσατε με επιτυχία στον πινάκα μηνυμάτων.",
	'messageboard:deleted' => "Διαγράψατε με επιτυχία το μήνυμα.",

	/**
	 * Email messages
	 */

	'messageboard:email:subject' => 'Έχετε ένα νέο σχόλιο στον πίνακα μηνυμάτων',
	'messageboard:email:body' => "You have a new message board comment from %s.

It reads:

%s

To view your message board comments, click here:
%s

To view %s's profile, click here:
%s",

	/**
	 * Error messages
	 */

	'messageboard:blank' => "Λυπούμαστε,  θα πρέπει προσθέσετε κάτι στην περιοχή μηνυμάτων για να μπορέσουμε να το αποθηκεύσουμε.",
	'messageboard:notdeleted' => "Λυπούμαστε, δεν μπορέσαμε να διαγράψουμε αυτό το μήνυμα.",

	'messageboard:failure' => "Παρουσιάστηκε μη αναμενόμενο σφάλμα κατά την προσθήκη του μηνύματος σας. Παρακαλούμε δοκιμάστε ξανά.",

	'widgets:messageboard:name' => "Πίνακας μηνυμάτων",
	'widgets:messageboard:description' => "Αυτός είναι ένας πίνακας μηνυμάτων που μπορείτε να βάλετε στο προφίλ σας, όπου άλλοι χρήστες θα μπορούν να σχολιάσουν.",
);
