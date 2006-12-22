<form action="" method="post">

    <table width="350" class="actionbox">
    
        <caption align="top">
            __gettext("Log On");
        </caption>
        <tr>
            <td>
                <label for="username">__gettext("Username")</label>
            </td>
            <td>
                <input type="text" name="username" id="username" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="password">__gettext("Password")</label>
            </td>
            <td>
                <input type="password" name="password" id="password" />
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <label><input type="checkbox" name="remember" checked="checked" /> __gettext("Remember Login")</label>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <label>Log on: <input type="submit" name="submit" value="__gettext("Go") /></label>
            </td>
        </tr>
    
    </table>

</form>
