<?php
require('./php/lib/auth.php');
require('./php/lib/helpers.php');

newPage();


?>
<!DOCTYPE html>
<html>
    <head>
        <title>TripsWiki - Login or Register</title>
        <link rel="stylesheet" type="text/css" href="./resources/css/style.css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    </head>
    <body>
        <header class="overflow">
            <div id="title" class="left">
                <a href="./index.php">TripsWiki</a>
            </div>
            <div id="user" class="right">
                <?php
                if(isLoggedIn()) {
                    echo 'Logged in as ' . getUserName($pdo, getUserId()) . '. <a href="./logout.php">Logout</a>';
                } else {
                    echo '<a href="login.php">Login or Create an Account</a>';
                }
                ?>
            </div>
        </header>
        <?php
            if(isset($_SESSION['error'])) { echo '<div class="error">' . $_SESSION['error'] . '</div>'; }
            if(isset($_SESSION['msg'])) { echo '<div class="msg">' . $_SESSION['msg'] . '</div>'; }
            if(isset($_SESSION['auth_response'])) {
                echo '<div class="error">' . $_SESSION['auth_response']['response'] . '</div>';
            }
        ?>
        <main id="login" class="overflow">
            <h1>Help contribute to TripsWiki and create an account today!</h1>
            <h2>Or log in on the right side if you're already an user.</h2>
            <form id="register_form" class="left" method="post" action="./php/auth.php">
                <h3>Register</h3>
                <input type="hidden" value="register" name="auth_type"/>
                <label>Username <span class="required">required</span></label>
                <input type="text" name="username" value="<?php if(isset($_SESSION['auth_response'])) { if($_SESSION['auth_response']['auth_type'] == 0) { echo $_SESSION['auth_response']['username']; }} ?>"/>
                <label>Password <span class="required">required</span></label>
                <input type="password" name="password" />
                <label>Repeat Password <span class="required">required</span></label>
                <input type="password" name="repeat_password" />
                <input type="submit" value="Register" />
            </form>
            <form id="login_form" class="right" method="post" action="./php/auth.php">
                <h3>Login</h3>
                <input type="hidden" value="login" name="auth_type" />
                <label>Username <span class="required">required</span></label>
                <input type="text" name="username"  value="<?php if(isset($_SESSION['auth_response'])) { if($_SESSION['auth_response']['auth_type'] == 1) { echo $_SESSION['auth_response']['username']; }} ?>"/>
                <label>Password <span class="required">required</span></label>
                <input type="password" name="password" />
                <input type="submit" value="Login" />
            </form>
        </main>
        <footer class="overflow">
            <div id="copyright" class="left">
                Copyright TripsWiki 2017 &copy;
            </div>
            <div id="links" class="right">
                <a href="./admin.php">Admin Page</a><br />
                <a href="./createplace.php">Contribute to TripsWiki</a>
            </div>
        </footer>
    </body>
</html>
<?php clearOldSessions();
