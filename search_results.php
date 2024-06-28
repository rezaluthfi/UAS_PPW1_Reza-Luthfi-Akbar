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

// Get search query
$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

// Perform search
$sql_search = "SELECT m.*, g.genre_name FROM movie m 
               LEFT JOIN genre g ON m.genre_id = g.genre_id 
               WHERE m.title LIKE '%$query%' OR m.description LIKE '%$query%'";
$result_search = $conn->query($sql_search);
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
</head>

<body>
    <?php 
    $active_page = 'search'; // Set active page to search
    include_once('header.php'); 
    ?>

    <!-- Search Results Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Search Results for "<?php echo htmlspecialchars($query); ?>"</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if ($result_search->num_rows > 0) {
                                while ($movie = $result_search->fetch_assoc()) {
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
                                echo "<p>No results found for \"" . htmlspecialchars($query) . "\"</p>";
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
                                // Query to get top rated movies
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
    </section>
    <!-- Search Results Section End -->

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
</body>

</html>

<?php
$conn->close();
?>
