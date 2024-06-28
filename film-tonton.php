<?php
// Database connection
$localhost = "localhost";
$username = "root";
$password = "";
$database = "removie";

$conn = new mysqli($localhost, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session for user authentication
session_start();

// Get movie_id from URL
$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : 1; // Default to 1 if no ID is passed

// Fetch movie details
$sql_movie = "SELECT m.title, g.genre_name
              FROM movie m
              LEFT JOIN genre g ON m.genre_id = g.genre_id
              WHERE m.movie_id = $movie_id";
$result_movie = $conn->query($sql_movie);
if (!$result_movie) {
    die("Query failed: " . $conn->error);
}
$movie = $result_movie->fetch_assoc();

// Fetch reviews with usernames
$sql_reviews = "SELECT r.review_id, r.comment, r.review_date, u.username, r.user_id
                FROM review r
                INNER JOIN user u ON r.user_id = u.user_id
                WHERE r.movie_id = $movie_id";
$result_reviews = $conn->query($sql_reviews);
if (!$result_reviews) {
    die("Query failed: " . $conn->error);
}

// Check if user is logged in
$user_logged_in = false; // Assuming user is not logged in by default

// Example authentication logic (replace with your own)
if (isset($_SESSION['user_id'])) {
    $user_logged_in = true;
}

// Handle review form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$user_logged_in) {
        // Simpan URL halaman sebelumnya di session
        $_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];
        
    }

    // Process review submission
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $comment = $conn->real_escape_string($_POST['comment']); // Sanitize input

    // Insert review into database
    $insert_review_sql = "INSERT INTO review (user_id, movie_id, comment, review_date)
                          VALUES ($user_id, $movie_id, '$comment', NOW())";

    if ($conn->query($insert_review_sql) === TRUE) {
        // Redirect to prevent form resubmission on refresh
        header("Location: film-tonton.php?movie_id=$movie_id&review_added=true");
        exit();
    } else {
        echo "Error: " . $insert_review_sql . "<br>" . $conn->error;
    }
}


// Handle review deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['review_id'])) {
    $review_id = $_GET['review_id'];
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

    // Delete review only if the user owns it
    $delete_review_sql = "DELETE FROM review WHERE review_id = $review_id AND user_id = $user_id";

    if ($conn->query($delete_review_sql) === TRUE) {
        // Redirect to prevent form resubmission on refresh
        header("Location: film-tonton.php?movie_id=$movie_id&review_deleted=true");
        exit();
    } else {
        echo "Error: " . $delete_review_sql . "<br>" . $conn->error;
    }
}

// Success messages
$success_message = '';

if (isset($_GET['review_added']) && $_GET['review_added'] == 'true') {
    $success_message = 'Berhasil menambahkan komentar.';
} elseif (isset($_GET['review_deleted']) && $_GET['review_deleted'] == 'true') {
    $success_message = 'Berhasil menghapus komentar.';
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Removie | Tempatnya Nonton Film!</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    
    <style>
        .success-message {
            display: none;
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <?php include_once('header.php'); ?>

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="./categories.php">Categories</a>
                        <a href="#"><?php echo $movie['genre_name']; ?></a>
                        <span><?php echo $movie['title']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <?php if (!empty($success_message)) : ?>
                <div class="success-message" id="success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="anime__video__player">
                        <video id="player" playsinline controls data-poster="./videos/thumbnail-dummy.jpg">
                            <source src="videos/video-dummy.mp4" type="video/mp4" />
                            <!-- Captions are optional -->
                            <track kind="captions" label="English captions" src="#" srclang="en" default />
                        </video>
                    </div>
                    <div class="anime__details__title">
                        <div class="section-title">
                            <h5><?php echo $movie['title']; ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="anime__details__review">
                        <div class="section-title">
                            <h5>Reviews</h5>
                        </div>
                        <?php
                        if ($result_reviews->num_rows > 0) {
                            while ($review = $result_reviews->fetch_assoc()) {
                                echo '
                                <div class="anime__review__item">
                                    <div class="anime__review__item__pic">
                                        <img src="img/profile-review.jpg" alt="">
                                    </div>
                                    <div class="anime__review__item__text">
                                        <h6>' . $review["username"] . ' - <span>' . $review["review_date"] . '</span></h6>
                                        <p>' . $review["comment"] . '</p>';

                                // Display delete option only for the user's own reviews
                                if ($user_logged_in && $review['user_id'] == $_SESSION['user_id']) {
                                    echo '<a href="film-tonton.php?action=delete&review_id=' . $review['review_id'] . '&movie_id=' . $movie_id . '">Delete</a>';
                                }

                                echo '</div>
                                </div>';
                            }
                        } else {
                            echo "<p>No reviews yet.</p>";
                        }
                        ?>
                    </div>
                    <div class="anime__details__form">
                        <div class="section-title">
                            <h5>Your Comment</h5>
                        </div>
                        <?php if ($user_logged_in) : ?>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?movie_id=$movie_id"; ?>">
                                <textarea name="comment" placeholder="Your Comment" required></textarea>
                                <button type="submit"><i class="fa fa-location-arrow"></i> Review</button>
                            </form>
                        <?php else : ?>
                            <p>You must <a href="login.php">login</a> or <a href="signup.php">sign up</a> to leave a review.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Anime Section End -->

    <?php include_once('footer.php'); ?>

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        $(document).ready(function() {
            var successMessage = $('#success-message');
            if (successMessage.length) {
                successMessage.fadeIn().delay(3000).fadeOut();
            }
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
