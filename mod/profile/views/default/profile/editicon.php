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

    //function to display a preview of the users cropped section
    function preview(img, selection) {
        var origWidth = $("#user_avatar").width(); //get the width of the users master photo
        var origHeight = $("#user_avatar").height(); //get the height of the users master photo
        var scaleX = 100 / selection.width; 
        var scaleY = 100 / selection.height; 
        $('#user_avatar + div > img').css({ 
            width: Math.round(scaleX * origWidth) + 'px', 
            height: Math.round(scaleY * origHeight) + 'px', 
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
         }); 
    } 
        
    //variables for the newly cropped avatar
    var $x1, $y1, $x2, $y2, $w, $h;
        
        function selectChange(img, selection){
            
           //delete this once we have tested, it is for the coordinate display
           $x1.text(selection.x1);
           $y1.text(selection.y1);
           $x2.text(selection.x2);
           $y2.text(selection.y2);
           $w.text(selection.width);
           $h.text(selection.height);
           
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
            
            $('<div><img src="<?php echo $user_master_image; ?>" style="position: relative;" /></div>') 
            .css({ float: 'left', position: 'relative', overflow: 'hidden', width: '100px', height: '100px' }) 
            .insertAfter($('#user_avatar'));
            
        }); 
        
        $(window).load(function () { 
            
            //this produces the coordinates
            $('#user_avatar').imgAreaSelect({ selectionOpacity: 0, onSelectEnd: selectChange });
            //show the preview
            $('#user_avatar').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });
  
        });
 
</script>

<p>
<img id="user_avatar" src="<?php echo $user_master_image; ?>" alt="User profile photo"
 style="float: left; margin-right: 10px;" />
</p>
 <div style="float: right; margin-left: 10px; margin-top:-200px;">
  <p style="background: #eee; border: solid 1px #ddd; margin: 0; padding: 10px;">
   <b>Selection coordinates:</b><br />

   <b>X<sub>1</sub>:</b> <span id="x1"></span><br />
   <b>Y<sub>1</sub>:</b> <span id="y1"></span><br />
   <b>X<sub>2</sub>:</b> <span id="x2"></span><br />

   <b>Y<sub>2</sub>:</b> <span id="y2"></span><br />
   <br />
   <b>Selection dimensions:</b><br />
   <b>Width:</b> <span id="w"></span><br />
   <b>Height:</b> <span id="h"></span>

  </p>
 </div>
 
<form name="" action="" />
    <input type="hidden" name="x_1" value="" id="x_1" />
    <input type="hidden" name="x_2" value="" id="x_2" />
    <input type="hidden" name="y_1" value="" id="y_1" />
    <input type="hidden" name="y_2" value="" id="y_2" />
    <input type="submit" name="submit" value="create your avatar" />
</form>