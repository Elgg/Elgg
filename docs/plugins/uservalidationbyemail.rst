User validation by e-mail
=========================

The uservalidationbyemail plugin adds a step to the user registration process. After the user registered on the site, an e-mail is sent to their
e-mail address in order to validate that the e-mail address belongs to the user. In the e-mail is an verification link, only after the user clicked
on the link will the account of the user be able to login to the site.

The process for the user
------------------------

1. The user creates an account by going to the registration page of your site
2. After the account is created the user lands on a page with instructions to check their e-mail account for the validation e-mail
3. In the validation e-mail is a link to confirm their e-mail address
4. After clicking on the link, the account is validated
5. If possible the user gets logged in

If the user tries to login before validating their account an error is shown to indicate that the user needs to check their e-mail account. Also the
validation e-mail is sent again.

Options for site administrators
-------------------------------

A site administrator can take some actions on unvalidated accounts. Under Administration -> Users -> Unvalidated is a list of unvalidated users.
The administrator can manualy validate or delete the user. Also the option to resend the validation e-mail is present.
 