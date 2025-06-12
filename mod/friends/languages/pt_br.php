<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s solicitou amizade com %s",
	'relationship:friendrequest:pending' => "%s quer ser seu amigo",
	'relationship:friendrequest:sent' => "Você solicitou amizade com %s",
	
	// plugin settings
	'friends:settings:request:description' => "Por padrão, qualquer usuário pode adicionar outro como amigo, funciona como seguir a atividade de outro usuário.
Ao ativar as solicitações de amizade, quando o usuário A quiser ser amigo do usuário B, o usuário B precisará aprovar a solicitação. Após a aprovação, A será amigo de B e B será amigo de A (amizade mútua).",
	'friends:settings:request:label' => "Ativar solicitações de amizade",
	'friends:settings:request:help' => "Os usuários precisam aprovar solicitações de amizade; as amizades se tornam bidirecionais",
	
	'friends:owned' => "Amigos de %s",
	'friend:add' => "Adicionar Amigo",
	'friend:remove' => "Desfazer Amizade",
	'friends:menu:request:status:pending' => "Solicitação de Amizade Pendente",

	'friends:add:successful' => "Você adicionou %s como amigo com sucesso.",
	'friends:add:duplicate' => "Você já é amigo de %s.",
	'friends:add:failure' => "Não foi possível adicionar %s como amigo.",
	'friends:request:successful' => 'Uma solicitação de amizade foi enviada para %s.',
	'friends:request:error' => 'Ocorreu um erro ao processar sua solicitação de amizade com %s.',

	'friends:remove:successful' => "Você removeu %s da sua lista de amigos com sucesso.",
	'friends:remove:no_friend' => "Você e %s não são amigos.",
	'friends:remove:failure' => "Não foi possível remover %s da sua lista de amigos.",

	'friends:none' => "Ainda sem Amigos(as)... =(",
	'friends:of:owned' => "Pessoas que adicionaram %s como amigo(a)",

	'friends:of' => "Amigos de",
	
	'friends:request:pending' => "Solicitações de Amizade Pendentes",
	'friends:request:pending:none' => "Nenhuma Solicitação de Amizade Pendente encontrada.",
	'friends:request:sent' => "Solicitações de Amizade Enviadas",
	'friends:request:sent:none' => "Nenhuma Solicitação de Amizade foi Enviada.",
	
	'friends:num_display' => "Número de Amigos a Exibir",
	
	'widgets:friends:name' => "Amigos",
	'widgets:friends:description' => "Exibe alguns dos seus amigos.",
	
	'widgets:friends_of:name' => "Amigos de",
	'widgets:friends_of:description' => "Exibe quem te adicionou como amigo",
	
	'friends:notification:request:subject' => "%s quer ser seu amigo!",
	
	'friends:notification:request:decline:subject' => "%s recusou a sua solicitação de amizade",
	'friends:notification:request:decline:message' => "%s recusou a sua solicitação de amizade.",
	
	'friends:notification:request:accept:subject' => "%s aceitou sua a solicitação de amizade",
	'friends:notification:request:accept:message' => "%s aceitou sua a solicitação de amizade",
	
	'friends:action:friendrequest:revoke:fail' => "Ocorreu um erro ao cancelar a solicitação de amizade, por favor tente novamente",
	'friends:action:friendrequest:revoke:success' => "A solicitação de amizade foi cancelada com sucesso!",
	
	'friends:action:friendrequest:decline:fail' => "Ocorreu um erro ao recusar a solicitação de amizade, por favor tente novamente",
	'friends:action:friendrequest:decline:success' => "A solicitação de amizade foi recusada com sucesso!",
	
	'friends:action:friendrequest:accept:success' => "A solicitação de amizade foi aceita com sucesso!",
	
	// notification settings
	'friends:notification:settings:description' => 'Configurações padrão de notificações para usuários que você adiciona como amigo',
);
