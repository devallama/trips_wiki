<?php
require('./php/lib/auth.php');
require('./php/lib/helpers.php');
require('./php/lib/db.php');

// Update user last_page session
newPage();

// Check if page id is set, if not show 404
if(!isset($_GET['id'])) {
    redirect404();
}

// Get page id
$page_id = $_GET['id'];

// Get the page data using the id given
$page_data = getPlaceData($pdo, $page_id);

// Get the reviews data from the reviews table
$reviews_data = getReviewData($pdo, $page_id);

// Get page data
function getPlaceData($pdo, $id) {
    // SQL query to get place data using id
    $sql = 'SELECT * FROM places WHERE ID = :id';
    // Input data for SQL query
    $input_data = array(
        'id' => array(
            'data' => $id
        )
    );

    // Fetch the data
    $result = fetchData($pdo, $input_data, $sql, true);

    // if result is empty, then place does not exist
    if(empty($result)) {
        // throw 404 error
        redirect404();
    } else {
        return $result;
    }
}

// Get the review data for the place
function getReviewData($pdo, $id) {
    // SQL query to get all reviews using place id that have been approved
    $sql = 'SELECT * FROM reviews WHERE placeID = :id AND pending = 0 ORDER BY review_date DESC';
    // Input data for sql query
    $input_data = array(
        'id' => array(
            'data' => $id
        )
    );

    // fetch the data
    return fetchData($pdo, $input_data, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>TripsWiki - <?php output($page_data['name']); ?></title>
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
        <main id="place">
            <div class="place_info">
                <div class="overflow">
                    <div class="left">
                        <div class="name">
                            <h1><?php output($page_data['name']); ?></h1>
                        </div>
                        <div class="type">
                            <h3><?php output(ucfirst($page_data['type'])); ?></h3>
                        </div>
                        <div class="location">
                            <?php output($page_data['region']); echo ', '; output($page_data['country']); ?>
                        </div>
                    </div>
                    <div class="right">
                        <div id="place_rating">
                            <?php
                            $rating = round($page_data['avg_rating']);
                            for($i = 1; $i <= 10; $i++) {
                                if($i <= $rating) {
                                    $src = "./resources/imgs/star_filled.png";
                                } else {
                                    $src = "./resources/imgs/star.png";
                                }
                                echo '<img src="' . $src . '" alt="Rating Star Filled" id="ratingstar_' . $i . '" class="ratingstar" onmouseover="rateHover(' . $i . ')" onclick="ratePlace(' . $page_data['ID'] . ',' . $i . ');"/>';
                            }
                            ?>
                        </div>
                        <div class="rating_info">
                            Average Rating: <span id="average_rating"><?php echo $page_data['avg_rating']; ?></span> From <span id="num_raters"><?php echo $page_data['num_raters']; ?></span> Ratings
                        </div>
                        <div id="user_rating">

                        </div>
                        <div id="user_rating_info">

                        </div>
                    </div>
                </div>
                <div class="description">
                    <?php output($page_data['description']); ?>
                </div>
            </div>
            <hr />
            <h3>Reviews</h3>
            <div id="reviews">
                <?php
                if(!empty($reviews_data)) {
                    foreach($reviews_data as $key => $review) {
                        ?>
                        <div class="review">
                            <div class="author">
                                from <?php echo getUserName($pdo, $review['userID']); ?>
                            </div>
                            <div class="content">
                                <?php output($review['review']); ?>
                            </div>
                            <div class="date">
                                posted <?php echo $review['review_date']; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo 'No reviews.';
                }
                ?>
            </div>
            <hr />
            <h3>Write a review</h3>
            <div class="error">

            </div>
            <div id="review_response">

            </div>
            <form id="review_form" action="./php/process_createreview.php" method="post">
            <?php
                if(isLoggedIn()) {
                    ?>
                    <input type="hidden" id="form_placeid" name="placeid" value="<?php echo $page_data['ID']; ?>" />
                    <textarea id="form_review" name="review"></textarea>
                    <input type="submit" value="Submit review" />
                <?php
            } else {
                echo 'Please <a href="./login.php">login or register</a> to write reviews';
            }
            ?>
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
        <script src="resources/js/place.js"></script>
        <script src="resources/js/review.js"></script>
    </body>
</html>
<?php clearOldSessions(); ?>
