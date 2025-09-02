<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'add:group:group' => "Criar um Novo Grupo",
	'groups:menu:sort:member' => "Data de Entrada",
	
	'groups' => "Grupos",
	'groups:owned' => "Grupos que Administro",
	'groups:owned:user' => 'Grupos que %s Administra',
	'groups:yours' => "Meus Grupos",
	'groups:user' => "Grupos de %s",
	'groups:all' => "Todos os Grupos",
	'groups:add' => "Criar um Novo Grupo",
	'groups:edit' => "Editar Grupo",
	'groups:edit:profile' => "Perfil",
	'groups:edit:images' => "Imagens",
	'groups:edit:access' => "Acesso",
	'groups:edit:tools' => "Ferramentas",
	'groups:edit:settings' => "Configurações",
	'groups:membershiprequests' => 'Gerenciar Solicitações de Entrada',
	'groups:membershiprequests:pending' => 'Gerenciar Solicitações de Entrada (%s)',
	'groups:invitedmembers' => "Gerenciar Convites",
	'groups:invitations' => 'Convites para o Grupo',
	'groups:invitations:pending' => 'Convites para o grupo (%s)',
	
	'relationship:invited' => '%2$s recebe um convite para entrar em %1$s',
	'relationship:membership_request' => '%s solicitou entrada no grupo %s',

	'groups:name' => 'Nome do Grupo',
	'groups:description' => 'Descrição',
	'groups:briefdescription' => 'Breve Descrição',
	'groups:interests' => 'Tags',
	'groups:members' => 'Membros do Grupo',

	'groups:members_count' => '%s Membro(s)',

	'groups:members:title' => 'Membros de %s',
	'groups:members:more' => "Ver Todos os Membros",
	'groups:membership' => "Permissões de Participação ao Grupo",
	'groups:content_access_mode' => "Acessibilidade do Conteúdo do Grupo",
	'groups:content_access_mode:warning' => "Atenção: alterar essa configuração não muda a permissão de acesso do conteúdo existente no Grupo.",
	'groups:content_access_mode:unrestricted' => "Irrestrito - O acesso depende das configurações de cada conteúdo",
	'groups:content_access_mode:membersonly' => "Apenas Membros - Não membros nunca poderão acessar o conteúdo do Grupo",
	'groups:owner' => "Proprietário",
	'groups:owner:placeholder' => "Buscar um Membro do Grupo",
	'groups:owner:warning' => "Atenção: se você alterar esse valor, não será mais o Proprietário deste Grupo.",
	'groups:widget:num_display' => 'Número de Grupos para exibir',
	'widgets:a_users_groups:name' => 'Participação a Grupos',
	'widgets:a_users_groups:description' => 'Exibe os Grupos dos quais você é Membro no seu Perfil',

	'groups:cantcreate' => 'Você não pode criar um Grupo. Apenas os Administradores podem!',
	'groups:cantedit' => 'Você não pode editar este Grupo',
	'groups:saved' => 'Grupo Salvo!',
	'groups:save_error' => 'Não foi possível salvar o Grupo...',
	'groups:featured' => 'Grupos em Destaque',
	'groups:featuredon' => '%s agora é um grupo em Destaque.',
	'groups:unfeatured' => '%s foi removido do Grupos em Destaque.',
	'groups:featured_error' => 'Grupo inválido.',
	'groups:nofeatured' => 'Nenhum Grupo em Destaque',
	'groups:joinrequest' => 'Solicitar Participação',
	'groups:join' => 'Participar do Grupo',
	'groups:leave' => 'Sair do Grupo',
	'groups:invite' => 'Convidar Membros',
	'groups:invite:title' => 'Convidar Membros para este Grupo',
	'groups:invite:friends:help' => 'Busque por um Membro pelo Nome ou através do Nome de Usuário e selecione-o na lista',
	'groups:invite:resend' => 'Reenviar os convites para Usuários já Convidados',
	'groups:invite:member' => 'Já é membro deste Grupo',
	'groups:invite:invited' => 'Já foi convidado para este Grupo',

	'groups:nofriendsatall' => 'Você não tem amigos para convidar!',
	'groups:group' => "Grupo",
	'groups:search:title' => "Buscar Grupos com '%s'",
	'groups:search:none' => "Nenhum Grupo correspondente foi encontrado",
	'groups:search_in_group' => "Buscar neste Grupo",
	'groups:acl' => "Grupo: %s",
	'groups:acl:in_context' => 'Membros do Grupo',

	'groups:notfound' => "Grupo não encontrado",
	
	'groups:requests:none' => 'Não há solicitações de entrada no momento.',

	'groups:invitations:none' => 'Não há convites no momento.',

	'groups:open' => "Grupo Aberto",
	'groups:closed' => "Grupo Fechado",
	'groups:search' => "Buscar Grupos",

	'groups:more' => 'Mais Grupos',
	'groups:none' => 'Nenhum Grupo',

	/**
	 * Access
	 */
	'groups:access:private' => 'Fechado - Usuários devem ser Convidados',
	'groups:access:public' => 'Aberto - Qualquer Usuário pode Entrar',
	'groups:access:group' => 'Apenas Membros do Grupo',
	'groups:closedgroup' => "A Participação neste Grupo é fechada.",
	'groups:closedgroup:request' => 'Para participar do Grupo, clique no menu "Solicitar Participação".',
	'groups:closedgroup:membersonly' => " Participação neste Grupo é Fechada e o Conteúdo é Acessível Apenas por Membros.",
	'groups:opengroup:membersonly' => "O conteúdo deste Grupo é Acessível Apenas por Membros.",
	'groups:opengroup:membersonly:join' => 'Para se tornar Membro, clique no menu "Participar do Grupo".',
	'groups:visibility' => 'Quem pode ver este Grupo?',
	'groups:content_default_access' => 'Acesso Padrão ao Conteúdo do Grupo',
	'groups:content_default_access:help' => 'Aqui você pode configurar o Acesso Padrão para novos conteúdos neste Grupo. O modo de conteúdo do Grupo pode impedir que a opção selecionada tenha efeito.',
	'groups:content_default_access:not_configured' => 'Sem Acesso Padrão configurado, ficará a cargo do Usuário',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Grupos',

	'groups:notitle' => 'Grupos devem ter um título',
	'groups:cantjoin' => 'Não é possível entrar no Grupo',
	'groups:cantleave' => 'Não foi possível sair do Grupo',
	'groups:removeuser' => 'Remover do Grupo',
	'groups:cantremove' => 'Não foi possível remover o Usuário do Grupo',
	'groups:removed' => '%s foi removido do Grupo com sucesso!',
	'groups:addedtogroup' => 'Usuário adicionado ao Grupo com sucesso!',
	'groups:joinrequestnotmade' => 'Não foi possível solicitar a Entrada no Grupo',
	'groups:joinrequestmade' => 'Solicitação de Participação Enviada!',
	'groups:joinrequest:exists' => 'Você já solicitou a Participação neste Grupo...',
	'groups:button:joined' => 'Participando',
	'groups:button:owned' => 'Administrador',
	'groups:joined' => 'Você entrou no Grupo com sucesso!',
	'groups:left' => 'Você saiu do Grupo com sucesso!',
	'groups:userinvited' => 'Usuário Convidado com sucesso!',
	'groups:usernotinvited' => 'Não foi possível convidar o Usuário...\'',
	'groups:useralreadyinvited' => 'O Usuário já foi Convidado',
	'groups:invite:message' => "Você foi Convidado(a) para este Grupo em %s",
	'groups:invite:subject' => "%s, você foi Convidado(a) para o Grupo %s!",
	'groups:joinrequest:revoke' => 'Revogar Participação',
	'groups:joinrequest:remove:check' => 'Tem certeza de que deseja remover esta solicitação de entrada?',
	'groups:invite:remove:check' => 'Tem certeza de que deseja remover este convite?',
	'groups:invite:body' => "%s convidou você para participar do Grupo '%s

Clique abaixo para ver seus convites:
%s\",",

	'groups:welcome:subject' => "Bem-vindo(a) ao Grupo %s!",
	'groups:welcome:body' => "Agora você é Membro do Grupo '%s'

Clique abaixo para começar a publicar!
%s\",",

	'groups:request:subject' => "%s solicitou entrada no grupo %s",
	'groups:request:body' => "%s solicitou entrada no grupo '%s'.

Clique abaixo para ver o perfil:
%s

Ou clique abaixo para ver as solicitações de entrada no grupo:
%s",

	'river:group:create' => '%s criou o grupo %s',
	'river:group:join' => '%s entrou no grupo %s',

	'groups:allowhiddengroups' => 'Permitir Grupos Privados (invisíveis)?',
	'groups:whocancreate' => 'Quem pode Criar Novos Grupos?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'O convite foi excluído.',
	'groups:joinrequestkilled' => 'A solicitação de entrada foi excluída.',
	'groups:error:addedtogroup' => "Não foi possível adicionar %s ao grupo",
	'groups:add:alreadymember' => "%s já é membro deste grupo",
	
	// Notification settings
	'groups:usersettings:notification:group_join:description' => "Configuração Padrão de Notificação ao Entrar em um Novo Grupo",
	
	'groups:usersettings:notifications:title' => 'Notificações de Grupos',
	'groups:usersettings:notifications:description' => 'Para receber notificações quando novos conteúdos forem adicionados a um grupo do qual você participa, encontre o grupo abaixo e selecione os métodos de notificação desejados.',
	
	// accessibility
	'groups:aria:label:group_search' => "Buscar Grupos",
	'groups:aria:label:search_in_group' => "Buscar neste Grupo",
);
