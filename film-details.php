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

// Get movie_id from URL
$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : 1; // Default to 1 if no ID is passed

// Fetch data from the Movie table
$sql = "SELECT m.*, g.genre_name, d.director_name, l.language_name
        FROM movie m
        LEFT JOIN genre g ON m.genre_id = g.genre_id
        LEFT JOIN director d ON m.director_id = d.director_id
        LEFT JOIN language l ON m.language_id = l.language_id
        WHERE m.movie_id = $movie_id";
$result = $conn->query($sql);
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
</head>

<body>
    <!-- Page Preloader -->
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
                        <span>Film Detail</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Film Details Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="anime__details__content">
                <div class="row">
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo '
                            <div class="col-lg-3">
                                <div class="anime__details__pic set-bg" data-setbg="' . $row["image_url"] . '">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="anime__details__text">
                                    <div class="anime__details__title">
                                        <h3>' . $row["title"] . '</h3>
                                        <span>Release Date: ' . $row["release_date"] . '</span>
                                    </div>
                                    <div class="anime__details__rating">
                                        <div class="rating">
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                            <a href="#"><i class="fa fa-star-half-o"></i></a>
                                        </div>
                                        <span>' . $row["rating"] . ' Votes</span>
                                    </div>
                                    <p>Duration: ' . $row["duration"] . ' minutes</p>
                                    <p>' . $row["description"] . '</p>
                                    <div class="anime__details__widget">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <ul>
                                                    <li><span>Type:</span> Movie</li>
                                                    <li><span>Director:</span> ' . $row["director_name"] . '</li>
                                                    <li><span>Genre:</span> ' . $row["genre_name"] . '</li>
                                                    <li><span>Language:</span> ' . $row["language_name"] . '</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="anime__details__btn">
                                        <a href="film-tonton.php?movie_id=' . $row["movie_id"] . '" class="watch-btn"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo "No movie found.";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Film Details Section End -->

    <?php include_once('footer.php'); ?>

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
