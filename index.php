<?php
session_start();

// connect to database
$localhost = "localhost";
$username = "root";
$password = "";
$database = "removie";

// Create connection
$conn = new mysqli($localhost, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get data from database with genre text
$sql = "SELECT m.*, g.genre_name FROM movie m
        LEFT JOIN genre g ON m.genre_id = g.genre_id
        WHERE m.movie_id = 2";
$result = $conn->query($sql);

// Success messages
$success_message = '';

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success_message = 'Berhasil keluar';
}
?>



<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Anime Template" />
    <meta name="keywords" content="Anime, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Removie | Tempatnya Nonton Film!</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/plyr.css" type="text/css" />
    <link rel="stylesheet" href="css/nice-select.css" type="text/css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css" />
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />

    <style>
        .success-message {
            display: none;
            position: fixed;
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
    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>
    
    <?php include_once('header.php'); ?>

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="hero__slider owl-carousel">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <!-- image data-setbg from database column image_url -->
                        <div class="hero__items set-bg" data-setbg="<?php echo $row['image_url']; ?>">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="hero__text">
                                        <div class="label"><?php echo $row['genre_name']; ?></div>
                                        <h2><?php echo $row['title']; ?></h2>
                                        <p><?php echo $row['description']; ?></p>
                                        <a href="film-details.php?movie_id=<?php echo $row['movie_id']; ?>"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Trending Now</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="#" class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            // Adjusted query to include genre_name
                            $sql_trending = "SELECT m.*, g.genre_name FROM movie m LEFT JOIN genre g ON m.genre_id = g.genre_id ORDER BY CHAR_LENGTH(m.title) DESC LIMIT 12";
                            $result_trending = $conn->query($sql_trending);
                            if ($result_trending->num_rows > 0) {
                                while ($movie = $result_trending->fetch_assoc()) {
                            ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <a href="film-details.php?movie_id=<?php echo $movie['movie_id']; ?>">
                                                <div class="product__item__pic set-bg" data-setbg="<?php echo $movie['image_url']; ?>">
                                                    <div class="ep"><?php echo $movie['duration'] . ' min'; ?></div>
                                                    <div class="view"><i class="fa fa-star"></i> <?php echo $movie['rating']; ?></div>
                                                </div>
                                            </a>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li><?php echo $movie['genre_name']; ?></li>
                                                </ul>
                                                <h5><a href="film-details.php?movie_id=<?php echo $movie['movie_id']; ?>"><?php echo $movie['title']; ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "No movies found.";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="recent__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Recently Added Film</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="#" class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            // Query untuk mengambil data film yang baru ditambahkan
                            $sql_recent = "SELECT m.*, g.genre_name FROM movie m LEFT JOIN genre g ON m.genre_id = g.genre_id ORDER BY release_date DESC LIMIT 6";
                            $result_recent = $conn->query($sql_recent);
                            if ($result_recent->num_rows > 0) {
                                while ($movie = $result_recent->fetch_assoc()) {
                            ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <a href="film-details.php?movie_id=<?php echo $movie['movie_id']; ?>">
                                                <div class="product__item__pic set-bg" data-setbg="<?php echo $movie['image_url']; ?>">
                                                    <div class="ep"><?php echo $movie['duration'] . ' min'; ?></div>
                                                    <div class="view"><i class="fa fa-star"></i> <?php echo $movie['rating']; ?></div>
                                                </div>
                                            </a>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li><?php echo $movie['genre_name']; ?></li>
                                                </ul>
                                                <h5><a href="film-details.php?movie_id=<?php echo $movie['movie_id']; ?>"><?php echo $movie['title']; ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "No movies found.";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Movie Begin -->
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                            <div class="section-title">
                                <h5>Top Rated</h5>
                            </div>
                            <div class="filter__gallery">
                                <?php
                                // Query untuk mengambil data film dengan rating tertinggi
                                $sql_top_rated = "SELECT * FROM movie ORDER BY rating DESC LIMIT 5";
                                $result_top_rated = $conn->query($sql_top_rated);
                                if ($result_top_rated->num_rows > 0) {
                                    while ($movie = $result_top_rated->fetch_assoc()) {
                                ?>
                                        <a href="film-details.php?movie_id=<?php echo $movie['movie_id']; ?>">
                                            <div class="product__sidebar__view__item set-bg mix day years" data-setbg="<?php echo $movie['image_url']; ?>">
                                                <div class="ep"><?php echo $movie['duration'] . ' min'; ?></div>
                                                <div class="view"><i class="fa fa-star"></i> <?php echo $movie['rating']; ?></div>
                                                <h5><a href="film-details.php?movie_id=<?php echo htmlspecialchars($movie['movie_id']); ?>"><?php echo htmlspecialchars($movie['title']); ?></a></h5>
                                            </div>
                                        </a>
                                <?php
                                    }
                                } else {
                                    echo "No movies found.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar Movie End -->
            </div>
        </div>

        <!-- Search model Begin -->
        <div class="search-model">
            <div class="h-100 d-flex align-items-center justify-content-center">
                <div class="search-close-switch"><i class="icon_close"></i></div>
                <form class="search-model-form">
                    <input type="text" id="search-input" placeholder="Search here.....">
                </form>
            </div>
        </div>
        <!-- Search model end -->
    </section>
    <!-- Product Section End -->

    <div class="success-message">Berhasil keluar</div>

    <?php include_once('footer.php'); ?>

     <!-- Script to display success message -->
     <script>
        // Show success message if exists
        <?php if ($success_message): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var successMessage = document.querySelector('.success-message');
                successMessage.style.display = 'block';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000); // Hide after 5 seconds
            });
        <?php endif; ?>
    </script>

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
