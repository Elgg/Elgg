<?php
return array(
	// menu
	'admin:develop_tools' => 'Ferramentas',
	'admin:develop_tools:sandbox' => 'Temas',
	'admin:develop_tools:inspect' => 'Inspecionar',
	'admin:develop_tools:unit_tests' => 'Unidades de Testes',
	'admin:developers' => 'Desenvolvedores',
	'admin:developers:settings' => 'Configurações dos desenvolvedores',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Controle seus desenvolvimento e configurações de depuração abaixo.  Algumas destas configurações estão disponível em outras páginas de administração.',
	'developers:label:simple_cache' => 'Usar \'cache\' simples',
	'developers:help:simple_cache' => 'Desligar o \'cache de arquivos\' enquanto estiver desenvolvendo.  Caso contrário, as mudanças nas visões <i>( ou \'views\')</i>, incluindo o CSS, serão ignoradas.',
	'developers:label:system_cache' => 'Use sistema de armazenamento (cache)',
	'developers:help:system_cache' => 'Desligue isto enquanto estiver desenvolvendo.  Caso contrário, mudanças em seus plugins não serão registradas. ',
	'developers:label:debug_level' => "Nível de rastreio",
	'developers:help:debug_level' => "Estes controlam a quantidade de informação registrada.  Veja 'elgg_log()' para mais informações.",
	'developers:label:display_errors' => 'Apresente erros críticos no PHP <i>(fatal PHP errors)</i>',
	'developers:help:display_errors' => "Por padrão, o arquivo '.htaccess' do Elgg suprime a apresentação de erros fatais.",
	'developers:label:screen_log' => "Apresenta os registros (logs) na tela",
	'developers:help:screen_log' => "Apresenta as saídas de elgg/log() e elgg_dump() na página web.",
	'developers:label:show_strings' => "Apresenta os textos ('strings') que geram as traduções.",
	'developers:help:show_strings' => "Apresenta os textos ('strings') usados por elgg_echo().",
	'developers:label:wrap_views' => "Envolver as visões (Wrap views)",
	'developers:help:wrap_views' => "Este envolve quase todas as vistas com comentários códigos HTML. Útil para encontrar as visões que criam algum código HTML em particular",
	'developers:label:log_events' => "Registra eventos e 'plugin hooks'",
	'developers:help:log_events' => "Escreve eventos e 'plugin hooks' no registro de logs. Aviso: existem muitos destes por página",

	'developers:debug:off' => 'Desligar',
	'developers:debug:error' => 'Erro',
	'developers:debug:warning' => 'Aviso',
	'developers:debug:notice' => 'Notícias',
	'developers:debug:info' => 'Info',
	
	// inspection
	'developers:inspect:help' => 'Inspecionar configuração do framework Elgg.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' em %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Introdução',
	'theme_sandbox:breakout' => 'Rompendo o iframe (Break out of iframe)',
	'theme_sandbox:buttons' => 'Botões',
	'theme_sandbox:components' => 'Componentes',
	'theme_sandbox:forms' => 'Formulários',
	'theme_sandbox:grid' => 'Grade',
	'theme_sandbox:icons' => 'Icones',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Esboços (Layouts)',
	'theme_sandbox:modules' => 'Módulos',
	'theme_sandbox:navigation' => 'Navegação',
	'theme_sandbox:typography' => 'Tipografia',

	'theme_sandbox:icons:blurb' => 'Use <em>elgg_view_icon($name)</em> ou a classe elgg-icon-$name para apresentar os icones.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg possui testes de unidades e de integração para detecção de erros nas classes e funções do núcleo (core).',
	'developers:unit_tests:warning' => 'Aviso: Não execute estes testes em sites de produção. Eles podem corromper a base de dados.',
	'developers:unit_tests:run' => 'Execute',

	// status messages
	'developers:settings:success' => 'Configurações salvas',
);
