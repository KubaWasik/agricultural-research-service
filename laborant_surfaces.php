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
?>
<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Powierzchnie - Laborant</title>
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
									<div id="accordion_' . $row['surface_id'] . '" role="tablist">
										<div class="card">
											<div class="btn btn-outline-secondary card-header text-dark d-flex justify-content-between" role="tab" id="surface_' . $row['surface_id'] . '" type="button" data-toggle="collapse" data-target="#area_' . $row['surface_id'] . '" aria-expanded="false" aria-controls="area_' . $row['surface_id'] . '">
												<div> <strong>ID</strong> : ' . $row['surface_id'] . ' </div>
												<div> <strong>Nazwa</strong>: ' . $row['surface_name'] . ' </div>
												<div> <strong>Rozmiar</strong>: ' . $row['size'] . ' </div>
										</div>
										<div id="area_' . $row['surface_id'] . '" class="collapse" role="tabpanel" aria-labelledby="surface_' . $row['surface_id'] . '" data-parent="#accordion_' . $row['surface_id'] . '">
											<div class="card-body">
												<h4>Lista obszarów</h4>
												<table class="table table-striped table-hover">
													<thead>
														<tr>
															<th>ID Obszaru</th>
															<th>Nazwa</th>
															<th>Rozmiar</th>
														</tr>
													</thead>
													<tbody>';
								$areas_query = "SELECT * FROM areas WHERE surface_id = " . $row['surface_id'];
								$areas_result = $conn->query($areas_query);
								$number_of_areas = $areas_result->num_rows;
								if ($number_of_areas > 0) {
									while ($areas_row = $areas_result->fetch_assoc()) {
										echo '
										<tr>
											<th>' . $areas_row['area_id'] . '</th>
											<td>' . $areas_row['area_name'] . '</td>
											<td>' . $areas_row['size'] . '</td>
										</tr>';
									}
								} else {
									echo '
									<tr>
										<td colspan="5">Brak obszarów</td>
									</tr>';
								}
								echo '
														</tbody>
													</table>
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