<?php

	/**
	 * Elgg standard tools drop down
	 * This will be populated depending on the plugins active - only plugin navigation will appear here
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */
	 
		$menu = get_register('menu');
		
		//var_export($menu);

		if (is_array($menu) && sizeof($menu) > 0) {
		
?>
<style type="text/css">


#tools_menu, #tools_menu ul{
	margin:0;
	padding:0;
	display:inline;
	float:left;
	list-style-type:none;
	list-style-position:outside;
	/*position:relative;
	line-height:1.5em;*/
}

#tools_menu {
	margin:0pt 15px 0pt 5px;
}

#tools_menu a {
	display:block;
	padding:3px;
	color:white;
	text-decoration:none;
}

#tools_menu a:hover {
	background-color:#4690d6;
}
.tools_menu_on {
	background:#4690d6;
}

#tools_menu li {
	float:left;
	position:relative;
}

#tools_menu ul {
	position:absolute;
	display:none;
	top:24px;
	border-top:1px solid #333;
	border-bottom:1px solid #333;
	
	width:134px;
}

#tools_menu ul a {
	background:white;
	border-left:1px solid #333;
	border-right:1px solid #333;
	color:#4690d6;
	padding:6px;
}

#tools_menu ul a:hover {
	color:white;
}

#tools_menu li ul a {
	width:120px;
	height:auto;
	float:left;
}

#tools_menu ul ul{
	top:auto;
}	

#tools_menu li ul ul {
	left:120px;
	margin:0px 0 0 13px;
}

#tools_menu li:hover ul ul, #tools_menu li:hover ul ul ul, #tools_menu li:hover ul ul ul ul{
	display:none;

}
#tools_menu li:hover ul, #tools_menu li li:hover ul, #tools_menu li li li:hover ul, #tools_menu li li li li:hover ul{
	display:block;
}
</style>
<ul id="tools_menu">
    <li><a href="#">Tools</a>
      <ul>
      <?php

			foreach($menu as $item) {
    			
    			echo "<li><a href=\"{$item->value}\">" . $item->name . "</a></li>";
    			
			} 
				
     ?>
      </ul>
    </li>
</ul>

<?php

		}

?>

<script type="text/javascript">
function tools_menu(){
$(" #tools_menu ul ").css({display: "none"}); // opera + ie fix
$(" #tools_menu li").hover(function(){
		$(this).find('ul:first').css("display", "none");
        $(this).find('ul:first').slideDown("fast");
		$(this).parent().parent().parent().find("#tools_menu a").addClass('tools_menu_on');
		
		},function(){
		$(this).find('ul:first').slideUp("fast");
		$(this).parent().parent().parent().find("#tools_menu a").removeClass('tools_menu_on');
		});
}

 
 
 $(document).ready(function(){					
	tools_menu();
});
</script>

