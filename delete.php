<html>
	<head>
		<?php include 'db.php'; ?>
		<link rel="icon"  type="image/png" href="kMxOTN1.png" />
		<style>
		.error {
			color: #FF0000;
		}
		</style>
		<title>Employee Delete</title>
	</head>

	<body>
		<?php
			$d_emp_no = "";
			if(!empty($_GET["ID"])) {
			$d_emp_no= $_GET["ID"];
			}
			else {
				$d_emp_no = -1;
			}
			$d_emp_no = (int)$d_emp_no;

			$link = mysqli_connect($host, $user, $pass);
			$db = mysqli_select_db($link, $dbname);
			$d_emp_no = mysqli_real_escape_string($link,$d_emp_no);
			if (!$link) {
				die("Connection failed: " . mysqli_connect_error());
			}

			$sql = <<<SQL
				SELECT
				  emp_no
				 FROM `employees`
				 WHERE `emp_no` = $d_emp_no
				 LIMIT 1
SQL;

			if ($query = mysqli_query($link, $sql)) {
				if (mysqli_num_rows($query) == 0) {
					$d_emp_no = cleanup($d_emp_no);
					die("Employee with ID $d_emp_no not found in the database.");
				}
			}

			$sql = "DELETE FROM	employees	WHERE	emp_no = $d_emp_no";

			if(isset($_POST['delete'])) {
				if (mysqli_query($link, $sql)) {
					$d_emp_no = test_input($d_emp_no);
					echo "Employee with ID $d_emp_no deleted sucessfully";
				}
				else {
					echo "Error deleting record: " . mysqli_error($link);
				}
			}
			mysqli_close($link);

			function cleanup($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>
		<form method="post"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?ID=<?php echo "$d_emp_no"?>">
			<b>
			Are you sure you want to delete Employee with ID: <?php echo "$d_emp_no"?>????"
			<input name="delete" type="submit" id="delete" value="Yes, Delete!">
		</form>
	</body>
</html>
