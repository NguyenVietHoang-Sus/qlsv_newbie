<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Danh sách lớp</title>
</head>
<body>
	<?php
		require 'connection.php';
		$sql_select = "SELECT * FROM t_lop";
		$result = $conn->query($sql_select);
	?>
	<center>
		<h2>Danh sách lớp</h2><br>
		<table border="1">
			<th>STT</th>
			<th>Mã khoa</th>
			<th>Mã lớp</th>
			<th>Tên lớp</th>
			<th>Mã GVCN</th>
		<?php 
			if($result->num_rows > 0){
				$stt = 1;
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					echo "<td>" . $stt . "</td>";
					echo "<td>" . $row["ma_khoa"] . "</td>";
					echo "<td>" . $row["ma_lop"] . "</td>";
					echo "<td>" . $row["ten_lop"] . "</td>";
					echo "<td>" . $row["ma_gvcn"] . "</td>";
					echo "</tr>";
				}
			}
			else{
				echo "0 results";
			}
			$conn->close(); 
		?>
		</table>
	</center>
</body>
</html>