<?php

    $descOne = __gettext("Access is controlled through the use of Access groups and this is one of the most powerful features of Elgg. Access groups allow you to have complete control over who sees what within your environment. You can create as many access groups as you wish. Each access group can have as many of your friends as you like and each access group created becomes another option in the 'access restriction' dropdown menu beside each profile item, weblog post and file uploaded.");
    $descTwo = __gettext("When you create an access group, add people to it then select it as your access restriction on a weblog post only the people in that group will be able to see that weblog post and so on for all elements in your learning landscape.");
    $run_result .= <<< END

    <p>$descOne</p>
    <p>$descTwo</p>
END;

?>