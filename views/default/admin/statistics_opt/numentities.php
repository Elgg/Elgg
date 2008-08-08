<?php
	/**
	 * Elgg statistics screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	
	// Get entity statistics
	$entity_stats = get_entity_statistics();
?>
<div class="admin_statistics">
    <h2><?php echo elgg_echo('admin:statistics:label:numentities'); ?></h2>
    <table>
        <?php
            foreach ($entity_stats as $k => $entry)
            {
            	arsort($entry);
                foreach ($entry as $a => $b)
                {
                    if ($a == "__base__") {
                    	$a = elgg_echo("item:{$k}");
                    	if (empty($a)) 
                        	$a = $k;
                    }
                    else {
                    		if (empty($a))
                    			$a = elgg_echo("item:{$k}");
                    		else
                				$a = elgg_echo("item:{$k}:{$a}");
                    		if (empty($a)) {
								$a = "$k $a";
                    		}
                    	  }
                    echo <<< END
                        <tr>
                            <td style="width: 250px">{$a}:</td>
                            <td>{$b}</td>
                        </tr>
END;
                }
            }
        ?>
    </table>
</div>