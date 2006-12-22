<?php

    $header = __gettext("Create a new group"); // gettext variable
    $labelValue = __gettext("Group name:"); // gettext variable
    $buttonValue = __gettext("Create"); // gettext variable
    $run_result .= <<< END

<h5>
    $header
</h5>
<form action="" method="post">
    <p>
        <label>
            $labelValue
            <input type="text" name="name" value="" />
        </label>
        <input type="submit" value=$buttonValue />
        <input type="hidden" name="action" value="group:create" />
    </p>
</form>

END;

?>