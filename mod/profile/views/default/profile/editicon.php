<?php

	/**
	 * Elgg profile icon edit form
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 * @uses $vars['profile'] Profile items from $CONFIG->profile, defined in profile/start.php for now 
	 */

	$currentuser = page_owner_entity();
    if (!$currentuser)
    	$currentuser=$_SESSION['user'];
?>
<!-- grab the required js for icon cropping -->
<div class="contentWrapper">
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/profile/views/default/js/jquery.imgareaselect-0.8.min.js"></script>

<p><?php echo elgg_echo('profile:profilepictureinstructions'); ?></p>

<div id="current_user_avatar">

	<label><?php echo elgg_echo('profile:currentavatar'); ?></label>
	<?php 
		
		$user_avatar = $currentuser->getIcon('medium');
		echo "<img src=\"{$user_avatar}\" alt=\"avatar\" />";

	?>

</div>

<div id="profile_picture_form">
	<form action="<?php echo $vars['url']; ?>action/profile/iconupload" method="post" enctype="multipart/form-data">
	<?php echo elgg_view('input/securitytoken'); ?>
	<input type="hidden" name="username" value="<?php echo $currentuser->username; ?>" />
	<p><label><?php echo elgg_echo("profile:editicon"); ?></label><br />
	
		<?php
			
			echo elgg_view("input/file",array('internalname' => 'profileicon'));
		?>
		<br /><input type="submit" class="submit_button" value="<?php echo elgg_echo("upload"); ?>" />
	</p>
	</form>
</div>
	
<div id="profile_picture_croppingtool">	
<label><?php echo elgg_echo('profile:profilepicturecroppingtool'); ?></label><br />
<p>	
<?php

    echo elgg_echo("profile:createicon:instructions");
    
    //display the current user photo
     
    $user_master_image = $currentuser->getIcon('master');//$vars['url'] . "pg/icon/" . $currentuser->username . "/master/" . $currentuser->icontime . ".jpg";
    
?>
</p>
<script>

    //function to display a preview of the users cropped section
    function preview(img, selection) {
        var origWidth = $("#user_avatar").width(); //get the width of the users master photo
        var origHeight = $("#user_avatar").height(); //get the height of the users master photo
        var scaleX = 100 / selection.width; 
        var scaleY = 100 / selection.height; 
        $('#user_avatar_preview > img').css({ 
            width: Math.round(scaleX * origWidth) + 'px', 
            height: Math.round(scaleY * origHeight) + 'px', 
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
         }); 
    } 
        
    //variables for the newly cropped avatar
    var $x1, $y1, $x2, $y2, $w, $h;
        
        function selectChange(img, selection){
           
           //populate the form with the correct coordinates once a user has cropped their image
           document.getElementById('x_1').value = selection.x1;
           document.getElementById('x_2').value = selection.x2;
           document.getElementById('y_1').value = selection.y1;
           document.getElementById('y_2').value = selection.y2;
           
         }     
         
        $(document).ready(function () {
            
            //get and set the coordinates
            $x1 = $('#x1');
            $y1 = $('#y1');
            $x2 = $('#x2');
            $y2 = $('#y2');
            $w = $('#w');
            $h = $('#h');
            
            
            $('<div id="user_avatar_preview"><img src="<?php echo $user_master_image; ?>" /></div>') 
            .insertAfter($('#user_avatar'));
            
            $('<div id="user_avatar_preview_title"><label><?php echo elgg_echo('profile:preview'); ?></label></div>').insertBefore($('#user_avatar_preview'));
            
        }); 
        
        $(window).load(function () { 
            
            //this produces the coordinates
            $('#user_avatar').imgAreaSelect({ selectionOpacity: 0, onSelectEnd: selectChange });
            //show the preview
            $('#user_avatar').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });
  
        });
 
</script>

<p>
<img id="user_avatar" src="<?php echo $user_master_image; ?>" alt="<?php echo elgg_echo("profile:icon"); ?>" />
</p>

<div class="clearfloat"></div>

<form action="<?php echo $vars['url']; ?>action/profile/cropicon" method="post" />
	<?php echo elgg_view('input/securitytoken'); ?>
	<input type="hidden" name="username" value="<?php echo $vars['user']->username; ?>" />
	<input type="hidden" name="x_1" value="<?php echo $vars['user']->x1; ?>" id="x_1" />
    <input type="hidden" name="x_2" value="<?php echo $vars['user']->x2; ?>" id="x_2" />
    <input type="hidden" name="y_1" value="<?php echo $vars['user']->y1; ?>" id="y_1" />
    <input type="hidden" name="y_2" value="<?php echo $vars['user']->y2; ?>" id="y_2" />
	<input type="submit" name="submit" value="<?php echo elgg_echo("profile:createicon"); ?>" />
</form>

</div>
<div class="clearfloat"></div>

</div>
