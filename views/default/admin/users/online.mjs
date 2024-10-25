import 'jquery';
import Ajax from 'elgg/Ajax';

var onlineInterval = setInterval(function(){
	var ajax = new Ajax(false);
	
	ajax.path('admin/online_users_count', {
		showErrorMessages: false,
		success: function (result) {
			$('.elgg-menu-admin-header .elgg-menu-item-online-users-count .elgg-badge').html('<span title="' + result.number + '">' + result.formatted + '</span>');
		},
		error: function() {
			clearInterval(onlineInterval);
		}
	});
}, 60 * 1000);
