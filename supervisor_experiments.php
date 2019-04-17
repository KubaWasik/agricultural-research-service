<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
	exit();
} else {
	if (isset($_SESSION['laborant'])) {
		header('Location: laborant_experiments.php');
		exit();
	}
}
if (isset($_POST['submit'])) {
	switch ($_POST['submit']) {
		case "Usuń doświadczenie":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = "DELETE FROM experiments WHERE experiment_id = " . $_POST['experiment_id'];
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
					<p class="card-text">Usunięto experiment o id = ' . $_POST['experiment_id'] . '</p>
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
					$query = 'INSERT INTO experiments(experiment_name, plant_id, fertilizer_id)
						VALUES ("' . $_POST['name'] . '",' . $_POST['plant'] . ',' . $_POST['fertilizer'] . ')';
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
          <p class="card-text">Dodano experiment: ' . $_POST['name'] . '</p>
        </div>
      </div>';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Strona główna - Nazdorca</title>
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
		<a href="supervisor_laborants.php">Laboranci</a>
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
				<h2 class="header mt-3">Lista utworzonych doświadczeń</h2>
				<?php
				require_once "credentials.php";
				try {
					$conn = new mysqli($host, $db_user, $db_password, $db_name);
					if ($conn->connect_errno != 0) {
						throw new Exception(mysqli_connect_errno());
					} else {
						$conn->set_charset("utf8");
						$query =
							"SELECT experiment_id, experiment_name, plant_name, fertilizer_name, create_date, is_done
						FROM experiments AS e
						INNER JOIN plants AS p ON e.plant_id = p.plant_id
						INNER JOIN fertilizers AS f ON e.fertilizer_id = f.fertilizer_id
						ORDER BY experiment_id";
						$result = $conn->query($query);
						$number_of_experiments = $result->num_rows;
						if ($number_of_experiments > 0) {
							while ($row = $result->fetch_assoc()) {
								$sample_count_query = "SELECT count(sample_id) AS quantity FROM samples where experiment_id = " . $row['experiment_id'];
								$samples = $conn->query($sample_count_query);
								$samples = $samples->fetch_assoc();
								echo '
								<div class="card-body">
									<div id="accordion' . $row['experiment_id'] . '" role="tablist">
										<div class="card">
											<div class="btn btn-outline-secondary card-header text-dark d-flex justify-content-between" role="tab"
												id="experiment_' . $row['experiment_id'] . '" type="button" data-toggle="collapse" 
												data-target="#sample_' . $row['experiment_id'] . '" aria-expanded="false" 
												aria-controls="sample_' . $row['experiment_id'] . '">
												<span> <strong>ID</strong>: ' . $row['experiment_id'] . '</span>&nbsp;
												<span> <strong>Nazwa</strong>: ' . $row['experiment_name'] . ' </span>&nbsp;
												<span> <strong>Data utworzenia</strong>: ' . $row['create_date'] . ' </span>&nbsp;
												<span> <strong>Ilość próbek</strong>: ' . $samples['quantity'] . '</span>&nbsp;
											</div>
											<div id="sample_' . $row['experiment_id'] . '" class="collapse" role="tabpanel"
												aria-labelledby="experiment_' . $row['experiment_id'] . '" data-parent="#accordion' . $row['experiment_id'] . '">
												<div class="card-body">
													<h4>Lista probek (<strong>Roślina</strong>: ' . $row['plant_name'] . ' <strong>Nawóz</strong>: '
									. $row['fertilizer_name'] . ')</h4>
													<table class="table table-striped table-hover">
														<thead>
															<tr>
																<th>ID sample</th>
																<th>Nazwa</th>
																<th>Laborant</th>
																<th>Obszar</th>
																<th>Ilość próbek</th>
																<th>Wielkość próbek</th>
																<th>Jakość próbek</th>
																<th>Data próbki</th>
															</tr>
														</thead>
													<tbody>';
								$samples_query =
									"SELECT sample_id, sample_name, laborant_name, area_name, quantity, s.size AS size, quality, s.create_date AS create_date	FROM samples AS s INNER JOIN experiments AS e ON s.experiment_id = e.experiment_id INNER JOIN laborants AS l ON s.laborant_id = l.laborant_id INNER JOIN areas AS a ON a.area_id = e.area_id WHERE s.experiment_id = " . $row['experiment_id'];
								$samples_result = $conn->query($samples_query);
								$number_of_samples = $samples_result->num_rows;
								if ($number_of_samples > 0) {
									while ($row_samples = $samples_result->fetch_assoc()) {
										echo '
										<tr>
											<th>' . $row_samples['sample_id'] . '</th>
											<td>' . $row_samples['sample_name'] . '</td>
											<td>' . $row_samples['laborant_name'] . '</td>
											<td>' . $row_samples['area_name'] . '</td>
											<td>' . $row_samples['quantity'] . '</td>
											<td>' . $row_samples['size'] . '</td>
											<td>' . $row_samples['quality'] . '</td>
											<td>' . $row_samples['create_date'] . '</td>
										</tr>';
									}
								} else {
									echo '<tr><td colspan="8">Brak próbek</td></tr>';
								}
								echo '
									</tbody>
								</table>
								<div class="d-flex justify-content-around">
									<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
										<input type="hidden" name="experiment_id" value="' . $row['experiment_id'] . '" />
										<input class="btn btn-outline-danger" type="submit" name="submit" value="Usuń doświadczenie">
									</form>';
								if ($row['is_done']) {
									echo '<a class="btn btn-outline-success" href="supervisor_results.php">Doświadczenie zakończone, przejdź do wyników</a>';
								} else {
									echo '
									<form action="supervisor_results.php" method="post">
										<input type="hidden" name="experiment_id" value="' . $row["experiment_id"] . '" />
										<input class="btn btn-outline-warning" type="submit" name="submit" value="Zakończ doświadczenie i przejdź do wyników">
									</form>';
								}
								echo '
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';
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
				<div class="card-body">
					<h4 class="pb-4">Dodaj doświadczenie</h4>
					<form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post">
						<div class="d-flex justify-content-around align-items-center form-group">
							<span class="m-2" style="width: auto">ID</span>
							<input class="form-control m-2" style="width: auto" type="text" name="name" placeholder="Nazwa">
							<select class="custom-select m-2" style="width: auto" name="plant">
								<?php
								require_once "credentials.php";
								try {
									$conn = new mysqli($host, $db_user, $db_password, $db_name);
									if ($conn->connect_errno != 0) {
										throw new Exception(mysqli_connect_errno());
									} else {
										$conn->set_charset("utf8");
										$query = "SELECT * FROM plants";
										$result = $conn->query($query);
										$quantity = $result->num_rows;
										if ($quantity > 0) {
											while ($row = $result->fetch_assoc()) {
												echo '<option value="' . $row['plant_id'] . '">' . $row['plant_name'] . '</option>';
											}
										} else {
											echo '<div>Brak nawozów</div>';
										}
										$conn->close();
									}
								} catch (Exception $e) {
									echo '<p class="red">Błąd serwera!</p>';
									echo '<br><p>Informacja: ' . $e . '</p>';
								}
								?>
							</select>
							<select class="custom-select m-2" style="width: auto" name="fertilizer">
								<?php
								require_once "credentials.php";
								try {
									$conn = new mysqli($host, $db_user, $db_password, $db_name);
									if ($conn->connect_errno != 0) {
										throw new Exception(mysqli_connect_errno());
									} else {
										$conn->set_charset("utf8");
										$query = "SELECT * FROM fertilizers";
										$result = $conn->query($query);
										$quantity = $result->num_rows;
										if ($quantity > 0) {
											while ($row = $result->fetch_assoc()) {
												echo '<option value="' . $row['fertilizer_id'] . '">' . $row['fertilizer_name'] . '</option>';
											}
										} else {
											echo '<div>Brak nawozów</div>';
										}
										$conn->close();
									}
								} catch (Exception $e) {
									echo '<p class="red">Błąd serwera!</p>';
									echo '<br><p>Informacja: ' . $e . '</p>';
								}
								?>
							</select>
							<span class="m-2" style="width: auto">Aktualna data</span>
							<input class="btn btn-outline-success m-2" style="width: auto" type="submit" name="submit" value="Dodaj">
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