<?php
	$page_title = 'Danh sach lop';
	$active_menu = 'lop';
	require 'includes/header.php';
	$sql_select = "SELECT * FROM t_lop";
	$result = $conn->query($sql_select);
?>
		<h1>Danh sách lớp</h1><br>
		<table border="1">
			<tr>
			<th>STT</th>
			<th>Mã khoa</th>
			<th>Mã lớp</th>
			<th>Tên lớp</th>
			<th>Mã GVCN</th>
			</tr>
		<?php 
			if($result->num_rows > 0){
				$stt = 1;
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					echo "<td>" . $stt++ . "</td>";
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
		?>
		</table>

<?php  require 'includes/footer.php'; ?>