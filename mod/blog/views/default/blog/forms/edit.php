<script>
$(document).ready(function(){
	$('#excerpt.excerpt').each(function(){
		var allowed = 200;
	
		// set the initial value
		$('#countervalue').text(allowed);
		
		// bind on key up event
		$(this).keyup(function(){
			var counter_value = ((allowed - ($(this).val().length)));
			
			$("#countervalue").removeClass();
			
			if ((counter_value > 10)) {
				$("#countervalue").addClass("positive");
			}
			else if ((counter_value <= 10) && (counter_value >= 0)) {
				$("#countervalue").addClass("gettingclose");
			}
			else if ((counter_value < 0)) {
				$("#countervalue").addClass("negative");
			}
		
			// insert new length
			$('#countervalue').text(counter_value);
						
		});
	});
});
</script>
<?php
/**
* Elgg blog edit/add page
*/

//access details
$loggedin_user_access = get_default_access(get_loggedin_user());
$user_acl = get_readable_access_level($loggedin_user_access);

//Populate the title, body and acction variables if it is an edit, declare them if it is a new post
if (isset($vars['entity'])) {
	$title = sprintf(elgg_echo("blog:editpost"),$object->title);
	$action = "blog/edit";
	$title = $vars['entity']->title;
	$body = $vars['entity']->description;
	$tags = $vars['entity']->tags;
	if ($vars['entity']->comments_on == 'Off')
		$comments_on = false;
	else
		$comments_on = true;
	$access_id = $vars['entity']->access_id;
	$show_excerpt = $vars['entity']->show_excerpt;
	if($show_excerpt)
		$excerpt = $vars['entity']->excerpt;
	else
		$excerpt = "";
	$page_title =  elgg_view_title(elgg_echo('blog:editpost'));
}else{
	$title = elgg_echo("blog:addpost");
	$action = "blog/add";
	$tags = "";
	$title = "";
	$comments_on = true;
	$description = "";
	$excerpt = "";
	$show_excerpt = '';
	$page_title =  elgg_view_title(elgg_echo('blog:addpost'));
	if(page_owner_entity() instanceof ElggGroup){
		//if in a group, set the access level to default to the group
		$access_id = page_owner_entity()->group_acl;
	}else{
		$access_id = $loggedin_user_access;
	}		
	$container = $vars['container_guid'] ? elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $vars['container_guid'])) : "";
}

//Just in case we have some cached details
if (empty($body)) {
	$body = $vars['user']->blogbody;
	if (!empty($body)) {
		$title = $vars['user']->blogtitle;
		$tags = $vars['user']->blogtags;
	}
}

//set the required input fields
$title_label = elgg_echo('blog:title');
$title_textbox = elgg_view('input/text', array('internalname' => 'blogtitle', 'value' => $title));
$text_label = elgg_echo('blog:text');
$text_textarea = elgg_view('input/longtext', array('internalname' => 'blogbody', 'value' => $body));
$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_counter = "<div class='thewire_characters_remaining'><span id='countervalue'></span></div>";
$excerpt_textarea = elgg_view('input/text', array('internalname' => 'blogexcerpt', 'internalid' => 'excerpt', 'class' => 'excerpt input_textarea', 'value' => $excerpt));
$excerpt_desc = elgg_echo('blog:excerptdesc');
$show_excerpt_field = elgg_view('input/hidden', array('internalname' => 'show_excerpt', 'value' => $show_excerpt));
$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('internalname' => 'blogtags', 'value' => $tags));
$access_label = elgg_echo('access');
if($comments_on)
	$comments_on_switch = "checked=\"checked\"";
else
	$comment_on_switch = "";
//if it is a group, pull out the group access view
if(page_owner_entity() instanceof ElggGroup){
	$options = group_access_options(page_owner_entity());
}else{
	$options = '';
} 
$access_input = elgg_view('input/access', array('internalname' => 'access_id', 'value' => $access_id, 'options' => $options));
$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('publish')));
$conversation = elgg_echo('Conversation');
$publish = elgg_echo('publish');
$cat = elgg_echo('categories');
$preview = elgg_echo('blog:preview');
$privacy = elgg_echo('access');
$savedraft = elgg_echo('blog:draft:save');
$draftsaved = elgg_echo('blog:draft:saved');
$never = elgg_echo('blog:never');
$allowcomments = elgg_echo('blog:comments:allow');
$user_default_access = elgg_echo('blog:defaultaccess');
$ownerblock = elgg_view('blog/ownerblock', array('entity' => $vars['entity']));
if($vars['entity']){
	$deletepage = elgg_view('output/confirmlink',array(	
				'href' => $vars['url'] . "action/blog/delete?blogpost=" . $vars['entity']->getGUID(),
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("blog:delete:confirm"),
				'class' => "Action_Button Disabled")); 
}else{
	$deletepage = "";
}

//INSERT EXTRAS HERE
$extras = elgg_view('categories',$vars);
if (!empty($extras)) $extras = '<div class="SidebarBox">' .$cat .'<div class="ContentWrapper">'. $extras . '</div></div>';
		  
?>

<?php
//construct the form
$form_body = <<<EOT
<div id="LayoutCanvas_2ColumnRHS_Sidebar">
	{$ownerblock}
	<div class="SidebarBox">
			<h3>{$publish}</h3>

		<div class="ContentWrapper">

			<div class="blog_access">
				<p>{$privacy}: {$access_input}</p>
			</div>
			<div class="current_access">{$user_default_access}<br /><b>{$user_acl}</b></span></div>
		</div>
		
		<div class="ContentWrapper">
			<div class="allow_comments">
					<label><input type="checkbox" name="comments_select"  {$comments_on_switch} /> {$allowcomments}</label>
			</div>
		</div>
			
		<div class="ContentWrapper">

			<div class="publish_blog">
				<div class="publish_controls">
					{$draftsaved}: <span id="draftSavedCounter">{$never}</span>
					<a href="#" onclick="javascript:saveDraft(false);return false;">{$savedraft}</a>
				</div>

				{$submit_input}
			</div>
		</div>
	</div>

	{$extras}
	{$container}
</div>

<!-- main content -->
<div id="LayoutCanvas_2ColumnRHS_MainArea">


<div id="Page_Header">
	<div class="Page_Header_Title">
		{$page_title}
	</div>

	<div class="Page_Header_Options">

	<a class="Action_Button" onclick="javascript:saveDraft(true);return true;">{$preview}</a>
	{$deletepage}
	</div><div class='clearfloat'></div>
</div>




<div class="ContentWrapper">
EOT;

if (isset($vars['entity']))
	$entity_hidden = elgg_view('input/hidden', array('internalname' => 'blogpost', 'value' => $vars['entity']->getGUID()));
else
   	$entity_hidden = '';

$form_body .= <<<EOT
	<p><label>$title_label</label><br />$title_textbox</p>
	<p class='longtext_editarea'>
            $text_textarea
	</p>
	<div id='excerpt_editarea'>
			<label>$excerpt_label</label><br />$excerpt_desc $excerpt_counter<br />
            $excerpt_textarea        
	</div>
	<p><label>$tag_label</label><br />$tag_input</p>
	<p>$entity_hidden</p>
	$show_excerpt_field
</div>
</div>
<div class="clearfloat"></div>
EOT;

//display the form
echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'blogPostForm'));
?>

<script type="text/javascript">
	setInterval( "saveDraft(false)", 120000);
	function saveDraft(preview) {
		temppreview = preview;

		if (typeof(tinyMCE) != 'undefined') {
			tinyMCE.triggerSave();
		}
		
		var drafturl = "<?php echo $vars['url']; ?>mod/blog/savedraft.php";
		var temptitle = $("input[name='blogtitle']").val();
		var tempbody = $("textarea[name='blogbody']").val();
		var temptags = $("input[name='blogtags']").val();
		
		var postdata = { blogtitle: temptitle, blogbody: tempbody, blogtags: temptags };
		
		$.post(drafturl, postdata, function() {
			var d = new Date();
			var mins = d.getMinutes() + '';
			if (mins.length == 1) mins = '0' + mins;
			$("span#draftSavedCounter").html(d.getHours() + ":" + mins);
			if (temppreview == true) {
				$("form#blogPostForm").attr("action","<?php echo $vars['url']; ?>mod/blog/preview.php");
				$("input[name='submit']").click();
				//$("form#blogPostForm").submit();
				//document.blogPostForm.submit();
			}
		});
				
	}
	
</script>
