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
		<button onclick="openAddModal()">Thêm sinh viên mới</button>
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
			<th>Hành động</th>
		<?php 
			if($result->num_rows > 0){
				$stt = 1;
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					echo "<td> 
						<button onclick=\"openEditModal('" . $row['ma_sv'] . "')\">Sửa</button>
						<button onclick=\"deleteStudent('" . $row['ma_sv'] . "')\">Xóa</button>
					</td>";
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
	<div id="studentModal" class="modal" style="display: none;">
		<div class="modal-content">
			<button type="button" class="close-btn" onclick="closeModal()">&times;</button>
			<h3 id="modalTitle">Thêm sinh viên mới</h3>
			<form id="studentForm">
				<input type="hidden" id="action_type" name="action_type" value="add">

				<label for="ma_khoa">Mã khoa:</label>
				<input type="text" id="ma_khoa" name="ma_khoa" required><br><br>

				<label for="ma_lop">Mã lớp:</label>
				<input type="text" id="ma_lop" name="ma_lop" required><br><br>

				<label for="ma_sv">Mã sinh viên:</label>
				<input type="text" id="ma_sv" name="ma_sv" required><br><br>

				<label for="ho_ten">Họ và tên:</label>
				<input type="text" id="ho_ten" name="ho_ten" required><br><br>

				<label for="gioi_tinh">Giới tính:</label>
				<select id="gioi_tinh" name="gioi_tinh">
					<option value="Nam"></option>
					<option value="Nữ"></option>
					<option value="Khác"></option>
				</select><br><br>

				<label for="ngay_sinh">Ngày sinh:</label>
				<input type="text" id="ngay_sinh" name="ngay_sinh" required><br><br>

				<label for="dia_chi">Địa chỉ:</label>
				<input type="text" id="dia_chi" name="dia_chi" required><br><br>

				<label for="email">Email:</label>
				<input type="text" id="email" name="email" required><br><br>

				<label for="sdt">Số điện thoại:</label>
				<input type="text" id="sdt" name="sdt" required><br><br>

				<button type="submit" id="btnSave">Lưu thông tin</button>
				<button type="button" onclick="closeModal()">Hủy</button>
			</form>
		</div>
	</div>
</body>
</html>