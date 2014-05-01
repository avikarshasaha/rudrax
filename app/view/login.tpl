{config_load file="website.conf"}
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{#webcontext#}res/css/newstyle.css" />
    </head>

    <body>
        <form class="loginPanel" method="post" action="/">
            <input type="text" name="uname" placeholder="Email Id" required  />
            <input type="password" name="pass" placeholder="Password" required  />
            <button type="submit">Login</button>
            <label for="rember"><input id="rember" type="checkbox">Remember This User</label>
        </form>

    </body>
</html>
