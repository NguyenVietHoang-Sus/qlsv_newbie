<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Danh sách sinh viên</title>
</head>
<body>
	<?php
		require 'connection.php';
		$sql_select = 'SELECT * FROM t_sinhvien';
		$result = $conn->query($sql_select);
	?>
	<center>
		<h2>Danh sách sinh viên</h2>
		<table border="1">
			<th>STT</th>
			<th>Mã khoa</th>
			<th>Mã lớp</th>
			<th>Mã sinh viên</th>
			<th>Họ và tên</th>
			<th>Giới tính</th>
			<th>Ngày sinh</th>
			<th>Địa chỉ</th>
			<th>Email</th>
			<th>Số điện thoại</th>
		<?php 
			if($result->num_rows > 0){
				$stt = 1;
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					echo "<td>" . $stt++ . "</td>";
					echo "<td>" . $row["ma_khoa"] . "</td>";
					echo "<td>" . $row["ma_lop"] . "</td>";
					echo "<td>" . $row["ma_sv"] . "</td>";
					echo "<td>" . $row["ho_ten"] . "</td>";
					echo "<td>" . $row["gioi_tinh"] . "</td>";
					echo "<td>" . $row["ngay_sinh"] . "</td>";
					echo "<td>" . $row["dia_chi"] . "</td>";
					echo "<td>" . $row["email"] . "</td>";
					echo "<td>" . $row["sdt"] . "</td>";
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