<?php
return array(
	'install:title' => 'Instalação do Elgg',
	'install:welcome' => 'Bem-vindo',
	'install:requirements' => 'Verificando requisitos',
	'install:database' => 'Instalação do Banco de Dados',
	'install:settings' => 'Configurar o site',
	'install:admin' => 'Criar conta do administrador',
	'install:complete' => 'Finalizado',

	'install:next' => 'Proximo',
	'install:refresh' => 'Atualiza',

	'install:welcome:instructions' => "A instalacao do Elgg possui 6 fases simples e ler esta mensagem de boas vindas e o primeiro passo!

Se você ainda não fez, leia através da instalação as instrucoes incluidas com Elgg (ou clique no link de instrucoes no final da pagina).

Se você está pronto para prosseguir, clique no botão PROXIMO.",
	'install:requirements:instructions:success' => "Seu servidor passou na verificacao de requisitos.",
	'install:requirements:instructions:failure' => "Seu servidor falhou na verificacao de requisitos. Depois que voce corrigir as situacoes apontadas abaixo, atualize a pagina. Verifique os links de solucao de problemas <i>(troubleshooting links)</i> no final da pagina se voce necessitar de ajuda adicional.",
	'install:requirements:instructions:warning' => "Seu servidor passou na verificacao de requisitos, mas existe pelo menos um aviso. Recomendamos que verifique a pagina de de solucao de problemas <i>(troubleshooting page)</i> para mais detalhes.",

	'install:require:php' => 'PHP ',
	'install:require:rewrite' => 'Servidor Web',
	'install:require:settings' => 'Arquivos de configuração',
	'install:require:database' => 'Banco de dados',

	'install:check:root' => 'Seu servidor web nao possui permissao para criar o arquivo <b>.htaccess</b> no diretorio raiz do Elgg. Voce tem duas opcoes:

		1. Altere as permissoes do diretorio raiz

		2. Copie o arquivo htaccess_dist para \'.htaccess',

	'install:check:php:version' => 'O Elgg necessita que esteja instalado o PHP %s ou superior. Este servidor esta usando a versao %s.',
	'install:check:php:extension' => 'O Elgg necessita a extensao PHP %s ativada.',
	'install:check:php:extension:recommend' => 'É recomendado que a extensao PHP %s esteja instalada.',
	'install:check:php:open_basedir' => 'A diretiva PHP <b>open_basedir</b> <i>(PHP directive)</i> pode prevenir que o Elgg salve arquivos para o diretório de dados <i>(data directory)</i>.',
	'install:check:php:safe_mode' => 'Executar o PHP no modo \'safe mode\' nao e recomendado e pode causar problemas com o Elgg.',
	'install:check:php:arg_separator' => '<b>arg_separator.output</b> deve ser <b>&amp;</b> para o Elgg executar e o valor do seu servidor e %s',
	'install:check:php:register_globals' => '<b>Register globals</b> deve ser desligado.',
	'install:check:php:session.auto_start' => "<b>session.auto_start</b> deve estar desligado para o Elgg executar. Senão <i>(Either)</i> altere a configuracao do seu servidor e adicione esta diretiva no arquivo <b>.htaccess</b> do Elgg.",

	'install:check:enginedir' => 'Seu servidor web nao possui permissao para criar o arquivo <b>settings.php</b> no diretorio <b>engine</b>. Voce tem duas opcoes:

		1. Altere as permissoes do diretorio <b>engine</b>

		2. Copie o arquivo <b>settings.example.php</b> para <b>settings.php</b> e siga as instruções dele para configurar os parametros do banco de dados.',
	'install:check:readsettings' => 'Um arquivo de configuração existe no diretorio <b>engine</b>, mas o servidor web nao pode executar a leitura. Voce pode apagar o arquivo ou alterar as permissoes de leitura dele.',

	'install:check:php:success' => "Seu servidor de PHP satisfaz todas as necessidades do Elgg.",
	'install:check:rewrite:success' => 'O teste de regras de escrita foi um sucesso <i>(rewrite rules)</i>.',
	'install:check:database' => 'As necessidades do banco de dados sao verificadas quando o Elgg carrega esta base.',

	'install:database:instructions' => "Se voce ainda nao criou a base de dados para o Elgg, faca isso agora.  Entao preencha os valores abaixo para iniciar o banco de dados do Elgg.",
	'install:database:error' => 'Aconteceu um erro ao criar a base de dados do Elgg e a instalacao nao pode continuar. Revise a mensagem abaixo e corriga os problemas. se voce precisar de mais ajuda, visite o link de solucao de problemas de instalacao <i>(Install troubleshooting link)</i> ou envie mensagem no forum da comundade Elgg.',

	'install:database:label:dbuser' =>  'Usuário no banco de dados <i>(Database Username)</i>',
	'install:database:label:dbpassword' => 'Senha no banco de dados <i>(Database Password)</i>',
	'install:database:label:dbname' => 'Nome da base de dados <i>(Database Name)</i>',
	'install:database:label:dbhost' => 'Hospedagem da base de dados <i>(Database Host)</i>',
	'install:database:label:dbprefix' => 'Prefixo das tabelas no banco de dados <i>(Database Table Prefix)</i>',

	'install:database:help:dbuser' => 'Usuario que possui acesso pleno ao banco de dados MySQL que voce criou para o Elgg',
	'install:database:help:dbpassword' => 'Senha para a conta do usuário da base de dados definida acima',
	'install:database:help:dbname' => 'Nome da base de dados do Elgg',
	'install:database:help:dbhost' => 'Hospedagem do servidor MySQL (geralmente <b>localhost</b>)',
	'install:database:help:dbprefix' => "O prefixo a ser atribuido para todas as tabelas do Elgg (geralmente <b>elgg_</b>)",

	'install:settings:instructions' => 'Precisamos de algumas informacoes sobre o site assim que configuramos o Elgg. Se voce ainda nao criou um diretorio de dados <i>(data directory)</i> para o Elgg, por favor faca isso antes de completar esta etapa.',

	'install:settings:label:sitename' => 'Nome do Site <i>(Site Name)</i>',
	'install:settings:label:siteemail' => 'Endereco de email do site <i>(Site Email Address)</i>',
	'install:settings:label:wwwroot' => 'URL do site <i>(Site URL)</i>',
	'install:settings:label:path' => 'Diretorio de instalacão do Elgg <i>(Install Directory)</i>',
	'install:settings:label:dataroot' => 'Diretorio de dados <i>(Data Directory)</i>',
	'install:settings:label:language' => 'Linguagem do site <i>(Site Language)</i>',
	'install:settings:label:siteaccess' => 'Acesso padrão de segurança do site <i>(Default Site Access)</i>',
	'install:label:combo:dataroot' => 'Elgg cria um diretório de dados',

	'install:settings:help:sitename' => 'O nome do seu novo site Elgg',
	'install:settings:help:siteemail' => 'Endereço de email usado pelo Elgg para comunicação com os usuários',
	'install:settings:help:wwwroot' => 'O endereço do site (Elgg geralmente atribui isto corretamente)',
	'install:settings:help:path' => 'O diretório onde voce pretende colocar o código do Elgg (Elgg geralmente atribui isto corretamente)',
	'install:settings:help:dataroot' => 'O diretorio que voce criou para o Elgg salvar os arquivos (as permissões deste diretório serão verificadas quando voce clicar em PROXIMO)',
	'install:settings:help:dataroot:apache' => 'Você possui a opção do Elgg criar o diretório de dados ou entrar com o diretório que você já havia criada para guardar os arquivos (as permissões deste diretório serão checadas quando você clicar em PROXIMO)',
	'install:settings:help:language' => 'A linguagem padrao do site',
	'install:settings:help:siteaccess' => 'O nivel de acesso padrao para os novos conteúdos criados pelos usuários',

	'install:admin:instructions' => "Agora é o momento de criar a conta do administrador.",

	'install:admin:label:displayname' => 'Nome de exibição',
	'install:admin:label:email' => 'Endereço de email',
	'install:admin:label:username' => 'Usuário',
	'install:admin:label:password1' => 'Senha',
	'install:admin:label:password2' => 'Repetir a senha',

	'install:admin:help:displayname' => 'O nome que sera apresentado no site para esta conta',
	'install:admin:help:email' => '',
	'install:admin:help:username' => 'O login que sera usado pelo usuario para entrar na rede',
	'install:admin:help:password1' => "Senhas devem ter pelo menos %u caracteres.  <b>Não devem conter caracteres especiais ou espacos em branco</b>",
	'install:admin:help:password2' => 'Redigite a senha para confirmar',

	'install:admin:password:mismatch' => 'Senhas devem ser iguais.',
	'install:admin:password:empty' => 'A senha nao pode estar vazia.',
	'install:admin:password:tooshort' => 'Sua senha é muito pequena',
	'install:admin:cannot_create' => 'Não foi possível criar a conta do administrador.',

	'install:complete:instructions' => 'Seu site Elgg esta agora pronto para ser usado. Clique no botao abaixo para entrar no seu site.',
	'install:complete:gotosite' => 'Ir para o  site',

	'InstallationException:UnknownStep' => '%s é uma etapa desconhecida na instalação.',
	'InstallationException:MissingLibrary' => 'Nao foi possivel acessar %s',
	'InstallationException:CannotLoadSettings' => 'Elgg nao pode carregar os arquivos de configuracao. Ele nao existe ou existe uma questao de permissao de acesso ao arquivo.',

	'install:success:database' => 'Base de dados foi instalada.',
	'install:success:settings' => 'Configurações do site foram salvas.',
	'install:success:admin' => 'Conta do administrador foi criada.',

	'install:error:htaccess' => 'Não foi possivel criar o arquivo <b>.htaccess</b>',
	'install:error:settings' => 'Não foi possivel criar o arquivo de configurações <i>(settings file)</i>',
	'install:error:databasesettings' => 'Não foi possivel conectar ao banco de dados com estas configurações.',
	'install:error:database_prefix' => 'Caracteres invalidos no prefixo da base de dados (database prefix)',
	'install:error:oldmysql' => 'MySQL deve ser da versao 5.0 ou superior. Seu servidor está usando %s.',
	'install:error:nodatabase' => 'Não foi possivel usar o banco de dados %s. Ele pode não existir.',
	'install:error:cannotloadtables' => 'Não foi possivel carregar as tabelas da base de dados',
	'install:error:tables_exist' => 'Já existem tabelas do Elgg no banco de dados. Voce precisa apagar estas tabelas ou reiniciar o instalador e nos tentaremos utiliza-las. Para reiniciar o instalar, remova o <b>\'?step=database\' </b> do URL no seu endereco na barra do navegador e pressione ENTER.',
	'install:error:readsettingsphp' => 'Não foi possível ler o arquivo <b>engine/settings.example.php</b>',
	'install:error:writesettingphp' => 'Não foi possivel escrever o arquivo <b>engine/settings.php</b>',
	'install:error:requiredfield' => '%s é necessario',
	'install:error:relative_path' => 'Nao acreditamos que "%s" seja um caminho absoluto para seu diretorio de dados (data directory)',
	'install:error:datadirectoryexists' => 'Seu diretório de dados <i>(data directory)</i> %s não existe.',
	'install:error:writedatadirectory' => 'Seu diretório de dados <i>(data directory)</i> %s não possui permissão de escrita pelo servidor web.',
	'install:error:locationdatadirectory' => 'Seu diretório de dados <i>(data directory)</i> %s deve estar fora do seu caminho de instalação por razões de seguranca.',
	'install:error:emailaddress' => '%s não é um endereço de email válido',
	'install:error:createsite' => 'Não foi possivel criar o site.',
	'install:error:savesitesettings' => 'Não foi possível salvar as configurações do site',
	'install:error:loadadmin' => 'Não foi possível carregar o usuário administrador.',
	'install:error:adminaccess' => 'Não foi possível atribuir para nova conta de usuário os privilégios de administrador.',
	'install:error:adminlogin' => 'Não foi possével fazer login com o novo usuário administrador automaticamente.',
	'install:error:rewrite:apache' => 'Nos achamos que seu servidor está funcionando em um servidor Apache <i>(Apache web server)</i>.',
	'install:error:rewrite:nginx' => 'Nos achamos que seu servidor está funcionando em um servidor Nginx <i>(Nginx web server)</i>.',
	'install:error:rewrite:lighttpd' => 'Nos achamos que seu servidor está funcionando em um servidor Lighttpd <i>(Lighttpd web server)</i>.',
	'install:error:rewrite:iis' => 'Nos achamos que seu servidor está funcionando em um servidor IIS <i>(IIS web server)</i>.',
	'install:error:rewrite:allowoverride' => "O teste de escrita falhou e a causa mais provavel foi que <b>AllowOverride</b> nao esta definida para todos diretorios do Elgg. Isto previne o Apache de processar o arquivo <b>.htaccess</b> que contem as regras de redirecionamento (rewrite rules).
				\n\nUm causa menos provavel seria se o Apache foi configurado com um <b>alias</b> para seu diretorio Elgg e voce precisa definir o <b>RewriteBase</b> no seu <b>.htaccess</b>. Existem instrucoes complementares no arquivo <b>.htaccess</b> no seu diretorio do Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Seu servidor web nao possui permissao para criar o arquivo <b>.htaccess</b> no diretorio do Elgg. Voce precisa copiar manualmente o arquivo <b>htaccess_dist</b> para <b>.htaccess</b> ou alterar as permissoes no diretorio.',
	'install:error:rewrite:htaccess:read_permission' => 'Existe um arquivo <b>.htaccess</b> no diretorio do Elgg, mas seu servidor web nao possui permissao para ler este arquivo.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'Existe um arquivo <b>.htaccess</b> no diretorio do Elgg que nao foi criado pelo Elgg.  Por favor, remova o arquivo.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Parece que existe um arquivo antigo do <b>.htaccess</b> no diretorio do Elgg. Ele não contem as regras de redirecionamento (rewrite rules) para realizar os testes no servidor web.',
	'install:error:rewrite:htaccess:cannot_copy' => 'Um erro desconhecido ocorreu enquanto era criado o arquivo <b>.htaccess</b>. Voce precisa copiar manualmente o arquivo <b>htaccess_dist</b> para <b>.htaccess</b>.',
	'install:error:rewrite:altserver' => 'O teste com as regras de redirecionamento (rewrite rules) falhou. Voce precisa configurar seu servidor web com as regras de escrita do Elgg e tentar novamente.',
	'install:error:rewrite:unknown' => 'Não foi possivel identificar qual o tipo de servidor web esta funcionando no seu servidor e ocorreu uma falha com as regras de redirecionamento (rewrite rules).  Não nos é possivel fornecer qualquer tipo de conselho. Por favor verifique o link de solução de problemas <i>(troubleshooting link)</i>.',
	'install:warning:rewrite:unknown' => 'Seu servidor nao suporta testes automaticos das regras de redirecionamento (rewrite rules). Você pode continuar a instalação.  Contudo voce pode ter problemas com seu site. Voce pode realizar os testes manualmente com as regras de escrita clicando neste link: <a href="%s" target="_blank">teste</a>. Voce visualizará a palavra SUCESSO se as regras estiverem funcionando.',
    
	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Um erro irrecuperavel ocorreu e foi registrado. se voce for o administrador do site verifique seus arquivos de configuracoes, ou entre em contato com o administrador do site com as seguintes informacoes:',
	'DatabaseException:WrongCredentials' => "Elgg nao pode se conectar ao banco de dados usando as credenciais informadas.  Verifique seu arquivo de configuracoes.",
);
