<?php

	/**
	 * Elgg custom profile 
	 * You can edit the layout of this page with your own layout and style. Whatever you put in the file
	 * will replace the frontpage of your Elgg site.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */
	 
?>

<div id="custom_index">

    <!-- left column content -->
    <div id="index_left">
        <!-- welcome message -->
        <div id="index_welcome"> 
        	<?php
        		if (isloggedin()){
	        		echo "<h2>" . elgg_echo("welcome") . " ";
        			echo $vars['user']->name;
        			echo "</h2>";
    			}
        	?>
            <?php
            	//include a view that plugins can extend
            	echo elgg_view("index/lefthandside");
            ?>
	        <?php
	            //this displays some content when the user is logged out
			    if (!isloggedin()){
	            	//display the login form
			    	echo $vars['area1'];
			    	echo "<div class=\"clearfloat\"></div>";
		        }
	        ?>
        </div>
<?php
    if(is_plugin_enabled('file')){
?> 	
        <!-- display latest files -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:files"); ?></h2>
            <?php 
                if (!empty($vars['area2'])) {
                    echo $vars['area2'];//this will display files
                }else{
                    echo "<p><?php echo elgg_echo('custom:nofiles'); ?></p>";
                }
            ?>
        </div>
<?php
	}
	
    if(is_plugin_enabled('groups')){
?> 
        <!-- display latest groups -->
	    <div class="index_box">
            <h2><?php echo elgg_echo("custom:groups"); ?></h2>
        <?php 
                if (!empty($vars['area5'])) {
                    echo $vars['area5'];//this will display groups
                }else{
                    echo "<p><?php echo elgg_echo('custom:nogroups'); ?>.</p>";
                }
            ?>
    	</div>
<?php
	}
?>
    </div>
    
    <!-- right hand column -->
    <div id="index_right">
        <!-- more content -->
	    <?php
            //include a view that plugins can extend
            echo elgg_view("index/righthandside");
        ?>
        <!-- latest members -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:members"); ?></h2>
            <div class="contentWrapper">
            <?php 
                if(isset($vars['area3'])) {
                    //display member avatars
                    foreach($vars['area3'] as $members){
                        echo "<div class=\"index_members\">";
                        echo elgg_view("profile/icon",array('entity' => $members, 'size' => 'small'));
                        echo "</div>";
                    }
                }
            ?>
	        <div class="clearfloat"></div>
	        </div>
        </div>
<?php
    if(is_plugin_enabled('blog')){
?> 
        <!-- latest blogs -->
        <div class="index_box">
            <h2><?php echo elgg_echo("custom:blogs"); ?></h2>
            <?php 
                if (isset($vars['area4'])) 
                    echo $vars['area4']; //display blog posts
            ?>
        </div>
<?php
	}

    if(is_plugin_enabled('bookmarks')){
?>
        <!-- display latest bookmarks -->
    	<div class="index_box">
            <h2><?php echo elgg_echo("custom:bookmarks"); ?></h2>
            <?php 
                if (isset($vars['area6'])) 
                    echo $vars['area6']; //display bookmarks
            ?>
        </div>
<?php
	}
?>
    </div>
    <div class="clearfloat"></div>
</div>