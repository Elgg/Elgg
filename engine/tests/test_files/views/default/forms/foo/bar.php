<input type="text" name="baz" />
<?php

echo elgg_format_element('span', [], $vars['baz2']);

elgg_set_form_footer('<input type="submit" value="Save" />');

?>

<input type="text" name="baz3" />