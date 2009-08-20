<?php 

	/**
	 * Elgg calendar input
	 * Displays a calendar input field
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 * 
	 * @uses $vars['value'] The current value, if any
	 * @uses $vars['js'] Any Javascript to enter into the input tag
	 * @uses $vars['internalname'] The name of the input field
	 * 
	 */

    static $calendarjs;
    if (empty($calendarjs)) {
        
        echo <<< END
        
<script language="JavaScript" src="{$vars['url']}vendors/calendarpopup/CalendarPopup.js"></script>
        
END;
        $calendarjs = 1;
    }
    $strippedname = sanitise_string($vars['internalname']);
    $js = "cal" . $strippedname;

    if ($vars['value'] > 86400) {
        $val = date("F j, Y",$vars['value']);
    } else {
        $val = $vars['value'];
    }
    
?>
<script language="JavaScript">
    var cal<?php echo $strippedname; ?> = new CalendarPopup();
</script>
<input type="text" <?php echo $vars['js']; ?> name="<?php echo $vars['internalname']; ?>" id="<?php echo $strippedname; ?>" value="<?php echo $val; ?>" />
<a href="#" onclick="<?php echo $js; ?>.select(document.getElementById('<?php echo $strippedname; ?>'),'anchor<?php echo $strippedname; ?>','MMM dd, yyyy'); return false;" TITLE="<?php echo $js; ?>.select(document.forms[0].<?php echo $strippedname; ?>,'anchor<?php echo $strippedname; ?>','MMM dd, yyyy'); return false;" NAME="anchor<?php echo $strippedname; ?>" ID="anchor<?php echo $strippedname; ?>">select</a> 