	<?php
		$page_title = 'Danh sach sinh vien';
    	$active_menu = 'sinhvien';
    	require 'includes/header.php';
		$sql_select = 'SELECT * FROM t_sinhvien';
		$result = $conn->query($sql_select);
	?>
		<h2>Danh sách sinh viên</h2>
		<button onclick="openAddModal()">Thêm sinh viên mới</button>
		<table border="1">
			<tr>
			<th colspan="1">Hành động</th>
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
			</tr>
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
		?>
		</table>
	<div id="studentModal" class="modal" style="display: none;">
		<div class="modal-content">
			<button type="button" class="close-btn" onclick="closeModal()">&times;</button>
			<h3 id="modalTitle">Thêm sinh viên mới</h3>
			<form id="studentForm">
				<input type="hidden" id="action_type" name="action_type" value="add">

				<label for="ma_khoa">Mã khoa:</label>
				<select id="ma_khoa" name="ma_khoa" required>
					<option value="CNTT">Công nghệ thông tin</option>
					<option value="KT">Kinh tế</option>
					<option value="NN">Ngoại ngữ</option>
					<option value="MT">Môi trường</option>
				</select><br><br>

				<label for="ma_lop">Mã lớp:</label>
				<select id="ma_lop" name="ma_lop" required>
					<option value="C1">C1</option>
					<option value="C2">C2</option>
					<option value="C3">C3</option>
					<option value="KT1">KT1</option>
					<option value="KT2">KT2</option>
					<option value="NN1">NN1</option>
					<option value="MT1">MT1</option>
				</select><br><br>

				<label for="ma_sv">Mã sinh viên:</label>
				<input type="text" id="ma_sv" name="ma_sv" required><br><br>

				<label for="ho_ten">Họ và tên:</label>
				<input type="text" id="ho_ten" name="ho_ten" required><br><br>

				<label for="gioi_tinh">Giới tính:</label>
				<select id="gioi_tinh" name="gioi_tinh">
					<option value="Nam">Nam</option>
					<option value="Nữ">Nữ</option>
					<option value="Khác">Khác</option>
				</select><br><br>

				<label for="ngay_sinh">Ngày sinh:</label>
				<input type="date" id="ngay_sinh" name="ngay_sinh" required><br><br>

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
	<script>
		// Ham mo modal them moi
		function openAddModal() {
			document.getElementById('studentForm').reset(); // Xoa du lieu cu cua form
			document.getElementById('action_type').value = 'add';
			document.getElementById('ma_sv').readOnly = false; 
			document.getElementById('modalTitle').innerText = 'Thêm sinh viên mới';
			document.getElementById('studentModal').style.display = 'flex';
		}

		// Ham mo modal de sua
		function openEditModal(ma_sv){
			fetch(`ajax_lay_sv.php?ma_sv=${ma_sv}`)
			.then(response => response.json())
			.then(res => {
				if(res.status === 'success'){
					const data = res.data;
					document.getElementById('action_type').value = 'edit';
					document.getElementById('ma_khoa').value = data.ma_khoa;
					document.getElementById('ma_lop').value = data.ma_lop;
					document.getElementById('ma_sv').value = data.ma_sv;
					document.getElementById('ma_sv').readOnly = true;
					document.getElementById('ho_ten').value = data.ho_ten;
					document.getElementById('gioi_tinh').value = data.gioi_tinh;
					document.getElementById('ngay_sinh').value = data.ngay_sinh;
					document.getElementById('dia_chi').value = data.dia_chi;
					document.getElementById('email').value = data.email;
					document.getElementById('sdt').value = data.sdt;

					document.getElementById('modalTitle').innerText = 'Cập nhật sinh viên';
					document.getElementById('studentModal').style.display = 'flex';
				}
				else{
					alert(res.message);
				}
			});
		}

		// Ham dong modal
		function closeModal(){
			document.getElementById('studentModal').style.display = 'none';
		}

		// Xu ly khi nhan nut Luu tren Form (Them hoac sua)
		document.getElementById('studentForm').addEventListener('submit', function(e){
			e.preventDefault(); // Chan khong cho browser reload

			const formData = new FormData(this);
			const actionType = document.getElementById('action_type').value;
			const url = actionType === 'add' ? 'ajax_them_sv.php' : 'ajax_sua_sv.php';

			fetch(url, {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(text => {
				try{
					const res = JSON.parse(text);
					alert(res.message);
					if(res.status === 'success'){
						closeModal();
						location.reload(); // Tai lai trang de cap nhat bang
					}
				}catch(err){
					console.error("Lỗi dữ liệu trả về từ server: ", text);
					alert("Có lỗi từ máy chủ: " + text);
				}
			})
			.catch(err => {
				console.error(err);
				alert("Lỗi kết nối đến máy chủ");
			});
		});

		// Ham xoa sinh vien
		function deleteStudent(ma_sv){
			if(confirm(`Bạn có chắc chắn muốn xóa sinh viên ${ma_sv} không?`)){
				fetch(`ajax_xoa_sv.php?ma_sv=${ma_sv}`)
				.then(response => response.json())
				.then(res => {
					alert(res.message);
					if(res.status === 'success'){
						location.reload();
					}
				});
			}
		}
	</script>

<?php  require 'includes/footer.php'; ?>