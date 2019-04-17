<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
	exit();
} else
    if (isset($_SESSION['supervisor'])) {
	header('Location: supervisor_experiments.php');
	exit();
}
if (isset($_POST['submit'])) {
	switch ($_POST['submit']) {
		case "Dodaj próbkę":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = 'INSERT INTO samples(experiment_id, laborant_id, sample_name, quantity, size, quality) VALUES (' . $_POST['id'] . ',' . $_SESSION["laborant_id"] . ',"' . $_POST['sample_name'] . '",' . $_POST['quantity'] . ',' . $_POST['size'] . ',' . $_POST['quality'] . ')';
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
				<p class="card-text">Dodano probke: ' . $_POST['sample_name'] . '</p>
				</div>
			</div>';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Próbki - Laborant</title>
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
			z-index: 1;
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
		<a href="laborant_surfaces.php">Powierzchnie</a>
		<a href="laborant_samples.php">Próbki</a>
		<a href="laborant_experiments.php">Doświadczenia</a>
		<a href="laborant_results.php">Wyniki</a>
	</div>
	<div class="container-fluid" id="main">
		<nav class="row justify-content-center navbar navbar-dark bg-success">
			<div class="navbar-brand" id="menu">
				<span style="cursor:pointer" class="navbar-toggler-icon" onclick="openNav()"></span>
			</div>
			<a class="col navbar-brand" href="laborant_experiments.php">
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
				<h2 class="header mt-3">Dodaj próbkę</h2>
				<form class="card text-center m-4" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
					<div class="card-header">
						<h4 class="card-title">Podaj zebrane dane</h4>
					</div>
					<div class="card-body">
						<div class="pb-3 mb-3">
							<label class="my-1 mr-2" for="experiment">Wybierz doświadczenie</label>
							<select name="id" class="custom-select my-1 mr-sm-2" id="experiment">
								<option selected>Wybierz...</option>
								<?php
								require_once "credentials.php";
								try {
									$conn = new mysqli($host, $db_user, $db_password, $db_name);
									if ($conn->connect_errno != 0) {
										throw new Exception(mysqli_connect_errno());
									} else {
										$conn->set_charset("utf8");
										$query = "SELECT experiment_id,  experiment_name FROM experiments";
										$result = $conn->query($query);
										$number_of_experiments = $result->num_rows;
										if ($number_of_experiments > 0) {
											while ($row = $result->fetch_assoc()) {
												echo '<option value="' . $row['experiment_id'] . '">' . $row['experiment_name'] . '</option>';
											}
										} else {
											echo '<div>Brak doświadczeń</div>';
										}
									}
									$conn->close();
								} catch (Exception $e) {
									echo '<p class="red">Błąd serwera!</p>';
									echo '<br><p>Informacja: ' . $e . '</p>';
								}

								?>
							</select>
						</div>
						<div class="form-row">
							<div class="col">
								<label for="sample_name">Nazwa próbki</label>
								<input type="text" class="form-control" id="sample_name" name="sample_name" placeholder="Nazwa" required>
								<div class="invalid-feedback">
									Podaj nazwę próbki
								</div>
							</div>
							<div class="col">
								<label for="quantity">Ilość próbek</label>
								<input type="number" class="form-control" min="1" id="quantity" name="quantity" placeholder="Ilość" requried>
								<div class="invalid-feedback">
									Podaj ilość próbek
								</div>
							</div>
							<div class="col">
								<label for="quantity">Wielkość próbek</label>
								<input type="number" class="form-control" min="0.01" max="99.99" step="0.01" id="size" name="size" placeholder="Wielkość" requried>
								<div class="invalid-feedback">
									Podaj wielkość próbek
								</div>
							</div>
							<div class="col">
								<label for="quantity">Jakość próbek</label>
								<input type="number" class="form-control" min="1" max="10" step="1" id="quality" name="quality" placeholder="Jakość" requried>
								<div class="invalid-feedback">
									Podaj jakość próbek
								</div>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="submit" name="submit" value="Dodaj próbkę" class="btn btn-success">Dodaj próbkę</button>
					</div>
				</form>
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