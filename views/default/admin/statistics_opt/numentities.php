<?php
	/**
	 * Elgg statistics screen
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	
	// Get entity statistics
	$entity_stats = get_entity_statistics();
?>
<div>    
    <h2><?php echo elgg_echo('admin:statistics:label:numentities'); ?></h2>
    <table>
        <?php
            foreach ($entity_stats as $k => $entry)
            {
                echo "<table>";
                foreach ($entry as $a => $b)
                {
                    if ($a == "__base__") 
                        $a=$k;
                    else
                        $a = "$k $a";
                    echo <<< END
                        <tr>
                            <td><b>$a :</b></td>
                            <td>$b</td>
                        </tr>
END;
                }
                echo "</table>";
            }
        ?>
    </table>
</div>