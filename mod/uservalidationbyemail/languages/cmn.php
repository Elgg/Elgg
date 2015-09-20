<?php
return array(
	'admin:users:unvalidated' => '未驗證',
	
	'email:validate:subject' => "%s 請確認您用於 %s 的電子郵件地址！",
	'email:validate:body' => "%s，

在您可以開始使用 %s 之前，您必須確認電子郵件地址。

請在以下的鏈結上按一下以確認電子郵件地址：

%s

如果您無法按一下鏈結，請手動將它拷貝並貼上瀏覽器。

%s
%s
",
	'email:confirm:success' => "您已確認電子郵件地址！",
	'email:confirm:fail' => "電子郵件地址無法驗證…",

	'uservalidationbyemail:emailsent' => "Email sent to <em>%s</em>",
	'uservalidationbyemail:registerok' => "要啟用帳號，請藉由按一下我們剛才發送給您的鏈結以確認電子郵件地址。",
	'uservalidationbyemail:login:fail' => "您的帳號尚未驗證因而嘗試登入時失敗。已發送另一封驗證電子郵件。",

	'uservalidationbyemail:admin:no_unvalidated_users' => '沒有未驗證的使用者。',

	'uservalidationbyemail:admin:unvalidated' => '未驗證',
	'uservalidationbyemail:admin:user_created' => '已註冊 %s',
	'uservalidationbyemail:admin:resend_validation' => '重新發送驗證',
	'uservalidationbyemail:admin:validate' => '驗證',
	'uservalidationbyemail:confirm_validate_user' => '要驗證 %s？',
	'uservalidationbyemail:confirm_resend_validation' => '重新發送驗證電子郵件給 %s？',
	'uservalidationbyemail:confirm_delete' => '要刪除 %s？',
	'uservalidationbyemail:confirm_validate_checked' => '要驗證勾選的使用者？',
	'uservalidationbyemail:confirm_resend_validation_checked' => '要重新發送驗證給勾選的使用者？',
	'uservalidationbyemail:confirm_delete_checked' => '要刪除勾選的使用者？',
	
	'uservalidationbyemail:errors:unknown_users' => '不明使用者',
	'uservalidationbyemail:errors:could_not_validate_user' => '無法驗證使用者。',
	'uservalidationbyemail:errors:could_not_validate_users' => '無法驗證所有勾選的使用者。',
	'uservalidationbyemail:errors:could_not_delete_user' => '無法刪除使用者。',
	'uservalidationbyemail:errors:could_not_delete_users' => '無法刪除所有勾選的使用者。',
	'uservalidationbyemail:errors:could_not_resend_validation' => '無法重新發送驗證要求。',
	'uservalidationbyemail:errors:could_not_resend_validations' => '無法重新發送驗證要求給所有勾選的使用者。',

	'uservalidationbyemail:messages:validated_user' => '使用者已驗證。',
	'uservalidationbyemail:messages:validated_users' => '所有勾選的使用者已驗證。',
	'uservalidationbyemail:messages:deleted_user' => '使用者已刪除。',
	'uservalidationbyemail:messages:deleted_users' => '所有勾選的使用者已刪除。',
	'uservalidationbyemail:messages:resent_validation' => '驗證要求已重新發送。',
	'uservalidationbyemail:messages:resent_validations' => '驗證要求已重新發送給所有勾選的使用者。'

);
