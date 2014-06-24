<?php
return array(
	'twitter_api' => 'Servizos de Twitter',

	'twitter_api:requires_oauth' => '«Servizos de Twitter» necesita o complemento «Bibliotecas de OAuth».',

	'twitter_api:consumer_key' => 'Chave de cliente',
	'twitter_api:consumer_secret' => 'Segredo de cliente',

	'twitter_api:settings:instructions' => 'Debe obter unha chave e un segredo de cliente de <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Complete o formulario de solicitude para un novo programa. Seleccione o tipo de programa «Navegador» e acceso de «Lectura e escritura». O URL de resposta é «%stwitter_api/authorize».',

	'twitter_api:usersettings:description' => "Ligue a súa conta %s con Twitter.",
	'twitter_api:usersettings:request' => "Primeiro debe <a href=\"%s\">autorizar</a> o acceso de %s á súa conta de Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Non pode desligar a súa conta de Twitter porque non indicou un enderezo de correo ou contrasina. <a href=\"%s\">Indíqueos</a>.",
	'twitter_api:authorize:error' => 'Non se concedeu autorización para acceder a Twitter.',
	'twitter_api:authorize:success' => 'Autorizouse o acceso a Twitter.',

	'twitter_api:usersettings:authorized' => "Concedeu a %s autorización para acceder á súa conta de Twitter: @%s.",
	'twitter_api:usersettings:revoke' => 'Prema <a href="%s">aquí</a> para revogar o acceso.',
	'twitter_api:usersettings:site_not_configured' => 'Un administrador debe configurar Twitter para poder usalo.',

	'twitter_api:revoke:success' => 'Revogouse o acceso a Twitter.',

	'twitter_api:post_to_twitter' => "Enviar as publicación do usuario na liña a Twitter?",

	'twitter_api:login' => 'Permitir aos usuarios acceder mediante Twitter?',
	'twitter_api:new_users' => 'Permitir que os novos usuarios se rexistren usando a súa conta de Twitter aínda cando o rexistro de novos usuarios estea desactivado?',
	'twitter_api:login:success' => 'Accedeu correctamente.',
	'twitter_api:login:error' => 'Non foi posíbel acceder mediante Twitter.',
	'twitter_api:login:email' => "Debe indicar un enderezo de correo electrónico válido para a súa nova conta, «%s».",

	'twitter_api:invalid_page' => 'Páxina non válida',

	'twitter_api:deprecated_callback_url' => 'O URL de resposta para a API de Twitter cambiou a %s. Pídalle ao administrador que o actualice.',

	'twitter_api:interstitial:settings' => 'Cambiar a configuración persoal',
	'twitter_api:interstitial:description' => 'Está a piques de poder usar %s! Só faltan algúns detalles. Trátase de detalles opcionais, pero permitiranlle acceder ao sitio aínda que Twitter non funcione ou en caso de que decida desligar a conta de Twitter da súa conta neste sitio.',

	'twitter_api:interstitial:username' => 'Este é o seu nome de usuario. Non pode cambialo. Se define un contrasinal, pode usar o nome de usuario ou o enderezo de correo electrónico para identificarse.',

	'twitter_api:interstitial:name' => 'Este é o nome que a xente verá cando interactúe con vostede.',

	'twitter_api:interstitial:email' => 'O seu enderezo de correo electrónico. De maneira predeterminada, o resto de usuarios non pode velo.',

	'twitter_api:interstitial:password' => 'Un contrasinal para acceder se Twitter non está a funcionar ou se decide desligar a conta de Twitter da conta deste sitio.',
	'twitter_api:interstitial:password2' => 'O mesmo contrasinal, outra vez.',

	'twitter_api:interstitial:no_thanks' => 'Non, grazas',

	'twitter_api:interstitial:no_display_name' => 'Ten que ter un nome para mostrar',
	'twitter_api:interstitial:invalid_email' => 'Debe escribir un enderezo de correo electrónico válido ou deixar o campo baleiro',
	'twitter_api:interstitial:existing_email' => 'O enderezo de correo electrónico indicado xa está rexistrado no sitio.',
	'twitter_api:interstitial:password_mismatch' => 'Os contrasinais non coinciden.',
	'twitter_api:interstitial:cannot_save' => 'Non foi posíbel gardar os detalles da conta.',
	'twitter_api:interstitial:saved' => 'Gardáronse os detalles da conta.',
);
