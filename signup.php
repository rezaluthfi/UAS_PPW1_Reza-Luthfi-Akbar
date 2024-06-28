<?php
// Include file koneksi ke database
include_once('connection.php');

// Inisialisasi variabel untuk menyimpan pesan error dan sukses
$error = '';
$success = '';

// Cek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa apakah email atau username sudah ada
    $query_check = "SELECT * FROM user WHERE email = :email OR username = :username LIMIT 1";
    $statement_check = $conn->prepare($query_check);
    $statement_check->bindParam(':email', $email);
    $statement_check->bindParam(':username', $username);
    $statement_check->execute();
    $result = $statement_check->fetch();

    if ($result) {
        if ($result['email'] == $email) {
            $error = "Email sudah digunakan, silakan gunakan email yang berbeda.";
        } elseif ($result['username'] == $username) {
            $error = "Username sudah digunakan, silakan gunakan username yang berbeda.";
        }
    } else {
        // Hash password sebelum disimpan di database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk menyimpan data ke database
        $query_insert = "INSERT INTO user (username, PASSWORD, email) VALUES (:username, :password, :email)";

        // Persiapkan statement PDO untuk insert
        $statement = $conn->prepare($query_insert);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashed_password);
        $statement->bindParam(':email', $email);

        // Eksekusi statement
        if ($statement->execute()) {
            // Set pesan sukses
            $success = "Akun berhasil dibuat. Silakan login dengan akun baru Anda.";

            // Redirect setelah menampilkan pesan sukses
            header("refresh:3;url=login.php"); // Redirect ke login.php setelah 3 detik
        } else {
            $error = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
        }
    }
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
    <title>Removie | Tempatnya Nonton Film</title>

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

    <!-- Signup Section Begin -->
    <section class="signup spad">
        <div class="container">
              <div class="row">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>Sign Up</h3>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php elseif (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="input__item">
                                <input type="email" name="email" placeholder="Email address" required>
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="username" placeholder="Username" required>
                                <span class="icon_profile"></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="password" placeholder="Password" required>
                                <span class="icon_lock"></span>
                            </div>
                            <button type="submit" class="site-btn">Sign Up</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login__register">
                        <h3>Already Have An Account?</h3>
                        <a href="login.php" class="primary-btn">Login Now</a>
                    </div>
                </div>
              </div>
        </div>
    </section>
    <!-- Signup Section End -->

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
