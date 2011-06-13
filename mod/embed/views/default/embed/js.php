<?php
/**
 * JS interface for inserting content into the active editor.
 * 
 * @todo: 1.8 JS: Ugh
 */

?>
$(function() { 
	$('a[rel*=facebox]').facebox();

	// Only apply the .live binding after facebox has been displayed
	$(document).bind('afterReveal.facebox', function() {
	
		// fire off the ajax upload
		$('#file_embed_upload').live('submit', function() {
			var options = {
				success: function(data) {
					var info = jQuery.parseJSON(data);

					if (info.status == 'success') {
						$('.popup .content').load(elgg.get_site_url() + 'embed/embed?active_section=file');
					} else {
						$('.popup .content').find('form').prepend('<p>' + info.message + '</p>');
					}
				}
			};
			$(this).ajaxSubmit(options);
			return false;
		});
	});
});
function elggEmbedInsertContent(content, textAreaId) {
	content = ' ' + content + ' ';
	
	// default input.
	// if this doesn't match anything it won't add anything.
	$('#' + textAreaId).val($('#' + textAreaId).val() + ' ' + content);
	
	<?php
		// This view includes the guts of the function to do the inserting.
		// Anything that overrides input/longtext with its own editor
		// needs to supply its own function here that inserts
		// content into textAreaId.
		// See TinyMCE as an example.
		
		// for compatibility
		// the old on that was overriden.
		echo elgg_view('embed/addcontentjs');
		
		// the one you should extend.
		//@todo This should fire a plugin hook or event, not require extending the view! >_<
		echo elgg_view('embed/custom_insert_js');
	?>
	
	
	$.facebox.close();
}

/*
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *
 *  jQuery(document).ready(function() {
 *    jQuery('a[rel*=facebox]').facebox()
 *  })
 *
 *  <a href="#terms" rel="facebox">Terms</a>
 *    Loads the #terms div in the box
 *
 *  <a href="terms.html" rel="facebox">Terms</a>
 *    Loads the terms.html page in the box
 *
 *  <a href="terms.png" rel="facebox">Terms</a>
 *    Loads the terms.png image in the box
 *
 *
 *  You can also use it programmatically:
 *
 *    jQuery.facebox('some html')
 *
 *  The above will open a facebox with "some html" as the content.
 *
 *    jQuery.facebox(function($) {
 *      $.get('blah.html', function(data) { $.facebox(data) })
 *    })
 *
 *  The above will show a loading screen before the passed function is called,
 *  allowing for a better ajaxy experience.
 *
 *  The facebox function can also display an ajax page or image:
 *
 *    jQuery.facebox({ ajax: 'remote.html' })
 *    jQuery.facebox({ image: 'dude.jpg' })
 *
 *  Want to close the facebox?  Trigger the 'close.facebox' document event:
 *
 *    jQuery(document).trigger('close.facebox')
 *
 *  Facebox also has a bunch of other hooks:
 *
 *    loading.facebox
 *    beforeReveal.facebox
 *    reveal.facebox (aliased as 'afterReveal.facebox')
 *    init.facebox
 *
 *  Simply bind a function to any of these hooks:
 *
 *   $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
 *
 */
(function($) {
$.facebox = function(data, klass) {
	$.facebox.loading()

	if (data.ajax) fillFaceboxFromAjax(data.ajax)
	else if (data.image) fillFaceboxFromImage(data.image)
	else if (data.div) fillFaceboxFromHref(data.div)
	else if ($.isFunction(data)) data.call($)
	else $.facebox.reveal(data, klass)
}

/*
   * Public, $.facebox methods
   */

$.extend($.facebox, {
	settings: {
	opacity      : 0.7,
	overlay      : true,
	loadingImage : '<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif',
	closeImage   : '<?php echo elgg_get_site_url(); ?>_graphics/spacer.gif',
	imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
	faceboxHtml  : '\
	<div id="facebox" class="hidden"> \
	<div class="popup"> \
		<div class="body"> \
			<div class="footer"> \
				<a href="#" class="close"> \
					<img src="<?php echo elgg_get_site_url(); ?>_graphics/spacer.gif" title="close" class="close_image" width="22" height="22" border="0" /> \
				</a> \
				</div> \
				<div class="content"> \
				</div> \
		</div> \
	</div> \
	</div>'
	},

	loading: function() {
	init()
	if ($('#facebox .loading').length == 1) return true
	showOverlay()

	$('#facebox .content').empty()
	$('#facebox .body').children().hide().end().
		append('<div class="loading"><br /><br /><img src="'+$.facebox.settings.loadingImage+'"/><br /><br /></div>')

	$('#facebox').css({
		top:	getPageScroll()[1] + (getPageHeight() / 10),
		// Curverider addition (pagewidth/2 - modalwidth/2)
		left: ((getPageWidth() / 2) - ($('#facebox').width() / 2))
	}).show()

	$(document).bind('keydown.facebox', function(e) {
		if (e.keyCode == 27) $.facebox.close()
		return true
	})
	$(document).trigger('loading.facebox')
	},

	reveal: function(data, klass) {
	$(document).trigger('beforeReveal.facebox')
	if (klass) $('#facebox .content').addClass(klass)
	$('#facebox .content').append(data)

	setTimeout(function() {
		$('#facebox .loading').remove();
		$('#facebox .body').children().fadeIn('slow');
		$('#facebox').css('left', $(window).width() / 2 - ($('#facebox').width() / 2));
		$(document).trigger('reveal.facebox').trigger('afterReveal.facebox');
		}, 100);

	//$('#facebox .loading').remove()
	//$('#facebox .body').children().fadeIn('slow')
	//$('#facebox').css('left', $(window).width() / 2 - ($('#facebox').width() / 2))
	//$(document).trigger('reveal.facebox').trigger('afterReveal.facebox')

	},

	close: function() {
	$(document).trigger('close.facebox')
	return false
	}
})

/*
   * Public, $.fn methods
   */

// Curverider addition
/*
	$.fn.wait = function(time, type) {
		time = time || 3000;
		type = type || "fx";
		return this.queue(type, function() {
			var self = this;
			setTimeout(function() {
				//$(self).queue();
				$('#facebox .loading').remove();
			}, time);
		});
	};
*/

$.fn.facebox = function(settings) {
	init(settings)

	function clickHandler() {
	$.facebox.loading(true)

	// support for rel="facebox.inline_popup" syntax, to add a class
	// also supports deprecated "facebox[.inline_popup]" syntax
	var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
	if (klass) klass = klass[1]

	fillFaceboxFromHref(this.href, klass)
	return false
	}

	return this.click(clickHandler)
}

/*
   * Private methods
   */

// called one time to setup facebox on this page
function init(settings) {
	if ($.facebox.settings.inited) return true
	else $.facebox.settings.inited = true

	$(document).trigger('init.facebox')
	/* makeCompatible() */

	var imageTypes = $.facebox.settings.imageTypes.join('|')
	$.facebox.settings.imageTypesRegexp = new RegExp('\.' + imageTypes + '$', 'i')

	if (settings) $.extend($.facebox.settings, settings)
	$('body').append($.facebox.settings.faceboxHtml)

	var preload = [ new Image(), new Image() ]
	preload[0].src = $.facebox.settings.closeImage
	preload[1].src = $.facebox.settings.loadingImage
	preload.push(new Image())

/*
	$('#facebox').find('.b:first, .bl, .br, .tl, .tr').each(function() {
	preload.push(new Image())
	preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
	})
*/

	$('#facebox .close').click($.facebox.close)
	$('#facebox .close_image').attr('src', $.facebox.settings.closeImage)
}

// getPageScroll() by quirksmode.com
function getPageScroll() {
	var xScroll, yScroll;
	if (self.pageYOffset) {
	yScroll = self.pageYOffset;
	xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
	yScroll = document.documentElement.scrollTop;
	xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
	yScroll = document.body.scrollTop;
	xScroll = document.body.scrollLeft;
	}
	return new Array(xScroll,yScroll)
}

	// Adapted from getPageSize() by quirksmode.com
	function getPageHeight() {
	var windowHeight
	if (self.innerHeight) {	// all except Explorer
	windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
	windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
	windowHeight = document.body.clientHeight;
	}
	return windowHeight
	}

	// Curverider addition
	function getPageWidth() {
	var windowWidth;
	if( typeof( window.innerWidth ) == 'number' ) {
		windowWidth = window.innerWidth; //Non-IE
	} else if( document.documentElement && ( document.documentElement.clientWidth ) ) {
		windowWidth = document.documentElement.clientWidth; //IE 6+ in 'standards compliant mode'
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		windowWidth = document.body.clientWidth; //IE 4 compatible
	}
	return windowWidth
	}



// Backwards compatibility
/*
function makeCompatible() {
	var $s = $.facebox.settings

	$s.loadingImage = $s.loading_image || $s.loadingImage
	$s.closeImage = $s.close_image || $s.closeImage
	$s.imageTypes = $s.image_types || $s.imageTypes
	$s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
}
*/

// Figures out what you want to display and displays it
// formats are:
//     div: #id
//   image: blah.extension
//    ajax: anything else
function fillFaceboxFromHref(href, klass) {
	// div
	if (href.match(/#/)) {
	var url    = window.location.href.split('#')[0]
	var target = href.replace(url,'')
	$.facebox.reveal($(target).clone().show(), klass)

	// image
	} else if (href.match($.facebox.settings.imageTypesRegexp)) {
	fillFaceboxFromImage(href, klass)
	// ajax
	} else {
	fillFaceboxFromAjax(href, klass)
	}
}

function fillFaceboxFromImage(href, klass) {
	var image = new Image()
	image.onload = function() {
	$.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
	}
	image.src = href
}

function fillFaceboxFromAjax(href, klass) {
	$.get(href, function(data) { $.facebox.reveal(data, klass) })
}

function skipOverlay() {
	return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
}

function showOverlay() {
	if (skipOverlay()) return

	if ($('facebox_overlay').length == 0)
	$("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

	$('#facebox_overlay').hide().addClass("facebox_overlayBG")
	.css('opacity', $.facebox.settings.opacity)
	/* .click(function() { $(document).trigger('close.facebox') }) */
	.fadeIn(400)
	return false
}

function hideOverlay() {
	if (skipOverlay()) return

	$('#facebox_overlay').fadeOut(400, function(){
	$("#facebox_overlay").removeClass("facebox_overlayBG")
	$("#facebox_overlay").addClass("facebox_hide")
	$("#facebox_overlay").remove()
	})

	return false
}

/*
   * Bindings
   */

$(document).bind('close.facebox', function() {
	$(document).unbind('keydown.facebox')
	$('#facebox').fadeOut(function() {
	$('#facebox .content').removeClass().addClass('content')
	hideOverlay()
	$('#facebox .loading').remove()
	})
})




	// Curverider addition
	$(window).resize(function(){
	//alert("resized");

	$('#facebox').css({
		top:	getPageScroll()[1] + (getPageHeight() / 10),
		left: ((getPageWidth() / 2) - 365)
	})


	});





})(jQuery);
