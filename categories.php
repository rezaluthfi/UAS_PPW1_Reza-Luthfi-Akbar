<?php
// Database connection
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

// Get genre_id from URL parameter or default to 1
$genre_id = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 1;

// Query to get genre name
$sql_genre_name = "SELECT genre_name FROM genre WHERE genre_id = ?";
$stmt_genre_name = $conn->prepare($sql_genre_name);
$stmt_genre_name->bind_param("i", $genre_id);
$stmt_genre_name->execute();
$result_genre_name = $stmt_genre_name->get_result();
$genre_name = $result_genre_name->fetch_assoc()['genre_name'];

// Initial SQL query to get movies by genre_id (without sorting)
$sql_movies = "SELECT * FROM movie WHERE genre_id = ?";
$stmt_movies = $conn->prepare($sql_movies);
$stmt_movies->bind_param("i", $genre_id);
$stmt_movies->execute();
$result_movies = $stmt_movies->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Removie | Tempatnya Nonton Film!</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/elegant-icons.css">
    <link rel="stylesheet" href="css/plyr.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/slicknav.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header -->
    <?php include_once('header.php'); ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="./categories.php">Categories</a>
                        <span><?php echo htmlspecialchars($genre_name); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Section -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    <div class="section-title">
                                        <h4><?php echo htmlspecialchars($genre_name); ?></h4>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="product__page__filter">
                                        <p>Order by:</p>
                                        <select id="sortSelect">
                                            <option value="title ASC">A-Z</option>
                                            <option value="rating DESC">Top Rated</option>
                                            <option value="release_date DESC">Newest</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="movieList">
                            <?php
                            if ($result_movies->num_rows > 0) {
                                while ($movie = $result_movies->fetch_assoc()) {
                            ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <div class="product__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($movie['image_url']); ?>">
                                                <div class="ep"><?php echo htmlspecialchars($movie['duration'] . ' min'); ?></div>
                                                <div class="view"><i class="fa fa-star"></i> <?php echo htmlspecialchars($movie['rating']); ?></div>
                                                <a href="film-details.php?movie_id=<?php echo htmlspecialchars($movie['movie_id']); ?>" class="product__item__link"></a>
                                            </div>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li>
                                                        <?php echo htmlspecialchars($genre_name); ?>
                                                    </li>
                                                </ul>
                                                <h5><a href="film-details.php?movie_id=<?php echo htmlspecialchars($movie['movie_id']); ?>"><?php echo htmlspecialchars($movie['title']); ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<p>No movies found.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                            <div class="section-title">
                                <h5>Top Rated</h5>
                            </div>
                            <div class="filter__gallery" id="topRatedList">
                                <?php
                                // Initial query for top rated movies
                                $sql_top_rated = "SELECT * FROM movie ORDER BY rating DESC LIMIT 5";
                                $result_top_rated = $conn->query($sql_top_rated);

                                if ($result_top_rated->num_rows > 0) {
                                    while ($top_movie = $result_top_rated->fetch_assoc()) {
                                ?>
                                        <div class="product__sidebar__view__item set-bg mix day years" data-setbg="<?php echo htmlspecialchars($top_movie['image_url']); ?>">
                                            <div class="ep"><?php echo htmlspecialchars($top_movie['duration'] . ' min'); ?></div>
                                            <div class="view"><i class="fa fa-star"></i> <?php echo htmlspecialchars($top_movie['rating']); ?></div>
                                            <h5><a href="film-details.php?movie_id=<?php echo htmlspecialchars($top_movie['movie_id']); ?>"><?php echo htmlspecialchars($top_movie['title']); ?></a></h5>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "<p>No top rated movies found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Footer -->
    <?php include_once('footer.php'); ?>

    <!-- JavaScript Files -->
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
            // Handle change event on select element
            $('#sortSelect').change(function() {
                var sortBy = $(this).val();

                // AJAX call to fetch sorted movies
                $.ajax({
                    url: 'fetch_sorted_movies.php',
                    type: 'GET',
                    data: {
                        genre_id: <?php echo $genre_id; ?>,
                        sort_by: sortBy
                    },
                    success: function(response) {
                        $('#movieList').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

</body>

</html>
