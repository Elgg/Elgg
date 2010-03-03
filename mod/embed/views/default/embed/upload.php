<h1 class="mediaModalTitle">Embed / Upload Media</h1>
<?php

	echo elgg_view('embed/tabs',array('tab' => 'upload', 'internalname'=>get_input('internalname')));

	if (!elgg_view_exists('file/upload')) {
		echo "<p>" . elgg_echo('embed:file:required') . "</p>";
	} else {
		$action = 'file/upload';
		
?>
	<form id="mediaUpload" action="<?php echo $vars['url']; ?>action/file/upload" method="post" enctype="multipart/form-data">
		<p>
			<label for="upload"><?php echo elgg_echo("file:file"); ?><br />
		<?php
			echo elgg_view('input/securitytoken');
			echo elgg_view("input/file",array('internalname' => 'upload', 'js' => 'id="upload"'));
			
		?>
		</label></p>
		<p>
			<label><?php echo elgg_echo("title"); ?><br />
			<?php

				echo elgg_view("input/text", array(
									"internalname" => "title",
									"value" => $title,
													));
			
			?>
			</label>
		</p>
		<p>
		<label for="filedescription"><?php echo elgg_echo("description"); ?><br />
		<textarea class="input-textarea" name="description" id="filedescription"></textarea>
		</label></p>
		
		<p>
			<label><?php echo elgg_echo("tags"); ?><br />
			<?php
				echo elgg_view("input/tags", array(
									"internalname" => "tags",
									"value" => $tags,
													));
	
			?>
			</label>
		</p>
		<p>
			<label>
				<?php echo elgg_echo('access'); ?><br />
				<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => ACCESS_DEFAULT)); ?>
			</label>
		</p>
	
		<p>
			<?php

				if (isset($vars['container_guid']))
					echo "<input type=\"hidden\" name=\"container_guid\" value=\"{$vars['container_guid']}\" />";
				if (isset($vars['entity']))
					echo "<input type=\"hidden\" name=\"file_guid\" value=\"{$vars['entity']->getGUID()}\" />";
			
			?>
			<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
		</p>
	</form>
	<script type="text/javascript"> 
        // wait for the DOM to be loaded 
        //$(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            $('#mediaUpload').submit(function() { 
	            var options = {  
				    success:    function() { 
				        $('.popup .content').load('<?php echo $vars['url'] . 'pg/embed/media'; ?>?internalname=<?php echo $vars['internalname']; ?>'); 
				    } 
				}; 
            	$(this).ajaxSubmit(options);
                return false; 
            }); 
        //}); 
    </script> 

<?php
		
	}
	
?>
