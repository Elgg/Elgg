<?php

	/**
	 * Elgg profile icon edit form
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
	 */

?>
<!-- grab the required js for icon cropping -->
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/profile/views/default/js/jquery.imgareaselect-0.4.2.js"></script>

	<form action="<?php echo $vars['url']; ?>action/profile/iconupload" method="post" enctype="multipart/form-data">
	<p>
		<?php echo elgg_echo("profile:editicon"); ?>:
	</p>
	<p>
		<?php

			echo elgg_view("input/file",array('internalname' => 'profileicon'));
		
		?>
	</p>
	<p>
		<input type="submit" class="submit_button" value="<?php echo elgg_echo("upload"); ?>" />
	</p>
	</form>
	
<?php

    echo "Your current master photo: <br />";
    //display the current user photo 
    $user_master_image = $vars['url'] . "pg/icon/" . $_SESSION['user']->username . "/master/" . $_SESSION['user']->icontime . ".jpg";
    
?>

<script>
    function preview(img, selection) { 
        var scaleX = 100 / selection.width; 
        var scaleY = 100 / selection.height; 
        $('#user_avatar + div > img').css({ 
            width: Math.round(scaleX * 600) + 'px', 
            height: Math.round(scaleY * 500) + 'px', 
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
         }); } 
         
        $(document).ready(function () { 
            $('<div><img src="<?php echo $user_master_image; ?>" style="position: relative;" /></div>') 
            .css({ float: 'left', position: 'relative', overflow: 'hidden', width: '100px', height: '100px' }) 
            .insertAfter($('#user_avatar')); 
        }); 
        
        $(window).load(function () { 
            $('#user_avatar').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });
            $('#user_avatar').imgAreaSelect({ x1: 100, y1: 100, x2: 200, y2: 200 }); 
        });
</script>

<p>
<img id="user_avatar" src="<?php echo $user_master_image; ?>" alt="User profile photo"
 style="float: left; margin-right: 10px;" />
</p>