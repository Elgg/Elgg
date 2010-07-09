<?php 
/**
 * URL -> ECML converter for supported 3rd party services.
 * 
 * Tries to automatically detect which site/ecml to use.
 * Will highlight the correct one.
 * If can find none, will show an error.
 * Lets users click to force an ECML keyword, but will display a warning.
 * 
 */

$internal_name = elgg_get_array_value('internal_name', $vars);

$keywords = ecml_get_keywords();
elgg_sort_3d_array_by_value($keywords, 'name');

$keyword_js_array = array();
$keywords_html = "<ul class='ecml_web_service_list'>";
// include support for standard ECML so you can get previews and validation.
//$keyword_html = '<li class="ecml_web_service"><a class="ecml">Generic ECML</a></li>';

foreach ($keywords as $i => $v) {
	if (!isset($v['type']) || $v['type'] != 'web_service') {
		unset ($keywords[$i]);
		continue;
	}
	
	// make sure the classname doens't have invalid chars.
	$class = str_replace(array('.', ','), '_', $i);
	$keyword_js_array[] = $class;
	
	$keywords_html .= "<li class='ecml_web_service'><a class=\"$class link\">{$v['name']}</a></li>";
}

$keywords_html .= "</ul>";

$keywords_js = json_encode($keyword_js_array);

$input = elgg_view('input/text', array(
	'internalid' => 'web_services_resource'
));

$embed = elgg_view('input/button', array(
	'name' => 'buggy',
	'internalid' => 'embed_submit',
	'type' => 'button',
	'value' => elgg_echo('embed:embed'),
	'class' => 'submit_button embed_disabled',
	'disabled' => TRUE
));

echo '<p>' . elgg_echo('ecml:embed:instructions') . '</p>';

echo $keywords_html;

echo "<h2 class='embed_content_section instructions hidden'><a class='ecml_embed_instructions link'>Instructions</a></h2><div id='embed_ecml_keyword_help' class='hidden'></div>";

echo "<h2 class='embed_content_section'>URL</h2><div id='embed_ecml_url'>".$input."</div>";

echo "<p>ECML: <span id='ecml_code'></span></p>";

echo "<h2 class='embed_content_section preview hidden'><a class='ecml_embed_preview link'>Preview</a></h2><div id='ecml_preview' class='hidden'></div>";

echo $embed;

?>

<script type="text/javascript">

$(document).ready(function() {
	$('a.ecml_embed_instructions.link').click(function() {
		elgg_slide_toggle($(this), '#facebox', '#embed_ecml_keyword_help');
	});
	
	$('a.ecml_embed_preview.link').click(function() {
		elgg_slide_toggle($(this), '#facebox', '#ecml_preview');
	});
});

$(function() {
	var keywords = <?php echo $keywords_js; ?>;
	var selected_service = '';
	var manual_selected_service = false;
	var embed_button = $('#embed_submit');
	var embed_resource_input = $('#web_services_resource');

	// counter for paused input to try to validate/generate a preview.
	var rest_timeout_id = null;
	var rest_min_time = 750;
	var embed_generate_ecml_url = '<?php echo $vars['url']; ?>pg/ecml_generate';
	var embed_ecml_keyword_help_url = '<?php echo $vars['url']; ?>pg/ecml/';
	var internal_name = '<?php echo addslashes($internal_name); ?>';
	
	var web_services_ecml_update = function() {
		if (rest_timeout_id) {
			clearTimeout(rest_timeout_id);
		}
		
		if (manual_selected_service) {
			// fire off preview attempt
			rest_timeout_id = setTimeout(generate_ecml, rest_min_time);
			return true;
		}
		
		var value = $(this).val();
		var value_length = value.length;
		 
		if (value_length > 0) {
			embed_button.removeAttr('disabled').removeClass('embed_disabled').addClass('embed_warning');
		} else {
			embed_button.attr('disabled', 'disabled').addClass('embed_disabled');
		}
		
		if (value_length < 5) {
			$('.ecml_web_service a').removeClass('selected');
			return true;
		}

		// if this is ECML check that it's a valid keyword
		if (value.substr(0, 1) == '[') {
			var keyword = value.split(' ')[0];
			keyword = keyword.replace('[', '');
			keyword = keyword.replace(']', '');
			if ($.inArray(keyword, keywords) >= 0) { 
				select_service(keyword);
			}
		} else {
			// check if any of the ECML keywords exist in the
			// string and select that service.
			$(keywords).each(function(index, keyword) {
				if (value.indexOf(keyword) >= 0) {
					select_service(keyword);
					return true;
				}
			});
		}

		// fire off a preview attempt
		if (selected_service) {
			$('.embed_content_section.preview').removeClass('hidden'); // reveal preview link/panel
			rest_timeout_id = setTimeout(generate_ecml, rest_min_time);
		}
	};

	var select_service = function(service) {
		if ($.inArray(service, keywords) === false) {
			return false;
		}
		
		selected_service = service;
		$('.ecml_web_service a').removeClass('selected');
		$('.ecml_web_service a.' + service).addClass('selected');
		$('.embed_content_section.instructions').removeClass('hidden'); // reveal instructions link/panel
	}

	// pings back core to generate the ecml.
	// includes a status, ecml code, and the generated html. 
	var generate_ecml = function() {
		if (!selected_service) {
			return false;
		}
		
		var resource = embed_resource_input.val();
		var post_data = {'service': selected_service, 'resource': resource};

		$.post(embed_generate_ecml_url, post_data, function(data) {
			if (data.status == 'success') {
				// show previews and update embed code.
				$('#ecml_preview').html(data.html);
				$('#ecml_code').html(data.ecml);
				$('body').data('elgg_embed_ecml', data.ecml);

				// set status for embed button
				embed_button.removeAttr('disabled').removeClass('embed_disabled').removeClass('embed_warning').addClass('embed_good');
			}
		}, 'json');
	}

	// auto guess the service.
	embed_resource_input.keyup(web_services_ecml_update);

	// manually specify the service
	$('.ecml_web_service a').click(function() {
		select_service($(this).attr('class').split(' ')[0]);
		manual_selected_service = true;

		// show tip
		var help_url = embed_ecml_keyword_help_url + selected_service + '?ajax=true';
		$('#embed_ecml_keyword_help').load(help_url);
	});
	
	$('#embed_submit').click(function() {
		// insert the ECML
		// if the ECML input is empty, insert the resource.
		if (!(content = $('body').data('elgg_embed_ecml'))) {
			// @todo display an error?
			content = embed_resource_input.val();
		}

		elggEmbedInsertContent(content, internal_name);
		
		return false;
	});
});


</script>