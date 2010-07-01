{include file='inc/header.tpl'}

<h1>Login</h1>

<form method="post">
    <input type="hidden" name="goto" value="{$smarty.request.goto}" />

    <label for="username">User name</label><br />
    <input type="text" name="username" id="username" />
    
    <br /><br />

    <label for="password">Password</label><br />
    <input type="text" name="password" id="password" />

    <br /><br />
    
    <input type="submit" value="Login" />
</form>

{include file='inc/footer.tpl'}
