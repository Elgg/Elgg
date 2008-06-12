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

	global $CONFIG;
	
	echo "<p>" . nl2br(elgg_echo("admin:statistics:description")) . "</p>";
         
        
        
        // Get entity statistics
        $entity_stats = get_entity_statistics();
                 
        // Work out number of users
        $users_stats = get_number_users();
        
        // users online
        $users_online = get_number_online();
        
        
	
?>
<div>
    <h2><?php echo elgg_echo('admin:statistics:label:basic'); ?></h2>
    <table>
        <tr>
            <td><b><?php echo elgg_echo('admin:statistics:label:numusers'); ?> :</b></td>
            <td><?php echo $users_stats; ?></td>
        </tr>
        <tr>
            <td><b><?php echo elgg_echo('admin:statistics:label:numonline'); ?> :</b></td>
            <td><?php echo $users_online; ?></td>
        </tr>
    </table> 
</div>   

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

<div>
    <h2><?php echo sprintf(elgg_echo('admin:statistics:label:onlineusers'), 10); ?></h2>
    
    
</div>