<?php
return array(
	'twitter_api' => 'Servicios de Twitter',

	'twitter_api:requires_oauth' => 'Los servicios de Twitter requieren que el plugin de la biblioteca OAuth Librares est&eacute; habilitada.',

	'twitter_api:consumer_key' => 'Clave p&uacute;blica',
	'twitter_api:consumer_secret' => 'Clave privada',

	'twitter_api:settings:instructions' => 'Debes obtener una clave p&uacute;blica y privada en <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Llena el formulario para la nueva aplicaci&oacute;n. Selecciona "Browser" como el nuevo tipo de aplicaci&oacute;n y "Read & Write" para el tipo de acceso. La URL de callback es is %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Enlaza tu cuenta %s con Twitter.",
	'twitter_api:usersettings:request' => "Primero debes <a href=\"%s\">autorizar</a> %s para acceder a tu cuenta de Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "No pueds desenlazar tu cuent6a con Twitter debido a que no has provisto una direcci&oacute;n de email y contrase&ntilde;a. <a href=\"%s\">Proveer una ahora</a>.",
	'twitter_api:authorize:error' => 'No se pudo autorizar a Twitter.',
	'twitter_api:authorize:success' => 'El acceso a Twiiter ha sido autorizado.',

	'twitter_api:usersettings:authorized' => "Has sido autorizado %s para acceder a tu cuenta de Twitter: @%s.",
	'twitter_api:usersettings:revoke' => 'Click <a href="%s">aqu&iacute;</a> para revocar el acceso.',
	'twitter_api:usersettings:site_not_configured' => 'Un administrador debe configurar Twitter primero para que esta caracter&iacute;stica est&eacute; disponible.',

	'twitter_api:revoke:success' => 'El acceso a Twitter ha sido rebocado.',

	'twitter_api:post_to_twitter' => "¿Enviar los posts de los usuarios a Twitter?",

	'twitter_api:login' => 'Permitir a los usuarios ingresar con Twitter?',
	'twitter_api:new_users' => '&iquest;Permitir a los usuarios nuevos a inscribirse con su cuenta de Twitter, incluso si el registro de usuario está desactivada?',
	'twitter_api:login:success' => 'Debes haber inicado sesi&oacute;n.',
	'twitter_api:login:error' => 'No se puede iniciar sesion en Twitter.',
	'twitter_api:login:email' => "Debes ingresar una direcci&oacute;n de email v&aacute;lida para la nueva cuenta %s.",

	'twitter_api:invalid_page' => 'P&aacute;gina inv&aacute;lida',

	'twitter_api:deprecated_callback_url' => 'La dirección URL de devolución de llamada para la API de Twitter ha cambiado a %s.  Por favor consulta al administrador como cambiarla.',

	'twitter_api:interstitial:settings' => 'Configuraciones',
	'twitter_api:interstitial:description' => 'Est&aacute;s casi listo para usar %s! Necesitamos algunos detalles adicionales antes de continuar. Esto es opcional, pero habilitar&aacute; el inicio de sesi&oacute;n si Twitter deshabilita tu cuenta.',

	'twitter_api:interstitial:username' => 'Este es tu nombre de usuario y no puede ser cambiado. Si configuras una contrase&ntilde;a, puedes usar tu nombre de usuario o email para iniciar sesi&oacute;n.',

	'twitter_api:interstitial:name' => 'Este es el nombre con el que ser&aacute;s visible y la gente podr&aacute; contactarte.',

	'twitter_api:interstitial:email' => 'Tu direcci&oacute;n de email. Esta no ser&aacute; vista por los dem&aacute;s usuarios.',

	'twitter_api:interstitial:password' => 'Una contrase&ntilde;a si Twitter decide cancelar tu cuenta.',
	'twitter_api:interstitial:password2' => 'Repite la contrase&ntilde;.',

	'twitter_api:interstitial:no_thanks' => 'No, gracias',

	'twitter_api:interstitial:no_display_name' => 'Debes tener un nombre para mostrar.',
	'twitter_api:interstitial:invalid_email' => 'Debes ingresar una direcci&oacute;n de eamil v&aacute;lida o dejar vacio.',
	'twitter_api:interstitial:existing_email' => 'esta direcci&oacute;n de email ya est6aacute; usada en este sitio.',
	'twitter_api:interstitial:password_mismatch' => 'Las contrase&ntilde;as no coinciden.',
	'twitter_api:interstitial:cannot_save' => 'No se pudieron guardar los detalles de la cuenta.',
	'twitter_api:interstitial:saved' => 'Configuraci&oacute;n de la cuenta guardada',
);
