/**
 * Elgg thewire text counter
 * 
 * @package ElggTheWire
 *
 * @question - do we want users to be able to edit thewire?
 * 
 * @uses $vars['entity'] Optionally, the note to view

<!-- Dynamic Version by: Nannette Thacker -->
<!-- http://www.shiningstar.net -->
<!-- Original by :  Ronnie T. Moore -->
<!-- Web Site:  The JavaScript Source -->
<!-- Limit the number of characters per textarea -->
*/

function textCounter(field,cntfield,maxlimit) {
    // if too long...trim it!
    if (field.value.length > maxlimit) {
        field.value = field.value.substring(0, maxlimit);
    } else {
        // otherwise, update 'characters left' counter
        cntfield.value = maxlimit - field.value.length;
    }
}
