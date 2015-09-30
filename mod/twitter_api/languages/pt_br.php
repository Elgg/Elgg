<?php
return array(
	'twitter_api' => 'Servicos do Twitter',

	'twitter_api:requires_oauth' => 'Servicos do Twitter necessitam que o plugin da Biblioteca \'OAuth\' esteja habilitado.',

	'twitter_api:consumer_key' => 'Chave publica <i>(Consumer Key)</i>',
	'twitter_api:consumer_secret' => 'Chave privada <i>(Consumer Secret)</i>',

	'twitter_api:settings:instructions' => 'Voce deve obter uma chave pública <i>(Consumer Key)</i> e outra chave privada <i>(Consumer Secret)</i> no site do <a href="https://twitter.com/oauth_clients" target="_blank">Twitter</a>. Os demais campos são auto-explicativos, a única informação que você terá que fornecer será o endereco de retorno <i>(callback url)<i> que tem o formato de %stwitter_api/authorize</i></i>',

	'twitter_api:usersettings:description' => "Vincule sua conta %s com o Twitter.",
	'twitter_api:usersettings:request' => "Voce deve primeiro autorizar %s acessar sua conta do Twitter.",
	'twitter_api:usersettings:cannot_revoke' => "Você não pode desvincular sua conta do Twitter por que você não forneceu seu email ou sua senha.",
	'twitter_api:authorize:error' => 'Nao foi possivel autorização pelo Twitter.',
	'twitter_api:authorize:success' => 'Acesso pelo Twitter foi autorizado.',

	'twitter_api:usersettings:authorized' => "Você possui autorização %s para acessar sua conta do Twitter: @%s.",
	'twitter_api:usersettings:revoke' => 'Clique <a href="%s">aqui</a> para revogar o acesso.',
	'twitter_api:usersettings:site_not_configured' => 'Um administrador deve configurar primeiro o Twitter antes dele poder ser usado.',

	'twitter_api:revoke:success' => 'Acesso do Twitter foi revogado.',

	'twitter_api:post_to_twitter' => "Envia postagens no micro-blog dos usuários para Twitter?",

	'twitter_api:login' => 'Permite aos usuarios existentes que possuem conta no Twitter a autenticação para acesso atraves do uso do Twitter?',
	'twitter_api:new_users' => 'Permite aos novos usuarios a autenticação para acesso através do uso do Twitter mesmo se o registro manual estiver desabilitado?',
	'twitter_api:login:success' => 'Voce foi autenticado e entrou na rede.',
	'twitter_api:login:error' => 'Não foi possivel autenticação através do Twitter.',
	'twitter_api:login:email' => "Voce deve digitar um endereço de email válido para sua nova conta %s.",

	'twitter_api:invalid_page' => 'Página inválida',

	'twitter_api:deprecated_callback_url' => 'O endereço de retorno <i>(callback URL)</i>  foi alterado para o API do Twitter para %s.  Por favor solicite ao seu administrador que efetue a alteração.',

	'twitter_api:interstitial:settings' => 'Configure suas preferências',
	'twitter_api:interstitial:description' => 'Você está próximo de usar %s!  São necessários poucos detalhes antes que você continue. Eles são opcionais mas permitiram que o login aconteça mesmo que o login pelo Twitter não esteja disponível ou você decida desvincular sua conta.',

	'twitter_api:interstitial:username' => 'Este é seu nome de usuário. Ele não pode ser alterado.  Se você definir uma senha você poder´autilizar seu login ou endereço de email para se autenticar na rede.',

	'twitter_api:interstitial:name' => 'Este será o nome que as pessoas usarão para interagir com você.',

	'twitter_api:interstitial:email' => 'Seu endereço de email Por padrão, as pessoas não conseguiram visualizar este email.',

	'twitter_api:interstitial:password' => 'Um senha para que o login aconteça mesmo que o login pelo Twitter não esteja disponível ou você decida desvincular sua conta.',
	'twitter_api:interstitial:password2' => 'Digite a mesma senha, novamente.',

	'twitter_api:interstitial:no_thanks' => 'Não obrigado',

	'twitter_api:interstitial:no_display_name' => 'Você deve ter um nome de apresentação para as pessoas.',
	'twitter_api:interstitial:invalid_email' => 'Você deve digitar um endereço de email válido ou deixar em branco.',
	'twitter_api:interstitial:existing_email' => 'Este endereço de email já foi registrado no site.',
	'twitter_api:interstitial:password_mismatch' => 'Suas senhas não são iguais.',
	'twitter_api:interstitial:cannot_save' => 'Não foi possível salvar os detalhes da conta.',
	'twitter_api:interstitial:saved' => 'Detalhes da conta foram salvos.',
);
