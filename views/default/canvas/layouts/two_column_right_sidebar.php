<?php

	/**
	 * Elgg 2 column right sidebar canvas layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

?>
<!-- main content -->
<div id="two_column_right_sidebar_maincontent">

<?php

    	echo elgg_view('page_elements/owner_block',array('content' => $vars['area1']));
    	if (isset($vars['area2'])) echo $vars['area2']; ?>

</div><!-- /two_column_right_sidebar_maincontent -->

<!-- right sidebar -->
<div id="two_column_right_sidebar">

<?php if (isset($vars['area1'])) echo $vars['area1']; ?>

</div><!-- /two_column_right_sidebar -->

