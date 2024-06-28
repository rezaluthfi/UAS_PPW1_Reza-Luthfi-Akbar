<?php
session_start();

// Include file koneksi ke database
include_once('connection.php');

// Cek apakah pengguna sudah login atau belum, jika belum redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

// Ambil informasi pengguna dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE user_id = :user_id";
$statement = $conn->prepare($query);
$statement->bindParam(':user_id', $user_id);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Set pesan berhasil masuk jika user baru saja login
$login_message = isset($_SESSION['login_message']) ? $_SESSION['login_message'] : '';
unset($_SESSION['login_message']); // hapus pesan setelah ditampilkan
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
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

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
    <?php include_once('header.php'); ?>

    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Profile Section Begin -->
    <section class="profile spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="profile__form text-center text-white">
                        <h3>User Profile</h3>
                        <!-- Tampilkan pesan berhasil masuk jika ada -->
                        <?php if (!empty($login_message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $login_message; ?>
                            </div>
                        <?php endif; ?>
                        <div class="profile__info text-white mt-5">
                            <img src="./img/profile-account.jpg" alt="Profile Picture" class="profile__picture">
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="profile__actions">
                            <!-- <a href="edit_profile.php" class="primary-btn">Edit Profile</a>
                            <a href="change_password.php" class="primary-btn">Change Password</a> -->
                            <a href="logout.php" class="primary-btn">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->

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
