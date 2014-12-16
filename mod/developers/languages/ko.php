<?php
return array(
	// menu
	'admin:develop_tools' => '도구',
	'admin:develop_tools:sandbox' => '테마 모래상자',
	'admin:develop_tools:inspect' => '살펴보기',
	'admin:develop_tools:unit_tests' => '단위 검사',
	'admin:developers' => '개발자',
	'admin:developers:settings' => '설정',

	// settings
	'elgg_dev_tools:settings:explanation' => '아래에 개발 및 디버깅 설정을 조절하세요. 이 설정들 중 일부 다른 관리자 화면에서 수정이 가능합니다',
	'developers:label:simple_cache' => '단순 캐쉬 사용',
	'developers:help:simple_cache' => '개발할 때에는 이 캐쉬를 끄세요. 그렇지않으면, 변경한 CSS 와 Javascript가 무시될 수 있습니다.',
	'developers:label:system_cache' => '시스템 캐쉬 사용',
	'developers:help:system_cache' => '개발할 때에는 이것을 끄세요. 그렇지 않으면, 플러긴의 변경점이 등록되지 않을 수 있습니다.',
	'developers:label:debug_level' => "추적 등급",
	'developers:help:debug_level' => "이것은 얼마나 많은 정보가 기록될지를 조절합니다. 자세한 설명은 elgg_log() 을 보세요.",
	'developers:label:display_errors' => '치명적인 PHP 오류 표시',
	'developers:help:display_errors' => "기본설정으로 Elgg의 .htaccess 파일은 치명적인 오류를 표시하지 않습니다.",
	'developers:label:screen_log' => "화면에 로그를 표시합니다.",
	'developers:help:screen_log' => "이것은 웹페이지에 elgg_log() 와 elgg_dump() 를 출력합니다.",
	'developers:label:show_strings' => "원본 번역 문자열을 보여줍니다.",
	'developers:help:show_strings' => "elgg_echo() 에서 사용된 번역 문자역을 보여줍니다.",
	'developers:label:wrap_views' => "테두리 보기",
	'developers:help:wrap_views' => "이것은 HTML주석과 모든 표시를 둘러쌉니다. 특정한 HTML을 만드는 표시를 찾는데 유용합니다.
									이것은 기본보기형태에서 non-HTML보기를 망가트릴 수 있습니다. 자세한 내용은  developers_wrap_views() 을 보세요.",
	'developers:label:log_events' => "사건과 플러긴 가로채기를 표시합니다.",
	'developers:help:log_events' => "사건과 플러긴 가로채기를 로그에 저장합니다. 경고:페이지당 여럿이 있을 수 있습니다.",

	'developers:debug:off' => '끄기',
	'developers:debug:error' => '오류',
	'developers:debug:warning' => '경고',
	'developers:debug:notice' => '공지',
	'developers:debug:info' => '정보',
	
	// inspection
	'developers:inspect:help' => ' Elgg 의 설정을 조사합니다.',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s 의 %s",

	// theme sandbox
	'theme_sandbox:intro' => '소개',
	'theme_sandbox:breakout' => ' iframe 탈출',
	'theme_sandbox:buttons' => '단추',
	'theme_sandbox:components' => '구성요소',
	'theme_sandbox:forms' => '폼',
	'theme_sandbox:grid' => '격자',
	'theme_sandbox:icons' => '아이콘',
	'theme_sandbox:javascript' => '자바스크립트',
	'theme_sandbox:layouts' => '터잡기',
	'theme_sandbox:modules' => '부품',
	'theme_sandbox:navigation' => '길잡이',
	'theme_sandbox:typography' => '활자',

	'theme_sandbox:icons:blurb' => '아이콘을 표시하려면 <em>elgg_view_icon($name)</em> 혹은  class elgg-icon-$name 을 사용하세요',

	// unit tests
	'developers:unit_tests:description' => 'Elgg는 주요 클래스와 함수의 오류를 검출하는 단위, 통합 검사를 가지고 있습니다.',
	'developers:unit_tests:warning' => '경고: 사용중인 누리집에서 이 검사를 실행하지 마세요. 자료를 망칠 수 있습니다.',
	'developers:unit_tests:run' => '실행',

	// status messages
	'developers:settings:success' => '설정 저장됨.',
);
