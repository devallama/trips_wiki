<?php
require('./php/lib/auth.php');
require('./php/lib/db.php');
require('./php/lib/helpers.php');

// Update user last_page session
newPage();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>TripsWiki - Home</title>
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
        ?>
        <main id="home">
            <h1>Welcome to TripsWiki, the best place to explore and find unique locations across the world.</h1>
            <h4>Search for the type of location you're looking for, such as a hotel, beach or bar.</h4>
            <form id="search_form">
                <input type="text" name="search" id="search_field" />
                <input type="submit" value="search" />
            </form>
            <div id="results">
                <br />
            </div>
            <hr />
            <h4><a href="createplace.php">Or help contribute to TripsWiki by submitting your favourite places to visit.</a></h4>
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
        <script src="resources/js/search.js"></script>
    </body>
</html>
<?php clearOldSessions();
