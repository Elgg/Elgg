<?php
/**
 * Elgg Messages CSS
 * 
 * @package ElggMessages
 */
?>

.messages-container {
	min-height: 200px;
}
.message.unread a {
	color: #d40005;
}
.messages-buttonbank {
	text-align: right;
}
.messages-buttonbank input {
	margin-left: 10px;
}

/*** message metadata ***/
.messages-owner {
	float: left;
	width: 20%;
	margin-right: 2%;
}
.messages-subject {
	float: left;
	width: 55%;
	margin-right: 2%;
}
.messages-timestamp {
	float: left;
	width: 14%;
	margin-right: 2%;
}
.messages-delete {
	float: left;
	width: 5%;
}
/*** topbar icon ***/
.messages-new {
	color: white;
	background-color: red;
	
	-webkit-border-radius: 10px; 
	-moz-border-radius: 10px;
	border-radius: 10px;
	
	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	
	position: absolute;
	text-align: center;
	top: 0px;
	left: 26px;
	min-width: 16px;
	height: 16px;
	font-size: 10px;
	font-weight: bold;
}
