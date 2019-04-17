<?php
	session_start();
	if ((!isset($_POST['login'])) || (!isset($_POST['passwd']))) {
		header('Location: index.php');
		exit();
	}
	require_once "credentials.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
  try {
		$login = $_POST['login'];
    $passwd = $_POST['passwd'];
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_errno!=0) {
    	throw new Exception(mysqli_connect_errno());
    } else {
			$conn->set_charset("utf8");
      $login = htmlentities($login, ENT_QUOTES, "UTF-8");
      $passwd = htmlentities($passwd, ENT_QUOTES, "UTF-8");
      if ($result = $conn->query(
          	sprintf("SELECT * FROM supervisors WHERE login='%s' AND passwd = PASSWORD('%s')",
            mysqli_real_escape_string($conn, $login), mysqli_real_escape_string($conn, $passwd))))
      {
        $number_of_supervisors = $result->num_rows;
        if($number_of_supervisors > 0) {
					$row = $result->fetch_assoc();
          $_SESSION["name"] = $row["supervisor_name"];
          $_SESSION['logged_in'] = true;
          $_SESSION['supervisor'] = true;
          unset($_SESSION['error']);
          $result->free_result();
          header('Location: supervisor_experiments.php');
        } else {
					if ($result = $conn->query(
								sprintf("SELECT * FROM laborants WHERE login='%s' AND passwd = PASSWORD('%s')",
								mysqli_real_escape_string($conn, $login), mysqli_real_escape_string($conn, $passwd))))
          {
            $number_of_laborants = $result->num_rows;
            if($number_of_laborants > 0) {
							$_SESSION['error'] = '<p style="color: red">Jesteś laborantem, zaloguj się jako laborant</p>';
            } else {
							$_SESSION['error'] = '<p style="color: red">Nie ma nadzorcy o podanych danych, sprawdź poprawność danych</p>';
            }
          }
          header('Location: index.php');
        }
      } else {
				throw new Exception($conn->error);
			}
			$conn->close();
	  }
	}
	catch(Exception $e)
	{
		echo '<p class="red">Błąd serwera!</p>';
		echo '<br><p>Informacja: '.$e.'</p>';
	}
?>
