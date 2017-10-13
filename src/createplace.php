<?php
require('./php/lib/auth.php');
require('./php/lib/db.php');
require('./php/lib/helpers.php');

// Check user is logged in
checkUserLoggedIn();
// Update user last_page session
newPage();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>TripsWiki - Contribute</title>
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
            $previous_data;
            if(isset($_SESSION['error'])) { echo '<div class="error">' . $_SESSION['error'] . '</div>'; }
            if(isset($_SESSION['msg'])) { echo '<div class="msg">' . $_SESSION['msg'] . '</div>'; }
            if(isset($_SESSION['form_response'])) {
                $previous_data = $_SESSION['form_response']['data'];
                echo '<div class="error">' . $_SESSION['form_response']['failed_fields'][0][1] . '</div>';
            }
        ?>
        <main id="createplace">
            <h1>Submit your favourite places to TripsWiki.</h1>
            <form id="createplace_form" method="post" action="./php/process_createplace.php">
                <label>Place name:</label>
                <input type="text" name="name" value="<?php if(isset($previous_data['name'])) { echo $previous_data['name']['data']; } ?>"/>
                <label>Place type:</label>
                <select name="type">
                    <option value="hotel">
                        Hotel
                    </option>
                    <option value="city">
                        City
                    </option>
                    <option value="historical site">
                        Historical Site
                    </option>
                    <option value="restaurant">
                        Restaurant
                    </option>
                    <option value="bar">
                        Bar
                    </option>
                    <option value="beach">
                        Beach
                    </option>
                    <option value="mountain">
                        Mountain
                    </option>
                </select>
                <label>Description:</label>
                <textarea name="description"><?php if(isset($previous_data['description'])) { echo $previous_data['description']['data']; } ?></textarea>
                <label>Region:</label>
                <input type="text" name="region" value="<?php if(isset($previous_data['region'])) { echo $previous_data['region']['data']; } ?>"/>
                <label>Country:</label>
                <input type="text" name="country" value="<?php if(isset($previous_data['country'])) { echo $previous_data['country']['data']; } ?>"/>
                <input type="submit" value="Create" />
                <a href="./index.php">Return home</a>
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
