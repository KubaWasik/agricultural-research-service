<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
	header('Location: index.php');
	exit();
} elseif (isset($_SESSION['laborant'])) {
	header('Location: laborant_experiments.php');
	exit();
}
if (isset($_POST['submit'])) {
	switch ($_POST['submit']) {
		case "Usuń wyniki":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query_delete = "DELETE FROM results WHERE result_id = " . $_POST['result_id'];
					$conn->query($query_delete);
					$query_update = "UPDATE experiments SET is_done = false WHERE experiment_name = '" . $_POST['experiment_name'] . "'";
					$conn->query($query_update);
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
						<p class="card-text">Usunięto wynik o id = ' . $_POST['result_id'] . '.<br>
							Status doświadczenia zmieniony, można dalej dodawać próbki.
						</p>
					</div>
				</div>';
			break;
		case "Zakończ doświadczenie i przejdź do wyników":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = 'SELECT s.experiment_id AS experiment_id, experiment_name, e.area_id AS area_id, plant_id, fertilizer_id, SUM(quantity) AS quantity,  SUM(quantity) / a.size AS quantity_ratio, AVG(s.size) AS average_size, AVG(quality) AS plants_quality
					FROM samples AS s INNER JOIN experiments AS e ON s.experiment_id = e.experiment_id
					INNER JOIN areas AS a ON e.area_id = a.area_id
					WHERE s.experiment_id = ' . $_POST['experiment_id'];
					$result = $conn->query($query);
					$number_of_experiments = $result->num_rows;
					if ($number_of_experiments > 0) {
						$row = $result->fetch_assoc();
						$query_results = 'INSERT INTO results(experiment_name, area_id, plant_id, fertilizer_id, plants_quantity, quantity_ratio, average_size, plants_quality) VALUES("' . $row['experiment_name'] . '",' . $row['area_id'] . ',' . $row['plant_id'] . ',' . $row['fertilizer_id'] . ',' . $row['quantity'] . ',' . $row['quantity_ratio'] . ',' . $row['average_size'] . ',' . $row['plants_quality'] . ')';
						$conn->query($query_results);
						$is_done_query = "UPDATE experiments SET is_done = true WHERE experiment_id = " . $_POST['experiment_id'];
						$conn->query($is_done_query);
					} else {
						$_SESSION['info'] = '
						<div class="card border-danger mb-3" style="max-width: 18rem;">
							<div class="card-header">Informacja</div>
							<div class="card-body text-danger">
								<p class="card-text">Błąd w dodawaniu wyniku doswiadczenia o id = ' . $_POST['experiment_id'] . '</p>
							</div>
						</div>';
						break;
					}
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
					<p class="card-text">Dodano wynik eksperymentu: ' . $row['experiment_name'] . '</p>
				</div>
			</div>';
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Wyniki - Nazdorca</title>
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
		<div class="row justify-content-center">
			<div class="col">
				<div class="row p-2 m-2 text-center justify-content-center">
					<?php
					if (isset($_SESSION['info'])) {
						echo $_SESSION['info'];
						unset($_SESSION['info']);
					}
					?>
				</div>
				<h2 class="header mt-3">Lista ukończonych doświadczeń</h2>
					<?php
					require_once "credentials.php";
					try {
						$conn = new mysqli($host, $db_user, $db_password, $db_name);
						if ($conn->connect_errno != 0) {
							throw new Exception(mysqli_connect_errno());
						} else {
							$conn->set_charset("utf8");
							$query = "SELECT result_id, experiment_name, area_name, plant_name, fertilizer_name, plants_quantity, quantity_ratio, average_size, plants_quality, finish_date FROM results AS r INNER JOIN plants AS p ON r.plant_id = p.plant_id INNER JOIN fertilizers AS f ON r.fertilizer_id = f.fertilizer_id INNER JOIN areas AS a ON r.area_id = a.area_id order by result_id";
							$result = $conn->query($query);
							$number_of_results = $result->num_rows;
							if ($number_of_results > 0) {
								while ($row = $result->fetch_assoc()) {
									echo '
									<div class="card-body">
										<div id="accordion' . $row['result_id'] . '" role="tablist">
											<div class="card">
												<div class="btn btn-outline-secondary card-header text-dark d-flex justify-content-between" role="tab"
												id="experiment_' . $row['result_id'] . '" type="button" data-toggle="collapse" data-target="#details_'
												. $row['result_id'] . '" aria-expanded="false" aria-controls="details_' . $row['result_id'] . '">
												<span><strong>ID</strong> : ' . $row['result_id'] . '</span>
												<span><strong>Nazwa</strong>: ' . $row['experiment_name'] . '</span>
												<span><strong>Obszar</strong>: ' . $row['area_name'] . '</span>
												<span><strong>Data ukonczenia</strong>: ' . $row['finish_date'] . '</span>
											</div>
											<div id="details_' . $row['result_id'] . '" class="collapse" role="tabpanel" aria-labelledby="experiment_'
											. $row['result_id'] . '" data-parent="#accordion' . $row['result_id'] . '">
												<div class="card-body">
													<h4 class="d-flex justify-content-around">
														<span><strong>Roślina</strong>: ' . $row['plant_name'] . ' </span>
														<span><strong>Nawóz</strong>: ' . $row['fertilizer_name'] . '</span>
													</h4>
													<table class="table table-striped table-hover">
														<thead>
															<tr>
																<th>Liczba roślin</th>
																<th>Ilość roślin na metr kwadratowy</th>
																<th>Średnia wielkość roślin</th>
																<th>Jakość roślin (w skali 1-10)</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<th>' . $row['plants_quantity'] . '</th>
																<td>' . $row['quantity_ratio'] . '</td>
																<td>' . $row['average_size'] . '</td>
																<td>' . $row['plants_quality'] . '</td>
															</tr>
														</tbody>
													</table>
                          <div class="d-flex flex-row-reverse">
                            <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
								<input type="hidden" name="result_id" value="' . $row["result_id"] . '" />
								<input type="hidden" name="experiment_name" value="' . $row["experiment_name"] . '" />
                              <input class="btn btn-outline-danger" type="submit" name="submit" value="Usuń wyniki">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';
								}
							} else {
								echo '<div>Brak wyników</div>';
							}
						}
						$conn->close();
					} catch (Exception $e) {
						echo '<p class="red">Błąd serwera!</p>';
						echo '<br><p>Informacja: ' . $e . '</p>';
					}
					?>
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