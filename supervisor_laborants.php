<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
} else if (isset($_SESSION['laborant'])) {
    header('Location: laborant_experiments.php');
    exit();
}

if (isset($_POST['submit'])) {
    switch ($_POST['submit']) {
        case "Usuń":
            require_once "credentials.php";
            try {
                $conn = new mysqli($host, $db_user, $db_password, $db_name);
                if ($conn->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $conn->set_charset("utf8");
                    $query = "DELETE FROM laborants WHERE laborant_id = " . $_POST['laborant_id'];
                    $conn->query($query);
                }
                $conn->close();
            } catch (Exception $e) {
                echo '<p class="red">Błąd serwera!</p>';
                echo '<br><p>Informacja: ' . $e . '</p>';
            }
            $_SESSION['info'] = '
            <div class="card border-warning mb-3" style="max-width: 18rem;">
                <div class="card-header">Informacja</div>
                <div class="card-body text-warning">
                <p class="card-text">Usunięto laboranta o id = ' . $_POST['laborant_id'] . '</p>
                </div>
            </div>';
            break;
        case "Dodaj":
            require_once "credentials.php";
            try {
                $conn = new mysqli($host, $db_user, $db_password, $db_name);
                if ($conn->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $conn->set_charset("utf8");
                    $query = 'INSERT INTO laborants(laborant_name, login, passwd) VALUES ("' . $_POST['name'] . '","' . $_POST['login'] . '", PASSWORD("' . $_POST['passwd'] . '"))';
                    $conn->query($query);
                }
                $conn->close();
            } catch (Exception $e) {
                echo '<p class="red">Błąd serwera!</p>';
                echo '<br><p>Informacja: ' . $e . '</p>';
            }
            $_SESSION['info'] = '
            <div class="card border-success mb-3" style="max-width: 18rem;">
                <div class="card-header">Informacja</div>
                <div class="card-body text-success">
                <p class="card-text">Dodano laboranta: ' . $_POST['name'] . '</p>
                </div>
            </div>';
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <title>Laboranci - Nazdorca</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <style>
        body {
            transition: background-color .5s;
            margin-bottom: 50px;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 5;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        #main {
            transition: margin-left .5s;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>

</head>

<body>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="supervisor_laboarnts.php">Laboranci</a>
        <a href="supervisor_surfaces.php">Powierzchnie</a>
        <a href="supervisor_experiments.php">Doświadczenia</a>
        <a href="supervisor_results.php">Wyniki</a>
    </div>
    <div class="container-fluid" id="main">
        <nav class="row justify-content-center navbar navbar-dark bg-info">
            <div class="navbar-brand" id="menu">
                <span style="cursor:pointer" class="navbar-toggler-icon" onclick="openNav()"></span>
            </div>
            <a class="col navbar-brand" href="supervisor_experiments.php">
                System zarządzania doświadczeniami
            </a>
            <div class="d-flex col navbar-item">
                <div class="navbar-text">
                    Witaj <strong style="color: #fcc616"><?php echo $_SESSION["name"]; ?></strong>
                </div>
            </div>
            <div class="navbar-nav">
                <a class="ml-auto my-auto nav-link" href="logout.php">
                    Wyloguj
                </a>
            </div>
        </nav>
        <div class="container">
            <div class="col">
                <div class="row p-2 m-2 text-center justify-content-center">
                    <?php
                    if (isset($_SESSION['info'])) {
                        echo $_SESSION['info'];
                        unset($_SESSION['info']);
                    }
                    ?>
                </div>
                <h2 class="header mt-3">Lista laborantów</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Imię</th>
                            <th scope="col">Login</th>
                            <th scope="col">Hasło</th>
                            <th scope="col">Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once "credentials.php";
                        try {
                            $conn = new mysqli($host, $db_user, $db_password, $db_name);
                            if ($conn->connect_errno != 0) {
                                throw new Exception(mysqli_connect_errno());
                            } else {
                                $conn->set_charset("utf8");
                                $query = "SELECT * FROM laborants";
                                $result = $conn->query($query);
                                $number_of_laborants = $result->num_rows;
                                if ($number_of_laborants > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                        <tr>
                                            <th scope="row">' . $row["laborant_id"] . '</th>
                                            <td>' . $row["laborant_name"] . '</td>
                                            <td>' . $row["login"] . '</td>
                                            <td>' . $row["passwd"] . '</td>
                                            <td>
                                                <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                                                    <input type="hidden" name="laborant_id" value="' . $row["laborant_id"] . '" />
                                                    <input class="btn btn-danger" type="submit" name="submit" value="Usuń">
                                                </form>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '
                                    <tr>
                                        <td colspan="5">Brak Laborantów</td>
                                    </tr>';
                                }
                            }
                            $conn->close();
                        } catch (Exception $e) {
                            echo '<p class="red">Błąd serwera!</p>';
                            echo '<br><p>Informacja: ' . $e . '</p>';
                        }
                        ?>
                    </tbody>
                </table>
                <div>
                    <h4 class="pb-4">Dodaj laboranta</h4>
                    <form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post">
                        <div class="d-flex justify-content-around align-items-center form-group">
                            <span class="m-2" style="width: auto">ID</span>
                            <input class="form-control" style="width: auto" type="text" name="name" placeholder="Imię">
                            <input class="form-control" style="width: auto" type="text" name="login" placeholder="Login">
                            <input class="form-control" style="width: auto" type="password" name="passwd" placeholder="Hasło">
                            <input class="btn btn-success" style="width: auto" type="submit" name="submit" value="Dodaj">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
</body>

</html>