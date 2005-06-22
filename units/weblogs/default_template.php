<?php

	global $template;
	global $template_definition;
	
	$url = url;
	
	$template_definition[] = array(
									'id' => 'weblogpost',
									'name' => "Weblog Post",
									'description' => "A template for each weblog post.",
									'glossary' => array(
															'{{title}}' => 'Post title',
															'{{body}}' => 'The text of the post',
															'{{username}}' => 'The username of the person making the post',
															'{{usericon}}' => 'Their user icon',
															'{{fullname}}' => 'Their full name',
															'{{date}}' => 'The time and date of the post',
															'{{commentslink}}' => 'A link to any comments',
															'{{trackbackslink}}' => 'A link to any trackbacks',
															'{{comments}}' => 'A list of comments, if any'
														)
									);
	
	$template['weblogpost'] = <<< END

<table width="100%" style="border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000">
	<tr>
		<td valign="top" width="100" align="center">
			<div style="float:right">
				<p><br />
				<a href="{$url}{{username}}/weblog/">
					<img src="{$url}_icons/data/{{usericon}}" border="0" /><br />
					<a href="{$url}{{username}}/weblog/">{{fullname}}</a></p>
		</td>
		<td width="20">&nbsp;</td>
		<td valign="top">		
			<p>
				<br />
				<b>{{title}}</b>
			</p>
			<div style="margin-left: 20px">
				{{body}}
			</div>
			<p>
				<small>Posted by {{username}} at {{date}} | {{commentslink}} {{trackbackslink}}</small>
			</p>
			{{comments}}
		</td>
	</tr>
</table>

END;

	$template_definition[] = array(
									'id' => 'weblogcomments',
									'name' => "Weblog Comments",
									'description' => "A placeholder for weblog comments.",
									'glossary' => array(
															'{{comments}}' => 'The list of comments themselves'
														)
									);

	$template['weblogcomments'] = <<< END

<hr noshade="noshade" />
<h3>Comments</h3>
<ol>
{{comments}}
</ol>
	
END;

	$template_definition[] = array(
									'id' => 'weblogcomment',
									'name' => "Individual weblog comment",
									'description' => "A template for each individual weblog comment. (Displayed one after the other, embedded in the comment placeholder.)",
									'glossary' => array(
															'{{body}}' => 'Post body',
															'{{postedname}}' => 'The name of the person making the comment',
															'{{weblogcomment}}' => 'When the comment was posted'
														)
									);

	$template['weblogcomment'] = <<< END
	
<li>
	{{body}}
	<p style="border: 0px; border-bottom: 1px; border-style: solid; border-color: #000000">
		<small>Posted by {{postedname}} on {{posted}}</small>
	</p>
</li>
	
END;

	$template['css'] .= <<< END
	
		.weblogdateheader		{
			
										font-size: 0.6ems;
									}
	
END;
		
?>