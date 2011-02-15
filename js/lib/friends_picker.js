/*
	elgg friendsPicker plugin
	adapted from Niall Doherty's excellent Coda-Slider - http://www.ndoherty.com/coda-slider
 */


jQuery.fn.friendsPicker = function(iterator) {

	var settings; 
	settings = $.extend({ easeFunc: "easeOutExpo", easeTime: 1000, toolTip: false }, settings);

	return this.each(function() {

		var container = $(this);
		container.addClass("friends-picker");
		// set panelwidth manually as it's hidden initially - adjust this value for different themes/pagewidths 
		var panelWidth = 730;

		// count the panels in the container
		var panelCount = container.find("div.panel").size();
		// calculate the width of all the panels lined up end-to-end
		var friendsPicker_containerWidth = panelWidth*panelCount;
		// specify width for the friendsPicker_container
		container.find("div.friends-picker-container").css("width" , friendsPicker_containerWidth);

		// global variables for container.each function below
		var friendsPickerNavigationWidth = 0;
		var currentPanel = 1;

		// generate appropriate nav for each container
		container.each(function(i) {
			// generate Left and Right arrows
			$(this).before("<div class='friends-picker-navigation-l' id='friends-picker-navigation-l" + iterator + "'><a href='#'>Left</a><\/div>");
			$(this).after("<div class='friends-picker-navigation-r' id='friends-picker-navigation-r" + iterator + "'><a href='#'>Right</a><\/div>");

			// generate a-z tabs
			$(this).before("<div class='friends-picker-navigation' id='friends-picker-navigation" + iterator + "'><ul><\/ul><\/div>");
			$(this).find("div.panel").each(function(individualTabItemNumber) {
				$("div#friends-picker-navigation" + iterator + " ul").append("<li class='tab" + (individualTabItemNumber+1) + "'><a href='#" + (individualTabItemNumber+1) + "'>" + $(this).attr("title") + "<\/a><\/li>");		
			});

			// tabs navigation
			$("div#friends-picker-navigation" + iterator + " a").each(function(individualTabItemNumber) {
				// calc friendsPickerNavigationWidth by summing width of each li
				friendsPickerNavigationWidth += $(this).parent().width();
				// set-up individual tab clicks
				$(this).bind("click", function() {
					$(this).addClass("current").parent().parent().find("a").not($(this)).removeClass("current"); 
					var distanceToMoveFriendsPicker_container = - (panelWidth*individualTabItemNumber);
					currentPanel = individualTabItemNumber + 1;
					$(this).parent().parent().parent().next().find("div.friends-picker-container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				});
			});

			// Right arow click function
			$("div#friends-picker-navigation-r" + iterator + " a").click(function() {
				if (currentPanel == panelCount) {
					var distanceToMoveFriendsPicker_container = 0;
					currentPanel = 1; 
					$(this).parent().parent().find("div.friends-picker-navigation a.current").removeClass("current").parent().parent().find("a:eq(0)").addClass("current");
				} else {
					var distanceToMoveFriendsPicker_container = - (panelWidth*currentPanel);
					currentPanel += 1;
					$(this).parent().parent().find("div.friends-picker-navigation a.current").removeClass("current").parent().next().find("a").addClass("current");
				};
				$(this).parent().parent().find("div.friends-picker-container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				return false;
			});

			// Left arrow click function
			$("div#friends-picker-navigation-l" + iterator + " a").click(function() {
				if (currentPanel == 1) {
					var distanceToMoveFriendsPicker_container = - (panelWidth*(panelCount - 1));
					currentPanel = panelCount;
					$(this).parent().parent().find("div.friends-picker-navigation a.current").removeClass("current").parent().parent().find("li:last a").addClass("current");
				} else {
					currentPanel -= 1;
					var distanceToMoveFriendsPicker_container = - (panelWidth*(currentPanel - 1));
					$(this).parent().parent().find("div.friends-picker-navigation a.current").removeClass("current").parent().prev().find("a").addClass("current");
				};
				$(this).parent().parent().find("div.friends-picker-container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				return false;
			});

			// apply 'current' class to currently selected tab link
			$("div#friends-picker-navigation" + iterator + " a:eq(0)").addClass("current");
		});

		$("div#friends-picker-navigation" + iterator).append("<br />");		
	});
};