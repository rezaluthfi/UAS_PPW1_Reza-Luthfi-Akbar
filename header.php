<?php
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

// Function to check if the current page matches the given link
function is_active($link) {
    return (strpos($_SERVER['PHP_SELF'], $link) !== false) ? 'active' : '';
}

// get categories from database
$sql_categories = "SELECT genre_id, genre_name FROM genre";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Removie</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!-- Custom CSS for header right -->
    <style>
        .header__actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .header__search-form {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .header__search-input {
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 20px 0 0 20px;
            outline: none;
            width: 200px;
        }

        .header__search-button {
            background: #e53637;
            border: 1px solid #e53637;
            padding: 5px 10px;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            color: #fff;
        }

        .header__profile-icon {
            color: #e53637;
            font-size: 20px;
            margin-left: 10px;
            cursor: pointer;
        }

        /* Adjustments for mobile menu */
        @media (max-width: 768px) {
            .header__nav {
                width: 100%;
            }

            .header__actions {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                margin-top: 0;
            }

            .header__search-form {
                margin-right: 0;
                margin-bottom: 0;
                flex-grow: 1;
            }

            .header__search-input {
                width: calc(100% - 40px);
            }

            .header__search-button {
                width: 40px;
                border-radius: 0;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 992px){
            .header__profile-icon {
                display: visible;
                margin-right: 104px;
            }

        }
    </style>
</head>

<body>
    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo Section -->
                <div class="col-lg-2 col-md-2 col-6">
                    <div class="header__logo">
                        <a href="./index.php">
                            <img src="./img/logo_removie.png" alt="Removie Logo" />
                        </a>
                    </div>
                </div>
                <!-- Navigation Section -->
                <div class="col-lg-8 col-md-8 col-6">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li class="<?php echo is_active('index.php'); ?>"><a href="./index.php">Beranda</a></li>
                                <li class="<?php echo is_active('categories.php'); ?>">
                                    <a href="./categories.php">Kategori <span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">
                                        <?php
                                        if ($result_categories->num_rows > 0) {
                                            while ($category = $result_categories->fetch_assoc()) {
                                                echo '<li><a href="./categories.php?genre_id=' . $category['genre_id'] . '">' . $category['genre_name'] . '</a></li>';
                                            }
                                        } else {
                                            echo '<li><a href="#">No Categories Found</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Search and Profile Section -->
                <div class="col-lg-2 col-md-2 col-12">
                    <div class="header__actions">
                        <form action="search_results.php" method="GET" class="header__search-form">
                            <input type="text" name="query" placeholder="Search..." class="header__search-input">
                            <button type="submit" class="header__search-button"><i class="fa fa-search"></i></button>
                        </form>
                        <a href="./login.php"><span class="icon_profile header__profile-icon"></span></a>
                    </div>
                </div>
            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>
    <!-- Header End -->
</body>

</html>
