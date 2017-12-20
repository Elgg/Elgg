<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Ιστότοποι',

/**
 * Sessions
 */

	'login' => "Σύνδεση",
	'loginok' => "Έχετε συνδεθεί",
	'loginerror' => "Δεν μπορούμε να σας συνδέσουμε. Αυτό μπορεί να συμβαίνει επειδή δεν έχει επικυρωθεί ο λογαριασμός σας, τα στοιχεία που βάζετε είναι λάθος, ή έχετε κάνει πάρα πολλές εσφαλμένες προσπάθειες σύνδεσης. Βεβαιωθείτε ότι τα στοιχεία σας είναι σωστά και  προσπαθήστε ξανά.",
	'login:empty' => "Τα πεδία Όνομα Χρήστη/email και Κωδικός είναι υποχρεωτικά.",
	'login:baduser' => "Δεν είναι δυνατή η ανάκτηση του λογαριασμού σας.",
	'auth:nopams' => "Σφάλμα. Δεν έχει εγκατασταθεί καμία μέθοδος πιστοποίησης χρηστών.",

	'logout' => "Αποσύνδεση",
	'logoutok' => "Έχετε αποσυνδεθεί.",
	'logouterror' => "Δεν μπορούμε να σας αποσυνδέσουμε. Παρακαλούμε δοκιμάστε ξανά.",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "Πρέπει να συνδεθείτε για να δείτε αυτή τη σελίδα.",
	'adminrequired' => "Πρέπει να είστε διαχειριστής για να δείτε αυτή τη σελίδα.",
	'membershiprequired' => "Πρέπει να είστε μέλος της ομάδας για να δείτε αυτή τη σελίδα.",
	'limited_access' => "You do not have permission to view the requested page.",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "Σφάλμα.",
	'exception:contact_admin' => 'Ένα μη αναστρέψιμο σφάλμα έχει καταγραφεί. Επικοινωνήστε με το διαχειριστή της σελίδας με την ακόλουθη πληροφορία:',

	'actionundefined' => "Η ενέργεια που ζητήσατε (%s) δεν έχει καθοριστεί στο σύστημα",
	'actionnotfound' => "Δεν βρέθηκε το αρχείο για %s.",
	'actionloggedout' => "Λυπούμαστε, αλλά δεν μπορείτε να εκτελέσετε αυτήν την ενέργεια ενώ έχετε αποσυνδεθεί.",
	'actionunauthorized' => 'Δεν έχετε εξουσιοδότηση για να εκτελέσετε αυτή την ενέργεια',

	'ajax:error' => 'Unexpected error while performing an AJAX call. Maybe the connection to the server is lost.',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is a misconfigured plugin. It has been disabled. Please search the Elgg wiki for possible causes (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) δεν μπόρεσε να αρχικοποιηθεί και έχει απενεργοποιηθεί. Αιτία: %s',
	'PluginException:InvalidID' => "%s είναι μη έγκυρος κωδικός πρόσθετου (plugin).",
	'PluginException:InvalidPath' => "%s είναι μη έγκυρη διαδρομή για το plugin.",
	'PluginException:InvalidManifest' => 'Μη έγκυρο αρχείο manifest για το plugin %s',
	'PluginException:InvalidPlugin' => '%s είναι μη έγκυρο πρόσθετο (plugin).',
	'PluginException:InvalidPlugin:Details' => '%s είναι μη έγκυρο πρόσθετο (plugin): %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',
	'ElggPlugin:MissingID' => 'Missing plugin ID (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Missing ElggPluginPackage for plugin ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Missing file %s in package',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'This plugin\'s directory must be renamed to "%s" to match the ID in its manifest.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Μη έγκυρος τύπος εξάρτησης "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Invalid provides type "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Conflicts with plugin: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Plugin file "elgg-plugin.php" file is present but unreadable.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',
	'PluginException:NoPluginName' => "Το όνομα του plugin δεν ήταν δυνατόν να βρεθεί",
	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s είναι μη έγκυρο plugin και θα απενεργοποιηθεί.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:Requires' => 'Απαιτεί',
	'ElggPlugin:Dependencies:Suggests' => 'Προτείνει',
	'ElggPlugin:Dependencies:Conflicts' => 'Σε σύγκρουση',
	'ElggPlugin:Dependencies:Conflicted' => 'Conflicted',
	'ElggPlugin:Dependencies:Provides' => 'Παρέχει',
	'ElggPlugin:Dependencies:Priority' => 'Προτεραιότητα',

	'ElggPlugin:Dependencies:Elgg' => 'Έκδοση Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'PHP version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP επέκταση: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Ρύθμιση PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Μετά από %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Πριν από %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s δεν έχει εγκατασταθεί',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Λείπει',

	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items found without parents to link them to',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] found with a missing parent[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Duplicate registration found for menu item [%s]',

	'RegistrationException:EmptyPassword' => 'Ο κωδικός δεν μπορεί να είναι κενός',
	'RegistrationException:PasswordMismatch' => 'Τα πεδία για τον κωδικό πρέπει να ταιριάζουν',
	'LoginException:BannedUser' => 'Έχει απαγορευθεί η πρόσβαση σας σ\' αυτόν τον ιστότοπο και δεν μπορείτε να συνδεθείτε',
	'LoginException:UsernameFailure' => 'Δεν είναι δυνατή η πρόσβαση σας. Παρακαλούμε ελέγξτε το username/email και τον κωδικό σας.',
	'LoginException:PasswordFailure' => 'Δεν είναι δυνατή η πρόσβαση σας. Παρακαλούμε ελέγξτε το username/email και τον κωδικό σας.',
	'LoginException:AccountLocked' => 'Ο λογαριασμός σας έχει κλειδωθεί λόγω πολλών ανεπιτυχών προσπαθειών σύνδεσης.',
	'LoginException:ChangePasswordFailure' => 'Ο έλεγχος του τρέχοντος κωδικού σας απέτυχε.',
	'LoginException:Unknown' => 'Δεν είναι δυνατή η σύνδεση σας εξαιτίας κάποιου μη καθορισμένου σφάλματος.',

	'UserFetchFailureException' => 'Cannot check permission for user_guid [%s] as the user does not exist.',

	'deprecatedfunction' => 'Προειδοποίηση: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Προειδοποίηση: The page owner %d is not accessible!',
	'viewfailure' => 'Παρουσιάστηκε ένα εσωτερικό σφάλμα στην προβολή του %s',
	'view:missing_param' => "The required parameter '%s' is missing in the view %s",
	'changebookmark' => 'Παρακαλούμε αλλάξετε το σελιδοδείκτη σας γι\' αυτή τη σελίδα',
	'noaccess' => 'This content has been removed, is invalid, or you do not have permission to view it.',
	'error:missing_data' => 'Κάποια δεδομένα είναι ελλειπή στο αίτημα σας',
	'save:fail' => 'There was a failure saving your data',
	'save:success' => 'Your data was saved',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops... something went wrong.',
	'error:400:title' => 'Bad request',
	'error:400:content' => 'Sorry. The request is invalid or incomplete.',
	'error:403:title' => 'Forbidden',
	'error:403:content' => 'Sorry. You are not allowed to access the requested page.',
	'error:404:title' => 'Page not found',
	'error:404:content' => 'Sorry. We could not find the page that you requested.',

	'upload:error:ini_size' => 'The file you tried to upload is too large.',
	'upload:error:form_size' => 'The file you tried to upload is too large.',
	'upload:error:partial' => 'The file upload did not complete.',
	'upload:error:no_file' => 'No file was selected.',
	'upload:error:no_tmp_dir' => 'Cannot save the uploaded file.',
	'upload:error:cant_write' => 'Cannot save the uploaded file.',
	'upload:error:extension' => 'Cannot save the uploaded file.',
	'upload:error:unknown' => 'The file upload failed.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Banned',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Description',
	'table_columns:fromView:link' => 'Name/Title',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Language',
	'table_columns:fromView:owner' => 'Owner',
	'table_columns:fromView:time_created' => 'Time Created',
	'table_columns:fromView:time_updated' => 'Time Updated',
	'table_columns:fromView:user' => 'User',

	'table_columns:fromProperty:description' => 'Description',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Type',
	'table_columns:fromProperty:username' => 'Username',

	'table_columns:fromMethod:getSubtype' => 'Subtype',
	'table_columns:fromMethod:getDisplayName' => 'Name/Title',
	'table_columns:fromMethod:getMimeType' => 'MIME Type',
	'table_columns:fromMethod:getSimpleType' => 'Type',

/**
 * User details
 */

	'name' => "Εμφανιζόμενο Όνομα",
	'email' => "Διεύθυνση Email",
	'username' => "Όνομα Χρήστη",
	'loginusername' => "Όνομα Χρήστη ή email",
	'password' => "Κωδικός",
	'passwordagain' => "Κωδικός (ξανά για επιβεβαίωση)",
	'admin_option' => "Ορισμός του χρήστη ως διαχειριστή?",
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */

	'PRIVATE' => "Ιδιωτικό",
	'LOGGED_IN' => "Συνδεδεμένοι χρήστες",
	'PUBLIC' => "Δημόσιο",
	'LOGGED_OUT' => "Logged out users",
	'access:friends:label' => "Φίλοι",
	'access' => "Πρόσβαση",
	'access:overridenotice' => "Note: Due to group policy, this content will be accessible only by group members.",
	'access:limited:label' => "Περιορισμένος/νη",
	'access:help' => "Το επίπεδο πρόσβασης",
	'access:read' => "Read access",
	'access:write' => "Write access",
	'access:admin_only' => "Administrators only",
	'access:missing_name' => "Missing access level name",
	'access:comments:change' => "This discussion is currently visible to a limited audience. Be thoughtful about who you share it with.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Πίνακας Ελέγχου",
	'dashboard:nowidgets' => "Ο πίνακας ελέγχου σας επιτρέπει να παρακολουθείτε την δραστηριότητα και το περιεχόμενο που σας αφορά σε αυτόν τον ιστότοπο.",

	'widgets:add' => 'Προσθήκη widgets',
	'widgets:add:description' => "Πατήστε σε οποιοδήποτε widget παρακάτω για να το προσθέσετε στη σελίδα.",
	'widgets:panel:close' => "Close widgets panel",
	'widgets:position:fixed' => '(Διορθώθηκε η θέση στη σελίδα)',
	'widget:unavailable' => 'Έχετε ήδη προσθέσει αυτό το widget',
	'widget:numbertodisplay' => 'Αριθμός εγγραφών για εμφάνιση',

	'widget:delete' => 'Διαγραφή %s',
	'widget:edit' => 'Προσαρμογή αυτού του widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "Το widget αποθηκεύτηκε με επιτυχία.",
	'widgets:save:failure' => "Αδύνατη η αποθήκευση αυτού του widget. Παρακαλούμε προσπαθήστε ξανά.",
	'widgets:add:success' => "Το widget προστέθηκε με επιτυχία.",
	'widgets:add:failure' => "Δεν ήταν δυνατή η προσθήκη αυτού του widget.",
	'widgets:move:failure' => "Δεν ήταν δυνατή η αποθήκευση της νέας θέσης widget.",
	'widgets:remove:failure' => "Δεν ήταν δυνατή η διαγραφή αυτού του widget.",

/**
 * Groups
 */

	'group' => "Ομάδα",
	'item:group' => "Ομάδες",

/**
 * Users
 */

	'user' => "Χρήστης",
	'item:user' => "Χρήστες",

/**
 * Friends
 */

	'friends' => "Φίλοι",
	'friends:yours' => "Οι φίλοι σου",
	'friends:owned' => "Οι φίλοι του χρήστη %s",
	'friend:add' => "Προσθήκη φίλου",
	'friend:remove' => "Διαγραφή φίλου",

	'friends:add:successful' => "Ορίσατε με επιτυχία το χρήστη %s ως φίλο.",
	'friends:add:failure' => "Αδύνατη η προσθήκη του χρήστη %s στους φίλους σας. Παρακαλούμε προσπαθείστε ξανά.",

	'friends:remove:successful' => "Διαγράψατε με επιτυχία το χρήστη %s από τους φίλους σας.",
	'friends:remove:failure' => "Αδύνατη η διαγραφή του χρήστη %s  από τους φίλους σας. Παρακαλούμε προσπαθείστε ξανά.",

	'friends:none' => "Αυτός ο χρήστης δεν έχει ακόμα φίλους",
	'friends:none:you' => "Δεν έχετε ακόμα φίλους.",

	'friends:none:found' => "Δεν βρέθηκαν φίλοι.",

	'friends:of:none' => "Κανείς δεν έχει προσθέσει ακόμα αυτό το χρήστη ως φίλο.",
	'friends:of:none:you' => "Κανένας δεν σας έχει προσθέσει ως φίλο ακόμα. Αρχίστε να δημοσιεύετε περιεχόμενο και συμπληρώστε το προφίλ σας, ώστε να μπορούν οι άλλοι χρήστες να σας βρουν!",

	'friends:of:owned' => "Χρήστες που έχουν κάνει φίλο τον/την %s",

	'friends:of' => "Φίλος των",
	'friends:collections' => "Συλλογή φίλων",
	'collections:add' => "Νέα συλλογή",
	'friends:collections:add' => "Νέα συλλογή φίλων",
	'friends:addfriends' => "Επιλογή φίλων",
	'friends:collectionname' => "Όνομα συλλογής",
	'friends:collectionfriends' => "Φίλοι στη συλλογή",
	'friends:collectionedit' => "Επεξεργασία συλλογής",
	'friends:nocollections' => "Δεν έχετε ορίσει συλλογές.",
	'friends:collectiondeleted' => "Η συλλογή σας έχει διαγραφεί.",
	'friends:collectiondeletefailed' => "Δεν ήταν δυνατή η διαγραφή της συλλογής. Ή δεν έχετε τα απαραίτητα δικαιώματα ή κάποιο άλλο πρόβλημα παρουσιάστηκε.",
	'friends:collectionadded' => "Η συλλογή σας δημιουργήθηκε με επιτυχία",
	'friends:nocollectionname' => "Πρέπει να δώσετε κάποιο όνομα συλλογής προκειμένου να μπορέσει να δημιουργηθεί.",
	'friends:collections:members' => "Συλλογή μελών",
	'friends:collections:edit' => "Επεξεργασία συλλογής",
	'friends:collections:edited' => "Η συλλογή αποθηκεύτηκε",
	'friends:collection:edit_failed' => 'Δεν είναι δυνατή η αποθήκευση της συλλογής.',

	'friendspicker:chararray' => 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩ',

	'avatar' => 'Εικόνα προφίλ (avatar)',
	'avatar:noaccess' => "You're not allowed to edit this user's avatar",
	'avatar:create' => 'Δημιουργείστε την εικόνα του προφίλ σας  (avatar)',
	'avatar:edit' => 'Επεξεργασία εικόνας προφίλ',
	'avatar:preview' => 'Προεπισκόπιση',
	'avatar:upload' => 'Ανέβασμα νέας εικόνας προφίλ',
	'avatar:current' => 'Τρέχουσα εικόνας προφίλ',
	'avatar:remove' => 'Διαγράψτε την εικόνα του προφίλ σας και καθορίστε την προεπιλεγμένη εικόνα',
	'avatar:crop:title' => 'Εργαλείο κοπής εικόνας προφίλ',
	'avatar:upload:instructions' => "Η εικόνα προφίλ εμφανίζεται σ' αυτόν τον ιστότοπο. Μπορείτε να την αλλάξετε όποτε θέλετε. (Επιτρεπόμενοι τύποι αρχείου: GIF, JPG ή PNG)",
	'avatar:create:instructions' => 'Κάντε κλικ και σύρετε ένα τετράγωνο παρακάτω, για να καθορίσετε το πλαίσιο που επιθυμείτε να ορισθεί ως εικόνα του προφίλ σας. Μια προεπισκόπηση θα εμφανιστεί στο παράθυρο στα δεξιά. Όταν είστε ικανοποιημένοι με την προεπισκόπηση, κάντε κλικ στο κουμπί "Δημιουργία εικόνας προφίλ σας". Αυτό το πλαίσιο θα χρησιμοποιηθεί στον ιστότοπο ως εικόνα του προφίλ σας.',
	'avatar:upload:success' => 'Η εικόνα του προφίλ σας μεταφορτώθηκε με επιτυχία',
	'avatar:upload:fail' => 'Η μεταφόρτωση της εικόνας του προφίλ σας απέτυχε',
	'avatar:resize:fail' => 'Η αλλαγή μεγέθους της εικόνας του προφίλ σας απέτυχε',
	'avatar:crop:success' => 'Η περικοπή της εικόνας του προφίλ σας έγινε με επιτυχία',
	'avatar:crop:fail' => 'Η περικοπή της εικόνας του προφίλ σας απέτυχε',
	'avatar:remove:success' => 'Η διαγραφή της εικόνας του προφίλ σας έγινε με επιτυχία.',
	'avatar:remove:fail' => 'Η διαγραφή της εικόνας του προφίλ σας δεν πραγματοποιήθηκε.',

	'profile:edit' => 'Επεξεργασία προφίλ',
	'profile:aboutme' => "Για μένα",
	'profile:description' => "Για μένα",
	'profile:briefdescription' => "Σύντομη περιγραφή",
	'profile:location' => "Τοποθεσία",
	'profile:skills' => "Δεξιότητες",
	'profile:interests' => "Ενδιαφέροντα",
	'profile:contactemail' => "Email επικοινωνίας",
	'profile:phone' => "Τηλέφωνο",
	'profile:mobile' => "Κινητό τηλέφωνο",
	'profile:website' => "Ιστότοπος",
	'profile:twitter' => "Όνομα χρήστη στο Twitter",
	'profile:saved' => "Το προφίλ σας αποθηκεύτηκε με επιτυχία.",

	'profile:field:text' => 'Σύντομο κείμενο',
	'profile:field:longtext' => 'Περιοχή κειμένου',
	'profile:field:tags' => 'Ετικέτες',
	'profile:field:url' => 'Διεύθυνση Ιστότοπου',
	'profile:field:email' => 'Διεύθυνση Email',
	'profile:field:location' => 'Τοποθεσία',
	'profile:field:date' => 'Ημερομηνία',

	'admin:appearance:profile_fields' => 'Επεξεργασία Πεδίων Προφίλ',
	'profile:edit:default' => 'Επεξεργασία πεδίων προφίλ',
	'profile:label' => "Ετικέτα προφίλ",
	'profile:type' => "Τύπος προφίλ",
	'profile:editdefault:delete:fail' => 'Removed default profile item field failed',
	'profile:editdefault:delete:success' => 'Το πεδίο του προφίλ διαγράφηκε',
	'profile:defaultprofile:reset' => 'Τα πεδία του προφίλ αποκαταστάθηκαν σύμφωνα με τα προεπιλεγμένα του συστήματος',
	'profile:resetdefault' => 'Reset default profile',
	'profile:resetdefault:confirm' => 'Είστε σίγουροι για τη διαγραφή των πρόσθετων πεδίων του προφίλ σας?',
	'profile:explainchangefields' => "Μπορείτε να αντικαταστήσετε τα υπάρχοντα πεδία προφίλ με τα δικά σας χρησιμοποιώντας την παρακάτω φόρμα.

Δώστε στο νέο πεδίο προφίλ μια ετικέτα, π.χ. «Αγαπημένη ομάδα» και στη συνέχεια επιλέξτε τον τύπο πεδίου (π.χ. κείμενο, url, ετικέτες), και κάντε κλικ στο κουμπί 'Προσθήκη'. Για να αλλάξετε τη σειρά ταξινόμισης στα πεδία σύρετε τη λαβή δίπλα στην ετικέτα τομέα. Για να επεξεργαστείτε μια ετικέτα πεδίου, κάντε κλικ στο κείμενο της ετικέτας για επεξεργασία.

Ανά πάσα στιγμή μπορείτε να επιστρέψετε στο προεπιλεγμένο προφίλ που έχει συσταθεί, αλλά θα χάσετε όλες τις πληροφορίες που έχουν ήδη τεθεί σε προσαρμοσμένα πεδία στις σελίδες προφίλ.",
	'profile:editdefault:success' => 'Το νέο πεδίο του προφίλ προστέθηκε',
	'profile:editdefault:fail' => 'Δεν ήταν δυνατή η αποθήκευση του προφίλ',
	'profile:field_too_long' => 'Δεν είναι δυνατή η αποθήκευση του προφίλ σας επειδή η ενότητα "%s" είναι πολύ μεγάλη.',
	'profile:noaccess' => "Δεν έχετε δικαιώματα επεξεργασίας αυτού του προφίλ.",
	'profile:invalid_email' => '%s must be a valid email address.',


/**
 * Feeds
 */
	'feed:rss' => 'Ροή RSS feed γι\' αυτή τη σελίδα',
/**
 * Links
 */
	'link:view' => 'προβολή συνδέσμου',
	'link:view:all' => 'Προβολή όλων',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s είναι φίλος με το χρήστη %s",
	'river:update:user:avatar' => '%s έχει νέο προφίλ εικόνας',
	'river:update:user:profile' => '%s ενημέρωσε το προφίλ του/της',
	'river:noaccess' => 'Δεν έχετε δικαιώματα προβολής αυτής της εγγραφής.',
	'river:posted:generic' => '%s ανάρτησε',
	'riveritem:single:user' => 'ένας χρήστης',
	'riveritem:plural:user' => 'μερικοί χρήστες',
	'river:ingroup' => 'στην ομάδα %s',
	'river:none' => 'Δεν υπάρχoυν πρόσφατες δημοσιεύσεις',
	'river:update' => 'Ενημέρωση για %s',
	'river:delete' => 'Remove this activity item',
	'river:delete:success' => 'Εγγραφή διαγράφτηκε επιτυχώς.',
	'river:delete:fail' => 'Δεν είναι δυνατή η διαγραφή της εγγραφής.',
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:can_delete:invaliduser' => 'Cannot check canDelete for user_guid [%s] as the user does not exist.',
	'river:subject:invalid_subject' => 'Invalid user',
	'activity:owner' => 'View activity',

	'river:widget:title' => "Πρόσφατες δημοσιεύσεις",
	'river:widget:description' => "Εμφάνιση πρόσφατης δραστηριότητας",
	'river:widget:type' => "Τύπος δημοσιεύσεων",
	'river:widgets:friends' => 'Δημοσιεύσεις φίλων',
	'river:widgets:all' => 'Όλες οι δημοσιεύσεις',

/**
 * Notifications
 */
	'notifications:usersettings' => "Ρυθμίσεις ειδοποιήσεων",
	'notification:method:email' => 'Email',

	'notifications:usersettings:save:ok' => "Ρυθμίσεις ειδοποιήσεων αποθηκεύτηκαν με επιτυχία.",
	'notifications:usersettings:save:fail' => "Σφάλμα κατά την αποθήκευση των ρυθμίσεων ειδοποίησης.",

	'notification:subject' => 'Notification about %s',
	'notification:body' => 'View the new activity at %s',

/**
 * Search
 */

	'search' => "Αναζήτηση",
	'searchtitle' => "Αναζήτηση: %s",
	'users:searchtitle' => "Αναζήτηση για χρήστες: %s",
	'groups:searchtitle' => "Αναζήτηση για ομάδες: %s",
	'advancedsearchtitle' => "%s με αποτελέσματα που ταιριάζουν σε %s",
	'notfound' => "Δεν βρέθηκαν αποτελέσματα.",
	'next' => "Επόμενο",
	'previous' => "Προηγούμενο",

	'viewtype:change' => "Αλλαγή τύπου λίστας",
	'viewtype:list' => "Εμφάνιση σε λίστα",
	'viewtype:gallery' => "Γκαλερί",

	'tag:search:startblurb' => "Εγγραφές με ετικέτες που ταιριάζουν σε '%s':",

	'user:search:startblurb' => "Χρήστες που ταιριάζουν σε '%s':",
	'user:search:finishblurb' => "Για προβολή περισσότερων, πατήστε εδώ.",

	'group:search:startblurb' => "Ομάδες όπως '%s':",
	'group:search:finishblurb' => "Για προβολή περισσότερων, πατήστε εδώ.",
	'search:go' => 'Τράβα',
	'userpicker:only_friends' => 'Μόνο φίλοι',

/**
 * Account
 */

	'account' => "Λογαριασμός",
	'settings' => "Ρυθμίσεις",
	'tools' => "Εργαλεία",
	'settings:edit' => 'Επεξεργασία Ρυμίσεων',

	'register' => "Γίνετε Μέλος",
	'registerok' => "Η εγγραφή σας έγινε με επιτυχία ως %s.",
	'registerbad' => "Η εγγραφή σας δεν ήταν επιτυχής λόγω απροσδιόριστου σφάλματος.",
	'registerdisabled' => "Η εγγραφή σας έχει απενεργοποιηθεί από τον διαχειριστή του συστήματος",
	'register:fields' => 'Όλα τα πεδία είναι υποχρεωτικά',

	'registration:notemail' => 'Η διεύθυνση email που δώσατε δεν φαίνεται να είναι μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.',
	'registration:userexists' => 'Το όνομα χρήστη που δηλώσατε υπάρχει ήδη',
	'registration:usernametooshort' => 'Το όνομα χρήστη πρέπει να αποτελείται από τουλάχιστον %u χαρακτήρες.',
	'registration:usernametoolong' => 'Το όνομα χρήστη είναι πολύ μεγάλο. Δεν μπορεί να αποτελείται από περισσότερους από %u χαρακτήρες.',
	'registration:passwordtooshort' => 'Ο κωδικός πρέπει να αποτελείται από τουλάχιστον %u χαρακτήρες.',
	'registration:dupeemail' => 'Η διεύθυνση email που δηλώσατε χρησιμοποιείται ήδη.',
	'registration:invalidchars' => 'Λυπούμαστε, το όνομα χρήστη που δηλώσατε περιέχει τον μη έγκυρο χαρακτήρα %s. Οι ακόλουθοι χαρακτήρες δεν επιτρέπονται: %s',
	'registration:emailnotvalid' => 'Λυπούμαστε, η διεύθυνση email που δηλώσατε είναι μη έγκυρη στο σύστημα μας',
	'registration:passwordnotvalid' => 'Λυπούμαστε, ο κωδικός που δηλώσατε είναι μη έγκυρος στο σύστημα μας',
	'registration:usernamenotvalid' => 'Λυπούμαστε, το όνομα χρήστη που δηλώσατε είναι μη έγκυρο στο σύστημα μας',

	'adduser' => "Προσθήκη Χρήστη",
	'adduser:ok' => "Προσθέσατε με επιτυχία το νέο χρήστη.",
	'adduser:bad' => "Δεν ήταν δυνατή η δημιουργία του νέου χρήστη.",

	'user:set:name' => "Ρυθμίσεις ονόματος λογαριασμού",
	'user:name:label' => "Εμφανιζόμενο όνομα",
	'user:name:success' => "Το όνομα σας αλλάχθηκε με επιτυχία στο σύστημα.",
	'user:name:fail' => "Δεν ήταν δυνατή η αλλαγή του ονόματος σας στο σύστημα. Παρακαλούμε σιγουρευτείτε ότι το όνομα δεν είναι αρκετά μεγάλο και προσπαθείστε ξανά.",

	'user:set:password' => "Κωδικός λογαριασμού",
	'user:current_password:label' => 'Τρέχον κωδικός',
	'user:password:label' => "Νέος κωδικός",
	'user:password2:label' => "Νέος κωδικός ξανά",
	'user:password:success' => "Έγινε η αλλαγή του κωδικού σας",
	'user:password:fail' => "Δεν ήταν δυνατή η αλλαγή του κωδικού σας στο σύστημα.",
	'user:password:fail:notsame' => "Οι δύο κωδικοί δεν είναι ίδιοι !",
	'user:password:fail:tooshort' => "Ο κωδικός είναι πολύ μικρός!",
	'user:password:fail:incorrect_current_password' => 'Ο τρέχον κωδικός σας δεν είναι σωστός.',
	'user:changepassword:unknown_user' => 'Invalid user.',
	'user:changepassword:change_password_confirm' => 'This will change your password.',

	'user:set:language' => "Ρυθμίσεις γλώσσας",
	'user:language:label' => "Επιλεγμένη γλώσσα",
	'user:language:success' => "Οι ρυθμίσεις γλώσσας έχουν αποθηκευτεί.",
	'user:language:fail' => "Οι ρυθμίσεις γλώσσας δεν μπορούν να αποθηκευτούν.",

	'user:username:notfound' => 'Το όνομα χρήστη %s δεν βρέθηκε.',

	'user:password:lost' => 'Υπενθύμιση κωδικού',
	'user:password:changereq:success' => 'Successfully requested a new password, email sent',
	'user:password:changereq:fail' => 'Could not request a new password.',

	'user:password:text' => 'Για να αιτηθείτε νέο κωδικό, εισάγετε το όνομα χρήστη ή τη διεύθυνση email.',

	'user:persistent' => 'Να με θυμάσαι',

	'walled_garden:welcome' => 'Καλώς ήλθατε',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Διαχείριση',
	'menu:page:header:configure' => 'Ρυθμίσεις',
	'menu:page:header:develop' => 'Ανάπτυξη',
	'menu:page:header:default' => 'Άλλο',

	'admin:view_site' => 'Προβολή Ιστότοπου',
	'admin:loggedin' => 'Συνδεδεμένος/η ως %s',
	'admin:menu' => 'Μενού',

	'admin:configuration:success' => "Οι ρυθμίσεις αποθηκεύτηκαν.",
	'admin:configuration:fail' => "Δεν ήταν δυνατή η αποθήκευση των ρυθμίσεων σας.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',
	'admin:configuration:default_limit' => 'The number of items per page must be at least 1.',

	'admin:unknown_section' => 'Μη έγκυρη ενότητα διαχείρισης.',

	'admin' => "Διαχείριση",
	'admin:description' => "Η ενότητα διαχείρισης σας επιτρέπει να ελέγχετε όλες τις πτυχές του συστήματος, από τη διαχείριση των χρηστών έως το πώς συμπεριφέρονται τα plugins. Επιλέξτε μια από τις παρακάτω επιλογές για να ξεκινήσετε.",

	'admin:statistics' => "Στατιστικά",
	'admin:statistics:overview' => 'Με μια ματιά',
	'admin:statistics:server' => 'Πληροφορίες Διακομιστή',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Latest Cron Jobs',
	'admin:cron:period' => 'Cron period',
	'admin:cron:friendly' => 'Last completed',
	'admin:cron:date' => 'Date and time',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

	'admin:appearance' => 'Εμφάνιση',
	'admin:administer_utilities' => 'Χρήσιμα',
	'admin:develop_utilities' => 'Χρήσιμα',
	'admin:configure_utilities' => 'Utilities',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Χρήστες",
	'admin:users:online' => 'Συνδεδεμένοι τώρα',
	'admin:users:newest' => 'Νέοι χρήστες',
	'admin:users:admins' => 'Διαχειριστές',
	'admin:users:add' => 'Προσθήκη Νέου Χρήστη',
	'admin:users:description' => "Η ενότητα διαχείρισης σας επιτρέπει να ελέγχετε τις ρυθμίσεις χρηστών του συστήματος. Επιλέξτε μια από τις παρακάτω επιλογές για να ξεκινήσετε.",
	'admin:users:adduser:label' => "Πατήστε εδώ για προσθήκη νέου χρήστη...",
	'admin:users:opt:linktext' => "Ρύθμιση χρηστών...",
	'admin:users:opt:description' => "Ρυθμίσεις χρηστών και λογαριασμών.",
	'admin:users:find' => 'Εύρεση',

	'admin:administer_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Upgrades',

	'admin:settings' => 'Ρυθμίσεις',
	'admin:settings:basic' => 'Βασικές Ρυθμίσεις',
	'admin:settings:advanced' => 'Ρυθμίσεις για Προχωρημένους',
	'admin:site:description' => "Η ενότητα διαχείρισης σας επιτρέπει να ελέγχετε το σύνολο των ρυθμίσεων του ιστότοπου. Επιλέξτε μια από τις παρακάτω επιλογές για να ξεκινήσετε.",
	'admin:site:opt:linktext' => "Ρύθμιση ιστότοπου...",
	'admin:settings:in_settings_file' => 'This setting is configured in settings.php',

	'admin:legend:security' => 'Security',
	'admin:site:secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:site:secret_regenerated' => "Your site secret has been regenerated.",
	'admin:site:secret:regenerate' => "Regenerate site secret",
	'admin:site:secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Weak",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderate",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Strong",
	'site_secret:strength_msg:strong' => "Your site secret is sufficiently strong. There is no need to regenerate it.",

	'admin:dashboard' => 'Πίνακας Ελέγχου',
	'admin:widget:online_users' => 'Συνδεδεμένοι χρήστες',
	'admin:widget:online_users:help' => 'Προβολή χρηστών που είναι συνδεδεμένοι τώρα',
	'admin:widget:new_users' => 'Νέοι χρήστες',
	'admin:widget:new_users:help' => 'Προβολή των πιο πρόσφατων χρηστών',
	'admin:widget:banned_users' => 'Banned users',
	'admin:widget:banned_users:help' => 'Lists the banned users',
	'admin:widget:content_stats' => 'Στατιστικά περιεχομένου',
	'admin:widget:content_stats:help' => 'Παρακολουθήστε το περιεχόμενο που δημιουργείται από τους χρήστες σας',
	'admin:widget:cron_status' => 'Cron status',
	'admin:widget:cron_status:help' => 'Shows the status of the last time cron jobs finished',
	'widget:content_stats:type' => 'Τύπος περιεχομένου',
	'widget:content_stats:number' => 'Αριθμός',

	'admin:widget:admin_welcome' => 'Καλώς ήλθατε',
	'admin:widget:admin_welcome:help' => "Σύντομη εισαγωγή στην περιοχή διαχείρισης του Elgg",
	'admin:widget:admin_welcome:intro' =>
'Καλώς ήρθατε στο Elgg! Αυτή τη στιγμή ψάχνετε στο ταμπλό διαχείρισης. Είναι χρήσιμο για την παρακολούθηση σε ότι συμβαίνει στον ιστότοπο.',

	'admin:widget:admin_welcome:admin_overview' =>
"Η πλοήγηση στην περιοχή διαχείρισης παρέχεται από το μενού στα δεξιά. Χωρίζεται σε τρεις ενότητες:
	<dl>
		<dt>Διαχείριση</dt><dd>Καθημερινές εργασίες, όπως η παρακολούθηση περιεχομένου που έχει αναφεθεί, έλεγχος συνδεδεμένων χρηστών καθώς και προβολή στατιστικών.
		</dd><dt>Ρυθμίσεις</dt><dd>Περιστασιακές εργασίες όπως καθορισμός ονόματος ιστότοπου ή ενεργοποίηση ενός plugin.</dd>
		<dt>Ανάπτυξη</dt><dd>Για τους προγραμματιστές που αναπτύσσουν plugins ή θέματα σχεδιασμού. </dd>
	</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br>Βεβαιωθείτε να συμβουλευτείτε τους πόρους που διατίθενται στους συνδέσμους χαμηλά. Ευχαριστούμε που χρησιμοποιείτε το Elgg!',

	'admin:widget:control_panel' => 'Πίνακας ελέγχου',
	'admin:widget:control_panel:help' => "Παρέχει εύκολη πρόσβαση σε κοινούς ελέγχους",

	'admin:cache:flush' => 'Ανανέωση cache',
	'admin:cache:flushed' => "Η λανθάνουσα μνήμη (cache) έχει ανανεωθεί",

	'admin:footer:faq' => 'Συχνές ερωτήσεις διαχείρισης',
	'admin:footer:manual' => 'Εγχειρίδιο χρήσης διαχείρισης',
	'admin:footer:community_forums' => 'Φόρουμ Κοινότητας Χρηστών του Elgg',
	'admin:footer:blog' => 'Ιστολόγιο Elgg',

	'admin:plugins:category:all' => 'Όλα τα plugins',
	'admin:plugins:category:active' => 'Ενεργά plugins',
	'admin:plugins:category:inactive' => 'Ανενεργά plugins',
	'admin:plugins:category:admin' => 'Διαχείριση',
	'admin:plugins:category:bundled' => 'Συνοδευτικά',
	'admin:plugins:category:nonbundled' => 'Μη Συνοδευτικά',
	'admin:plugins:category:content' => 'Περιεχόμενο',
	'admin:plugins:category:development' => 'Ανάπτυξη',
	'admin:plugins:category:enhancement' => 'Βελτιώσεις',
	'admin:plugins:category:api' => 'Υπηρεσία/API',
	'admin:plugins:category:communication' => 'Επικοινωνία',
	'admin:plugins:category:security' => 'Ασφάλεια',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Πολυμέσα',
	'admin:plugins:category:theme' => 'Εμφάνιση',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Χρήσιμα',

	'admin:plugins:markdown:unknown_plugin' => 'Μη καθορισμένο plugin.',
	'admin:plugins:markdown:unknown_file' => 'Μη καθορισμένο αρχείο.',

	'admin:notices:could_not_delete' => 'Αδύνατη η διαγραφή της σημείωσης.',
	'item:object:admin_notice' => 'Σημείωση διαχειριστή',

	'admin:options' => 'Επιλογές διαχείρισης',

/**
 * Plugins
 */

	'plugins:disabled' => 'Τα plugins δεν φορτώνονται επειδή ένα αρχείο με όνομα "disabled" βρίσκεται στον κατάλογο mod.',
	'plugins:settings:save:ok' => "Οι ρυθμίσεις του plugin %s αποθηκεύτηκαν με επιτυχία.",
	'plugins:settings:save:fail' => "Παρουσιάστηκε πρόβλημα κατά την αποθήκευση των ρυθμίσεων για το plugin %s.",
	'plugins:usersettings:save:ok' => "Οι ρυθμίσεις χρήστη για το plugin %s αποθηκεύτηκαν με επιτυχία.",
	'plugins:usersettings:save:fail' => "Παρουσιάστηκε πρόβλημα κατά την αποθήκευση των ρυθμίσεων χρήστη για το plugin %s.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Ενεργοποίηση Όλων',
	'admin:plugins:deactivate_all' => 'Απενεργοποίηση Όλων',
	'admin:plugins:activate' => 'Ενεργοποίηση',
	'admin:plugins:deactivate' => 'Απενεργοποίηση',
	'admin:plugins:description' => "Η Ενότητα Διαχείρισης σας επιτρέπει να ελέγχετε και να διαμορφώσετε τα εργαλεία που είναι εγκατεστημένα στον ιστότοπο σας.",
	'admin:plugins:opt:linktext' => "Ρύθμιση εργαλείων...",
	'admin:plugins:opt:description' => "Ρύθμιση εργαλείων που υπάρχουν στην ιστοσελίδα.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Name",
	'admin:plugins:label:author' => "Συγγραφέας",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Κατηγορίες',
	'admin:plugins:label:licence' => "Άδεια Χρήσης",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Files",
	'admin:plugins:label:resources' => "Resources",
	'admin:plugins:label:screenshots' => "Screenshots",
	'admin:plugins:label:repository' => "Κωδικός",
	'admin:plugins:label:bugtracker' => "Αναφέρατε σχετικά",
	'admin:plugins:label:donate' => "Δωρεά",
	'admin:plugins:label:moreinfo' => 'περισσότερες πληροφορίες',
	'admin:plugins:label:version' => 'Έκδοση',
	'admin:plugins:label:location' => 'Τοποθεσία',
	'admin:plugins:label:contributors' => 'Contributors',
	'admin:plugins:label:contributors:name' => 'Name',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Website',
	'admin:plugins:label:contributors:username' => 'Community username',
	'admin:plugins:label:contributors:description' => 'Description',
	'admin:plugins:label:dependencies' => 'Εξαρτήσεις',

	'admin:plugins:warning:unmet_dependencies' => 'Αυτό το plugin έχει ανικανοποίητες εξαρτήσεις και δεν μπορεί να ενεργοποιηθεί. Ελέγξτε τις εξαρτήσεις στις περισσότερες πληροφορίες.',
	'admin:plugins:warning:invalid' => '%s δεν είναι έγκυρο Elgg plugin.  Check <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:warning:invalid:check_docs' => 'Επισκεφτείτε τη σελίδα <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> για συμβουλές αντιμετώπισης προβλημάτων.',
	'admin:plugins:cannot_activate' => 'αδύνατη η ενεργοποίηση',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

	'admin:plugins:set_priority:yes' => "Αναδιάταξη του %s.",
	'admin:plugins:set_priority:no' => "Αδύνατη η αναδιάταξη του %s.",
	'admin:plugins:set_priority:no_with_msg' => "Αδύνατη η αναδιάταξη του %s. Σφάλμα: %s",
	'admin:plugins:deactivate:yes' => "Απενεργοποιήθηκε %s.",
	'admin:plugins:deactivate:no' => "Δεν ήταν δυνατή η απενεργοποίηση του %s.",
	'admin:plugins:deactivate:no_with_msg' => "Δεν ήταν δυνατή η απενεργοποίηση του %s. Σφάλμα: %s",
	'admin:plugins:activate:yes' => "Ενεργοποιήθηκε %s.",
	'admin:plugins:activate:no' => "Δεν ήταν δυνατή η ενεργοποίηση του %s.",
	'admin:plugins:activate:no_with_msg' => "Δεν ήταν δυνατή η ενεργοποίηση του %s. Σφάλμα: %s",
	'admin:plugins:categories:all' => 'Όλες οι κατηγορίες',
	'admin:plugins:plugin_website' => 'Ιστότοπος του plugin',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Έκδοση %s',
	'admin:plugin_settings' => 'Ρυθμίσεις Plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'Αυτό το plugin είναι ενεργό, αλλά έχει ανεκπλήρωτες εξαρτήσεις. Ενδεχομένως αντιμετωπίσετε προβλήματα. Ανατρέξτε στην ενότητα "περισσότερες πληροφορίες" παρακάτω για λεπτομέρειες.',

	'admin:plugins:dependencies:type' => 'Τύπος',
	'admin:plugins:dependencies:name' => 'Όνομα',
	'admin:plugins:dependencies:expected_value' => 'Αναμενόμενη Τιμή',
	'admin:plugins:dependencies:local_value' => 'Πραγματική τιμή',
	'admin:plugins:dependencies:comment' => 'Σχόλιο',

	'admin:statistics:description' => "Εδώ είναι μια επισκόπηση των στατιστικών στοιχείων για τον ιστότοπο σας. Αν χρειάζεστε πιο λεπτομερή στατιστικά, είναι διαθέσιμο ένα πιο επαγγελματικό διαχειριστικό εργαλείο.",
	'admin:statistics:opt:description' => "Δείτε τα στατιστικά στοιχεία σχετικά με τους χρήστες και τις οντότητες του ιστότοπου.",
	'admin:statistics:opt:linktext' => "Προβολή στατιστικών...",
	'admin:statistics:label:basic' => "Βασικά στατιστικά του ιστότοπου",
	'admin:statistics:label:numentities' => "Οντότητες στον ιστότοπο",
	'admin:statistics:label:numusers' => "Αριθμός χρηστών",
	'admin:statistics:label:numonline' => "Αριθμός χρηστών συνδεδεμένοι",
	'admin:statistics:label:onlineusers' => "Χρήστες συνδεδεμένοι τώρα",
	'admin:statistics:label:admins'=>"Διαχειριστές",
	'admin:statistics:label:version' => "Έκδοση Elgg",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Έκδοση",

	'admin:server:label:php' => 'Πληροφορίες PHP',
	'admin:server:label:web_server' => 'Εξυπηρετητής Ιστότοπων',
	'admin:server:label:server' => 'Εξυπηρετητής',
	'admin:server:label:log_location' => 'Τοποθεσία αρχείων καταγραφής',
	'admin:server:label:php_version' => 'Έκδοση PHP',
	'admin:server:label:php_ini' => 'Τοποθεσία αρχείου PHP ini',
	'admin:server:label:php_log' => 'Αρχεία καταγραφής PHP',
	'admin:server:label:mem_avail' => 'Διαθέσιμη μνήμη',
	'admin:server:label:mem_used' => 'Μνήμη σε χρήση',
	'admin:server:error_log' => "Αρχείο καταγραφής σφαλμάτων του εξυπηρετητή ιστότοπων",
	'admin:server:label:post_max_size' => 'Μέγιστο μέγεθος ανάρτησης',
	'admin:server:label:upload_max_filesize' => 'Μέγιστο μέγεθος αρχείων για ανέβασμα',
	'admin:server:warning:post_max_too_small' => '(Σημείωση: το πεδίο post_max_size πρέπει να είναι μεγαλύτερο από αυτή την τιμή ώστε να είναι εφικτό το ανέβασμα αρχείων αυτού του μεγέθους )',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcache is not setup on this server or it has not yet been configured in Elgg config.
		For improved performance, it is recommended that you enable and configure memcache.
	',

	'admin:user:label:search' => "Εύρεση χρηστών:",
	'admin:user:label:searchbutton' => "Αναζήτηση",

	'admin:user:ban:no' => "Δεν ήταν δυνατή η άρση αποκλεισμού χρήστη",
	'admin:user:ban:yes' => "Ο χρήστης αποκλείστηκε.",
	'admin:user:self:ban:no' => "Δεν μπορείτε να αποκλείσετε τον εαυτό σας",
	'admin:user:unban:no' => "Δεν ήταν δυνατή η άρση αποκλεισμού χρήστη",
	'admin:user:unban:yes' => "Έγινε άρση αποκλεισμού χρήστη",
	'admin:user:delete:no' => "Αδύνατη η διαγραφή του χρήστη",
	'admin:user:delete:yes' => "Ο χρήστης %s δεν διαγράφηκε",
	'admin:user:self:delete:no' => "Δεν μπορείτε να διαγράψετε τον εαυτό σας",

	'admin:user:resetpassword:yes' => "Ο κωδικος πρόσβασης αρχικοποιήθηκε και στάλθηκε ειδοποίηση στο χρήστη.",
	'admin:user:resetpassword:no' => "Δεν ήταν δυνατή η αρχικοποίηση του κωδικού πρόσβασης.",

	'admin:user:makeadmin:yes' => "Ο χρήστης ορίστηκε ως διαχειριστής.",
	'admin:user:makeadmin:no' => "Δεν είναι δυνατός ο ορισμός του χρήστης ως διαχειριστής.",

	'admin:user:removeadmin:yes' => "Ο χρήστης δεν είναι πλέον διαχειριστής.",
	'admin:user:removeadmin:no' => "Δεν είναι δυνατή η διαγραφή των δικαιωμάτων διαχείρισης από το χρήστη.",
	'admin:user:self:removeadmin:no' => "Δεν μπορείτε να διαγράψετε τα δικαιώματα διαχείρισης από το δικό σας λογαριασμό.",

	'admin:appearance:menu_items' => 'Πεδία Μενού',
	'admin:menu_items:configure' => 'Ρύθμιση των πεδίων του βασικού μενού πλοήγησης',
	'admin:menu_items:description' => 'Επιλέξτε τα στοχεία του μενού πλοήγησης που θέλετε να εμφανίζονται ως προτεινόμενοι σύνδεσμοι (links). Τα αχρησιμοποίητα στοιχεία θα προστεθούν ως "Περισσότερα" στο τέλος της λίστας.',
	'admin:menu_items:hide_toolbar_entries' => 'Διαγραφή συνδέσμων (links) από το μενού γραμμής εργαλείων',
	'admin:menu_items:saved' => 'Τα στοιχεία του μενού πλοήγησης αποθηκεύτηκαν.',
	'admin:add_menu_item' => 'Προσθέστε ένα προσαρμοσμένο στοιχείο στο μενού πλοήγησης',
	'admin:add_menu_item:description' => 'Συμπληρώστε το όνομα και τη διεύθυνση URL εμφάνισης για να προσθέσετε ένα προσαρμοσμένο στοιχείο στο μενού πλοήγησης.',

	'admin:appearance:default_widgets' => 'Προεπιλεγμένα Widgets',
	'admin:default_widgets:unknown_type' => 'Άγνωστος τύπος widget',
	'admin:default_widgets:instructions' => 'Προσθήκη, διαγραφή, θέση και ρύθμιση προεπιλεγμένων widgets για την επιλεγμένη σελίδα widget.  Οι αλλαγές που θα κάνετε θα είναι διαθέσιμες μόνο για τους νέους χρήστες του ιστότοπου.',

	'admin:robots.txt:instructions' => "Edit this site's robots.txt file below",
	'admin:robots.txt:plugins' => "Plugins are adding the following to the robots.txt file",
	'admin:robots.txt:subdir' => "The robots.txt tool will not work because Elgg is installed in a sub-directory",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'This site is down for maintenance',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Maintenance mode',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'The site is in maintenance mode.',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "Ο πίνακας ρυθμίσεων χρήστη σας επιτρέπει να ελέγχετε όλες τις προσωπικές σας ρυθμίσεις, από τη διαχείριση των χρηστών έως το πώς συμπεριφέρονται υα plugins. Επιλέξτε μια από τις παρακάτω επιλογές για να ξεκινήσετε.",

	'usersettings:statistics' => "Τα στατιστικά σας",
	'usersettings:statistics:opt:description' => "Δείτε τα στατιστικά στοιχεία σχετικά με τους χρήστες και τις οντότητες του ιστότοπου.",
	'usersettings:statistics:opt:linktext' => "στατιστικά λογαριασμού",
	
	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "Οι ρυθμίσεις σας",
	'usersettings:user:opt:description' => "Αυτό σας επιτρέπει να ελέγχετε τις ρυθμίσεις χρήστη.",
	'usersettings:user:opt:linktext' => "Αλλαγή των ρυθμίσεων σας",

	'usersettings:plugins' => "Εργαλεία",
	'usersettings:plugins:opt:description' => "Διαμόρφωση ρυθμίσεων (εάν υπάρχουν) για τα ενεργά εργαλεία σας.",
	'usersettings:plugins:opt:linktext' => "Ρυθμίστε τα εργαλεία σας",

	'usersettings:plugins:description' => "Αυτή η ομάδα σας επιτρέπει να ελέγχετε και να διαμορφώσετε τις προσωπικές ρυθμίσεις για τα εργαλεία που είναι εγκατεστημένα από το διαχειριστή του συστήματος σας.",
	'usersettings:statistics:label:numentities' => "Το περιεχόμενο σας",

	'usersettings:statistics:yourdetails' => "Οι λεπτομέρειες σας",
	'usersettings:statistics:label:name' => "Πλήρες όνομα",
	'usersettings:statistics:label:email' => "Ηλεκτρονική διεύθυνση (email)",
	'usersettings:statistics:label:membersince' => "Μέλος από",
	'usersettings:statistics:label:lastlogin' => "Τελευταία είσοδος",

/**
 * Activity river
 */

	'river:all' => 'Όλες οι δημοσιεύσεις',
	'river:mine' => 'Οι δημοσιεύσεις μου',
	'river:owner' => 'Activity of %s',
	'river:friends' => 'Δημοσιεύσεις φίλων',
	'river:select' => 'Εμφάνιση %s',
	'river:comments:more' => '+%u περισσότερα',
	'river:comments:all' => 'View all %u comments',
	'river:generic_comment' => 'σχολίασε σε %s %s',

	'friends:widget:description' => "Εμφανίζονται μερικοί από τους φίλους σας.",
	'friends:num_display' => "Αριθμός φίλων για εμφάνιση",
	'friends:icon_size' => "Μέγεθος εικόνας",
	'friends:tiny' => "πολύ μικρό",
	'friends:small' => "μικρό",

/**
 * Icons
 */

	'icon:size' => "Icon size",
	'icon:size:topbar' => "Topbar",
	'icon:size:tiny' => "Tiny",
	'icon:size:small' => "Small",
	'icon:size:medium' => "Medium",
	'icon:size:large' => "Large",
	'icon:size:master' => "Extra Large",

/**
 * Generic action words
 */

	'save' => "Αποθήκευση",
	'reset' => 'Αρχικοποίηση',
	'publish' => "Δημοσίευση",
	'cancel' => "Ακύρωση",
	'saving' => "Αποθήκευση ...",
	'update' => "Ενημέρωση",
	'preview' => "Προεπισκόπιση",
	'edit' => "Επεξεργασία",
	'delete' => "Διαγραφή",
	'accept' => "Αποδοχή",
	'reject' => "Reject",
	'decline' => "Decline",
	'approve' => "Approve",
	'activate' => "Activate",
	'deactivate' => "Deactivate",
	'disapprove' => "Disapprove",
	'revoke' => "Revoke",
	'load' => "Φόρτωση",
	'upload' => "Μεταφόρτωση",
	'download' => "Download",
	'ban' => "Αποκλεισμός",
	'unban' => "Άρση αποκλεισμού",
	'banned' => "Αποκλεισμένος",
	'enable' => "Ενεργοποίηση",
	'disable' => "Απενεργοποίηση",
	'request' => "Αίτημα",
	'complete' => "Ολοκλήρωση",
	'open' => 'Άνοιγμα',
	'close' => 'Κλείσιμο',
	'hide' => 'Hide',
	'show' => 'Show',
	'reply' => "Απάντηση",
	'more' => 'Περισσότερα',
	'more_info' => 'More info',
	'comments' => 'Σχόλια',
	'import' => 'Εισαγωγή',
	'export' => 'Εξαγωγή',
	'untitled' => 'Χωρίς τίτλο',
	'help' => 'Βοήθεια',
	'send' => 'Αποστολή',
	'post' => 'Υποβολή',
	'submit' => 'Καταχώρηση',
	'comment' => 'Σχόλιο',
	'upgrade' => 'Αναβάθμιση',
	'sort' => 'Ταξινόμιση',
	'filter' => 'Φίλτρο',
	'new' => 'Νέο',
	'add' => 'Προσθήκη',
	'create' => 'Δημιουργία',
	'remove' => 'Διαγραφή',
	'revert' => 'Επαναφορά',

	'site' => 'Ιστότοπος',
	'activity' => 'Πρόσφατα',
	'members' => 'Μέλη',
	'menu' => 'Menu',

	'up' => 'Πάνω',
	'down' => 'Κάτω',
	'top' => 'Κορυφή',
	'bottom' => 'Κάτω μέρος',
	'right' => 'Right',
	'left' => 'Left',
	'back' => 'Πίσω',

	'invite' => "Πρόσκληση",

	'resetpassword' => "Αρχικοποίηση κωδικού",
	'changepassword' => "Change password",
	'makeadmin' => "Ορισμός ως διαχειριστή",
	'removeadmin' => "Διαγραφή διαχειριστή",

	'option:yes' => "Ναι",
	'option:no' => "Όχι",

	'unknown' => 'Άγνωστο',
	'never' => 'Never',

	'active' => 'Ενεργό',
	'total' => 'Σύνολο',

	'ok' => 'OK',
	'any' => 'Any',
	'error' => 'Error',

	'other' => 'Other',
	'options' => 'Options',
	'advanced' => 'Advanced',

	'learnmore' => "Πατήστε εδώ για να μάθετε περισσότερα.",
	'unknown_error' => 'Unknown error',

	'content' => "Περιεχόμενο",
	'content:latest' => 'Πρόσφατη δραστηριότητα',
	'content:latest:blurb' => 'Εναλλακτικά, κάντε κλικ εδώ για να δείτε το πιο πρόσφατο περιεχόμενο του ιστότοπου.',

	'link:text' => 'προβολή συνδέσμου',

/**
 * Generic questions
 */

	'question:areyousure' => 'Είστε σίγουρος?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Unsaved Draft',
	'status:draft' => 'Draft',
	'status:unpublished' => 'Unpublished',
	'status:published' => 'Published',
	'status:featured' => 'Featured',
	'status:open' => 'Open',
	'status:closed' => 'Closed',

/**
 * Generic sorts
 */

	'sort:newest' => 'Newest',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alphabetical',
	'sort:priority' => 'Priority',

/**
 * Generic data words
 */

	'title' => "Τίτλος",
	'description' => "Περιγραφή",
	'tags' => "Ετικέτες",
	'all' => "Όλα",
	'mine' => "Δικά μου",

	'by' => 'από',
	'none' => 'κανένα',

	'annotations' => "Σχολιασμοί",
	'relationships' => "Σχέσεις",
	'metadata' => "Μετα-δεδομένα",
	'tagcloud' => "Ετικέτες",

	'on' => 'Ναι',
	'off' => 'Όχι',

/**
 * Entity actions
 */

	'edit:this' => 'Επεξεργασία',
	'delete:this' => 'Διαγραφή',
	'comment:this' => 'Σχολιάστε',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Είστε σίγουρος για τη διαγραφή αυτής της εγγραφής?",
	'deleteconfirm:plural' => "Θέλετε σίγουρα να διαγράψετε αυτές τις εγγραφές ?",
	'fileexists' => "Ένα αρχείο έχει ήδη φορτωθεί. Για να το αντικαταστήσετε, επιλέξτε παρακάτω:",

/**
 * User add
 */

	'useradd:subject' => 'Ο λογαριασμός χρήστη δημιουργήθηκε',
	'useradd:body' => '%s,

Ένα λογαριασμός έχει δημιουργηθεί για σας στον ιστότοπο %s. Για να συνδεθείτε επισκευθείτε τη σελίδα:

%s

Και χρησιμοποιήστε τα παρακάτω αναγνωριστικά εισόδου:

Όνομα Χρήστη: %s
Κωδικός: %s

Αφού συνδεθείτε, σας προτείνουμε να αλλάξετε τον κωδικό σας.',

/**
 * System messages
 */

	'systemmessages:dismiss' => "πατήστε για αποδέσμευση",


/**
 * Import / export
 */

	'importsuccess' => "Η εισαγωγή δεδομένων ήταν επιτυχής",
	'importfail' => "Η εισαγωγή δεδομένων από OpenDD απέτυχε.",

/**
 * Time
 */

	'friendlytime:justnow' => "μόλις τώρα",
	'friendlytime:minutes' => "%s λεπτά πριν",
	'friendlytime:minutes:singular' => "πριν 1 λεπτό",
	'friendlytime:hours' => "%s ώρες πριν",
	'friendlytime:hours:singular' => "μια ώρα πριν",
	'friendlytime:days' => "%s μέρες πριν",
	'friendlytime:days:singular' => "χθες",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "in %s minutes",
	'friendlytime:future:minutes:singular' => "in a minute",
	'friendlytime:future:hours' => "in %s hours",
	'friendlytime:future:hours:singular' => "in an hour",
	'friendlytime:future:days' => "in %s days",
	'friendlytime:future:days:singular' => "tomorrow",

	'date:month:01' => 'Ιανουάριος %s',
	'date:month:02' => 'Φεβρουάριος %s',
	'date:month:03' => 'Μάρτιος %s',
	'date:month:04' => 'Απρίλιος %s',
	'date:month:05' => 'Μάιος %s',
	'date:month:06' => 'Ιούνιος %s',
	'date:month:07' => 'Ιούλιος %s',
	'date:month:08' => 'Αύγουστος %s',
	'date:month:09' => 'Σεπτέμβριος %s',
	'date:month:10' => 'Οκτώβριος %s',
	'date:month:11' => 'Νοέμβριος %s',
	'date:month:12' => 'Δεκέμβριος %s',
	
	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'May %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dec %s',

	'date:weekday:0' => 'Sunday',
	'date:weekday:1' => 'Monday',
	'date:weekday:2' => 'Tuesday',
	'date:weekday:3' => 'Wednesday',
	'date:weekday:4' => 'Thursday',
	'date:weekday:5' => 'Friday',
	'date:weekday:6' => 'Saturday',

	'date:weekday:short:0' => 'Sun',
	'date:weekday:short:1' => 'Mon',
	'date:weekday:short:2' => 'Tue',
	'date:weekday:short:3' => 'Wed',
	'date:weekday:short:4' => 'Thu',
	'date:weekday:short:5' => 'Fri',
	'date:weekday:short:6' => 'Sat',

	'interval:minute' => 'Every minute',
	'interval:fiveminute' => 'Every five minutes',
	'interval:fifteenmin' => 'Every fifteen minutes',
	'interval:halfhour' => 'Every half hour',
	'interval:hourly' => 'Hourly',
	'interval:daily' => 'Daily',
	'interval:weekly' => 'Weekly',
	'interval:monthly' => 'Monthly',
	'interval:yearly' => 'Yearly',
	'interval:reboot' => 'On reboot',

/**
 * System settings
 */

	'installation:sitename' => "Το όνομα του ιστότοπου σας:",
	'installation:sitedescription' => "Σύντομη περιγραφή του ιστότοπου (προαιρετικό):",
	'installation:wwwroot' => "Διεύθυνση του ιστότοπου (URL):",
	'installation:path' => "Το πλήρες μονοπάτι (path) του φακέλου που είναι εγκατεστημένο το Elgg:",
	'installation:dataroot' => "Το πλήρες μονοπάτι (path) του φακέλου δεδομένων:",
	'installation:dataroot:warning' => "Πρέπει να δημιουργήσετε αυτόν τον κατάλογο χειροκίνητα. Θα πρέπει να είναι σε ένα διαφορετικό κατάλογο από εκεί που είναι εγκατεστημένο το Elgg.",
	'installation:sitepermissions' => "Προεπιλεγμένα δικαιώματα πρόσβασης:",
	'installation:language' => "Προεπιλεγμένη γλώσσα για τον ιστότοπο σας:",
	'installation:debug' => "Η λειτουργία debug παρέχει επιπλέον πληροφορίες που μπορούν να χρησιμοποιηθούν για τη διάγνωση βλαβών. Ωστόσο, μπορεί να επιβραδύνει το σύστημά σας, έτσι πρέπει να χρησιμοποιείται μόνο αν έχετε προβλήματα:",
	'installation:debug:label' => "Log level:",
	'installation:debug:none' => 'Απενεργοποιήστε τη λειτουργία debug (συνιστάται)',
	'installation:debug:error' => 'Εμφάνιση μόνο κρίσιμων σφαλμάτων',
	'installation:debug:warning' => 'Εμφάνιση σφαλμάτων και προειδοποιήσεων',
	'installation:debug:notice' => 'Καταγραφή όλων των σφαλμάτων, προειδοποιήσεων και ανακοινώσεων',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'Η εγγραφή χρηστών είναι ενεργοποιημένη από προεπιλογή. Απενεργοποιήστε το αν δεν θέλετε νέοι χρήστες να είναι σε θέση να εγγραφούν από μόνοι τους.',
	'installation:registration:label' => 'Επιτρέπεται η εγγραφή σε νέους χρήστες',
	'installation:walled_garden:description' => 'Ενεργοποιήστε τον ιστότοπο για να λειτουργεί ως ένα ιδιωτικό δίκτυο. Αυτό δεν θα επιτρέψει την πρόσβαση στο περιεχόμενο για μη συνδεδεμένους χρήστες του ιστότοπου πλην εκείνου που έχει δημόσιο χαρακτήρα.',
	'installation:walled_garden:label' => 'Περιορισμός πρόσβασης μόνο σε εγγεγραμμένους χρήστες',

	'installation:view' => "Εισάγετε την προβολή η οποία θα χρησιμοποιηθεί ως προεπιλογή για την ιστοσελίδα σας ή αφήστε κενό για την προεπιλεγμένη προβολή (σε περίπτωση αμφιβολίας, αφήστε ως προεπιλογή):",

	'installation:siteemail' => "Διεύθυνση email του ιστότοπου (χρησιμοποιείται όταν στέλνονται ηλεκτρονικά μηνύματα):",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "Η αλλαγή της ρύθμισης πρόσβασης θα έχει επίδραση μόνο στο περιεχόμενο που θα δημιουργηθεί μελλοντικά.",
	'installation:allow_user_default_access:description' => "Εάν επιλεγεί, οι μεμονωμένοι χρήστες θα έχουν τη δυνατότητα να καθορίζουν το δικό τους επίπεδο πρόσβασης, το οποίο θα υπερισχύει του προεπιλεγμένου επιπέδου πρόσβασης του συστήματος.",
	'installation:allow_user_default_access:label' => "Να επιτραπεί στους χρήστες η επιλογή προεπιλεγμένου επιπέδου πρόσβασης",

	'installation:simplecache:description' => "Η απλή λανθάνουσα μνήμη (cache) αυξάνει την απόδοση με την προσωρινή αποθήκευση στατικού περιεχομένου, συμπεριλαμβανομένων ορισμένων αρχείων CSS και JavaScript.",
	'installation:simplecache:label' => "Χρήση απλής λανθάνουσας μνήμης (cache) (προτεινόμενο)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "The simple cache can also improve performance by compressing JavaScript and CSS files. (Requires that simple cache is enabled.)",
	'installation:minify_js:label' => "Compress JavaScript (recommended)",
	'installation:minify_css:label' => "Compress CSS (recommended)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file so that the path is injected into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "Η λανθάνουσα μνήμη (cache) του συστήματος μειώνει το χρόνο φόρτωσης του Elgg από την προσωρινή αποθήκευση των δεδομένων σε αρχεία.",
	'installation:systemcache:label' => "Χρησιμοποιείστε τη λανθάνουσα μνήμη του συστήματος (προτεινόμενο)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Content Access',
	'admin:legend:site_access' => 'Site Access',
	'admin:legend:debug' => 'Debugging and Logging',

	'upgrading' => 'Γίνεται αναβάθμιση...',
	'upgrade:core' => 'Your elgg installation was upgraded.',
	'upgrade:unlock' => 'Ξεκλείδωμα διαδικασίας αναβάθμισης.',
	'upgrade:unlock:confirm' => "Η βάση δεδομένων είναι κλειδωμένη από άλλη διαδικασία αναβάθμισης. Ταυτόχρονη εκτέλεση διαδικασιών αναβάθμισης  είναι επικίνδυνη. Θα πρέπει να συνεχίσετε μόνο αν γνωρίζετε δεν υπάρχει άλλη διαδικασία αναβάθμισης. Να ξεκλειδωθεί;",
	'upgrade:locked' => "Δεν είναι δυνατή η αναβάθμιση. Μία άλλη διαδικασία αναβάθμιση εκτελείται ήδη. Για ξεκλείδωμα της διαδικασίας, επισκεφθείτε την ενότητα διαχείρισης (Admin section).",
	'upgrade:unlock:success' => "Η διαδικασία αναβάθμισης ξεκλειδώθηκε με επιτυχία.",
	'upgrade:unable_to_upgrade' => 'Αδυναμία αναβάθμισης.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br><br>

		If you need detailed instructions, please visit the <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Το Twitter API απενεργοποιήθηκε κατά τη διαδικασία αναβάθμισης. Παρακαλούμε ενεργοποιείστε το πάλι χειροκίνητα εφόσον απαιτείται.',
	'update:oauth_api:deactivated' => 'Το OAuth API (παλιότερα ως OAuth Lib) απενεργοποιήθηκε κατά τη διαδικασία αναβάθμισης. Παρακαλούμε ενεργοποιείστε το πάλι χειροκίνητα εφόσον απαιτείται.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",

	'deprecated:function' => '%s() θεωρήθηκε ως μη συμβατό από %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Errors:',
	'upgrade:river_update_failed' => 'Failed to update the river entry for item id %s',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',

	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Καλώς ήλθατε",
	'welcome:user' => 'Καλώς ήλθατε %s',

/**
 * Emails
 */

	'email:from' => 'From',
	'email:to' => 'To',
	'email:subject' => 'Subject',
	'email:body' => 'Body',

	'email:settings' => "Ρυθμίσεις email",
	'email:address:label' => "Η διεύθυνση σας email",

	'email:save:success' => "Η νέα διεύθυνση email αποθηκεύτηκε. Απαιτείται επιβεβαίωση.",
	'email:save:fail' => "Η νέα σας διεύθυνση email δεν ήταν δυνατόν να αποθηκευτεί.",

	'friend:newfriend:subject' => "%s σας έκανε φίλο!",
	'friend:newfriend:body' => "%s σας έκανε φίλο!

Για προβολή του προφίλ, πατήστε εδώ:

%s

Δεν μπορείτε να απαντήσετε σε αυτό το μήνυμα.",

	'email:changepassword:subject' => "Password changed!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Επαναφοράς κωδικού πρόσβασης!",
	'email:resetpassword:body' => "Γεια σας %s,

Έχει γίνει επαναφορά του κωδικού σας πρόσβασης σε: %s",

	'email:changereq:subject' => "Request for password change.",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for their account.

If you requested this, click on the link below. Otherwise ignore this email.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "Προεπιλεγμένο επίπεδο πρόσβασης σας",
	'default_access:label' => "Προεπιλεγμένη πρόσβαση",
	'user:default_access:success' => "Το νέο προεπιλεγμένο επίπεδο πρόσβασης σας αποθηκεύτηκε.",
	'user:default_access:failure' => "Το νέο προεπιλεγμένο επίπεδο πρόσβασης σας δεν ήταν δυνατόν να αποθηκευτεί.",

/**
 * Comments
 */

	'comments:count' => "%s σχόλια",
	'item:object:comment' => 'Comments',

	'river:comment:object:default' => '%s σχολίασε σε %s',

	'generic_comments:add' => "Σχολιάστε",
	'generic_comments:edit' => "Edit comment",
	'generic_comments:post' => "Δημοσίευση σχολίου",
	'generic_comments:text' => "Σχόλιο",
	'generic_comments:latest' => "Πρόσφατα σχόλια",
	'generic_comment:posted' => "Το σχόλιο σας δημοσιεύτηκε με επιτυχία.",
	'generic_comment:updated' => "The comment was successfully updated.",
	'generic_comment:deleted' => "Το σχόλιο διαγράφηκε με επιτυχία.",
	'generic_comment:blank' => "Λυπούμαστε, πρέπει να γράψετε κάτι στο σχόλιο σας για να καταχωρηθεί.",
	'generic_comment:notfound' => "Λυπούμαστε, δεν βρέθηκε η συγκεκριμένη καταχώρηση.",
	'generic_comment:notfound_fallback' => "Sorry, we could not find the specified comment, but we've forwarded you to the page where it was left.",
	'generic_comment:notdeleted' => "Λυπούμαστε, δεν έγινε διαγραφή του σχολίου.",
	'generic_comment:failure' => "Παρουσιάστηκε σφάλμα κατά την προσθήκη του σχολίου. Παρακαλούμε δοκιμάστε ξανά.",
	'generic_comment:none' => 'Χωρίς σχόλια',
	'generic_comment:title' => 'Σχόλιο από %s',
	'generic_comment:on' => '%s σε %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:email:subject' => 'Έχετε ένα νέο σχόλιο!',
	'generic_comment:email:body' => "Έχετε ένα νέο σχόλιο στην καταχώρηση σας \"%s\" από %s:


%s


Για προβολή του σχολίου ή απάντηση, πατήστε εδώ:

%s

Για προβολή του προφίλ του χρήστη, πατήστε εδώ:

%s

Δεν μπορείτε να απαντήσετε σε αυτό το μήνυμα.",

/**
 * Entities
 */

	'byline' => 'Από %s',
	'byline:ingroup' => 'in the group %s',
	'entity:default:strapline' => 'Δημιουργήθηκε %s από %s',
	'entity:default:missingsupport:popup' => 'Η οντότητα αυτή δεν μπορεί να εμφανιστεί σωστά. Αυτό μπορεί να συμβαίνει επειδή απαιτείται υποστήριξη από ένα plugin το οποίο δεν είναι πλέον εγκατεστημένο.',

	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item not found.',
	'entity:delete:permission_denied' => 'You do not have permissions to delete this item.',
	'entity:delete:success' => 'Η οντότητα %s έχει διαγραφεί',
	'entity:delete:fail' => 'Η οντότητα %s δεν ήταν δυνατόν να διαγραφεί',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Λείπει κάποιο από τα στοιχεία της φόρμας __token ή __ts fields',
	'actiongatekeeper:tokeninvalid' => "We encountered an error (token mismatch). This probably means that the page you were using expired. Please try again.",
	'actiongatekeeper:timeerror' => 'Η σελίδα που χρησιμοποιείτε έχει λήξει. Παρακαλούμε δοκιμάστε ξανά και ανανεώστε τη σελίδα.',
	'actiongatekeeper:pluginprevents' => 'Καποια επέκταση έχει εμποδίσει αυτή την υποβολή αυτής της φόρμας.',
	'actiongatekeeper:uploadexceeded' => 'Το μέγεθος του αρχείου είναι μεγαλύτερο από το όριο που έχει καθοριστεί από το διαχειριστή',
	'actiongatekeeper:crosssitelogin' => "Sorry, logging in from a different domain is not permitted. Please try again.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'και, το, τότε, αλλά, αυτή, του, της, τον, ένα, όχι, επίσης, περίπου, τώρα, ως εκ τούτου, όμως, ακόμη, ομοίως, διαφορετικά, κατά συνέπεια, αντίθετα, μάλλον, κατά συνέπεια, επίσης, παρόλα αυτά, αντίθετα, εν τω μεταξύ, κατά συνέπεια, αυτό, φαίνεται, ό, τι, ποιον, του οποίου, όποιος, όποιον',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Ετικέτες',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Δεν είναι δυνατή η επικοινωνία με %s. Ίσως αντιμετωπίσετε πρόβλημα αποθήκευσης του περιεχομένου.',
	'js:security:token_refreshed' => 'Η σύνδεση σε %s αποκαταστάθηκε!',
	'js:lightbox:current' => "image %s of %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "Mandarin Chinese", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Ελληνικά",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Σκοπιανά",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"sr_latin" => "Serbian (Latin)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Έως",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Required',

);
