<?php
// Database connection (same as before)
include_once('connection.php');

// Get genre_id from URL parameter or default to 1
$genre_id = isset($_GET['genre_id']) ? intval($_GET['genre_id']) : 1;

// Get sort_by from AJAX request
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'title ASC';

// Query to get genre name
$sql_genre_name = "SELECT genre_name FROM genre WHERE genre_id = :genre_id";
$stmt_genre_name = $conn->prepare($sql_genre_name);
$stmt_genre_name->bindParam(':genre_id', $genre_id, PDO::PARAM_INT);
$stmt_genre_name->execute();
$result_genre_name = $stmt_genre_name->fetch(PDO::FETCH_ASSOC);
$genre_name = $result_genre_name['genre_name'];

// Query to get sorted movies
$sql_sorted_movies = "SELECT * FROM movie WHERE genre_id = :genre_id ORDER BY $sort_by";
$stmt_sorted_movies = $conn->prepare($sql_sorted_movies);
$stmt_sorted_movies->bindParam(':genre_id', $genre_id, PDO::PARAM_INT);
$stmt_sorted_movies->execute();
$result_sorted_movies = $stmt_sorted_movies->fetchAll(PDO::FETCH_ASSOC);

// Output HTML for sorted movie list
if (count($result_sorted_movies) > 0) {
    foreach ($result_sorted_movies as $movie) {
?>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="product__item">
            <div class="product__item__pic">
                <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                <div class="ep"><?php echo htmlspecialchars($movie['duration'] . ' min'); ?></div>
                <div class="view"><i class="fa fa-star"></i> <?php echo htmlspecialchars($movie['rating']); ?></div>
                <a href="film-details.php?movie_id=<?php echo htmlspecialchars($movie['movie_id']); ?>" class="product__item__link"></a>
            </div>
            <div class="product__item__text">
                <ul>
                    <li><?php echo htmlspecialchars($genre_name); ?></li>
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
