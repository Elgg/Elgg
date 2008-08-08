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
	$entity_stats = get_entity_statistics($_SESSION['user']->guid);
	
	if ($entity_stats)
	{
?>
<div class="usersettings_statistics">
    <h3><?php echo elgg_echo('usersettings:statistics:label:numentities'); ?></h3>
    <table>
        <?php
            foreach ($entity_stats as $k => $entry)
            {
                foreach ($entry as $a => $b)
                {
                    if ($a == "__base__") {
                        $a = elgg_echo("item:{$k}");
                        if (empty($a))
                        	$a = $k;
                	} else {
                    		$a = elgg_echo("item:{$k}:{$a}");
                    		if (empty($a)) {
								$a = "$k $a";
                    		}
                    	 }
                    echo <<< END
                        <tr>
                            <td style="width: 250px"><b>{$a}:</b></td>
                            <td>{$b}</td>
                        </tr>
END;
                }
            }
        ?>
    </table>
</div>
<?php } ?>