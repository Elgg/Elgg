// elgg friendsPicker jquery plugin

// create a separate namespace for each picker - so we can have multiple pickers per page
var j = 0;

jQuery.fn.friendsPicker = function(settings) {

	settings = $.extend({ easeFunc: "easeOutExpo", easeTime: 1000, toolTip: false }, settings);
	 
	return this.each(function() {
	
		var container = $(this);
		container.addClass("friendsPicker");
		// set panelwidth manually as it's hidden initially
		var panelWidth = 685;

		// count the panels in the container
		var panelCount = container.find("div.panel").size();
		// calculate the width of all the panels lined up end-to-end
		var friendsPicker_containerWidth = panelWidth*panelCount;
		// specify width for the friendsPicker_container
		container.find("div.friendsPicker_container").css("width" , friendsPicker_containerWidth);
		
		// global variables for container.each function below
		var friendsPickerNavigationWidth = 0;
		var currentPanel = 1;
		
		// generate appropriate nav for each container
		container.each(function(i) {
			
			// generate Left and Right arrows
			$(this).before("<div class='friendsPickerNavigationL' id='friendsPickerNavigationL" + j + "'><a href='#'>Left</a><\/div>");
			$(this).after("<div class='friendsPickerNavigationR' id='friendsPickerNavigationR" + j + "'><a href='#'>Right</a><\/div>");
			
			// generate a-z tabs
			$(this).before("<div class='friendsPickerNavigation' id='friendsPickerNavigation" + j + "'><ul><\/ul><\/div>");
			
			$(this).find("div.panel").each(function(individualTabItemNumber) {
				
				$("div#friendsPickerNavigation" + j + " ul").append("<li class='tab" + (individualTabItemNumber+1) + "'><a href='#" + (individualTabItemNumber+1) + "'>" + $(this).attr("title") + "<\/a><\/li>");		
			});
			
			// tabs navigation
			$("div#friendsPickerNavigation" + j + " a").each(function(individualTabItemNumber) {
				// calc friendsPickerNavigationWidth by summing width of each li
				friendsPickerNavigationWidth += $(this).parent().width();
				// set-up individual tab clicks
				$(this).bind("click", function() {
					$(this).addClass("current").parent().parent().find("a").not($(this)).removeClass("current"); 
					var distanceToMoveFriendsPicker_container = - (panelWidth*individualTabItemNumber);
					currentPanel = individualTabItemNumber + 1;
					$(this).parent().parent().parent().next().find("div.friendsPicker_container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				});
			});
			
			// Right arow click function
			$("div#friendsPickerNavigationR" + j + " a").click(function() {
				if (currentPanel == panelCount) {
					var distanceToMoveFriendsPicker_container = 0;
					currentPanel = 1; 
					$(this).parent().parent().find("div.friendsPickerNavigation a.current").removeClass("current").parent().parent().find("a:eq(0)").addClass("current");
				} else {
					var distanceToMoveFriendsPicker_container = - (panelWidth*currentPanel);
					currentPanel += 1;
					$(this).parent().parent().find("div.friendsPickerNavigation a.current").removeClass("current").parent().next().find("a").addClass("current");
				};
				$(this).parent().parent().find("div.friendsPicker_container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				return false;
			});
			
			// Left arrow click function
			$("div#friendsPickerNavigationL" + j + " a").click(function() {
				if (currentPanel == 1) {
					var distanceToMoveFriendsPicker_container = - (panelWidth*(panelCount - 1));
					currentPanel = panelCount;
					$(this).parent().parent().find("div.friendsPickerNavigation a.current").removeClass("current").parent().parent().find("li:last a").addClass("current");
				} else {
					currentPanel -= 1;
					var distanceToMoveFriendsPicker_container = - (panelWidth*(currentPanel - 1));
					$(this).parent().parent().find("div.friendsPickerNavigation a.current").removeClass("current").parent().prev().find("a").addClass("current");
				};
				$(this).parent().parent().find("div.friendsPicker_container").animate({ left: distanceToMoveFriendsPicker_container}, settings.easeTime, settings.easeFunc);
				return false;
			});
						
				// apply 'current' class to currently selected tab link
				$("div#friendsPickerNavigation" + j + " a:eq(0)").addClass("current");
			
		});
		
		// manually add class to corresponding tab for panels that have content - needs to be automated eventually
		//$("div#friendsPickerNavigation"  + j + " li.tab3 a").addClass("tabHasContent");
		//$("div#friendsPickerNavigation"  + j + " li.tab6 a").addClass("tabHasContent");
		//$("div#friendsPickerNavigation"  + j + " li.tab9 a").addClass("tabHasContent");
		//$("div#friendsPickerNavigation"  + j + " li.tab17 a").addClass("tabHasContent");
		//$("div#friendsPickerNavigation"  + j + " li.tab22 a").addClass("tabHasContent");
		
		// generate link to 'all friends in collection' - removed for now
		//$("div#friendsPickerNavigation" + j).append("<div class='friendsPickerNavigationAll'><a href='#' >Collection members<\/a></div><br />");
		$("div#friendsPickerNavigation" + j).append("<br />");
		
		//$("div#friendsPickerNavigation" + j).hide();

		
		j++;
  });
};


