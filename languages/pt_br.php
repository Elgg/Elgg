<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
/**
 * Sites
 */

	'item:site:site' => 'Site',
	'collection:site:site' => 'Sites',
	'index:content' => '<p>Bem-vindo ao seu site Elgg. </p><p><strong>Dica:</strong>Muitos sites usam o plugin de<code>atividade</code> para colocar um fluxo de atividade do site nesta página.</p>',

/**
 * Sessions
 */

	'login' => "Entrar",
	'loginok' => "Você está autenticado.",
	'login:continue' => "Entre para continuar",
	'login:empty' => "Usuário/e-mail e senha são necessários.",
	'login:baduser' => "Não foi possível carregar a sua conta de usuário.",

	'logout' => "Sair",
	'logoutok' => "Você saiu com sucesso.",
	'logouterror' => "Não conseguimos desconectá-lo. Por favor, tente novamente.",
	'session_expired' => "Sua sessão expirou. Por favor, <a href='javascript:location.reload(true)'>recarregue</a> a página para entrar.",

	'loggedinrequired' => "Você precisa estar conectado para visualizar a página solicitada.",
	'loggedoutrequired' => "Você precisa estar desconectado para visualizar a página solicitada.",
	'adminrequired' => "Você deve ser um administrador para visualizar a página solicitada.",
	'membershiprequired' => "Você deve ser membro deste grupo para visualizar a página solicitada.",
	'limited_access' => "Você não tem permissão para visualizar a página solicitada.",
	'invalid_request_signature' => "A URL da página que você está tentando acessar é inválido ou expirou",

/**
 * Errors
 */

	'exception:title' => "Erro fatal.",
	'exception:contact_admin' => 'Ocorreu um erro irrecuperável e foi registrado. Entre em contato com o administrador do site com as seguintes informações:',

	'actionnotfound' => "O arquivo de ação para %s não foi encontrado.",
	'actionunauthorized' => 'Você não está autorizado a executar esta ação',

	'ajax:error' => 'Erro inesperado ao executar uma chamada AJAX. Talvez a conexão com o servidor tenha sido perdida.',
	'ajax:not_is_xhr' => 'Você não pode acessar visualizações AJAX diretamente',
	'ajax:pagination:no_data' => 'Nenhum dado da nova página foi encontrado',
	'ajax:pagination:load_more' => 'Carregar mais',

	'ElggEntity:Error:SetSubtype' => 'Use %s em vez do setter mágico para "subtipo"',
	'ElggEntity:Error:SetEnabled' => 'Usar %s em vez do setter mágico para "habilitado"',
	'ElggEntity:Error:SetDeleted' => 'Usar %s em vez do setter mágico para "excluído"',
	'ElggUser:Error:SetAdmin' => 'Usar %s em vez do setter mágico para "admin"',
	'ElggUser:Error:SetBanned' => 'Usar %s em vez do setter mágico para "banido"',

	'PluginException:CannotStart' => '%s (guid: %s) não pode ser iniciado e foi desativado. Motivo: %s',
	'PluginException:InvalidID' => "%s é um ID de plugin inválido.",
	'PluginException:PluginMustBeActive' => "Requer que o plugin '%s' esteja ativo.",
	'PluginException:PluginMustBeAfter' => "Precisa ser posicionado após o plugin '%s'.",
	'PluginException:PluginMustBeBefore' => "Precisa ser posicionado antes do plugin '%s'.",
	'ElggPlugin:MissingID' => 'ID do plugin ausente (guid %s)',
	'ElggPlugin:NoPluginComposer' => 'Composer.json ausente para o ID do plugin %s (guid %s)',
	'ElggPlugin:StartFound' => 'Para o plugin ID %s, foi encontrado um arquivo start.php. Isso pode indicar uma versão de plugin não suportada.',
	'ElggPlugin:IdMismatch' => 'O diretório deste plugin deve ser renomeado para "%s" para corresponder ao nome do projeto definido no plugin composer.json.',
	'ElggPlugin:Error' => 'Erro de plugin',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Não é possível incluir %s para o plugin %s (guid: %s) em %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Lançada exceção incluindo %s para o plugin %s (guid: %s) em %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Não é possível abrir o diretório de visualizações para o plugin %s (guid: %s) em %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s é um plugin inválido e foi desativado.',
	'ElggPlugin:activate:BadConfigFormat' => 'O arquivo do plugin "elgg-plugin.php" não retornou uma matriz serializável. ',
	'ElggPlugin:activate:ConfigSentOutput' => 'O arquivo do plugin "elgg-plugin.php" enviou a saída.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Existem outros plugins que listam %s como dependência. Você deve desabilitar os seguintes plugins antes de desabilitar este: %s',
	'ElggPlugin:Dependencies:MustBeActive' => 'Deve estar ativo',
	'ElggPlugin:Dependencies:Position' => 'Posição',

	'ElggMenuBuilder:Trees:NoParents' => 'Itens do menu encontrados sem pais para vinculá-los',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Item de menu [%s] encontrado com um pai ausente[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Registro duplicado encontrado para o item de menu [%s]',

	'RegistrationException:EmptyPassword' => 'Os campos de senha não podem ficar vazios',
	'RegistrationException:PasswordMismatch' => 'As senhas devem corresponder',
	'LoginException:BannedUser' => 'Você foi banido deste site e não pode mais conectar',
	'LoginException:UsernameFailure' => 'Não conseguimos fazer a sua conexão. Verifique o seu nome de usuário/e-mail e senha.',
	'LoginException:PasswordFailure' => 'Não conseguimos fazer a sua conexão. Verifique o seu nome de usuário/e-mail e senha.',
	'LoginException:AccountLocked' => 'A sua conta foi bloqueada devido a muitas falhas para conectar.',
	'LoginException:ChangePasswordFailure' => 'Falha na verificação da senha atual.',
	'LoginException:Unknown' => 'Não foi possível efetuar seu login devido a um erro desconhecido.',
	'LoginException:AdminValidationPending' => "A sua conta precisa ser validada por um administrador do site antes que você possa usá-la. Você será notificado quando a sua conta for validada.",
	'LoginException:DisabledUser' => "A sua conta foi desativada. Você não tem permissão para conectar.",

	'UserFetchFailureException' => 'Não é possível verificar a permissão para user_guid [%s] porque o usuário não existe.',

	'PageNotFoundException' => 'A página que você está tentando visualizar não existe ou você não tem permissão para visualizá-la',
	'EntityNotFoundException' => 'O conteúdo que você estava tentando acessar foi removido ou você não tem permissão para acessá-lo',
	'EntityPermissionsException' => 'Você não tem permissões suficientes para esta ação',
	'GatekeeperException' => 'Você não tem permissão para visualizar a página que está tentando acessar',
	'RegistrationAllowedGatekeeperException:invalid_invitecode' => "O código de convite fornecido não é válido",
	'BadRequestException:invalid_host_header' => 'A solicitação contém um cabeçalho HOST inválido',
	'BadRequestException:livesearch:no_query' => 'Livesearch requer uma consulta',
	'ValidationException' => 'Os dados enviados não atendem aos requisitos, verifique a sua entrada',
	'ValidationException:field:required' => 'O campo %s é obrigatório, dados vazios são fornecidos',
	'ValidationException:field:url' => 'O campo %s não atende aos requisitos de URL, verifique a sua entrada',
	'LogicException:InterfaceNotImplemented' => '%s deve implementar %s',
	'ForbiddenException' => 'Você não tem permissões suficientes para visualizar esta página',
	'GoneException' => 'O recurso solicitado não está mais disponível',
	'InternalServerErrorException' => 'Ocorreu um erro desconhecido ao tentar processar sua solicitação',
	'MethodNotAllowedException' => 'O método solicitado não é permitido para este recurso',
	'NotImplementedException' => 'O método solicitado não foi implementado para este recurso',
	'ServiceUnavailableException' => 'O servidor não conseguiu processar a sua solicitação, tente novamente mais tarde',
	'TooManyRequestsException' => 'Muitas solicitações, por favor, diminua a velocidade...',
	'UnauthorizedException' => 'Você não possui as credenciais de autenticação válidas para o recurso de destino',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "A senha fornecida não atende aos requisitos de caracteres",
	'Security:InvalidPasswordLengthException' => "A senha fornecida não atende ao requisito de comprimento mínimo de %s caracteres",
	
	'Entity:Subscriptions:InvalidMethodsException' => '%s requer que $methods seja uma string ou um array de strings',
	'error:missing_data' => 'Faltavam alguns dados na sua solicitação',
	'save:fail' => 'Ocorreu uma falha ao salvar seus dados',
	'save:success' => 'Seus dados foram salvos',
	'error:400:content' => 'Desculpe. A solicitação é inválida ou incompleta.',
	'error:401:title' => 'Não autorizado',
	'error:403:title' => 'Proibido',
	'error:403:content' => 'Desculpe. Você não tem permissão para acessar a página solicitada.',
	'error:404:title' => 'Página não encontrada',
	'error:404:content' => 'Desculpe. Não conseguimos encontrar a página solicitada.',
	'error:407:title' => 'Autenticação de Proxy necessária',
	'error:500:title' => 'Erro Interno do Servidor',
	'error:503:title' => 'Serviço indisponível',

	'upload:error:ini_size' => 'O arquivo que você tentou enviar é muito grande.',
	'upload:error:form_size' => 'O arquivo que você tentou enviar é muito grande.',
	'upload:error:partial' => 'O upload do arquivo não foi concluído.',
	'upload:error:no_file' => 'Nenhum arquivo foi selecionado.',
	'upload:error:no_tmp_dir' => 'Não é possível salvar o arquivo enviado.',
	'upload:error:cant_write' => 'Não é possível salvar o arquivo enviado.',
	'upload:error:extension' => 'Não é possível salvar o arquivo enviado.',
	'upload:error:unknown' => 'O upload do arquivo falhou.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Administrador',
	'table_columns:fromView:banned' => 'Banido',
	'table_columns:fromView:checkbox' => 'Selecione',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:entity_menu' => 'Menu',
	'table_columns:fromView:excerpt' => 'Descrição',
	'table_columns:fromView:link' => 'Nome/Título',
	'table_columns:fromView:icon' => 'Ícone',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Idioma',
	'table_columns:fromView:last_action' => 'Última ação',
	'table_columns:fromView:last_login' => 'Último login',
	'table_columns:fromView:owner' => 'Proprietário',
	'table_columns:fromView:prev_last_login' => 'Último login anterior',
	'table_columns:fromView:time_created' => 'Horário da Criação',
	'table_columns:fromView:time_updated' => 'Hora atualizada',
	'table_columns:fromView:unvalidated_menu' => 'Menu',
	'table_columns:fromView:user' => 'Usuário',

	'table_columns:fromProperty:description' => 'Descrição',
	'table_columns:fromProperty:email' => 'E-mail',
	'table_columns:fromProperty:name' => 'Nome',
	'table_columns:fromProperty:type' => 'Tipo',
	'table_columns:fromProperty:username' => 'Usuário',
	'table_columns:fromProperty:validated' => 'Validado',

	'table_columns:fromMethod:getSubtype' => 'Subtipo',
	'table_columns:fromMethod:getDisplayName' => 'Nome/Título',
	'table_columns:fromMethod:getMimeType' => 'Tipo de MIME',
	'table_columns:fromMethod:getSimpleType' => 'Tipo',

/**
 * User details
 */

	'name' => "Nome de Exibição",
	'email' => "Endereço de E-mail",
	'username' => "Usuário",
	'loginusername' => "Nome de Usuário ou e-mail",
	'password' => "Senha",
	'passwordagain' => "Senha (novamente para verificação)",
	'admin_option' => "Tornar este usuário um administrador?",
	'autogen_password_option' => "Gerar automaticamente uma senha segura?",

/**
 * Access
 */

	'access:label:private' => "Privado",
	'access:label:logged_in' => "Usuários Conectados",
	'access:label:public' => "Público",
	'access:label:logged_out' => "Usuários Desconectados",
	'access:label:friends' => "Amigos",
	'access' => "Quem pode ver isso",
	'access:limited:label' => "Limitado",
	'access:help' => "O nível de acesso",
	'access:read' => "Acesso de leitura",
	'access:write' => "Acesso de gravação",
	'access:admin_only' => "Somente Administradores",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Painel de Controle",
	'dashboard:nowidgets' => "Seu painel permite que você acompanhe a atividade e o conteúdo deste site que é importante para você.",

	'widgets:add' => 'Adicionar widgets',
	'widgets:add:description' => "Clique em qualquer botão de widget abaixo para adicioná-lo à página.",
	'widget:unavailable' => 'Você já adicionou este widget ',
	'widget:numbertodisplay' => 'Número de itens a serem exibidos',

	'widget:delete' => 'Remover %s',
	'widget:edit' => 'Personalizar este widget',

	'item:object:widget' => "Widget",
	'collection:object:widget' => 'Widgets',
	'widgets:add:success' => "O widget foi adicionado com sucesso.",
	'widgets:add:failure' => "Não foi possível adicionar seu widget.",
	'widgets:move:failure' => "Não foi possível armazenar a nova posição do widget.",
	'widgets:remove:failure' => "Não é possível remover este widget",
	'widgets:not_configured' => "Este widget ainda não está configurado",
	
/**
 * Groups
 */

	'group' => "Grupo",
	'item:group' => "Grupo",
	'collection:group' => 'Grupos',
	'item:group:group' => "Grupo",
	'collection:group:group' => 'Grupos',
	'groups:tool_gatekeeper' => "A funcionalidade solicitada não está habilitada neste grupo no momento",

/**
 * Users
 */

	'user' => "Usuário",
	'item:user' => "Usuário",
	'collection:user' => 'Usuários',
	'item:user:user' => 'Usuário',
	'collection:user:user' => 'Usuários',
	'notification:user:user:make_admin' => "Enviar uma notificação quando um usuário receber direitos de administrador",
	'notification:user:user:remove_admin' => "Enviar uma notificação quando os direitos de administrador de um usuário forem revogados",

	'friends' => "Amigos",
	'collection:friends' => 'Amigos de %s',

	'avatar' => 'Avatar',
	'avatar:edit' => 'Editar Avatar',
	'avatar:upload:instructions' => "Seu avatar é exibido em todo o site. Você pode alterá-lo sempre que quiser. (Formatos de arquivo aceitos: GIF, JPG ou PNG)",
	'avatar:upload:success' => 'Avatar enviado com sucesso',
	'avatar:upload:fail' => 'Falha no upload do avatar',
	'avatar:resize:fail' => 'Falha no redimensionamento do avatar',
	'avatar:remove:success' => 'A remoção do avatar foi bem-sucedida',
	'avatar:remove:fail' => 'Falha na remoção do avatar',
	
	'header:remove:success' => 'A remoção do cabeçalho foi bem-sucedida',
	'header:remove:fail' => 'Falha na remoção do cabeçalho',
	'header:upload:success' => 'O upload do cabeçalho foi bem-sucedido',
	'header:upload:fail' => 'Falha no upload do cabeçalho',
	
	'action:user:validate:already' => "%s já foi validado",
	'action:user:validate:success' => "%s foi validado",
	'action:user:validate:error' => "Ocorreu um erro ao validar %s",
	
	'action:user:login_as' => "Conectar como",
	'action:user:logout_as' => "Voltar para %s",
	
	'action:user:login_as:success' => "Agora você está conectado como %s",
	'action:user:login_as:unknown' => "Usuário desconhecido. Não foi possível conectar.",
	'action:user:login_as:error' => "Não foi possível conectar como %s",
	
	'action:admin:user:bulk:ban' => "%s usuários foram banidos com sucesso",

/**
 * Feeds
 */
	'feed:rss' => 'RSS',
	'feed:rss:title' => 'Feed RSS para esta página',
/**
 * Links
 */
	'link:view' => 'ver link',
	'link:view:all' => 'Ver tudo',
	'link:skip_to_main' => 'Pular para o conteúdo principal',

/**
 * River
 */
	'river' => "Atividades",
	'river:user:friend' => "%s agora é amigo de %s",
	'river:site:site:join' => "%s entrou no site",
	'river:update:user:avatar' => '%s tem um novo avatar',
	'river:posted:generic' => '%s publicou',
	'river:ingroup' => 'no grupo %s',
	'river:none' => 'Nenhuma atividade',
	'river:update' => 'Atualização para %s',
	'river:delete' => 'Remover este item de atividade',
	'river:delete:success' => 'O item de atividade foi excluído',
	'river:delete:fail' => 'O item de atividade não pôde ser excluído',
	'river:delete:lack_permission' => 'Você não tem permissão para excluir este item de atividade',
	'river:subject:invalid_subject' => 'Usuário inválido',
	'activity:owner' => 'Atividade',

/**
 * Relationships
 */
	
	'relationship:default' => "%s se relaciona com %s",

/**
 * Notifications
 */
	'notification:method:email' => 'E-mail',
	'notification:method:email:from' => '%s (via %s)',
	
	'usersettings:notifications:title' => "Configurações de Notificação",
	'usersettings:notifications:users:title' => 'Notificações por usuário',
	'usersettings:notifications:users:description' => 'Para receber notificações de seus amigos (individualmente) quando eles criarem novos conteúdos, encontre-os abaixo e selecione o método de notificação que deseja usar.',
	
	'usersettings:notifications:menu:page' => "Configurações de Notificação",
	'usersettings:notifications:menu:filter:settings' => "Configurações",
	
	'usersettings:notifications:default:description' => 'Configurações de notificação padrão para eventos do sistema',
	'usersettings:notifications:content_create:description' => 'Configurações de notificação padrão para novos conteúdos que você criou, isso pode causar notificações quando outras pessoas realizam ações em seu conteúdo, como deixar um comentário',
	'usersettings:notifications:create_comment:description' => "Configuração de notificação padrão quando você comenta um conteúdo para acompanhar o resto da conversa",
	'usersettings:notifications:mentions:description' => "Receba uma notificação quando você for @mencionado",
	'usersettings:notifications:admin_validation_notification:description' => "Receba uma notificação quando um usuário recém-registrado precisar ser validado",

	'usersettings:notifications:timed_muting' => "Desativar temporariamente as notificações ",
	'usersettings:notifications:timed_muting:help' => "Se você não deseja receber nenhuma notificação durante um determinado período (por exemplo, um feriado), você pode definir uma data de início e término para desativar temporariamente todas as notificações",
	'usersettings:notifications:timed_muting:start' => "Primeiro dia",
	'usersettings:notifications:timed_muting:end' => "Último dia",
	'usersettings:notifications:timed_muting:warning' => "Atualmente suas notificações estão temporariamente desativadas",
	
	'usersettings:notifications:save:ok' => "As configurações de notificação foram salvas com sucesso.",
	'usersettings:notifications:save:fail' => "Ocorreu um problema ao salvar as configurações de notificação.",
	
	'usersettings:notifications:subscriptions:save:ok' => "As assinaturas de notificação foram salvas com sucesso.",
	'usersettings:notifications:subscriptions:save:fail' => "Ocorreu um problema ao salvar as assinaturas de notificação.",

	'notification:default:salutation' => 'Caros %s,',
	'notification:default:sign-off' => 'Cumprimentos,

%s',
	'notification:subject' => 'Notificação sobre %s',
	'notification:body' => 'Veja a nova atividade em %s',
	
	'notification:mentions:subject' => '%s mencionou você',
	
	'notifications:delayed_email:subject:daily' => "Notificações diárias",
	'notifications:delayed_email:subject:weekly' => "Notificações semanais",
	'notifications:delayed_email:body:intro' => "Abaixo está uma lista de suas notificações atrasadas.",
	
	'notifications:subscriptions:record:settings' => 'Mostrar seleção detalhada',
	'notifications:subscriptions:no_results' => 'Ainda não há registros de assinaturas',
	'notifications:subscriptions:details:no_results' => 'Não há assinaturas detalhadas para configurar.',
	'notifications:subscriptions:details:reset' => 'Desfazer seleção',

	'notifications:mute:title' => "Silenciar notificações",
	'notifications:mute:description' => "Se você não deseja mais receber notificações como a que recebeu, configure um ou mais dos seguintes motivos para bloquear todas as notificações:",
	'notifications:mute:error:content' => "Nenhuma configuração de notificação pode ser determinada",
	'notifications:mute:entity' => "sobre '%s'",
	'notifications:mute:container' => "de '%s'",
	'notifications:mute:owner' => "por '%s'",
	'notifications:mute:actor' => "iniciado por '%s",
	'notifications:mute:group' => "escrito no grupo '%s'",
	'notifications:mute:user' => "escrito pelo usuário '%s'",
	
	'notifications:mute:save:success' => "Suas configurações de notificação foram salvas",
	
	'notifications:mute:email:footer' => "Ignorar estes e-mails",

/**
 * Search
 */

	'search' => "Pesquisar",
	'notfound' => "Nenhum resultado encontrado.",

	'viewtype:change' => "Alterar tipo de lista",
	'viewtype:list' => "Visualização de lista",
	'viewtype:gallery' => "Galeria",
	'search:go' => 'Vai!',
	'userpicker:only_friends' => 'Apenas amigos',

/**
 * Account
 */

	'account' => "Minha Conta",
	'settings' => "Configurações",
	'tools' => "Ferramentas",
	'settings:edit' => 'Editar Configurações',

	'register' => "Cadastre-se",
	'registerok' => "Você se registrou com sucesso para %s",
	'registerbad' => "Seu registro não foi bem-sucedido devido a um erro desconhecido.",
	'registerdisabled' => "O registro foi desabilitado pelo administrador do sistema",
	'register:fields' => 'Todos os campos são obrigatórios',

	'registration:noname' => 'Nome de exibição é obrigatório.',
	'registration:notemail' => 'O endereço de e-mail fornecido não parece ser um endereço de e-mail válido.',
	'registration:userexists' => 'Esse nome de usuário já existe',
	'registration:usernametooshort' => 'Seu nome de usuário deve ter no mínimo %u caracteres.',
	'registration:usernametoolong' => 'Seu nome de usuário é muito longo. Pode ter no máximo %u caracteres.',
	'registration:dupeemail' => 'Este endereço de e-mail já foi registrado.',
	'registration:invalidchars' => 'Desculpe, seu nome de usuário contém o caractere %s, que é inválido. Os seguintes caracteres são inválidos: %s',
	'registration:invalidchars:route' => 'Desculpe, seu nome de usuário contém o caractere %s, que é inválido.',
	'registration:emailnotvalid' => 'Desculpe, o endereço de e-mail que você digitou é inválido neste sistema',
	'registration:passwordnotvalid' => 'Desculpe, a senha que você digitou é inválida neste sistema',
	'registration:usernamenotvalid' => 'Desculpe, o nome de usuário que você digitou é inválido neste sistema',

	'adduser:ok' => "Você adicionou um novo usuário com sucesso.",
	
	'user:set:name' => "Configurações de nome de conta",
	'user:name:label' => "Nome de Exibição",
	'user:name:success' => "Nome de exibição alterado com sucesso no sistema.",
	'user:name:fail' => "Não foi possível alterar o nome de exibição no sistema.",
	'user:username:success' => "Nome de usuário alterado com sucesso no sistema.",
	'user:username:fail' => "Não foi possível alterar o nome de usuário no sistema.",

	'user:set:password' => "Senha da conta",
	'user:current_password:label' => 'Senha atual',
	'user:password:label' => "Nova Senha",
	'user:password2:label' => "Nova senha novamente",
	'user:password:success' => "Senha alterada",
	'user:changepassword:unknown_user' => 'Usuário inválido.',
	'user:changepassword:change_password_confirm' => 'Isso mudará sua senha.',

	'user:delete:title' => 'Confirmar exclusão da conta',
	'user:delete:confirm' => "Confirmo que desejo excluir este usuário",

	'user:set:language' => "Configurações do Idioma",
	'user:language:label' => "Idioma",
	'user:language:success' => "As configurações do idioma foram atualizadas.",

	'user:username:notfound' => 'Nome de usuário %s não encontrado.',
	'user:username:help' => 'Esteja ciente de que alterar um nome de usuário alterará todos os links dinâmicos relacionados ao usuário',

	'user:password:lost' => 'Esqueceu a Senha?',
	'user:password:hash_missing' => 'Lamentamos, mas pedimos que você redefina a sua senha. Melhoramos a segurança das senhas no site, mas não conseguimos migrar todas as contas durante o processo.',
	'user:password:changereq:success' => 'Nova senha solicitada com sucesso, e-mail enviado',

	'user:password:text' => 'Para solicitar uma nova senha, digite seu nome de usuário ou endereço de e-mail abaixo e clique no botão Solicitar.',

	'user:persistent' => 'Lembre de mim',

	'walled_garden:home' => 'Inicial',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "A senha precisa ter pelo menos %s caracteres.",
	'password:requirements:lower' => "A senha precisa ter pelo menos %s caracteres minúsculos.",
	'password:requirements:no_lower' => "A senha não deve conter caracteres minúsculos.",
	'password:requirements:upper' => "A senha precisa ter pelo menos %s caracteres maiúsculos.",
	'password:requirements:no_upper' => "A senha não deve conter caracteres maiúsculos.",
	'password:requirements:number' => "A senha precisa ter pelo menos %s caracteres.",
	'password:requirements:no_number' => "A senha não deve conter nenhum caractere numérico.",
	'password:requirements:special' => "A senha precisa ter pelo menos %s caracteres especiais.",
	'password:requirements:no_special' => "A senha não deve conter nenhum caractere especial.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:utilities' => 'Utilitários',
	'menu:page:header:develop' => 'Desenvolvedor',
	'menu:page:header:information' => 'Informação',
	'menu:page:header:default' => 'Outro',
	'menu:page:header:plugin_settings' => 'Configurações do Plugin',

	'admin:view_site' => 'Ver site',
	'admin:loggedin' => 'Conectado como %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "As suas configurações foram salvas.",
	'admin:configuration:fail' => "As suas configurações não podem ser salvas.",
	'admin:configuration:dataroot:relative_path' => 'Não é possível definir "%s" como raiz de dados porque não é um caminho absoluto',
	'admin:configuration:default_limit' => 'O número de itens por página deve ser de pelo menos 1.',

	'admin:unknown_section' => 'Seção de Administração Inválida.',

	'admin' => "Administração",
	'admin:header:release' => "Lançamento do Elgg: %s",
	'admin:description' => "O painel de administração permite que você controle todos os aspectos do sistema, desde o gerenciamento de usuários até o comportamento dos plugins. Escolha uma opção abaixo para começar.",

	'admin:performance' => 'Desempenho',
	'admin:performance:label:generic' => 'Genérico',
	'admin:performance:generic:description' => 'Abaixo está uma lista de sugestões/valores de desempenho que podem ajudar a ajustar seu site',
	'admin:performance:php:open_basedir:warning' => 'Uma pequena quantidade de limitações do open_basedir estão em vigor, o que pode afetar o desempenho.',
	'admin:performance:php:open_basedir:error' => 'Uma grande quantidade de limitações do open_basedir estão em vigor, o que provavelmente afetará o desempenho.',
	
	'admin:statistics' => 'Estatísticas',
	'admin:server' => 'Servidor',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Últimos Trabalhos do Cron',
	'admin:cron:period' => 'Período do Cron',
	'admin:cron:friendly' => 'Concluído pela última vez',
	'admin:cron:date' => 'Data e Hora',
	'admin:cron:msg' => 'Mensagem',
	'admin:cron:started' => 'As tarefas do Cron para "%s" começaram em %s',
	'admin:cron:started:actual' => 'O intervalo Cron "%s" iniciou o processamento em %s',
	'admin:cron:complete' => 'Tarefas do Cron para "%s" concluídas em %s',

	'admin:appearance' => 'Aparência',
	'admin:administer_utilities' => 'Utilitários',
	'admin:develop_utilities' => 'Utilitários',
	'admin:configure_utilities' => 'Utilitários',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuários",
	'admin:users:online' => 'Atualmente On-line',
	'admin:users:newest' => 'Recentes',
	'admin:users:admins' => 'Administradores',
	'admin:users:banned' => 'Banido',
	'admin:users:searchuser' => 'Pesquisar usuário para torná-lo administrador',
	'admin:users:existingadmins' => 'Lista de administradores existentes',
	'admin:users:add' => 'Adicionar Novo Usuário',
	'admin:users:description' => "Este painel de administração permite que você controle as configurações do usuário do seu site. Escolha uma opção abaixo para começar.",
	'admin:users:adduser:label' => "Clique aqui para adicionar um novo usuário...",
	'admin:users:opt:linktext' => "Configurar usuários...",
	'admin:users:opt:description' => "Configurar usuários e informações da conta.",
	'admin:users:find' => 'Localizar',
	'admin:users:unvalidated' => 'Não validado',
	'admin:users:unvalidated:no_results' => 'Nenhum usuário não validado.',
	'admin:users:unvalidated:registered' => 'Registrado: %s',
	'admin:users:unvalidated:change_email' => 'Alterar endereço de e-mail',
	'admin:users:unvalidated:change_email:user' => 'Alterar endereço de e-mail para: %s',
	'admin:users:inactive' => 'Inativo',
	'admin:users:inactive:last_login_before' => "Mostrar usuários não conectados após",
	'admin:users:details:attributes' => 'Atributos do usuário',
	'admin:users:details:profile' => 'Informações do perfil',
	'admin:users:details:profile:no_fields' => 'Nenhum campo de perfil configurado',
	'admin:users:details:profile:no_information' => 'Nenhuma informação de perfil disponível',
	'admin:users:details:statistics' => 'Estatísticas de conteúdo',
	
	'admin:configure_utilities:maintenance' => 'Modo de manutenção',
	'admin:upgrades' => 'Atualizações',
	'admin:upgrades:finished' => 'Concluído',
	'admin:upgrades:db' => 'Atualizações do Banco de Dados',
	'admin:upgrades:db:name' => 'Nome da atualização',
	'admin:upgrades:db:start_time' => 'Hora de início',
	'admin:upgrades:db:end_time' => 'Horário de Término',
	'admin:upgrades:db:duration' => 'Duração',
	'admin:upgrades:menu:pending' => 'Atualizações pendentes',
	'admin:upgrades:menu:completed' => 'Atualizações concluídas',
	'admin:upgrades:menu:db' => 'Atualizações do Banco de Dados',
	'admin:upgrades:menu:run_single' => 'Execute esta atualização',
	'admin:upgrades:run' => 'Execute esta atualização agora',
	'admin:upgrades:error:invalid_upgrade' => 'A entidade %s não existe ou não é uma instância válida do ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'O executor em lote para a atualização %s (%s) não pode ser instanciado',
	'admin:upgrades:completed' => 'Atualização "%s" concluída em %s',
	'admin:upgrades:completed:errors' => 'A atualização "%s" foi concluída em %s, mas encontrou %s erros',
	'admin:upgrades:failed' => 'A atualização "%s" falhou',
	'admin:action:upgrade:reset:success' => 'A atualização "%s" foi redefinida',

	'admin:settings' => 'Configurações',
	'admin:settings:basic' => 'Configurações Básicas',
	'admin:settings:i18n' => 'Internacionalização',
	'admin:settings:advanced' => 'Configurações Avançadas',
	'admin:settings:users' => 'Usuários',
	'admin:site_icons' => "Ícones do Site",
	'admin:site_icons:site_icon' => "Ícone do Site",
	'admin:site_icons:info' => "Carregue um ícone relacionado ao seu site. Este ícone será usado como favicon e na exibição do site, por exemplo, como remetente nas notificações do site.",
	'admin:site_icons:font_awesome' => "Font Awesome",
	'admin:site_icons:font_awesome:zip' => "Carregar arquivo ZIP",
	'admin:site_icons:font_awesome:zip:help' => "Aqui você pode fazer upload de uma fonte através de download do arquivo Font Awesome em https://fontawesome.com/download. Esta fonte da web será disponibilizada localmente.",
	'admin:site_icons:font_awesome:zip:error' => "O ZIP carregado não pode ser extraído",
	'admin:site_icons:font_awesome:remove_zip' => "Remover fonte enviada",
	'admin:theme' => "Tema",
	'admin:theme:warning' => "Esteja ciente de que essas mudanças podem prejudicar o seu estilo ou quebrar o site.",
	'admin:theme:css_variable:name' => "Variável CSS",
	'admin:theme:css_variable:value' => "Valor",
	'admin:site_settings' => "Configurações do Site",
	'admin:site:description' => "Este painel de administração permite que você controle as configurações globais do seu site. Escolha uma opção abaixo para começar.",
	'admin:site:opt:linktext' => "Configurar site...",
	'admin:settings:in_settings_file' => 'Esta configuração é definida em settings.php',

	'site_secret:current_strength' => 'Força da chave',
	'site_secret:strength:weak' => "Fraca",
	'site_secret:strength_msg:weak' => "Recomendamos fortemente que você gere novamente o segredo do seu site.",
	'site_secret:strength:moderate' => "Moderado",
	'site_secret:strength_msg:moderate' => "Recomendamos que você gere novamente o segredo do seu site para melhorar a segurança.",
	'site_secret:strength:strong' => "Forte",
	'site_secret:strength_msg:strong' => "O segredo do seu site é forte o suficiente. Não há necessidade de regenerá-lo.",

	'admin:dashboard' => 'Painel de Controle',
	'admin:widget:online_users' => 'Usuários On-line',
	'admin:widget:online_users:help' => 'Lista os usuários on-line atualmente no site',
	'admin:widget:new_users' => 'Novos usuários',
	'admin:widget:new_users:help' => 'Lista os usuários mais novos',
	'admin:widget:banned_users' => 'Usuários banidos',
	'admin:widget:banned_users:help' => 'Lista os usuários banidos',
	'admin:widget:content_stats' => 'Estatísticas de conteúdo',
	'admin:widget:content_stats:help' => 'Acompanhe o conteúdo criado dos seus usuários',
	'admin:widget:cron_status' => 'Status do Cron',
	'admin:widget:cron_status:help' => 'Mostra o status da última vez que os trabalhos do Cron foram concluídos',
	'admin:widget:elgg_blog' => 'Blog do Elgg',
	'admin:widget:elgg_blog:help' => 'Exibe as últimas postagens do blog do Elgg',
	'admin:widget:elgg_blog:no_results' => 'Não foi possível obter as últimas notícias do Elgg',
	'admin:statistics:numentities' => 'Estatísticas de Conteúdo',
	'admin:statistics:numentities:type' => 'Tipo de conteúdo',
	'admin:statistics:numentities:number' => 'Número',
	'admin:statistics:numentities:searchable' => 'Entidades pesquisáveis',
	'admin:statistics:numentities:other' => 'Outras entidades',

	'admin:statistics:database' => 'Informações do Banco de Dados',
	'admin:statistics:database:table' => 'Tabela',
	'admin:statistics:database:row_count' => 'Contagem de linhas',

	'admin:statistics:queue' => 'Informações da fila',
	'admin:statistics:queue:name' => 'Nome',
	'admin:statistics:queue:row_count' => 'Contagem de linhas',
	'admin:statistics:queue:oldest' => 'Registro mais antigo',
	'admin:statistics:queue:newest' => 'Registro mais recente',

	'admin:widget:admin_welcome' => 'Bem-vindo',
	'admin:widget:admin_welcome:help' => "Uma breve introdução à área administrativa do Elgg",
	'admin:widget:admin_welcome:intro' => 'Bem-vindo ao Elgg! Agora você está visualizando o painel de administração. Ele é útil para acompanhar o que está acontecendo no site.',

	'admin:widget:admin_welcome:registration' => "O cadastro de novos usuários está desativado no momento! Você pode ativá-lo na página %s.",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => 'Não deixe de conferir os recursos disponíveis nos links do rodapé e obrigado por usar o Elgg!',

	'admin:widget:control_panel' => 'Painel de controle',
	'admin:widget:control_panel:help' => "Fornece acesso fácil aos controles comuns",

	'admin:cache:flush' => 'Limpar os caches',
	'admin:cache:flushed' => "Os caches do site foram limpos",
	'admin:cache:invalidate' => 'Invalidar os caches',
	'admin:cache:invalidated' => "Os caches do site foram invalidados",
	'admin:cache:clear' => 'Limpe os caches',
	'admin:cache:cleared' => "Os caches do site foram limpos",
	'admin:cache:purge' => 'Limpar os caches',
	'admin:cache:purged' => "Os caches do site foram limpos",

	'admin:footer:faq' => 'Perguntas frequentes sobre administração',
	'admin:footer:manual' => 'Manual do Administrador',
	'admin:footer:community_forums' => 'Fóruns da Comunidade Elgg',
	'admin:footer:blog' => 'Blog do Elgg',

	'admin:plugins:category:all' => 'Todos os plugins',
	'admin:plugins:category:active' => 'Plugins ativos',
	'admin:plugins:category:inactive' => 'Plugins inativos',
	'admin:plugins:category:admin' => 'Administrador',
	'admin:plugins:category:bundled' => 'Empacotado',
	'admin:plugins:category:nonbundled' => 'Não empacotado',
	'admin:plugins:category:content' => 'Conteúdo',
	'admin:plugins:category:development' => 'Desenvolvimento',
	'admin:plugins:category:enhancement' => 'Melhorias',
	'admin:plugins:category:api' => 'Serviço/API',
	'admin:plugins:category:communication' => 'Comunicação',
	'admin:plugins:category:security' => 'Segurança e Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimídia',
	'admin:plugins:category:theme' => 'Temas',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilitários',

	'admin:plugins:markdown:unknown_plugin' => 'Plugin desconhecido.',
	'admin:plugins:markdown:unknown_file' => 'Arquivo desconhecido.',

	'admin:notices:delete_all' => 'Descartar todos os avisos %s',
	'admin:notices:could_not_delete' => 'Não foi possível excluir o aviso.',
	'item:object:admin_notice' => 'Aviso do administrador',
	'collection:object:admin_notice' => 'Avisos de administração',

	'admin:options' => 'Opções do administrador',

	'admin:security' => 'Segurança',
	'admin:security:information' => 'Informação',
	'admin:security:information:description' => 'Nesta página você pode encontrar uma lista de recomendações de segurança.',
	'admin:security:information:https' => 'O site é protegido por HTTPS',
	'admin:security:information:https:warning' => "É recomendável proteger seu site usando HTTPS, pois isso ajuda a proteger dados (por exemplo, senhas) de serem rastreados pela conexão de internet.",
	'admin:security:information:wwwroot' => 'A pasta principal do site é gravável',
	'admin:security:information:wwwroot:error' => "É recomendável instalar o Elgg em uma pasta que não seja gravável pelo seu servidor web. Visitantes mal-intencionados podem inserir códigos indesejados no seu site.",
	'admin:security:information:validate_input' => 'Validação de entrada',
	'admin:security:information:validate_input:error' => "Alguns plugins desabilitaram a validação de entrada no seu site, o que permitirá que os usuários enviem conteúdo potencialmente prejudicial (por exemplo, cross-site-scripting, etc.)",
	'admin:security:information:password_length' => 'Comprimento mínimo da senha',
	'admin:security:information:password_length:warning' => "É recomendável que a senha tenha no mínimo 6 caracteres.",
	'admin:security:information:username_length' => 'Comprimento mínimo do nome de usuário',
	'admin:security:information:username_length:warning' => "É recomendável que o nome de usuário tenha no mínimo 4 caracteres.",
	'admin:security:information:php:session_gc' => "Limpeza de sessão PHP",
	'admin:security:information:php:session_gc:chance' => "Chance de limpeza: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Duração da sessão %s segundos",
	
	'admin:security:settings' => 'Configurações',
	'admin:security:settings:label:account' => 'Minha Conta',
	'admin:security:settings:label:notifications' => 'Notificações',
	
	'admin:security:settings:minusername' => "Comprimento mínimo do nome de usuário",
	
	'admin:security:settings:min_password_length' => "Comprimento mínimo da senha",
	'admin:security:security_txt:contact' => "Contato",
	'admin:security:security_txt:language' => "Idioma",

/**
 * Plugins
 */
	'collection:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate' => 'Ativado',
	'admin:plugins:deactivate' => 'Desativado',
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Nome",
	'admin:plugins:label:copyright' => "Direitos Autorais",
	'admin:plugins:label:categories' => 'Categorias',
	'admin:plugins:label:info' => "Informação",
	'admin:plugins:label:files' => "Arquivos",
	'admin:plugins:label:version' => 'Versão',
	'admin:plugins:label:location' => 'Localização',
	'admin:plugins:label:priority' => 'Prioridade',
	'admin:plugins:categories:all' => 'Todas as categorias',
	'admin:plugins:plugin_website' => 'Site do Plugin',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versão %s',
	'admin:plugin_settings' => 'Configurações do Plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'Este plugin está ativo, mas possui dependências não atendidas. Você pode encontrar problemas. Veja "mais informações" abaixo para mais detalhes.',

	'admin:statistics:description' => "Esta é uma visão geral das estatísticas do seu site. Se precisar de estatísticas mais detalhadas, temos um recurso de administração profissional disponível.",
	'admin:statistics:opt:description' => "Visualize informações estatísticas sobre os usuários e objetos no seu site.",
	'admin:statistics:opt:linktext' => "Ver estatísticas...",
	'admin:statistics:label:user' => "Estatísticas do Usuário",
	'admin:statistics:label:numentities' => "Entidades no Site",
	'admin:statistics:label:numusers' => "Número de usuários",
	'admin:statistics:label:numonline' => "Número de usuários on-line",
	'admin:statistics:label:onlineusers' => "Usuários on-line agora",
	'admin:statistics:label:admins' => "Administradores",
	'admin:statistics:label:version' => "Versão do Elgg",
	'admin:statistics:label:version:release' => "Lançamento",
	'admin:statistics:label:version:version' => "Versão do Banco de Dados",
	'admin:statistics:label:version:code' => "Versão do Código",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Requisitos',
	'admin:server:label:php' => 'PHP ',
	'admin:server:label:phpinfo' => 'Exibir as informações do PHP',
	'admin:server:label:web_server' => 'Servidor Web',
	'admin:server:label:server' => 'Servidor',
	'admin:server:label:log_location' => 'Localização dos Logs',
	'admin:server:label:php_version' => 'Versão do PHP',
	'admin:server:label:php_version:required' => 'Elgg requer uma versão mínima do PHP 7.1',
	'admin:server:label:php_version:required_version' => 'Elgg requer uma versão mínima do PHP %s',
	'admin:server:label:php_ini' => 'Localização do arquivo PHP ini',
	'admin:server:label:php_log' => 'Log PHP',
	'admin:server:label:mem_avail' => 'Memória disponível',
	'admin:server:label:mem_used' => 'Memória utilizada',
	'admin:server:error_log' => "Log de Erros do Servidor Web",
	'admin:server:label:post_max_size' => 'Tamanho Máximo do POST',
	'admin:server:label:upload_max_filesize' => 'Tamanho Máximo para Upload',
	'admin:server:warning:post_max_too_small' => '(Observação: post_max_size deve ser maior que este valor para suportar uploads deste tamanho)',
	'admin:server:label:memcache' => 'Memcache',

	'admin:server:label:redis' => 'Redis',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => 'O OPcache não está disponível neste servidor ou ainda não foi habilitado. Para melhorar o desempenho, é recomendável habilitar e configurar o OPcache.',
	
	'admin:server:requirements:php_extension' => "Extensão PHP: %s",
	'admin:server:requirements:php_extension:required' => "Esta extensão PHP é necessária para o funcionamento correto do Elgg",
	'admin:server:requirements:php_extension:recommended' => "Esta extensão PHP é recomendada para o funcionamento ideal do Elgg",
	'admin:server:requirements:rewrite' => "Regras de reescrita .htaccess",
	'admin:server:requirements:rewrite:fail' => "Verifique seu .htaccess para as regras de reescrita corretas",
	
	'admin:server:requirements:database:server' => "Servidor do Banco de Dados",
	'admin:server:requirements:database:server:required_version' => "Elgg requer MySQL v%s ou superior para o seu Banco de Dados",
	'admin:server:requirements:database:client' => "Cliente do Banco de Dados",
	'admin:server:requirements:database:client:required' => "Elgg requer pdo_mysql para se conectar ao Servidor de Banco de Dados",

	'admin:server:requirements:webp' => "Suporte WebP",

	'admin:server:requirements:gc' => "Coleta de Lixo da Sessão",
	'admin:server:requirements:gc:info' => "Se a coleta de lixo não estiver configurada, a tabela de sessão não será limpa. Configure session.gc_divisor e session.gc_probability no seu php.ini.",
	
	'admin:user:label:search' => "Encontre usuários:",
	'admin:user:label:searchbutton' => "Pesquisar",

	'admin:user:ban:no' => "Não é possível banir o usuário",
	'admin:user:ban:yes' => "Usuário banido.",
	'admin:user:self:ban:no' => "Você não pode banir a si mesmo",
	'admin:user:delete:no' => "Não foi possível excluir o usuário",
	'admin:user:delete:yes' => "O usuário %s foi excluído",
	'admin:user:self:delete:no' => "Você não pode se excluir",

	'admin:user:resetpassword:yes' => "A senha foi redefinida e o usuário notificado.",
	'admin:user:resetpassword:no' => "A senha não pôde ser redefinida.",

	'admin:user:makeadmin:yes' => "O usuário agora é um administrador.",
	'admin:user:makeadmin:no' => "Não foi possível tornar este usuário um administrador.",

	'admin:user:removeadmin:yes' => "O usuário não é mais um administrador.",
	'admin:user:removeadmin:no' => "Não foi possível remover os privilégios de administrador deste usuário.",
	'admin:user:self:removeadmin:no' => "Você não pode remover os seus próprios privilégios de administrador.",

	'admin:configure_utilities:menu_items' => 'Itens do Menu',
	'admin:menu_items:configure' => 'Configurar itens do menu principal',
	'admin:menu_items:description' => 'Selecione a ordem dos itens do menu do site. Itens não configurados serão adicionados ao final da lista.',
	'admin:menu_items:hide_toolbar_entries' => 'Remover links do menu da barra de ferramentas?',
	'admin:menu_items:saved' => 'Itens de menu salvos.',
	'admin:add_menu_item' => 'Adicionar um item ao menu personalizado',
	'admin:add_menu_item:description' => 'Preencha o Nome de exibição e o URL para adicionar itens personalizados ao seu menu de navegação.',

	'admin:configure_utilities:default_widgets' => 'Widgets Padrão',
	'admin:default_widgets:unknown_type' => 'Tipo de widget desconhecido',
	'admin:default_widgets:instructions' => 'Adicione, remova, posicione e configure widgets padrão para a página de widgets selecionada. Essas alterações afetarão apenas novos usuários no site.',

	'admin:robots.txt:instructions' => "Edite o arquivo robots.txt deste site abaixo",
	'admin:robots.txt:plugins' => "Os plugins estão adicionando o seguinte ao arquivo robots.txt",
	'admin:robots.txt:subdir' => "A ferramenta robots.txt não funcionará porque o Elgg está instalado em um subdiretório",
	'admin:robots.txt:physical' => "A ferramenta robots.txt não funcionará porque um robots.txt físico está presente",

	'admin:maintenance_mode:default_message' => 'Este site está em manutenção',
	'admin:maintenance_mode:instructions' => 'O modo de manutenção deve ser usado para atualizações e outras grandes mudanças no site. Quando ativado, apenas administradores podem fazer login e navegar no site.',
	'admin:maintenance_mode:mode_label' => 'Modo de manutenção',
	'admin:maintenance_mode:message_label' => 'Mensagem exibida aos usuários quando o modo de manutenção está ativado',
	'admin:maintenance_mode:saved' => 'As configurações do modo de manutenção foram salvas.',
	'admin:maintenance_mode:indicator_menu_item' => 'O site está em modo de manutenção.',
	'admin:login' => 'Login de Administrador',

/**
 * User settings
 */

	'usersettings:statistics' => "Suas estatísticas",
	'usersettings:statistics:user' => "Estatísticas de %s",
	'usersettings:statistics:opt:linktext' => "Estatísticas da conta",

	'usersettings:statistics:login_history' => "Histórico de Login",
	'usersettings:statistics:login_history:date' => "Data",
	'usersettings:statistics:login_history:ip' => "Endereço IP",

	'usersettings:user' => "Configurações de %s",
	'usersettings:user:opt:linktext' => "Altere as suas configurações",

	'usersettings:plugins:opt:linktext' => "Configure as suas ferramentas",
	
	'usersettings:statistics:yourdetails' => "Seus detalhes",
	'usersettings:statistics:details:user' => "Detalhes de %s",
	'usersettings:statistics:numentities:user' => "Estatísticas de conteúdo para %s",
	'usersettings:statistics:label:name' => "Nome Completo",
	'usersettings:statistics:label:email' => "E-mail",
	'usersettings:statistics:label:lastlogin' => "Último login",
	'usersettings:statistics:label:membersince' => "Membro desde",
	'usersettings:statistics:label:numentities' => "Seu conteúdo",
	
	'usersettings:delayed_email:interval' => "Configurar o intervalo em que as notificações de e-mail atrasadas serão entregues",
	'usersettings:delayed_email:interval:help' => "Todas as notificações de e-mail atrasadas serão salvas e entregues em um e-mail combinado no intervalo configurado",

/**
 * Activity river
 */

	'river:all' => 'Todas as Atividades do Site',
	'river:mine' => 'Minha Atividade',
	'river:owner' => 'Atividade de %s',
	'river:friends' => 'Atividade dos Amigos',
	'river:select' => 'Exibir %s',
	'river:comments:all' => 'Ver todos os comentários %u',

/**
 * Icons
 */

	'icon:size' => "Tamanho do ícone",
	'icon:size:topbar' => "Barra Superior",
	'icon:size:small' => "Pequeno",
	'icon:size:medium' => "Médio",
	'icon:size:large' => "Grande",
	'icon:size:master' => "Extra Grande",
	
	'entity:edit:icon:crop_messages:generic' => "A imagem selecionada não atende às dimensões recomendadas. Isso pode resultar em ícones de baixa qualidade.",
	'entity:edit:icon:crop:img:alt' => "Imagem enviada",
	'entity:edit:icon:file:label' => "Carregar um novo ícone",
	'entity:edit:icon:file:help' => "Deixe em branco para manter o ícone atual.",
	'entity:edit:icon:remove:label' => "Remover ícone",

	'entity:edit:header:file:label' => "Carregar um novo cabeçalho",
	'entity:edit:header:file:help' => "Deixe em branco para manter o cabeçalho atual.",
	'entity:edit:header:remove:label' => "Remover imagem do cabeçalho",

/**
 * Generic action words
 */

	'save' => "Salvar",
	'save_go' => "Salvar e ir para %s",
	'reset' => 'Reiniciar',
	'publish' => "Publicar",
	'unfeature' => "Desapresentar",
	'cancel' => "Cancelar",
	'saving' => "Salvando ...",
	'update' => "Atualizar",
	'preview' => "Visualização",
	'edit' => "Editar",
	'delete' => "Excluir",
	'trash' => "Lixeira",
	'accept' => "Aceitar",
	'reject' => "Rejeitar",
	'decline' => "Declínio",
	'approve' => "Aprovado",
	'activate' => "Ativado",
	'deactivate' => "Desativado",
	'disapprove' => "Reprovar",
	'revoke' => "Revogar",
	'load' => "Carregar",
	'upload' => "Upload",
	'download' => "Download",
	'ban' => "Banir",
	'banned' => "Banido",
	'enable' => "Habilitar",
	'disable' => "Desativar",
	'request' => "Solicitar",
	'complete' => "Completo",
	'open' => 'Aberto',
	'close' => 'Fechar',
	'hide' => 'Ocultar',
	'show' => 'Exibir',
	'reply' => "Responder",
	'more' => 'Mais',
	'more_info' => 'Mais informações',
	'comments' => 'Comentários',
	'import' => 'Importar',
	'export' => 'Exportar',
	'untitled' => 'Sem título',
	'help' => 'Ajuda',
	'send' => 'Enviar',
	'resend' => 'Reenviar',
	'post' => 'Publicar',
	'submit' => 'Enviar',
	'comment' => 'Comentário',
	'upgrade' => 'Atualizar',
	'sort' => 'Organizar',
	'filter' => 'Filtro',
	'new' => 'Novo',
	'add' => 'Adicionar',
	'create' => 'Criar',
	'remove' => 'Remover',
	'revert' => 'Reverter',
	'validate' => 'Validar',
	'read_more' => 'Leia mais',
	'next' => 'Proximo',
	'previous' => 'Anterior',
	'older' => 'Antigos',
	'newer' => 'Recentes',
	
	'site' => 'Site',
	'activity' => 'Atividade',
	'members' => 'Membros',
	'menu' => 'Menu',
	'item' => 'Item',

	'up' => 'Acima',
	'down' => 'Abaixo',
	'top' => 'Topo',
	'bottom' => 'Fundo',
	'right' => 'Direita',
	'left' => 'Esquerda',
	'back' => 'Voltar',

	'invite' => "Convidar",

	'resetpassword' => "Redefinir a Senha",
	'changepassword' => "Alterar a Senha",
	'makeadmin' => "Tornar administrador",
	'removeadmin' => "Remover administrador",

	'option:yes' => "Sim",
	'option:no' => "Não",

	'unknown' => 'Desconhecido',
	'never' => 'Nunca',

	'active' => 'Ativo',
	'total' => 'Total',
	'unvalidated' => 'Não validado',
	
	'ok' => 'OK',
	'any' => 'Qualquer',
	'error' => 'Erro',

	'other' => 'Outro',
	'options' => 'Opções',
	'advanced' => 'Avançado',

	'learnmore' => "Clique aqui para saber mais.",
	'unknown_error' => 'Erro desconhecido',

	'content' => "conteúdo",
	'content:latest' => 'Última atividade',
	
	'list:out_of_bounds' => "Você chegou a uma parte da lista sem nenhum conteúdo, porém há conteúdo disponível.",
	'list:out_of_bounds:link' => "Voltar para a primeira página",
	'list:error:getter:user' => 'Ocorreu um erro ao buscar o conteúdo',

	'link:text' => 'ver link',
	
	'scroll_to_top' => 'Voltar ao Topo',

/**
 * Generic questions
 */

	'question:areyousure' => 'Tem certeza?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Rascunho Não Salvo',
	'status:draft' => 'Rascunho',
	'status:unpublished' => 'Não Publicado',
	'status:published' => 'Publicado',
	'status:open' => 'Aberto',
	'status:closed' => 'Fechado',
	'status:enabled' => 'Habilitado',
	'status:disabled' => 'Desabilitado',
	'status:unavailable' => 'Indisponível',
	'status:active' => 'Ativo',
	'status:inactive' => 'Inativo',
	'status:deleted' => 'Excluído',
	'status:trashed' => 'Lixeira',

/**
 * Generic sorts
 */

	'sort:newest' => 'Recentes',
	'sort:oldest' => 'Antigos',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfabética',
	'sort:priority' => 'Prioridade',
	'sort:relevance' => 'Relevância',
	'sort:az' => '%s (A-Z)',
	'sort:za' => '%s (Z-A)',

/**
 * Generic data words
 */

	'title' => "Título",
	'description' => "Descrição",
	'tags' => "Tags",
	'all' => "Tudo",
	'mine' => "Meu",

	'by' => 'por',
	'none' => 'nenhum',

	'annotations' => "Anotações",
	'relationships' => "Relacionamentos",
	'metadata' => "Metadados",
	'tagcloud' => "Nuvem de Tags",

	'on' => 'Ligado',
	'off' => 'Desligado',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%sK",
	'number_counter:view:million' => "%sM",
	'number_counter:view:billion' => "%sT",
	'number_counter:view:trillion' => "%sT",

/**
 * Entity actions
 */

	'edit:this' => 'Editar',
	'delete:this' => 'Excluir',
	'trash:this' => 'Jogar isso no Lixo',
	'restore:this' => 'Restaurar isto',
	'restore:this:move' => 'Restaurar e mover isto',
	'comment:this' => 'Comente sobre isso',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Tem certeza de que deseja excluir este item?",
	'trashconfirm' => "Tem certeza de que deseja descartar este item?",
	'restoreconfirm' => "Tem certeza de que deseja restaurar este item?",
	'restoreandmoveconfirm' => "Tem certeza de que deseja restaurar e mover este item?",
	'deleteconfirm:plural' => "Tem certeza de que deseja excluir esses itens?",
	'fileexists' => "Um arquivo já foi carregado. Para substituí-lo, selecione um novo abaixo.",
	'input:file:upload_limit' => 'O tamanho máximo do arquivo permitido é %s',
	'input:container_guid:info' => 'Este conteúdo será publicado em %s',

/**
 * User add
 */

	'useradd:subject' => 'Conta de usuário criada',

/**
 * Messages
 */
	'messages:title:success' => 'Sucesso',
	'messages:title:error' => 'Erro',
	'messages:title:warning' => 'Aviso',
	'messages:title:help' => 'Ajuda',
	'messages:title:notice' => 'Notificação',
	'messages:title:info' => 'Informação',

/**
 * Time
 */

	'input:date_format' => 'd-m-Y',
	'input:date_format:datepicker' => 'dd-mm-yy', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:updated' => "%s - Editado",
	'friendlytime:updated:title' => "Criado em: %s \nAtualizado em: %s",
	
	'friendlytime:justnow' => "agora",
	'friendlytime:minutes' => "há %s minutos",
	'friendlytime:minutes:singular' => "há um minuto",
	'friendlytime:hours' => "%s horas atrás",
	'friendlytime:hours:singular' => "há uma hora",
	'friendlytime:days' => "há %s dias",
	'friendlytime:days:singular' => "ontem",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "in %s minutes",
	'friendlytime:future:minutes:singular' => "em um minuto",
	'friendlytime:future:hours' => "em %s horas",
	'friendlytime:future:hours:singular' => "em uma hora",
	'friendlytime:future:days' => "em %s dias",
	'friendlytime:future:days:singular' => "amanhã",

	'date:month:01' => 'Janeiro %s',
	'date:month:02' => 'Fevereiro %s',
	'date:month:03' => 'Março %s',
	'date:month:04' => 'Abril %s',
	'date:month:05' => 'Maio %s',
	'date:month:06' => 'Junho %s',
	'date:month:07' => 'Julho %s',
	'date:month:08' => 'Agosto %s',
	'date:month:09' => 'Setembro %s',
	'date:month:10' => 'Outubro %s',
	'date:month:11' => 'Novembro %s',
	'date:month:12' => 'Dezembro %s',

	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Fev %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Abr %s',
	'date:month:short:05' => 'Maio %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Ago %s',
	'date:month:short:09' => 'Set %s',
	'date:month:short:10' => 'Out %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dez %s',

	'date:weekday:0' => 'Domingo',
	'date:weekday:1' => 'Segunda-feira',
	'date:weekday:2' => 'Terça-feira',
	'date:weekday:3' => 'Quarta-feira',
	'date:weekday:4' => 'Quinta-feira',
	'date:weekday:5' => 'Sexta-feira',
	'date:weekday:6' => 'Sábado',

	'date:weekday:short:0' => 'Dom',
	'date:weekday:short:1' => 'Seg',
	'date:weekday:short:2' => 'Ter',
	'date:weekday:short:3' => 'Qua',
	'date:weekday:short:4' => 'Qui',
	'date:weekday:short:5' => 'Sex',
	'date:weekday:short:6' => 'Sáb',

	'interval:minute' => 'A cada minuto',
	'interval:fiveminute' => 'A cada cinco minutos',
	'interval:fifteenmin' => 'A cada quinze minutos',
	'interval:halfhour' => 'A cada meia hora',
	'interval:hourly' => 'De hora em hora',
	'interval:daily' => 'Diário',
	'interval:weekly' => 'Semanalmente',
	'interval:monthly' => 'Mensal',
	'interval:yearly' => 'Anual',

/**
 * System settings
 */

	'installation:sitename' => "O nome do seu site:",
	'installation:sitedescription' => "Breve descrição do seu site (opcional):",
	'installation:sitedescription:help' => "Com plugins agrupados, isso aparece apenas na meta tag de descrição dos resultados do mecanismo de busca.",
	'installation:sitepermissions' => "As permissões de acesso padrão:",
	'installation:language' => "O idioma padrão do seu site:",
	'installation:debug' => "Controle a quantidade de informações gravadas no log do servidor.",
	'installation:debug:label' => "Nível de registro:",
	'installation:debug:none' => 'Desativar registros (recomendado)',
	'installation:debug:error' => 'Registrar apenas erros críticos',
	'installation:debug:warning' => 'Erros de Log e Avisos',
	'installation:debug:notice' => 'Registre todos os erros, avisos e notificações',
	'installation:debug:info' => 'Registrar tudo',

	// Walled Garden support
	'installation:registration:description' => 'Se habilitado, os visitantes podem criar as suas próprias contas de usuário.',
	'installation:registration:label' => 'Permitir que os visitantes se registrem',
	'installation:adminvalidation:description' => 'Se ativado, usuários recém-registrados precisarão de validação manual por um administrador antes de poderem usar o site.',
	'installation:adminvalidation:label' => 'Novos usuários exigem validação manual por um administrador',
	'installation:adminvalidation:notification:description' => 'Quando ativado, os administradores do site receberão uma notificação de que há validações de usuários pendentes. O administrador pode desativar a notificação em sua página de configurações pessoais.',
	'installation:adminvalidation:notification:label' => 'Notificar os administradores sobre validações de usuários pendentes',
	'installation:adminvalidation:notification:direct' => 'Direto',
	'installation:walled_garden:description' => 'Se ativado, visitantes desconectados poderão ver apenas páginas marcadas como públicas (como login e registro).',
	'installation:walled_garden:label' => 'Restringir páginas a usuários conectados',

	'installation:siteemail' => "Endereço de e-mail do site (usado ao enviar e-mails do sistema):",
	'installation:siteemail:help' => "Aviso: Não utilize um endereço de e-mail que você possa ter associado a outros serviços de terceiros, como sistemas de tickets, que realizam análise de e-mails recebidos, pois isso pode expor você e seus usuários a vazamentos não intencionais de dados privados e tokens de segurança. O ideal é criar um novo endereço de e-mail dedicado que atenda apenas este site.",
	'installation:default_limit' => "Número padrão de itens por página",

	'admin:site:access:warning' => "Esta é a configuração de privacidade sugerida aos usuários quando criam novos conteúdos. Alterá-la não altera o acesso ao conteúdo.",
	'installation:allow_user_default_access:description' => "Habilite isso para permitir que os usuários definam suas próprias configurações de privacidade sugeridas que substituem a sugestão do sistema.",
	'installation:allow_user_default_access:label' => "Permitir acesso padrão do usuário",

	'installation:simplecache:description' => "O cache simples aumenta o desempenho armazenando em cache conteúdo estático, incluindo alguns arquivos CSS e JavaScript.",
	'installation:simplecache:label' => "Usar cache simples (recomendado)",

	'installation:cache_symlink:description' => "O link simbólico para o diretório de cache simples permite que o servidor forneça visualizações estáticas ignorando o mecanismo, o que melhora consideravelmente o desempenho e reduz a carga do servidor",
	'installation:cache_symlink:label' => "Usar link simbólico para diretório de cache simples (recomendado)",
	'installation:cache_symlink:warning' => "O link simbólico foi estabelecido. Se, por algum motivo, você quiser remover o link, exclua o diretório de links simbólicos do seu servidor.",
	'installation:cache_symlink:error' => "Devido à configuração do seu servidor, o link simbólico não pode ser estabelecido automaticamente. Consulte a documentação e estabeleça o link simbólico manualmente.",

	'installation:minify:description' => "O cache simples também pode melhorar o desempenho compactando arquivos JavaScript e CSS. (Requer que o cache simples esteja habilitado.)",
	'installation:minify_js:label' => "Compactar JavaScript (recomendado)",
	'installation:minify_css:label' => "Compactar CSS (recomendado)",

	'installation:htaccess:needs_upgrade' => "Você deve atualizar seu arquivo .htaccess (use install/config/htaccess.dist como guia).",
	'installation:htaccess:localhost:connectionfailed' => "O Elgg não consegue se conectar a si mesmo para testar as regras de reescrita corretamente. Verifique se o curl está funcionando e se não há restrições de IP impedindo conexões com o host local.",

	'installation:systemcache:description' => "O cache do sistema diminui o tempo de carregamento do Elgg armazenando dados em cache em arquivos.",
	'installation:systemcache:label' => "Usar cache do sistema (recomendado)",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content' => 'Conteúdo',
	'admin:legend:comments' => 'Comentários',
	'admin:legend:content_access' => 'Acesso ao Conteúdo',
	'admin:legend:site_access' => 'Acessar o Site',
	'admin:legend:debug' => 'Depuração e Registros',
	
	'config:i18n:allowed_languages' => "Idiomas permitidos",
	'config:i18n:allowed_languages:help' => "Somente os idiomas permitidos podem ser utilizados pelos usuários. Inglês e o idioma do site são sempre permitidos.",
	'config:i18n:who_can_change_language' => "Quem pode alterar o idioma",
	'config:i18n:who_can_change_language:everyone' => "Todos",
	'config:i18n:who_can_change_language:admin_only' => "Somente Administradores",
	'config:i18n:who_can_change_language:nobody' => "Ninguém",
	
	'config:users:remove_unvalidated_users_days' => "Número de dias após os quais usuários não validados serão removidos",
	'config:users:remove_unvalidated_users_days:help' => "Usuários não validados serão removidos automaticamente após o número de dias configurado. Se deixado em branco, os usuários não validados não serão removidos automaticamente.",
	'config:users:can_change_username' => "Permitir que os usuários alterem seus nomes de usuário",
	'config:users:can_change_username:help' => "Se não for permitido, somente os administradores podem alterar o nome de usuário dos usuários do site",
	'config:users:user_joined_river' => "Adicionar uma notificação na atividade quando um usuário entrar no site",
	'config:remove_branding:label' => "Remover a marca Elgg",
	'config:remove_branding:help' => "Ao longo do site, há vários links e logotipos que mostram que este site foi criado com Elgg. Se você remover a marca, considere fazer uma doação em https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Desativar Feeds RSS",
	'config:disable_rss:help' => "Desabilite isso para não promover mais a disponibilidade de feeds RSS",
	'config:friendly_time_number_of_days:label' => "Número de dias em que o tempo amigável é apresentado",
	'config:friendly_time_number_of_days:help' => "Você pode configurar por quantos dias a notação de tempo amigável será usada. Após o período definido, a hora amigável mudará para o formato de data padrão. Definir como 0 desativará a notação de tempo amigável.",
	'config:content:comment_box_collapses' => "A caixa de comentários é recolhida após o primeiro comentário sobre o conteúdo",
	'config:content:comment_box_collapses:help' => "Isso só se aplica se a lista de comentários for classificada dos mais recentes primeiro",
	'config:content:comments_group_only' => "Somente os membros do grupo podem comentar o conteúdo do grupo",
	'config:content:comments_latest_first' => "Os comentários devem ser listados com o comentário mais recente primeiro",
	'config:content:comments_latest_first:help' => "Isto controla o comportamento padrão da listagem de comentários em uma página de detalhes de conteúdo. Se desabilitado, isso também moverá a caixa de comentários para o final da lista de comentários.",
	'config:content:comments_max_depth' => "Níveis máximos de comentários encadeados",
	'config:content:comments_max_depth:help' => "Quando ativado, comentários podem ser feitos em outros comentários até a profundidade máxima configurada.",
	'config:content:comments_max_depth:none' => "Não são permitidos comentários encadeados",
	'config:content:comments_per_page' => "O número de comentários por página",
	'config:content:pagination_behaviour' => "Comportamento de paginação padrão de listas",
	'config:content:pagination_behaviour:help' => "Controla como os dados da lista são atualizados ao usar paginação. Listagens individuais podem substituir esse comportamento padrão.",
	'config:content:pagination_behaviour:navigate' => "Navegar para a próxima página",
	'config:content:pagination_behaviour:ajax-replace' => "Substituir os dados da lista sem recarregar a página inteira",
	'config:content:pagination_behaviour:ajax-append' => "Adicionar novos dados de lista antes ou depois da lista",
	'config:content:pagination_behaviour:ajax-append-auto' => "Adicionar novos dados de lista antes ou depois da lista (automaticamente se rolado para a visualização)",
	'config:content:mentions_display_format' => "Menciona formato de exibição",
	'config:content:mentions_display_format:help' => "Isso decide como um usuário mencionado ficará visível em seu conteúdo",
	'config:content:mentions_display_format:username' => "Usuário",
	'config:content:mentions_display_format:display_name' => "Nome de Exibição",
	'config:content:trash_enabled:label' => "Habilitar Lixeira",
	'config:content:trash_enabled:help' => "Ao excluir um item, ele pode ser movido para a lixeira antes de ser excluído permanentemente. Itens na lixeira podem ser restaurados por um usuário.",
	'config:content:trash_retention:label' => "Número de dias que o conteúdo permanecerá na lixeira após ser excluído",
	'config:content:trash_retention:help' => "Você pode configurar por quantos dias as entidades excluídas permanecerão armazenadas na lixeira. Após o período de retenção, o item na lixeira será excluído permanentemente. Use 0 para manter os itens na lixeira por tempo indeterminado.",
	'config:email' => "E-mail",
	'config:email_html_part:label' => "Habilitar e-mail HTML",
	'config:email_html_part:help' => "O correio de saída será encapsulado em um modelo HTML",
	'config:email_html_part_images:label' => "Substitua imagens de e-mail",
	'config:email_html_part_images:help' => "Controle se e como as imagens em e-mails enviados devem ser processadas. Quando ativado, todas as imagens serão incorporadas aos e-mails. Nem todos os clientes de e-mail suportam as diferentes opções; certifique-se de testar a opção escolhida.",
	'config:email_html_part_images:base64' => "Codificado em Base64",
	'config:email_html_part_images:attach' => "Anexos",
	'config:delayed_email:label' => "Ativar notificações atrasadas por e-mail",
	'config:delayed_email:help' => "Ofereça aos usuários notificações de e-mail atrasadas para agrupar notificações recebidas em um período (diário, semanal)",
	'config:message_delay:label' => "Atraso de mensagem do sistema",
	'config:message_delay:help' => "Número padrão de segundos antes que uma mensagem de sucesso desapareça",

	'upgrading' => 'Atualizando...',
	'upgrade:core' => 'A sua instalação do Elgg foi atualizada.',
	'upgrade:unlock' => 'Desbloquear atualização',
	'upgrade:unlock:confirm' => "O Banco de Dados está bloqueado para outra atualização. Executar atualizações simultâneas é perigoso. Você só deve continuar se souber que não há outra atualização em andamento. Desbloquear?",
	'upgrade:terminated' => 'A atualização foi encerrada por um manipulador de eventos',
	'upgrade:locked' => "Não é possível atualizar. Outra atualização está em andamento. Para limpar o bloqueio de atualização, acesse a seção Admin.",
	'upgrade:unlock:success' => "Atualização desbloqueada com sucesso.",

	'admin:pending_upgrades' => 'O site tem atualizações pendentes que exigem sua atenção imediata.',
	'admin:view_upgrades' => 'Ver atualizações pendentes.',
	'item:object:elgg_upgrade' => 'Atualização do Site',
	'collection:object:elgg_upgrade' => 'Atualizações do Site',
	'admin:upgrades:none' => 'Sua instalação está atualizada!',

	'upgrade:success_count' => 'Atualizado:',
	'upgrade:error_count' => 'Erros: %s',
	'upgrade:finished' => 'Atualização concluída',
	'upgrade:should_be_skipped' => 'Nenhum item para atualizar',
	'upgrade:count_items' => '%d itens para atualizar',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Alinhar colunas GUID do Banco de Dados',
	
/**
 * Welcome
 */

	'welcome' => "Bem-vindo",
	'welcome:user' => 'Bem-vindo %s',

/**
 * Emails
 */

	'email:from' => 'De',
	'email:to' => 'Para',
	'email:subject' => 'Assunto',
	'email:body' => 'Conteúdo',

	'email:settings' => "Configurações de E-mail",
	'email:address:label' => "Endereço de E-mail",
	'email:address:help:confirm' => "Alteração de endereço de e-mail pendente para '%s'. Verifique a caixa de entrada para obter instruções.",
	'email:address:password' => "Senha",
	'email:address:password:help' => "Para poder alterar seu endereço de e-mail, você precisa fornecer sua senha atual.",

	'email:save:success' => "Novo endereço de e-mail salvo.",
	'email:save:fail' => "Não foi possível salvar o novo endereço de e-mail.",
	'email:save:fail:password' => "A senha não corresponde à sua senha atual, não foi possível alterar seu endereço de e-mail",

	'friend:newfriend:subject' => "%s fez de você um amigo!",

	'email:changepassword:subject' => "Senha alterada!",
	'email:changepassword:body' => "A sua senha foi alterada.",

	'email:resetpassword:subject' => "A senha foi redefinida!",
	'email:resetpassword:body' => "Sua senha foi redefinida para: %s",

	'email:changereq:subject' => "Solicitação de alteração de senha.",
	
	'account:email:request:success' => "Seu novo endereço de e-mail será salvo após a confirmação. Verifique a caixa de entrada de '%s' para obter mais instruções.",
	'email:request:email:subject' => "Por favor confirme seu endereço de e-mail",
	
	'account:email:request:error:no_new_email' => "Nenhuma alteração de endereço de e-mail pendente",
	
	'email:confirm:email:old:subject' => "Seu endereço de e-mail foi alterado",
	
	'email:confirm:email:new:subject' => "Seu endereço de e-mail foi alterado",

	'account:email:admin:validation_notification' => "Notifique-me quando houver usuários que necessitem de validação por um administrador",
	'account:email:admin:validation_notification:help' => "Devido às configurações do site, usuários recém-cadastrados precisam de validação manual por um administrador. Com esta configuração, você pode desativar notificações sobre solicitações de validação pendentes.",
	
	'account:validation:pending:title' => "Validação de conta pendente",
	'account:validation:pending:content' => "Sua conta foi registrada com sucesso! No entanto, antes de poder usá-la, um administrador do site precisa validá-la. Você receberá um e-mail quando sua conta for validada.",

/**
 * user default access
 */

	'default_access:settings' => "Seu nível de acesso padrão",
	'default_access:label' => "Acesso padrão",
	'user:default_access:success' => "Seu novo nível de acesso padrão foi salvo.",
	'user:default_access:failure' => "Seu novo nível de acesso padrão não pôde ser salvo.",

/**
 * Comments
 */

	'comments:count' => "%s comentários",
	'item:object:comment' => 'Comentário',
	'collection:object:comment' => 'Comentários',
	'notification:object:comment:create' => "Enviar uma notificação quando um comentário for criado",

	'river:object:default:comment' => '%s comentou em %s',

	'generic_comments:add' => "Deixe um comentário",
	'generic_comments:edit' => "Editar comentário",
	'generic_comments:latest' => "Últimos comentários",
	'generic_comment:login_required' => "Você precisa conectar para poder comentar.",
	'generic_comment:posted' => "Seu comentário foi postado com sucesso.",
	'generic_comment:updated' => "O comentário foi atualizado com sucesso.",
	'entity:delete:object:comment:success' => "O comentário foi excluído com sucesso.",
	'generic_comment:blank' => "Desculpe, você precisa realmente inserir algo em seu comentário antes que possamos salvá-lo.",
	'generic_comment:notfound' => "Desculpe, não conseguimos encontrar o comentário especificado.",
	'generic_comment:failure' => "Ocorreu um erro inesperado ao salvar o comentário.",
	'generic_comment:none' => 'Sem comentários',
	'generic_comment:on' => '%s em %s',
	'generic_comment:by_owner' => 'Comentário do Proprietário',
	'generic_comment:notification:owner:summary' => 'Você tem um novo comentário em: %s',
	
	'generic_comment:notification:user:summary' => 'Um novo comentário em: %s',

	'notification:mentions:object:comment:subject' => '%s mencionou você em um comentário',

/**
 * Entities
 */

	'byline' => 'Por %s',
	'byline:ingroup' => 'no grupo %s',
	
	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item não encontrado.',
	'entity:delete:permission_denied' => 'Você não tem permissão para excluir este item.',
	'entity:delete:success' => '%s foi excluído.',
	'entity:delete:fail' => '%s não pôde ser excluído.',

	'entity:edit:success' => 'A entidade foi salva com sucesso',
	'entity:edit:group:success' => 'O grupo foi salvo com sucesso',
	'entity:edit:object:success' => 'O objeto foi salvo com sucesso',
	'entity:edit:user:success' => 'O usuário foi salvo com sucesso',
	
	'entity:restore:item' => 'Item',
	'entity:restore:item_not_found' => 'Item não encontrado',
	'entity:restore:container_permission' => 'Você não tem permissão para restaurar este item para %s',
	'entity:restore:permission_denied' => 'Você não tem permissão para restaurar este item',
	'entity:restore:success' => '%s foi restaurado',
	'entity:restore:fail' => '%s não pôde ser restaurado',
	
	'entity:subscribe' => "Inscrever-se",
	'entity:subscribe:disabled' => "Suas configurações de notificação padrão impedem que você assine este conteúdo",
	'entity:subscribe:success' => "Você se inscreveu com sucesso em %s",
	'entity:subscribe:fail' => "Ocorreu um erro ao assinar %s",
	
	'entity:unsubscribe' => "Cancelar Inscrição",
	'entity:unsubscribe:success' => "Você cancelou a inscrição de %s com sucesso",
	'entity:unsubscribe:fail' => "Ocorreu um erro ao cancelar a assinatura de %s",
	
	'entity:mute' => "Silenciar notificações",
	'entity:mute:success' => "Você silenciou com sucesso as notificações de %s",
	'entity:mute:fail' => "Ocorreu um erro ao silenciar notificações de %s",
	
	'entity:unmute' => "Ativar notificações",
	'entity:unmute:success' => "Você ativou com sucesso as notificações de %s",
	'entity:unmute:fail' => "Ocorreu um erro ao ativar notificações de %s",


/**
 * Annotations
 */
	
	'annotation:delete:fail' => "Ocorreu um erro ao remover a anotação",
	'annotation:delete:success' => "A anotação foi removida com sucesso",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Formulário sem campos __token ou __ts',
	'actiongatekeeper:tokeninvalid' => "A página que você estava usando expirou. Tente novamente.",
	'actiongatekeeper:timeerror' => 'A página que você estava usando expirou. Atualize e tente novamente.',
	'actiongatekeeper:pluginprevents' => 'Desculpe. Seu formulário não pôde ser enviado por um motivo desconhecido.',
	'actiongatekeeper:uploadexceeded' => 'O tamanho do(s) arquivo(s) carregado(s) excedeu o limite definido pelo administrador do seu site',

/**
 * Javascript
 */
	'js:lightbox:current' => "imagem %s de %s",

/**
 * Diagnostics
 */
	'diagnostics:report' => 'Relatório de Diagnóstico',
	
/**
 * Trash
 */
	'trash:menu:page' => "Lixeira",
	
	'trash:imprint:actor' => "Excluído por: %s",
	'trash:imprint:type' => "Tipo: %s",
	
	'trash:owner:title' => "Lixeira",
	'trash:owner:title_owner' => "lixo de %s",
	'trash:group:title' => "lixo de %s",
	
	'trash:no_results' => "Nenhum item encontrado no lixo",
	
	'trash:notice:retention' => "Os itens descartados serão removidos automaticamente após %s dias.",
	
	'trash:restore:container:owner' => "Você pode restaurar este item da lixeira para sua seção pessoal, já que o grupo original também foi removido.",
	'trash:restore:container:choose' => "Como o grupo original deste item foi removido, você pode escolher onde restaurar o item.",
	'trash:restore:container:group' => "Restaurar em um grupo diferente",
	'trash:restore:group' => "Pesquisar Grupos",
	'trash:restore:group:help' => "Certifique-se de que o grupo selecionado tenha o recurso ativo para o item ou poderá ocorrer um erro.",
	'trash:restore:owner' => "Restaurar para o proprietário (%s)",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Desenvolvido por Elgg",
	'field:required' => "Obrigatório",

/**
 * Accessibility
 */
	'aria:label:admin:users:search' => "Pesquisar Usuário",

	'menu:admin_footer:header' => "Rodapé do Administrador",
	'menu:admin_header:header' => "Cabeçalho do Administrador",
	'menu:admin:users:bulk:header' => "Ações em massa de usuários",
	'menu:annotation:header' => "Anotação",
	'menu:comments:header' => "Comentários",
	'menu:entity:header' => "Entidade",
	'menu:entity_navigation:header' => "Navegação de Entidade",
	'menu:filter:header' => "Filtro",
	'menu:footer:header' => "Rodapé",
	'menu:login:header' => "Conectar",
	'menu:owner_block:header' => "Bloco do Proprietário",
	'menu:page:header' => "Páginas",
	'menu:relationship:header' => "Relacionamento",
	'menu:river:header' => "Atividades",
	'menu:site:header' => "Site",
	'menu:social:header' => "Social",
	'menu:title:header' => "Título",
	'menu:title:widgets:header' => "Administração de Widgets",
	'menu:topbar:header' => "Barra Superior",
	'menu:user_hover:header' => "Passar o mouse sobre o usuário",
	'menu:user:unvalidated:header' => "Usuário não validado",
	'menu:widget:header' => "Controles do Widget",

/**
 * Cli commands
 */
	'cli:login:success:log' => "Conectado como %s [guid: %s]",
	'cli:response:output' => "Resposta:",
	'cli:option:as' => "Execute o comando em nome de um usuário com o nome de usuário fornecido",
	'cli:option:language' => "Execute o comando no idioma fornecido (por exemplo, en, nl ou de)",
	
	'cli:cache:clear:description' => "Limpar Caches do Elgg",
	'cli:cache:invalidate:description' => "Invalidar Caches do Elgg",
	'cli:cache:purge:description' => "Limpar Caches do Elgg",
	
	'cli:cron:description' => "Executa manipuladores Cron para todos ou para o intervalo especificado",
	'cli:cron:option:interval' => "Nome do intervalo (por exemplo, de hora em hora)",
	'cli:cron:option:force' => "Forçar a execução de comandos Cron mesmo que ainda não estejam vencidos",
	'cli:cron:option:time' => "Hora da inicialização do Cron",
	'cli:database:seed:option:image_folder' => "Caminho para uma pasta local contendo imagens para semeadura",
	'cli:database:seed:option:type' => "Tipo de entidades a (des)semeadurar (%s)",
	'cli:database:seed:option:create_since' => "Uma string de tempo PHP para definir o limite inferior de tempo de criação de entidades semeadas",
	'cli:database:seed:log:error:logged_in' => "A semeadura do Banco de Dados não deve ser executada com um usuário conectado",
	'cli:database:seed:ask:limit' => "Quantos itens a semear para o semeador '%s'",

	'cli:database:seeders:description' => "Listar todos os semeadores de Banco de Dados disponíveis com a contagem atual de entidades semeadas",
	
	'cli:database:unseed:description' => "Remove entidades falsas semeadas do Banco de Dados",
	
	'cli:plugins:activate:description' => "Ativar plugin(s)",
	'cli:plugins:activate:option:force' => "Resolva conflitos desativando os plugins conflitantes e habilitando os necessários",
	'cli:plugins:activate:argument:plugins' => "IDs dos Plugins a serem ativados",
	'cli:plugins:activate:progress:start' => "Ativando plugins",
	
	'cli:plugins:deactivate:description' => "Desativar plugin(s)",
	'cli:plugins:deactivate:option:force' => "Forçar a desativação de todos os plugins dependentes",
	'cli:plugins:deactivate:argument:plugins' => "IDs dos Plugins a serem desativados",
	'cli:plugins:deactivate:progress:start' => "Desativando plugins",
	
	'cli:plugins:list:description' => "Listar todos os plugins instalados no site",
	'cli:plugins:list:option:status' => "Status do Plugin ( %s )",
	'cli:plugins:list:option:refresh' => "Atualizar lista de plugins com plugins instalados recentemente",
	'cli:plugins:list:error:status' => "%s não é um status válido. As opções permitidas são: %s",
	
	'cli:upgrade:description' => "Execute atualizações do sistema",
	'cli:upgrade:option:force' => "Forçar a execução das atualizações mesmo que uma atualização já esteja em execução.",
	'cli:upgrade:argument:async' => "Executar atualizações assíncronas pendentes",
	'cli:upgrade:system:upgraded' => "As atualizações do sistema foram executadas",
	'cli:upgrade:system:failed' => "As atualizações do sistema falharam",
	'cli:upgrade:async:upgraded' => "Atualizações assíncronas foram executadas",
	'cli:upgrade:aysnc:failed' => "As atualizações assíncronas falharam",
	
	'cli:upgrade:batch:description' => "Executa uma ou mais atualizações",
	'cli:upgrade:batch:argument:upgrades' => "Uma ou mais atualizações (nomes de classe) a serem executadas",
	'cli:upgrade:batch:option:force' => "Execute a atualização mesmo que ela tenha sido concluída antes",
	'cli:upgrade:batch:finished' => "A execução de atualizações foi concluída",
	'cli:upgrade:batch:notfound' => "Nenhuma classe de atualização encontrada para %s",

	'cli:upgrade:list:description' => "Lista todas as atualizações no sistema",
	'cli:upgrade:list:completed' => "Atualizações concluídas",
	'cli:upgrade:list:pending' => "Atualizações pendentes",
	'cli:upgrade:list:notfound' => "Nenhuma atualização encontrada",
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abcásio",
	"af" => "Afrikaans",
	"am" => "Amárico",
	"ar" => "Árabe",
	"as" => "Assamês",
	"ay" => "Aimará",
	"az" => "Azerbaijano",
	"ba" => "Basquir",
	"be" => "Bielorrusso",
	"bg" => "Búlgaro",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali / Bangla",
	"bo" => "Tibetano",
	"br" => "Bretão",
	"ca" => "Catalão",
	"cmn" => "Chinês Mandarim", // ISO 639-3
	"co" => "Córsega",
	"cs" => "Tcheco",
	"cy" => "Galês",
	"da" => "Dinamarquês",
	"de" => "Alemão",
	"dz" => "Dzongkha",
	"el" => "Grego",
	"en" => "Inglês",
	"eo" => "Esperanto",
	"es" => "Espanhol",
	"et" => "Estoniano",
	"eu" => "Basco",
	"eu_es" => "Basco (Espanha)",
	"fa" => "Persa",
	"fi" => "Finlandês",
	"fj" => "Fiji",
	"fo" => "Feroês",
	"fr" => "Francês",
	"fy" => "Frísio",
	"ga" => "Irlandês",
	"gd" => "(Escocês) Gaélico",
	"gl" => "Galego",
	"gn" => "Guarani",
	"gu" => "Guzerate",
	"he" => "Hebraico",
	"ha" => "Hauça",
	"hi" => "Hindi",
	"hr" => "Croata",
	"hu" => "Húngaro",
	"hy" => "Armênio",
	"ia" => "Interlingua",
	"id" => "Indonésio",
	"ie" => "Interlíngua",
	"ik" => "Inupiaq",
	"is" => "Islandês",
	"it" => "Italiano",
	"iu" => "Inuktitut",
	"iw" => "Hebraico (obsoleto)",
	"ja" => "Japonês",
	"ji" => "Iídiche (obsoleto)",
	"jw" => "Javanês",
	"ka" => "Georgiano",
	"kk" => "Cazaque",
	"kl" => "Groenlandês",
	"km" => "Khmer",
	"kn" => "Canarês",
	"ko" => "Coreano",
	"ks" => "Caxemira",
	"ku" => "Curdo",
	"ky" => "Quirguiz",
	"la" => "Latim",
	"ln" => "Lingala",
	"lo" => "Lao",
	"lt" => "Lituano",
	"lv" => "Letão",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedônio",
	"ml" => "Malayalam",
	"mn" => "Mongol",
	"mo" => "Moldavo",
	"mr" => "Marathi",
	"ms" => "Malaio",
	"mt" => "Maltês",
	"my" => "Birmanês",
	"na" => "Nauru",
	"ne" => "Nepalês",
	"nl" => "Holandês",
	"no" => "Norueguês",
	"oc" => "Occitano",
	"om" => "Oromo (Afan)",
	"or" => "Oriá",
	"pa" => "Punjabi",
	"pl" => "Polonês",
	"ps" => "Pashto / Pushto",
	"pt" => "Português",
	"pt_br" => "Português (Brasil)",
	"qu" => "Quechua",
	"rm" => "Romanche",
	"rn" => "Kirundi",
	"ro" => "Romeno",
	"ro_ro" => "Romeno (Romênia)",
	"ru" => "Russo",
	"rw" => "Kinyarwanda",
	"sa" => "Sânscrito",
	"sd" => "Sindi",
	"sg" => "Sango",
	"sh" => "Servo-Croata",
	"si" => "Cingalês",
	"sk" => "Eslovaco",
	"sl" => "Esloveno",
	"sm" => "Samoano",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanês",
	"sr" => "Sérvio",
	"sr_latin" => "Sérvio (Latim)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanês",
	"sv" => "Sueco",
	"sw" => "Suaíli",
	"ta" => "Tâmil",
	"te" => "Télugo",
	"tg" => "Tajique",
	"th" => "Tailandês",
	"ti" => "Tigrínia",
	"tk" => "Turcomeno",
	"tl" => "Tagalo",
	"tn" => "Tsuana",
	"to" => "Tonga",
	"tr" => "Turco",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ucraniano",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamita",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zhuang",
	"zh" => "Chinês",
	"zh_hans" => "Chinês Simplificado",
	"zu" => "Zulu",

/**
 * Upgrades
 */
	"core:upgrade:2017080900:title" => "Alterar codificação do Banco de Dados para suporte a vários bytes",
	"core:upgrade:2017080900:description" => "Altera a codificação do Banco de Dados e da tabela para utf8mb4, a fim de oferecer suporte a caracteres multibyte, como emojis",
	
	"core:upgrade:2020102301:title" => "Remover o Plugin de Diagnóstico",
	"core:upgrade:2020102301:description" => "Exclui a entidade associada ao Plugin de Diagnóstico removido no Elgg 4.0",
	
	"core:upgrade:2021022401:title" => "Migrar assinaturas de notificação",
	"core:upgrade:2021022401:description" => "As assinaturas de notificação são armazenadas de forma diferente no Banco de Dados. Use esta atualização para migrar todas as assinaturas para o novo formulário.",
	
	"core:upgrade:2021040701:title" => "Migrar configurações de notificação do usuário",
	"core:upgrade:2021040701:description" => "Para ter uma maneira mais amigável ao desenvolvedor de armazenar as configurações de notificação de um usuário, é necessária uma migração para a nova convenção de nomenclatura.",
	
	'core:upgrade:2021040801:title' => "Migrar Preferências de Notificação na coleta de acesso",
	'core:upgrade:2021040801:description' => "Foi introduzida uma nova maneira de armazenar preferências de notificação. Esta atualização migra as configurações antigas para a nova lógica.",
	
	'core:upgrade:2021041901:title' => "Remover o Plugin de Notificações",
	'core:upgrade:2021041901:description' => "Exclui a entidade associada ao Plugin de Notificações removido no Elgg 4.0",
	
	'core:upgrade:2021060401:title' => "Adicionar proprietários de conteúdo aos assinantes",
	'core:upgrade:2021060401:description' => "Os proprietários de conteúdo devem assinar seu próprio conteúdo. Esta atualização migra todo o conteúdo antigo.",
	
	'core:upgrade:2023011701:title' => "Remover comentários encadeados órfãos",
	'core:upgrade:2023011701:description' => "Devido a um erro na forma como os comentários encadeados foram removidos, havia uma chance de criar comentários órfãos. Esta atualização removerá esses órfãos.",
	
	'core:upgrade:2024020101:title' => "Migrar coordenadas de corte de ícones",
	'core:upgrade:2024020101:description' => "As coordenadas de corte são armazenadas de forma uniforme, esta atualização migra os antigos valores de metadados x1, x2, y1 e y2",

	'core:upgrade:2024020901:title' => "Remover metadados do icontime",
	'core:upgrade:2024020901:description' => "Remover o icontime de metadados não confiável do Banco de Dados",

	'core:upgrade:2024070201:title' => "Migrar configuração de depuração",
	'core:upgrade:2024070201:description' => "Altera o valor de configuração do Banco de Dados para registro de depuração para um valor suportado",

	'core:upgrade:2024071001:title' => "Migrar a preferência de notificação de validação do administrador",
	'core:upgrade:2024071001:description' => "Move o armazenamento da preferência de notificação do administrador para as configurações de notificações",
);
