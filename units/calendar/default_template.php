<?php

	global $template;
	global $template_definition;

	$template_definition[] = array(
									'id' => 'dateboxvertical',
									'name' => gettext("Date input box (vertical)"),
									'description' => gettext("Date input box for event entry."),
									'glossary' => array(
															'{{name1}}' => gettext("start date"),
															'{{name2}}' => gettext("end date"),
															'{{contents1}}' => gettext("The start date data itself"),
															'{{contents2}}' => gettext("The end date data itself")
														)
									);
									
$template['dateboxvertical'] = <<< END
<div class="databox_vertical">
	<table width="100%" class="fileTable" align="center" style="margin-bottom: 3px">
		<tr>
			<td class="fieldname" width="10%">
				<p><b>{{name1}}</b></p>
			</td>
			<td class="fieldname" width="60%">
				<p><b>{{name2}}</b></p>
			</td>
		</tr>
		<tr>
			<td>
				<p>{{contents1}}</p>
			</td>
			<td>
				<p>{{contents2}}</p>
			</td>
		</tr>		
	</table>
</div>
	
END;

$template_definition[] = array(
									'id' => 'dayofweekbox',
									'name' => gettext("Day of week display box"),
									'description' => gettext("Day of week display box for the monthly calendar view"),
									'glossary' => array(
															'{{contents}}' => gettext("The day of the week to be displyed")
														)
									);
									
$template['dayofweekbox'] = <<< END
<div class="dayofweekbox">
	<b>{{contents}}</b>
</div>

END;


$template_definition[] = array(
									'id' => 'activemonthbox',
									'name' => gettext("Active month and year display box"),
									'description' => gettext("The active month and year that the user is viewing events for in the calendar view"),
									'glossary' => array(
															'{{contents}}' => gettext("The current month and year that is to be displayed")
														)
									);

$template['activemonthbox'] = <<< END
	<div class="activemonthbox">
		<b>{{contents}}</b>
	</div>
END;

$template_definition[] = array(
									'id' => 'monthlynavigationbox',
									'name' => gettext("Monthly navigation box for calendar navigation"),
									'description' => gettext("Links to allow the user to navigate to the month before and after the month that they are currently viewing"),
									'glossary' => array(
															'{{monthbefore}}' => gettext("The month that comes before the month that the user is currently viewing"),
															'{{monthafter}}' => gettext("The month that comes after the month that the user is currently viewing")
														)
									);

$template['monthlynavigationbox'] = <<< END
<div class="monthlynavigationbox">
	<table width="40%">
		<tr>
			<td width="50%">
				{{monthbefore}}
			</td>
			<td/>
			<td width="50%">
				{{monthafter}}
			</td>
		</tr>
	</table>
</div>
END;

$template_definition[] = array(
									'id' => 'datelink',
									'name' => gettext("Link to display events on a given date"),
									'description' => gettext("A link to allow the user to view all of the events on a given date that appears in the top left corner of a calendar cell"),
									'glossary' => array(
															'{{url}}' => gettext("The url to the page where the events will be displayed"),
															'{{date}}' => gettext("The date to be displayed in the calendar cell")
														)
									);
									
$template['datelink'] = <<< END
<div class="datelink">
	<a href="{{url}}">{{date}}</a>
</div>
END;

$template_definition[] = array(
									'id' => 'publicevent',
									'name' => gettext("Link to display a public event"),
									'description' => gettext("A link to allow the user to view an event with public access. This link will be color coded so that a public event can be differentiated from another class of event."),
									'glossary' => array(
															'{{title}}' => gettext("The title of the event"),
															'{{url}}' => gettext("The url to the page where the event details can be viewed")
														)
									);

$template['publicevent'] = <<< END
<div class="publicevent">
	<a href="{{url}}">{{title}}</a>
</div>
END;

$template_definition[] = array(
									'id' => 'privateevent',
									'name' => gettext("Link to display a private event"),
									'description' => gettext("A link to allow the user to view an event with private access. This link will be color coded so that a private event can be differentiated from another class of event."),
									'glossary' => array(
															'{{title}}' => gettext("The title of the event"),
															'{{url}}' => gettext("The url to the page where the event details can be viewed")
														)
									);

$template['privateevent'] = <<< END
<div class="privateevent">
	<a href="{{url}}">{{title}}</a>
</div>
END;

$template_definition[] = array(
									'id' => 'loggedinevent',
									'name' => gettext("Link to display a logged in event"),
									'description' => gettext("A link to allow the user to view an event with logged in access. This link will be color coded so that a logged in event can be differentiated from another class of event."),
									'glossary' => array(
															'{{title}}' => gettext("The title of the event"),
															'{{url}}' => gettext("The url to the page where the event details can be viewed")
														)
									);

$template['loggedinevent'] = <<< END
<div class="loggedinevent">
	<a href="{{url}}">{{title}}</a>
</div>
END;
						
$template_definition[] = array(
									'id' => 'privateeventlegend',
									'name' => gettext("A legend entry for private events"),
									'description' => gettext("A legend entry that will allow the user identify a color with a private event"),
									'glossary' => array(
															'{{content}}' => gettext("The message that will be displayed to the user in the legend")
														)
									);

$template['privateeventlegend'] = <<< END
<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="privateeventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>

END;

$template_definition[] = array(
									'id' => 'publiceventlegend',
									'name' => gettext("A legend entry for public events"),
									'description' => gettext("A legend entry that will allow the user identify a color with a public event"),
									'glossary' => array(
															'{{content}}' => gettext("The message that will be displayed to the user in the legend")
														)
									);

$template['publiceventlegend'] = <<< END
<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="publiceventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>

END;

$template_definition[] = array(
									'id' => 'loggedineventlegend',
									'name' => gettext("A legend entry for logged in events"),
									'description' => gettext("A legend entry that will allow the user identify a color with a logged in event"),
									'glossary' => array(
															'{{content}}' => gettext("The message that will be displayed to the user in the legend")
														)
									);

$template['loggedineventlegend'] = <<< END
<table width="30%" style="font-size: 15px;">
	<tr>
		<td width="25px">
			<div class="loggedineventlegend">
				&nbsp;
			</div>
		</td>
		<td>
			{{content}}
		</td>
	</tr>
</table>

END;

	$template['css'] .= <<< END
.dayofweekbox {
	font-size: 11px;
	font-color: #FFFFFF;
	margin-left: 2px;
	margin-right: 2px;
}

.activemonthbox {
	font-size: 15px;
	font-color: #FFFFFF;
	margin-right: 5px;
}

.monthlynavigationbox{
	font-family: verdana;
	padding: 2px 0 0 0;
	margin: 0;
	font-size: 10px;
	font-weight: normal;
	color: #000;
}

.monthlynavigationbox a {
	font-weight:bold;
}

.monthlynavigationbox a:hover {
	text-decoration: underline;
}

.datelink a {
	font-weight:bold;
}

.datelink a:hover {
	text-decoration: underline;
}

.publicevent a {
	color: #66CC33;
}

.publicevent a:hover {
	text-decoration: underline;
}

.privateevent a {
	color: #CC3300;
}

.privateevent a:hover {
	text-decoration: underline;
}

.loggedinevent a {
	color: #3366FF;
}

.loggedinevent a:hover {
	text-decoration: underline;
}

.publiceventlegend {
	background-color: #66CC33;
	width: 25px;	
}

.privateeventlegend {
	background-color: #CC3300;
	width: 25px;	
}

.loggedineventlegend {
	background-color: #3366FF;
	width: 25px;	
}

END;

?>