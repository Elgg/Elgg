<?php
/*
 * invite_join_footer.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */

  $correctAge = __gettext("I am at least thirteen years of age."); // gettext variable
  $buttonValue = __gettext("Join"); // gettext variable
  $run_result .= <<< END
      <p align="center">
          <label for="over13checkbox"><input type="checkbox" id="over13checkbox" name="over13" value="yes" /> <strong>$correctAge</strong></label>
      </p>
END;


?>
