<?php
return array(
	'email:validate:subject' => "【登録確認メール】%s様、%sで登録されたメールアドレスにお送りしています",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "あなたの電子メールアドレスを確認しました。",
	'email:confirm:fail' => "あなたの電子メールアドレスが正しいアドレスなのかどうかを確認できませんでした...",

	'uservalidationbyemail:emailsent' => "<em>%s</em> さんにEmailを送信しました",
	'uservalidationbyemail:registerok' => "先ほどあなたが登録されたメールアドレスに確認メールをお送りしました。ご登録されたメールアドレスが正しければご覧になれるはずです。その確認メールに書いておりますリンクをクリックしていただいて、初めてアカウントが有効になり登録が完了となります。",
	'uservalidationbyemail:login:fail' => "あなたのアカウントはまだご確認させていただいておりませんので、ログインすることができません。再度別の確認メールをお送りいたしますのでそれにしたがって、確認作業を完了させていただくよう、よろしくお願いします。",

	'uservalidationbyemail:admin:resend_validation' => '確認メールを再送',
	'uservalidationbyemail:confirm_resend_validation' => '%s さんに確認メールを再送しますか？',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'チェックしたユーザに確認メールを再送しますか？',
	
	'uservalidationbyemail:errors:unknown_users' => 'そのユーザは登録されていません',
	'uservalidationbyemail:errors:could_not_resend_validation' => '確認メールの再送ができませんでした。',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'チエックしたユーザ全員に確認メールを再送できませんでした。',

	'uservalidationbyemail:messages:resent_validation' => '確認メールを送信しました。',
	'uservalidationbyemail:messages:resent_validations' => 'チェックしたユーザ全員に確認メールを再送しました。'
);
