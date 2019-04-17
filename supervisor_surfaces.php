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
		case "Usuń powierzchnię":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = "DELETE FROM areas WHERE surface_id = " . $_POST['surface_id'];
					$conn->query($query);
					$query = "DELETE FROM surfaces WHERE surface_id = " . $_POST['surface_id'];
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
					<p class="card-text">Usunięto powierzchnię o id = ' . $_POST['surface_id'] . '</p>
				</div>
			</div>';
			break;
		case "Dodaj powierzchnię":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = 'INSERT INTO surfaces(surface_name, size) VALUES ("' . $_POST['name'] . '",' . $_POST['size'] . ')';
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
						<p class="card-text">Dodano powierzchnię: ' . $_POST['name'] . '</p>
					</div>
				</div>';
			break;
		case "Usuń obszar":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = "DELETE FROM areas WHERE area_id = " . $_POST['area_id'];
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
					<p class="card-text">Usunięto obszar o id = ' . $_POST['area_id'] . '</p>
				</div>
			</div>';
			break;
		case "Dodaj obszar":
			require_once "credentials.php";
			try {
				$conn = new mysqli($host, $db_user, $db_password, $db_name);
				if ($conn->connect_errno != 0) {
					throw new Exception(mysqli_connect_errno());
				} else {
					$conn->set_charset("utf8");
					$query = 'INSERT INTO areas(surface_id,area_name,size) VALUES ("' . $_POST['surface_id'] . '","' . $_POST['name'] . '",' . $_POST['size'] . ')';
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
					<p class="card-text">Dodano obszar: ' . $_POST['name'] . '</p>
				</div>
			</div>';
			break;
	}
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Powierzchnie - Nazdorca</title>
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
				<h2 class="header mt-3">Lista powierzchni</h2>
				<?php
				require_once "credentials.php";
				try {
					$conn = new mysqli($host, $db_user, $db_password, $db_name);
					if ($conn->connect_errno != 0) {
						throw new Exception(mysqli_connect_errno());
					} else {
						$conn->set_charset("utf8");
						$query = "SELECT * FROM surfaces";
						$result = $conn->query($query);
						$number_of_surfaces = $result->num_rows;
						if ($number_of_surfaces > 0) {
							while ($row = $result->fetch_assoc()) {
								echo '
								<div class="card-body">
									<div id="accordion' . $row['surface_id'] . '" role="tablist">
										<div class="card">
											<div class="btn btn-outline-secondary card-header text-dark d-flex justify-content-between" role="tab" id="surface_' . $row['surface_id'] . '" type="button" data-toggle="collapse" data-target="#area_' . $row['surface_id'] . '" aria-expanded="false" aria-controls="area_' . $row['surface_id'] . '">
												<span> <strong>ID</strong> : ' . $row['surface_id'] . ' </span>
												<span> <strong>Nazwa</strong>: ' . $row['surface_name'] . ' </span>
												<span> <strong>Rozmiar</strong>: ' . $row['size'] . ' </span>
												<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
													<input type="hidden" name="surface_id" value="' . $row["surface_id"] . '" />
													<input class="btn btn-danger" type="submit" name="submit" value="Usuń powierzchnię">
												</form>
											</div>
											<div id="area_' . $row['surface_id'] . '" class="collapse" role="tabpanel" aria-labelledby="surface_' . $row['surface_id'] . '" data-parent="#accordion' . $row['surface_id'] . '">
												<div class="card-body">
													<h4>Lista obszarów</h4>
													<table class="table table-striped table-hover">
														<thead>
															<tr>
																<th>ID Obszaru</th>
																<th>Nazwa</th>
																<th>Rozmiar</th>
																<th>Akcja</th>
															</tr>
														</thead>
														<tbody>';
								$areas_query = "SELECT * FROM areas WHERE surface_id = " . $row['surface_id'];
								$areas_result = $conn->query($areas_query);
								$number_of_areas = $areas_result->num_rows;
								$size = 0;
								if ($number_of_areas > 0) {
									while ($row_areas = $areas_result->fetch_assoc()) {
										echo '
															<tr>
																<th>' . $row_areas['area_id'] . '</th>
																<td>' . $row_areas['area_name'] . '</td>
																<td>' . $row_areas['size'] . '</td>
																<td>
																	<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
																		<input type="hidden" name="area_id" value="' . $row_areas["area_id"] . '" />
																		<input class="btn btn-danger" type="submit" name="submit" value="Usuń obszar">
																	</form>
																</td>
															</tr>';
										$size = $size + $row_areas['size'];
									}
								} else {
									echo '
											<tr>
												<td colspan="4">Brak obszarów</td>
											</tr>
										</tbody>
									</table>';
								}
								$avaiable_size = $row['size'] - $size;
								echo '				
														</tbody>
													</table>
													<div class="p-2">
														<h4 class="pb-4">Dodaj obszar</h4>
														<form action="' . $_SERVER["PHP_SELF"] . '" method="post">
															<div class="d-flex justify-content-around align-items-center form-group">
																<span style="width: 10%">ID</span>
																<input type="hidden" name="surface_id" value="' . $row["surface_id"] . '" />
																<input class="form-control" style="width: 40%"  type="text" name="name" placeholder="Nazwa">
																<input class="form-control" style="width: 20%" data-toggle="tooltip" data-placement="top" title="Dostępne miejsce: ' . $avaiable_size . '" type="number" min="0" max="' . $avaiable_size . '" step="0.1" name="size" placeholder="Rozmiar">
																<input class="btn btn-success" style="width: auto" type="submit" name="submit" value="Dodaj obszar">
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';
							}
						} else {
							echo '
							<tr>
								<td colspan="4">Brak powierzchni</td>
							</tr>';
						}
					}
					$conn->close();
				} catch (Exception $e) {
					echo '<p class="red">Błąd serwera!</p>';
					echo '<br><p>Informacja: ' . $e . '</p>';
				}

				?>
				<div class="card-body">
					<h4 class="pb-4">Dodaj powierzchnie</h4>
					<form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method="post">
						<div class="d-flex justify-content-around align-items-center form-group">
							<span style="width: auto">ID</span>
							<input class="form-control" style="width: auto" type="text" name="name" placeholder="Nazwa">
							<input class="form-control" style="width: auto" type="number" min="0" step="0.1" name="size" placeholder="Rozmiar">
							<input class="btn btn-success" style="width: auto" type="submit" name="submit" value="Dodaj powierzchnię">
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
		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		})

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