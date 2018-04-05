<html>
<head>
<title>Employee Database Viewer</title>
<style>
a {
	padding: 0px 10px;
	word-wrap: normal;
	display: inline-block;
}
</style>
<?php include "db.php" ?>
</head>

<body>

	<h1>
		Welcome to the MySQL Employee Sample Database Viewer
	</h1>

		<form
			method="get"
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<!-- Provides 4 possible number of entries per page-->
			<h3>Select page size:</h3>
			<select name="entries">
				<option value="15">15</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="75">75</option>
			</select>

			<h3>Choose sort order:</h3>
			<select name="order">
				<option value="L_ASC">Last Name (ascending)</option>
				<option value="L_DESC">Last Name (descending)</option>
				<option value="F_ASC">First Name (ascending)</option>
				<option value="F_DESC">First Name (descending)</option>
				<option value="E_ASC">Employee Number (ascending)</option>
				<option value="E_DESC">Employee Number (descending)</option>
			</select>
			<input type="submit">
		</form>

		<?php
			$neworder = '';
			$order = "ASC";
			$type ="first_name";
			$slots = array();

			if (!empty($_GET["order"])) {
				$order = $_GET["order"];
				$slots = explode("_", $order);

				$neworder = $slots[1];
				switch ($slots[0]) {
					case "E":
						$type = "emp_no";
						break;
					case "F":
						$type = "first_name";
						break;
					case "L":
					default:
						$type = "last_name";
						break;
				}
			}
			$self = $_SERVER['PHP_SELF'];

			if($neworder != '') {
				if(!empty($_GET['entries'])) {
					$rec_limit = $_GET['entries'];
					//sets entries per page

					$link = mysqli_connect($host, $user, $pass);
					$db = mysqli_select_db($link, $dbname);
					// establishes link to sql
					$data = mysqli_query($link,"SELECT
						COUNT(emp_no)
						AS
						'total'
						FROM
						employees");

					$rowss = mysqli_fetch_assoc($data);
					$row_count = $rowss['total'];

					//get current page number and set page/offset accordingly
					if(isset($_GET{'page'} ) ) {
						$page = $_GET{'page'};
						$offset = $rec_limit * $page ;
					}
					else {
						$page = 0;
						$offset = 0;
					}

					//calculate amount of entries left
					$left_rec = $row_count - ($page * $rec_limit);

					// creates  query and result
					$sql = "SELECT *
						FROM
						employees
						ORDER BY $type $neworder
						LIMIT
						$offset, $rec_limit";

					$retval = mysqli_query( $link, $sql );

					echo '<table border="1">';
					echo '<tr>';

					// create table header
					echo"<th>First Name</th>";
					echo"<th>Last Name</th>";
					echo"<th>Gender</th>";
					echo"<th>Employee Number</th>";
					echo"<th>Birth Date</th>";
					echo"<th>Hire Date</th>";
					echo"<th colspan='3'>Options</th>";

					while ($row = mysqli_fetch_assoc($retval)) {
						echo"<tr>";
						$f = $row['first_name'];
						echo"<td>".$f."</td>";

						$l= $row['last_name'];
						echo"<td>".$l."</td>";

						$g= $row['gender'];
						echo"<td>".$g."</td>";

						$e = $row['emp_no'];
						echo"<td>".$e."</td>";

						$b= $row['birth_date'];
						echo"<td>".$b."</td>";

						$h= $row['hire_date'];
						echo"<td>".$h."</td>";

#						echo "<td><a href = \"delete.php?ID=$e\">Delete</a></td>";
#						echo "<td><a href = \"update.php?ID=$e\">Update</a></td>";
						echo "<td><a href = \"view.php?ID=$e\">View</a></td>";

						echo"</tr>";
					}

			}	echo "</table>";

			echo "<br>";

			// display paging data
			if( $page > 0 ) {
				$last = $page - 1;
				$next = $page + 1;
				echo "<a href = \"$self?page=$last&entries=$rec_limit&order=$order\">Last $rec_limit Records</a> |";
				echo "<a href = \"$self?page=$next&entries=$rec_limit&order=$order\">Next $rec_limit Records</a>";
			}
			else if( $page == 0 ) {
				$page = $page + 1;
				echo "<a href = \"$self?page=$page&entries=$rec_limit&order=$order\">Next $rec_limit Records</a>";
			}
		}
	?>

</body>
</html>
