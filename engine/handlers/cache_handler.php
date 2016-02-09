<?php
// if 500 is returned, output will not be shown to the user.
header('Content-Type: application/javascript;charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
?>
//<script>
!function(){
	if (!window.__cache_handler) {
		window.__cache_handler = 1;
		var msg = 'Update your web server configuration (e.g. .htaccess). See docs/admin/upgrading';
		console.log(msg);
		var html = '<h3 style="color:red">' + msg + '</h3>';
		document.querySelector('body').insertAdjacentHTML('afterbegin', html);
	}
}();
