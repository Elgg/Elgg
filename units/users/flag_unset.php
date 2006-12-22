<?php

// Flag functions: unset
// Ben Werdmuller, Sept 05
    
/* Parameters:

[0] - name of the flag
[1] - user ID
    
*/
    

$flagname = $parameter[0];
$userid = (int) $parameter[1];

user_flag_unset($flagname,$userid);

?>