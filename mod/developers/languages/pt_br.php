<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Ferramentas',
	
	// menu
	'admin:develop_tools:inspect' => 'Inspecionar',
	'admin:inspect' => 'Inspecionar',
	'admin:develop_tools:unit_tests' => 'Testes Unitários',
	'admin:develop_tools:entity_explorer' => 'Explorador de Entidades',
	'admin:developers' => 'Desenvolvedores',
	'admin:developers:settings' => 'Configurações',
	'menu:entity_explorer:header' => 'Explorador de Entidades',
	'menu:developers_inspect_viewtype:header' => 'Inspecionar Tipos de Visualização',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Controle as suas configurações de desenvolvimento e depuração abaixo. Algumas dessas configurações também estão disponíveis em outras páginas administrativas.',
	'developers:label:simple_cache' => 'Usar Cache Simples',
	'developers:help:simple_cache' => 'Desative este cache durante o desenvolvimento. Caso contrário, alterações no seu CSS e JavaScript serão ignoradas.',
	'developers:label:system_cache' => 'Usar Cache do Sistema',
	'developers:help:system_cache' => 'Desative esta opção durante o desenvolvimento. Caso contrário, mudanças nos seus Plugins não serão reconhecidas.',
	'developers:label:debug_level' => "Nível de Rastreamento",
	'developers:help:debug_level' => "Controla a quantidade de informações registradas. Veja elgg_log() para mais informações.",
	'developers:label:display_errors' => 'Exibir erros fatais do PHP',
	'developers:label:screen_log' => "Registrar na tela",
	'developers:show_strings:default' => "Tradução Normal",
	'developers:show_strings:key_append' => "Chave de Tradução anexada",
	'developers:show_strings:key_only' => "Mostrar apenas a Chave de Tradução",
	'developers:label:show_strings' => "Exibir strings de tradução brutas",
	'developers:help:show_strings' => "Exibe as strings de tradução usadas por elgg_echo().",
	'developers:label:wrap_views' => "Envolver Views",
	'developers:help:wrap_views' => "Isso envolve quase todas as views com comentários HTML. Útil para identificar qual view está gerando determinado código HTML.
Isso pode causar problemas em views que não são HTML no tipo de view padrão.",
	'developers:label:log_events' => "Registros de Eventos",
	'developers:help:log_events' => "Grava eventos no Log. Aviso: há muitos eventos por página.",
	'developers:label:block_email' => "Bloquear todos os e-mails enviados",
	'developers:help:block_email' => "Você pode bloquear o envio de e-mails para usuários comuns ou para todos os usuários.",
	'developers:label:forward_email' => "Encaminhar todos os e-mails para um único endereço",
	'developers:help:forward_email' => "Todos os e-mails enviados serão direcionados para o endereço configurado.",
	'developers:label:enable_error_log' => "Ativar Log de Erros",
	'developers:help:enable_error_log' => "Mantém um registro separado de erros e mensagens registradas pelo error_log() com base no nível de rastreamento configurado. O log pode ser visualizado via interface administrativa.",

	'developers:block_email:forward' => 'Encaminhar todos os e-mails',
	'developers:block_email:users' => 'Apenas usuários comuns',
	'developers:block_email:all' => 'Administradores e usuários comuns',
	
	'developers:debug:off' => 'Desligado',
	'developers:debug:error' => 'Erro',
	'developers:debug:warning' => 'Aviso',
	'developers:debug:notice' => 'Notificação',
	'developers:debug:info' => 'Informação',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Visualize informações sobre entidades e realize algumas ações básicas nelas.',
	'developers:entity_explorer:guid:label' => 'Informe o GUID da entidade para inspeção',
	'developers:entity_explorer:info:attributes' => 'Atributos',
	'developers:entity_explorer:info:metadata' => 'Metadados',
	'developers:entity_explorer:info:relationships' => 'Relacionamentos',
	'developers:entity_explorer:info:owned_acls' => 'Coleções de Acesso Proprietárias',
	'developers:entity_explorer:info:acl_memberships' => 'Membros das Coleções de Acesso',
	'developers:entity_explorer:delete_entity' => 'Remover esta entidade',
	'developers:entity_explorer:inspect_entity' => 'Inspecionar esta entidade',
	'developers:entity_explorer:view_entity' => 'Visualizar esta entidade no site',
	
	// inspection
	'developers:inspect:actions' => 'Ações',
	'developers:inspect:events' => 'Eventos',
	'developers:inspect:menus' => 'Menus',
	'developers:inspect:notifications' => 'Notificações',
	'developers:inspect:notifications:type' => 'Tipo',
	'developers:inspect:notifications:subtype' => 'Subtipo',
	'developers:inspect:notifications:action' => 'Ação',
	'developers:inspect:notifications:handler' => 'Manipulador',
	'developers:inspect:priority' => 'Prioridade',
	'developers:inspect:seeders' => 'Seeders',
	'developers:inspect:simplecache' => 'Cache Simples',
	'developers:inspect:routes' => 'Rotas',
	'developers:inspect:views' => 'Views',
	'developers:inspect:views:all_filtered' => "<b>Atenção!</b> Toda entrada/saída das views é filtrada por estes Eventos:",
	'developers:inspect:views:input_filtered' => "(entrada filtrada pelo manipulador de evento: %s)",
	'developers:inspect:views:filtered' => "(filtrado pelo manipulador de evento: %s)",
	'developers:inspect:widgets' => 'Widgets',
	'developers:inspect:widgets:context' => 'Contexto',
	'developers:inspect:functions' => 'Funções',
	'developers:inspect:file_location' => 'Caminho do arquivo a partir da raiz do Elgg ou controller',
	'developers:inspect:route' => 'Nome da Rota',
	'developers:inspect:path' => 'Padrão do Caminho',
	'developers:inspect:resource' => 'View de Recurso',
	'developers:inspect:handler' => 'Manipulador',
	'developers:inspect:controller' => 'Controladores',
	'developers:inspect:file' => 'Arquivos',
	'developers:inspect:middleware' => 'Arquivos',
	'developers:inspect:handler_type' => 'Manipulado por',
	'developers:inspect:services' => 'Serviços',
	'developers:inspect:service:name' => 'Nome',
	'developers:inspect:service:path' => 'Definição',
	'developers:inspect:service:class' => 'Classe',

	// event logging
	'developers:request_stats' => "Estatísticas da requisição (não inclui o evento de desligamento)",
	'developers:event_log_msg' => "%s: '%s, %s' em %s",
	'developers:log_queries' => "Consultas ao Banco de Dados: %s",
	'developers:boot_cache_rebuilt' => "O cache de inicialização foi reconstruído para esta requisição",
	'developers:elapsed_time' => "Tempo decorrido (s)",

	'admin:develop_tools:error_log' => 'Log de Erros',
	'developers:logs:empty' => 'O log de erros está vazio',
);
