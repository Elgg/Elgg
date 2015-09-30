<?php
return array(
	'admin:users:unvalidated' => 'Sem validação',
	
	'email:validate:subject' => "%s por favor, confirme seu endereço de email para %s!",
	'email:validate:body' => "Olá %s,

Antes de você iniciar o uso da %, você deve confirmar seu endereço de email.

Por favor, confirme seu endereço de email clicando no link abaixo:

%s

Se você não conseguir clicar no link, copie e cole o endereço para o navegador (browser) manualmente.

%s
%s
",
	'email:confirm:success' => "Você já confirmou seu endereço de email!",
	'email:confirm:fail' => "Seu endereço de email não pode ser confirmado...",

	'uservalidationbyemail:emailsent' => "Email enviado para <em>%s</em>",
	'uservalidationbyemail:registerok' => "Para ativar sua conta, por favor confirme seu endereço de email clicando no link que foi enviado para endereço de email que você registrou.",
	'uservalidationbyemail:login:fail' => "Sua conta não foi validada por isso ocorrem as falhas ao tentar entrar.  Outro email de validação foi enviado para você.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Nenhuma pessoa não validado.',

	'uservalidationbyemail:admin:unvalidated' => 'Usuários não validados',
	'uservalidationbyemail:admin:user_created' => 'Registrados %s',
	'uservalidationbyemail:admin:resend_validation' => 'Validação re-enviada',
	'uservalidationbyemail:admin:validate' => 'Validar',
	'uservalidationbyemail:confirm_validate_user' => 'Validar %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Re-envio email de validação para %s?',
	'uservalidationbyemail:confirm_delete' => 'Apagar %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Validar usuários marcados?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Re-envio validação para usuários marcados?',
	'uservalidationbyemail:confirm_delete_checked' => 'Apagar usuários marcados?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Usuários desconhecidos',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Não foi possível validar usuário.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Não foi possível validar todos usuários marcados.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Não foi possível apagar usuário.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Não foi possível apagar todos usuários marcados.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Não foi possível re-enviar solicitação de validação.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Não foi possível re-enviar solicitação de validação para usuários marcados.',

	'uservalidationbyemail:messages:validated_user' => 'Usuário validado.',
	'uservalidationbyemail:messages:validated_users' => 'Todos usuários marcados validados.',
	'uservalidationbyemail:messages:deleted_user' => 'Usuário apagado.',
	'uservalidationbyemail:messages:deleted_users' => 'Todos usuários marcados apagados.',
	'uservalidationbyemail:messages:resent_validation' => 'Solicitação de validação re-enviada.',
	'uservalidationbyemail:messages:resent_validations' => 'Solicitação de validação re-enviada para todos usuários marcados.'

);
