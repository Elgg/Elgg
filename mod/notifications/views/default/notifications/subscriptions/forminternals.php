<?php
/**
 * Hacked up friends picker that needs to be replaced
 *
 * @uses $vars['user'] ElggUser
 */

/* @var ElggUser $user */
$user = elgg_extract('user', $vars);

elgg_load_js('elgg.friendspicker');
elgg_load_js('jquery.easing');

$body = elgg_format_element('p', [], elgg_echo('notifications:subscriptions:description'));

// Get friends and subscriptions
$friends = $user->getFriends(['limit' => 0]);
		
$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
foreach($NOTIFICATION_HANDLERS as $method => $foo) {
	$subsbig[$method] = elgg_get_entities_from_relationship([
		'relationship' => 'notify' . $method,
		'relationship_guid' => $user->guid,
		'type' => 'user',
		'limit' => false,
	]);
}
		
$subs = [];
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
$name = elgg_extract('name', $vars, 'friend');
		
// Initialise values
if (!isset($vars['value'])) {
	$vars['value'] = [];
} else {
	if (!is_array($vars['value'])) {
		$vars['value'] = (int) $vars['value'];
		$vars['value'] = [$vars['value']];
	}
}

// Initialise whether we're calling back or not
$callback = elgg_extract('callback', $vars, false);
		
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

$users = [];
$activeletters = [];
		
// Are we displaying form tags and submit buttons?
// (If we've been given a target, then yes! Otherwise, no.)
$formtarget = elgg_extract('formtarget', $vars, false);
		
// Sort users by letter
if (is_array($friends) && sizeof($friends)) {
	foreach($friends as $friend) {
				
		$letter = elgg_substr($friend->name,0,1);
		$letter = elgg_strtoupper($letter);
		if (!elgg_substr_count($chararray,$letter)) {
			$letter = '*';
		}
		if (!isset($users[$letter])) {
			$users[$letter] = [];
		}
		$users[$letter][$friend->guid] = $friend;
	}
}

$placeholder_data = '';

$replacement = elgg_extract('replacement', $vars);
if (empty($replacement)) {
	
	if ($formtarget) {
		//@todo JS 1.8: no
		$placeholder_data .= <<< END
		<script>
		require(['jquery'], function($) {
			$(function () {
				$('#collectionMembersForm{$friendspicker}').submit(function() {
					var inputs = [];
					$(':input', this).each(function() {
						if (this.type != 'checkbox' || (this.type == 'checkbox' && this.checked != false)) {
							inputs.push(this.name + '=' + escape(this.value));
						}
					});
					$.ajax({
						type: "POST",
						data: inputs.join('&'),
						url: this.action,
						success: function(){
							$('a.collectionmembers{$friendspicker}').click();
						}
	
					});
					return false;
				});
			});
		});
		</script>
END;
	}

	$placeholder_data .= elgg_view('notifications/subscriptions/jsfuncs', $vars);

	$friendspicker_container = '';

	// Initialise letters
	$letter = elgg_substr($chararray,0,1);
	$letpos = 0;
	$chararray .= '*';
	while (1 == 1) {

		$wrapper = elgg_format_element('h3', [], $letter);

		if (isset($users[$letter])) {
			ksort($users[$letter]);

			$top_row = elgg_format_element('td', [], '&nbsp;');

			$i = 0;
			foreach($NOTIFICATION_HANDLERS as $method => $foo) {
				if ($i > 0) {
					$top_row .= elgg_format_element('td', ['class' => 'spacercolumn'], '&nbsp;');
				}

				$top_row .= elgg_format_element([
					'#tag_name' => 'td',
					'class' => "{$method}togglefield",
					'#text' => elgg_echo("notification:method:{$method}"),
				]);
				$i++;
			}
			$top_row .= elgg_format_element('td', [], '&nbsp;');
			
			$table_data = elgg_format_element('tr', [], $top_row);

			if (is_array($users[$letter]) && sizeof($users[$letter]) > 0) {
				foreach($users[$letter] as $friend) {
					if (!($friend instanceof ElggUser)) {
						continue;
					}
				
					if (!in_array($letter,$activeletters)) {
						$activeletters[] = $letter;
					}
			
					$method = [];
					$fields = '';
					$i = 0;
			
					foreach($NOTIFICATION_HANDLERS as $method => $foo) {
						$checked = false;
						if (isset($subs[$method]) && in_array($friend->guid,$subs[$method])) {
							$checked = true;
						}
						
						if ($i > 0) {
							$fields .= elgg_format_element('td', ['class' => 'spacercolumn'], '&nbsp;');
						}
							
						$toggle_input = elgg_view('input/checkbox', [
							'name' => "{$method}subscriptions[]",
							'id' => "{$method}checkbox",
							'value' => $friend->guid,
							'checked' => $checked,
							'onclick' => "adjust{$method}('{$method}{$friend->guid}');",
							'default' => false,
						]);
						$toggle_link = elgg_view('output/url', [
							'href' => false,
							'text' => $toggle_input,
							'id' => "{$method}{$friend->guid}",
							'class' => "{$method}toggleOff",
							'border' => '0',
							'onclick' => "adjust{$method}_alt('{$method}{$friend->guid}');",
						]);
							
						$fields .= elgg_format_element('td', ['class' => "{$method}togglefield"], $toggle_link);
						$i++;
					}
											
					$name_field = elgg_view('output/url', [
						'href' => $friend->getURL(),
						'text' => elgg_view_entity_icon($friend, 'tiny', ['use_hover' => false]),
					]);
					$name_field .= elgg_format_element([
						'#tag_name' => 'p',
						'class' => 'namefieldlink',
						'#text' => elgg_view('output/url', [
							'href' => $friend->getURL(),
							'text' => $friend->name,
						]),
					]);
					
					$friend_row = elgg_format_element('td', ['class' => 'namefield'], $name_field);
					$friend_row .= $fields;
					$friend_row .= elgg_format_element('td', [], '&nbsp;');
					
					$table_data .= elgg_format_element('tr', [], $friend_row);
				}
			}

			$table_attributes = [
				'id' => 'notificationstable',
				'border' => '0',
				'cellspacing' => '0',
				'cellpadding' => '4',
				'width' => '100%',
			];

			$wrapper .= elgg_format_element('table', $table_attributes, $table_data);
		}

		$panel = elgg_format_element('div', ['class' => 'wrapper'], $wrapper);

		$friendspicker_container .= elgg_format_element('div', ['class' => 'panel', 'title' => $letter], $panel);

		$letpos++;
		if ($letpos == elgg_strlen($chararray)) {
			break;
		}
		$letter = elgg_substr($chararray,$letpos,1);
	}
		
	$friendspicker_container = elgg_format_element([
		'#tag_name' => 'div',
		'class' => 'friends-picker-container',
		'#text' => $friendspicker_container,
	]);
	$friendspicker_wrapper = elgg_format_element([
		'#tag_name' => 'div',
		'id' => "friends-picker{$friendspicker}",
		'#text' => $friendspicker_container,
	]);
		
	$placeholder_data .= elgg_format_element('div', ['class' => 'friends-picker-wrapper'], $friendspicker_wrapper);
} else {
	$placeholder_data .= $replacement;
}

if ($callback) {
	$body .= $placeholder_data;
} else {

	$main = '';
	if (isset($vars['content'])) {
		$main .= $vars['content'];
	}
	
	$main .= elgg_format_element('div', ['id' => "friends-picker_placeholder{$friendspicker}"], $placeholder_data);
	$body .= elgg_format_element('div', ['class' => 'friends-picker-main-wrapper'], $main);
}

echo elgg_view_module('info', elgg_echo('notifications:subscriptions:title'), $body);

if (empty($replacement)) {
	//@todo JS 1.8: no ?>
<script>
require(['elgg', 'jquery'], function(elgg, $) {
	$(function () {
		// initialise picker
		$("div#friends-picker<?php echo $friendspicker; ?>").friendsPicker(<?php echo $friendspicker; ?>);

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
});
</script>
	<?php
}
