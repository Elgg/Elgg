<script>
/*
Part of multi file uploader
*/

var number_of_files = 1;

// wait for the DOM to be loaded 
$(document).ready(function() { 
	// bind 'file_form' and provide a simple callback function 
	$('#file_form').submit(function() {
		$('#form_container').hide();
		$('#form_message').html('<div class="contentWrapper"><?php echo $vars['submit_message']; ?></div>');
		$('#form_message').show();
		$(this).ajaxSubmit(function(response_message) {
			$('#form_message').html('<div class="contentWrapper">'+response_message+'</div>');
		}); 
		
		return false;
	});
});

function file_generate_bit(bit_label,prefix,classname,field_type,field_size) {
	bit = document.createElement('p');
    label = document.createElement('label');
    textnode = document.createTextNode(bit_label);
    label.appendChild(textnode);
    el = document.createElement('br');
    label.appendChild(el);
    el = document.createElement('input');
    el.type = field_type;
    el.className = classname;
    if (field_size > 0) {
    	el.size = field_size;
    }
    el.name = prefix+number_of_files;
    el.value = "";
    label.appendChild(el);
    bit.appendChild(label);
    
    return bit;    
}

function file_addtoform() {
    var o,el;
    o = document.getElementById('option_container');
    title_label = "<?php echo elgg_echo("title"); ?>";
    bit = file_generate_bit(title_label,'title_','input-text','text',0);
    o.appendChild(bit);
    file_label = "<?php echo elgg_echo("file:file"); ?>";
    bit = file_generate_bit(file_label,'upload_','input-file','file',30);
    o.appendChild(bit);
    
    number_of_files++;
    document.file_form.number_of_files.value = number_of_files;
}
</script>