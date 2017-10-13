<?php
require('./php/lib/db.php');
require('./php/lib/auth.php');
require('./php/lib/helpers.php');

// Check user is logged in
checkUserLoggedIn();
// Check user isadmin
isAdmin($pdo);
// Saves users current page to $_SESSION['last_page']
newPage();

// SQL to fetch pending reviews
$sql = 'SELECT * FROM reviews WHERE pending = :pending';
$input_data = array(
    'pending' => array(
        'data' => true
    )
);

// Array of pending reviews
$pending_reviews = fetchData($pdo, $input_data, $sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TripsWiki - Admin Page</title>
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
        ?>
        <main id="admin">
            <h1>Admin Page</h1>
            <h2>Pending reviews</h2>
            <div id="response">

            </div>
            <div id="pending_reviews">
                <table>
                    <tr>
                        <th>
                            Review
                        </th>
                        <th>
                            Date/Time
                        </th>
                        <th>
                            User
                        </th>
                        <th colspan="2">
                            Actions
                        </th>
                    </tr>
                    <?php
                    if(!empty($pending_reviews)) {
                        foreach($pending_reviews as $key => $pending_review) {
                            ?>
                            <tr id="review_<?php echo $pending_review['ID']; ?>">
                                <td>
                                    <?php output($pending_review['review']); ?>
                                </td>
                                <td>
                                    <?php output($pending_review['review_date']); ?>
                                </td>
                                <td>
                                    <?php echo getUserName($pdo, $pending_review['userID']); ?>
                                </td>
                                <td>
                                    <button onclick="processReview(<?php echo $pending_review['ID']; ?>, 1);">Approve</button>
                                </td>
                                <td>
                                    <button onclick="processReview(<?php echo $pending_review['ID']; ?>, 2);">Delete</button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo 'No pending reviews.';
                    }
                    ?>
                </table>
            </div>
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
        <script src="resources/js/admin.js"></script>
    </body>
</html>
<?php clearOldSessions();
