(function($){
	var updateUpDown = function(sortable){
		$('div:not(.ui-sortable-helper)', sortable)
			.removeClass('first').removeClass('last')
			.find('.up, .down').removeClass('disabled').end()
			.filter(':first').addClass('first').find('.up').addClass('disabled').end().end()
			.filter(':last').addClass('last').find('.down').addClass('disabled').end().end();
	};
	
	var moveUpDown = function(){
		var link = $(this),
			dl = link.parents('div'),
			prev = dl.prev('div'),
			next = dl.next('div');
	
		if(link.is('.up') && prev.length > 0)
			dl.insertBefore(prev);
	
		if(link.is('.down') && next.length > 0)
			dl.insertAfter(next);
	
		updateUpDown(dl.parent());
	};
	
	var addItem = function(){
		var sortable = $(this).parents('.ui-sortable');
		var options = '<span class="options"><a class="up">up</a><a class="down">down</a></span>';
		var tpl = '<dl class="sort"><dt>{name}' + options + '</dt><dd>{desc}</dd></dl>';
		var html = tpl.replace(/{name}/g, 'Dynamic name :D').replace(/{desc}/g, 'Description');
	
		sortable.append(html).sortable('refresh').find('a.up, a.down').bind('click', moveUpDown);
		updateUpDown(sortable);
	};
	
	//var emptyTrashCan = function(item){
	//	item.remove();
	//};
	
	var sortableChange = function(e, ui){
		if(ui.sender){
			var w = ui.element.width();
			ui.placeholder.width(w);
			ui.helper.css("width",ui.element.children().width());
		}
	};
	
	var sortableUpdate = function(e, ui){
		//if(ui.element[0].id == 'trashcan'){
		//	emptyTrashCan(ui.item);
		//} else {
			updateUpDown(ui.element[0]);
			if(ui.sender)
				updateUpDown(ui.sender[0]);
		//}
	};
	
	// toggle content panel
	var togglePanel = function(e) {
		var targetContent = $('div.panelcontent', this.parentNode.parentNode);
			if (targetContent.css('display') == 'none') {
				targetContent.slideDown(400);
				$(this).html('[-]');
				$(this).css("color","#cccccc");
			} else {
				targetContent.slideUp(400);
				$(this).html('[+]');
				$(this).css("color","#666666");
			}
		return false;
	};
	
	// toggle edit panel
	var editpanel = function(e) {
		var targetEditPanel = $('div.editpanel', this.parentNode.parentNode);
			if (targetEditPanel.css('display') == 'none') {
				targetEditPanel.slideDown(400);
			} else {
				targetEditPanel.slideUp(400);
			}
			$(this).toggleClass("active"); return false;
		return false;
	};
	
	$(document).ready(function(){
				
		// toggle edit panel 
		$('a.button_editpanel').bind('click', editpanel);
		
		// toggle content panel
		$('a.togglepanel').bind('click', togglePanel);

		var els = ['#mainContent', '#sidebar_right'];
		var $els = $(els.toString());
		
		//$('h2', $els.slice(0,-1)).append('<span class="options"><a class="add">add</a></span>');
		//$('dt', $els).append('<span class="options"><a class="up">up</a><a class="down">down</a></span>');
		
		//$('a.add').bind('click', addItem);
		//$('a.up, a.down').bind('click', moveUpDown);
		
		//$els.each(function(){
		//	updateUpDown(this);
		//});
		
		$els.sortable({
			items: '> div',
			handle: 'h1',
			cursor: 'move',
			//revert: true,
			cursorAt: { top: 10, left: 100 },
			//opacity: 0.8,
			containment: 'parent',
			appendTo: 'body',
			placeholder: 'placeholder',
			connectWith: els,
			start: function(e,ui) {
				ui.helper.css("width", ui.item.width());
			},
			change: sortableChange,
			update: sortableUpdate
		});
	});
	
	$(window).bind('load',function(){
		setTimeout(function(){
			$('#overlay').fadeOut(function(){
				$('body').css('overflow', 'auto');
			});
		}, 450);
	});
})(jQuery);