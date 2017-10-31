<html>
<head>
<style>
.error {
	color: #FF0000;
}
</style>
<title>Update Employee details</title>
<?php include "db.php" ?>
</head>
<body>
	<?php
		$e_first_name = $e_last_name = $e_emp_no =
			$e_gender = $e_birth_date = $e_hire_date = "";

		$link = mysqli_connect($host, $user, $pass);
		$db = mysqli_select_db($link, $dbname);
		if (!$link) {
			die("Connection failed: " . mysqli_connect_error());
		}
		if(isset($_GET["ID"])) {
			$u_emp_no= (int)$_GET["ID"];
		}
		else {
			$u_emp_no = -1;
		}
		$u_emp_no = mysqli_real_escape_string($link, $u_emp_no);
		$sql   = <<<SQL
			SELECT
			  *
			 FROM `employees`
			 WHERE `emp_no` = $u_emp_no
			 LIMIT 1
SQL;
		if ($query = mysqli_query($link, $sql)) {
			if ( mysqli_num_rows($query) > 0 ) {
				$results  = mysqli_fetch_assoc($query);
				$employee = $results;
			}
			else {
				header('Location: index.php');
				exit(0);
			}
		}
		else {
			die("Couldn't connect");
		}

		if (empty($_POST["u_first_name"]) and isset($_POST['add'])) {
			$e_first_name = "You must enter first name";
		}
		elseif(isset($_POST["u_first_name"]) and
			(strlen(trim($_POST["u_first_name"])) == 0 or
			strlen(trim($_POST["u_first_name"])) >= 14 ) ) {
			$e_first_name = "Invalid First Name";
		}
		elseif(!empty($_POST["u_first_name"])) {
			$u_first_name= trim($_POST["u_first_name"]);
		}

		if (empty($_POST["u_last_name"]) and isset($_POST['add'])) {
			$e_last_name = "You must enter last name";
		}
		elseif(!empty($_POST["u_last_name"]) and ( strlen(trim($_POST["u_last_name"])) == 0 or strlen(trim($_POST["u_last_name"])) >= 16 ) ){
			$e_last_name = "Invalid Last Name";
		}
		elseif(!empty($_POST["u_last_name"])) {
			$u_last_name= trim($_POST["u_last_name"]);
		}

		if (empty($_POST["u_gender"]) and isset($_POST['add'])) {
			$e_gender = "You must enter gender";
		}
		elseif(!empty($_POST["u_gender"]) and (strtoupper($_POST["u_gender"]) != 'M' and strtoupper($_POST["u_gender"]) != 'F' )) {
			$e_gender= "Only the letter M or F!";
		}
		elseif(!empty($_POST["u_gender"])) {
			$u_gender= strtoupper(trim($_POST["u_gender"]));
		}

		if (empty($_POST["u_birth_date"]) and isset($_POST['add'])) {
			$e_birth_date = "You must enter birth date";
		}
		elseif(!empty($_POST["u_birth_date"]) and !preg_match('/^([0-9]{4}\-[0-9]?[0-9]\-[0-9]?[0-9])$/', $_POST["u_birth_date"])) {
			$e_birth_date= "Only Proper YYYY-MM-DD Format!!";
		}
		elseif(!empty($_POST["u_birth_date"])) {
			$u_birth_date= trim($_POST["u_birth_date"]);
		}

		if (empty($_POST["u_hire_date"]) and isset($_POST['add'])) {
			$e_hire_date = "You must enter hire date";
		}
		elseif(!empty($_POST["u_hire_date"]) and !preg_match('/^([0-9]{4}\-[0-9]?[0-9]\-[0-9]?[0-9])$/', $_POST["u_hire_date"])) {
			$e_hire_date= "Only Proper YYYY-MM-DD Format!!";
		}
		elseif(!empty($_POST["u_hire_date"])) {
			$u_hire_date= trim($_POST["u_hire_date"]);
		}

		if(isset($_POST['add']) and $e_first_name == '' and
			$e_last_name == '' and  $e_emp_no == '' and
			$e_gender == '' and $e_birth_date == '' and $e_hire_date== '') {

			$u_first_name = mysqli_real_escape_string($link, $u_first_name);
			$u_last_name = mysqli_real_escape_string($link, $u_last_name);
			$u_gender = mysqli_real_escape_string($link, $u_gender);
			$u_birth_date= mysqli_real_escape_string($link, $u_birth_date);
			$u_hire_date = mysqli_real_escape_string($link, $u_hire_date);

			$sql = "UPDATE employees
							SET
								first_name = '$u_first_name',
								last_name = '$u_last_name',
								gender = '$u_gender',
								birth_date = '$u_birth_date',
								hire_date= '$u_hire_date'
							WHERE
								emp_no = $u_emp_no
							LIMIT 1";

			if (mysqli_query($link, $sql)) {
				echo " Employee Record with ID $u_emp_no Updated sucessfully";

				$employee['first_name'] = $u_first_name;
				$employee['last_name'] = $u_last_name;
				$employee['gender'] = $u_gender;
				$employee['birth_date'] = $u_birth_date;
				$employee['hire_date'] = $u_hire_date;
				$employee['first_name'] = $u_first_name;
			}
			else {
				echo "Error updating record: " . mysqli_error($link);
			}
			mysqli_close($link);
		}

		function cleanup($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
	?>

	<h3>Employee Data</h3>

	<form method="post"
		action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?ID=<?php echo test_input($u_emp_no)?>">

		<label>First Name:<input type="text" name="u_first_name"
			value="<?php echo cleanup($employee['first_name']);?>"></label>
		<span class="error">* <?php echo $e_first_name;?></span><br>
		<label>Last Name:<input type="text" name="u_last_name"
			value="<?php echo test_input($employee['last_name']);?>"></label>
			<span class="error">*	<?php echo $e_last_name;?></span><br>
		<label>Gender:<input type="text" name="u_gender"
			value="<?php echo test_input($employee['gender']);?>"></label>
			<span class="error">* <?php echo $e_gender;?></span><br>
		<label>Birth Date:<input type="text" name="u_birth_date"
			value="<?php echo test_input($employee['birth_date']);?>"></label>
			<span class="error">*	<?php echo $e_birth_date;?></span><br>
		<label>Hire Date:<input type="text" name="u_hire_date"
			value="<?php echo test_input($employee['hire_date']);?>"></label>
			<span class="error">*	<?php echo $e_hire_date;?></span><br>
		<input name="add" type="submit" id="add" value="Update Employee">
	</form>
	<br>
	<?php echo "<a href = \"index.php\"> Home </a>"; ?>
</body>
</html>
