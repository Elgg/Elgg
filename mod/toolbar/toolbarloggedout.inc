<div id="elggtoolbar"><!-- start toolbar -->
    <form action="{{url}}login/index.php" method="post">
        <a href="{{url}}">{{logon}}</a> 
        <label>{{username}}&nbsp;<input type="text" name="username" id="toolbarusername" style="size: 200px" /></label>
        <label>{{password}}&nbsp;<input type="password" name="password" id="toolbarpassword" style="size: 200px" /></label>
        <input type="checkbox" name="remember" checked="checked" />{{remember}}</label>
        <input type="submit" value="&gt;&gt;" />
    </form>
    <div id="powered-by-elgg"><!-- open div global_menuoptions -->
       <a href="http://elgg.org/"><img src="{{url}}mod/template/icons/elgg_powered.png" alt="{{poweredby}}" /></a>
    </div>
</div><!-- end toolbar div -->
<div style="clear:both;" />