<?php
/**
 * Hacked up friends picker that needs to be replaced
 */

elgg_load_js('elgg.friendspicker');
elgg_load_js('jquery.easing');

?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('notifications:subscriptions:title'); ?>
		</h3>
	</div>
	<p>
		<?php echo elgg_echo('notifications:subscriptions:description'); ?>
	</p>
<?php

// Get friends and subscriptions
$friends = get_user_friends(elgg_get_logged_in_user_guid(),'',9999,0);
		
global $NOTIFICATION_HANDLERS;
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$subsbig[$method] = elgg_get_entities_from_relationship(array('relationship' => 'notify' . $method, 'relationship_guid' => elgg_get_logged_in_user_guid(), 'types' => 'user', 'limit' => 99999));
}
		
$subs = array();
foreach($subsbig as $method => $big) {
	if (is_array($subsbig[$method]) && sizeof($subsbig[$method])) {
		foreach($subsbig[$method] as $u) { 
			$subs[$method][] = $u->guid;
		}
	}
}

// Let the system know that the friends picker is in use
global $pickerinuse;
$pickerinuse = true;
$chararray = elgg_echo('friendspicker:chararray');

// Initialise name
if (!isset($vars['name'])) {
	$name = "friend";
} else {
	$name = $vars['name'];
}
		
// Initialise values
if (!isset($vars['value'])) {
	$vars['value'] = array();
} else {
	if (!is_array($vars['value'])) {
		$vars['value'] = (int) $vars['value'];
		$vars['value'] = array($vars['value']);
	}
}

// Initialise whether we're calling back or not
if (isset($vars['callback'])) {
	$callback = $vars['callback'];
} else {
	$callback = false;
}
		
// We need to count the number of friends pickers on the page.
if (!isset($vars['friendspicker'])) {
	global $friendspicker;
	if (!isset($friendspicker)) {
		$friendspicker = 0;
	}
	$friendspicker++;
} else {
	$friendspicker = $vars['friendspicker'];
}

$users = array();
$activeletters = array();
		
// Are we displaying form tags and submit buttons?
// (If we've been given a target, then yes! Otherwise, no.)
if (isset($vars['formtarget'])) {
	$formtarget = $vars['formtarget'];
} else {
	$formtarget = false;
}
		
// Sort users by letter
if (is_array($friends) && sizeof($friends)) {
	foreach($friends as $user) {
				
		$letter = elgg_substr($user->name,0,1);
		$letter = elgg_strtoupper($letter);
		if (!elgg_substr_count($chararray,$letter)) {
			$letter = "*";
		}
		if (!isset($users[$letter])) {
			$users[$letter] = array();
		}
		$users[$letter][$user->guid] = $user;
	}
}

if (!$callback) {
			
?>

<div class="friends-picker-main-wrapper">

<?php

	if (isset($vars['content'])) {
		echo $vars['content'];
	}
	
?>

	<div id="friends-picker_placeholder<?php echo $friendspicker; ?>">

<?php
	
}
	
if (!isset($vars['replacement'])) {
	
	if ($formtarget) {
?>
<?php //@todo JS 1.8: no ?>
	<script language="text/javascript">
		$(function() { // onload...do
		$('#collectionMembersForm<?php echo $friendspicker; ?>').submit(function() {
			var inputs = [];
			$(':input', this).each(function() {
				if (this.type != 'checkbox' || (this.type == 'checkbox' && this.checked != false)) {
					inputs.push(this.name + '=' + escape(this.value));
				}
			});
			jQuery.ajax({
				type: "POST",
				data: inputs.join('&'),
				url: this.action,
				success: function(){
					$('a.collectionmembers<?php echo $friendspicker; ?>').click();
				}

			});
			return false;
		})
	})

	</script>

<?php

	}

	echo elgg_view('notifications/subscriptions/jsfuncs',$vars);
		
?>

	<div class="friends-picker-wrapper">
	<div id="friends-picker<?php echo $friendspicker; ?>">
		<div class="friends-picker-container">
<?php

	// Initialise letters
	$letter = elgg_substr($chararray,0,1);
	$letpos = 0;
	$chararray .= '*';
	while (1 == 1) {
?>
			<div class="panel" title="<?php echo $letter; ?>">
				<div class="wrapper">
					<h3><?php echo $letter; ?></h3>					
					
<?php

		if (isset($users[$letter])) {
			ksort($users[$letter]);
?>

<table id="notificationstable" cellspacing="0" cellpadding="4" border="0" width="100%">
<tr>
	<td>&nbsp;</td>
<?php
			$i = 0;
			foreach($NOTIFICATION_HANDLERS as $method => $foo) {
				if ($i > 0) {
					echo "<td class='spacercolumn'>&nbsp;</td>";
				}
?>
	<td class="<?php echo $method; ?>togglefield"><?php echo elgg_echo('notification:method:'.$method); ?></td>
<?php
				$i++;
			}
?>
	<td>&nbsp;</td>
</tr>

<?php

			if (is_array($users[$letter]) && sizeof($users[$letter]) > 0) {
				foreach($users[$letter] as $friend) {
					if ($friend instanceof ElggUser ) {
				
						if (!in_array($letter,$activeletters)) {
							$activeletters[] = $letter;
						}
				
						$method = array();
						$fields = '';
						$i = 0;
				
						foreach($NOTIFICATION_HANDLERS as $method => $foo) {
							if (isset($subs[$method]) && in_array($friend->guid,$subs[$method])) {
								$checked[$method] = 'checked="checked"';
							} else {
								$checked[$method] = '';
							}
							if ($i > 0) {
								$fields .= "<td class='spacercolumn'>&nbsp;</td>";
							}
							$fields .= <<< END
<td class="{$method}togglefield">
<a border="0" id="{$method}{$friend->guid}" class="{$method}toggleOff" onclick="adjust{$method}_alt('{$method}{$friend->guid}');">
<input type="checkbox" name="{$method}subscriptions[]" id="{$method}checkbox" onclick="adjust{$method}('{$method}{$friend->guid}');" value="{$friend->guid}" {$checked[$method]} /></a></td>
END;
							$i++;
						}
?>

<tr>
	<td class="namefield">
		<a href="<?php echo $friend->getURL(); ?>">
<?php
			echo elgg_view_entity_icon($friend, 'tiny', array('override' => true));
?>
		</a>
		<p class="namefieldlink">
			<a href="<?php echo $friend->getURL(); ?>"><?php echo $friend->name ?></a>
		</p>
	</td>
	
<?php echo $fields; ?>

<td>&nbsp;</td>
</tr>


<?php
					}
				}
			}

?>
</table>

<?php
		}

?>
			
				</div>
			</div>
<?php			
		$letpos++;
		if ($letpos == elgg_strlen($chararray)) {
			break;
		}
		$letter = elgg_substr($chararray,$letpos,1);
	}
		
?>
		</div>		
	</div>
	</div>
	
<?php
} else {
	echo $vars['replacement']; 
}
if (!$callback) {

?>
			
	</div>
</div>


<?php

}

?>
<?php
if (!isset($vars['replacement'])) {
?>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
		// initialise picker
		$("div#friends-picker<?php echo $friendspicker; ?>").friendsPicker(<?php echo $friendspicker; ?>);
</script>
<script type="text/javascript">
	$(function () {
	// manually add class to corresponding tab for panels that have content
<?php
	if (sizeof($activeletters) > 0) {
		$chararray .= "*";
		foreach($activeletters as $letter) {
			$tab = elgg_strpos($chararray, $letter) + 1;
?>
	$("div#friends-picker-navigation<?php echo $friendspicker; ?> li.tab<?php echo $tab; ?> a").addClass("tabHasContent");
<?php
		}
	}

?>
	});
</script>

<?php

}

?>

	<div class="elgg-form-footer-alt">
<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));
?>
	</p>
</div>
