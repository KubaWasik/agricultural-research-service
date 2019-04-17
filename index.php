<?php
session_start();
if (isset($_SESSION['logged_in'])) {
	if (isset($_SESSION['supervisor'])) {
		header('Location: supervisor_experiments.php');
		exit();
	} else {
		if (isset($_SESSION['laborant'])) {
			header('Location: laborant_experiments.php');
			exit();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
	<title>Strona logowania</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>

<body>
	<div class="container-fluid">
		<div class="row justify-content-md-center">
			<div class="col col-md-auto">
				<div class="jumbotron mt-3 text-center">
					<h1 class="display-4">System zarządzania doświadczeniami</h1>
					<p class="lead">Podaj swój login i hasło aby zalogować się do systemu</p>
				</div>
				<div class="text-center">
					<?php
					if (isset($_SESSION['error'])) {
						echo $_SESSION['error'];
						unset($_SESSION['error']);
					}
					?>
				</div>
				<div class="d-flex justify-content-around">
					<div class="card">
						<div class="card-header bg-success text-white">
							Zaloguj jako Laborant
						</div>
						<div class="card-body">
							<form method="post" action="laborant_login.php">
								<div class="form-group">
									<label for="login_laborant">Login</label>
									<div class="input-group pb-2">
										<input type="text" class="form-control" id="login_laborant" name="login" placeholder="Podaj login" required>
									</div>
									<label for="password_laborant">Hasło</label>
									<div class="input-group pb-3">
										<input type="password" class="form-control" id="password_laborant" name="passwd" placeholder="Podaj hasło" required>
									</div>
									<div class="text-center">
										<button type="submit" class="btn btn-outline-success">Zaloguj</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="card">
						<div class="card-header bg-info text-white">
							Zaloguj jako Nadzorca
						</div>
						<div class="card-body">
							<form method="post" action="supervisor_login.php">
								<div class="form-group">
									<label for="login_supervisor">Email</label>
									<div class="input-group pb-2">
										<input type="text" class="form-control" id="login_supervisor" name="login" placeholder="Podaj login" required>
									</div>
									<label for="password_supervisor">Hasło</label>
									<div class="input-group pb-3">
										<input type="password" class="form-control" id="password_supervisor" name="passwd" placeholder="Podaj hasło" required>
									</div>
									<div class="text-center">
										<button type="submit" class="btn btn-outline-info">Zaloguj</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous">
	</script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous">
	</script>
</body>

</html>
