{config_load file="website.conf"}
<html>
    <head>
     	<link rel="stylesheet" type="text/css" href="{#webcontext#}/res/css/newstyle.css" />
        <script type="text/javascript" src="{#webcontext#}/lib/jquery/jquery.js"></script>
    </head>

    <body>
        <form class="loginPanel" method="POST" action="{#webcontext#}/">
            <input type="text" name="uname" placeholder="Email Id" required  />
            <input type="password" name="pass" placeholder="Password" required  />
            <input type="submit" value="Login"/>
            <label for="rember"><input id="rember" type="checkbox">Remember This User</label>
        </form>

    </body>
</html>
