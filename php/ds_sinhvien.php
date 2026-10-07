<?php
	$page_title = 'Danh sách sinh viên';
	$active_menu = 'sinhvien';
	require 'includes/header.php';

	$keyword = trim($_GET['keyword'] ?? '');

	if($keyword !== ''){
		$keyword_safe = $conn->real_escape_string($keyword);
		$sql_select = "SELECT * FROM t_sinhvien WHERE ma_sv LIKE '%$keyword_safe%' OR ho_ten LIKE '%$keyword_safe%' ORDER BY ma_sv ASC";
	}
	else{
		$sql_select = "SELECT * FROM t_sinhvien ORDER BY ma_sv ASC";
	}
	$result = $conn->query($sql_select);

	// Lấy danh sách Khoa và Lớp động từ cơ sở dữ liệu để đồng bộ hoàn toàn
	$list_khoa = $conn->query("SELECT ma_khoa, ten_khoa FROM t_khoa ORDER BY ma_khoa ASC");
	$list_lop = $conn->query("SELECT ma_lop, ten_lop, ma_khoa FROM t_lop ORDER BY ma_lop ASC");

	$classes_array = [];
	if ($list_lop && $list_lop->num_rows > 0) {
		while ($l = $list_lop->fetch_assoc()) {
			$classes_array[] = [
				'ma_lop' => $l['ma_lop'],
				'ten_lop' => $l['ten_lop'],
				'ma_khoa' => $l['ma_khoa']
			];
		}
	}
?>
	<h2>Danh sách sinh viên</h2>
	<div class="toolbar">
		<button class="btn-add" onclick="openAddModal()">+ Thêm sinh viên mới</button>

		<form method="GET" action="ds_sinhvien.php" class="search-form">
			<input type="text"
				   name="keyword"
				   class="search-input"
				   placeholder="Nhập mã sinh viên hoặc họ tên..."
				   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" 
			>
			<button type="submit" class="btn-search">Tìm kiếm</button>
			<?php if(!empty($_GET['keyword'])): ?>
				<a href="ds_sinhvien.php" class="btn-reset">[Xoá bộ lọc]</a>
			<?php endif; ?>
		</form>
	</div>

	<table>
		<tr>
			<th>Hành động</th>
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
		if($result && $result->num_rows > 0){
			$stt = 1;
			while($row = $result->fetch_assoc()){
				echo "<tr>";
				echo "<td> 
					<button onclick=\"openEditModal('" . htmlspecialchars($row['ma_sv']) . "')\">Sửa</button>
					<button onclick=\"deleteStudent('" . htmlspecialchars($row['ma_sv']) . "')\">Xóa</button>
				</td>";
				echo "<td>" . $stt++ . "</td>";
				echo "<td>" . htmlspecialchars($row["ma_khoa"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["ma_lop"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["ma_sv"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["ho_ten"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["gioi_tinh"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["ngay_sinh"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["dia_chi"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["sdt"]) . "</td>";
				echo "</tr>";
			}
		}
		else{
			echo "<tr>";
			echo "<td colspan='11' style='text-align: center; padding: 16px; color: #666;'>";
			if($keyword !== ''){
				echo "Không tìm thấy sinh viên nào khớp với từ khóa: <b>" . htmlspecialchars($keyword) . "</b>";
			}
			else{
				echo "Chưa có sinh viên nào trong hệ thống.";
			}
			echo "</td>";
			echo "</tr>";
		}
	?>
	</table>

	<!-- Modal thêm / sửa sinh viên -->
	<div id="studentModal" class="modal" style="display: none;">
		<div class="modal-content">
			<button type="button" class="close-btn" onclick="closeModal()">&times;</button>
			<h3 id="modalTitle">Thêm sinh viên mới</h3>
			<form id="studentForm">
				<input type="hidden" id="action_type" name="action_type" value="add">

				<label for="ma_khoa">Mã khoa:</label>
				<select id="ma_khoa" name="ma_khoa" onchange="updateLopDropdown()" required>
					<option value="">-- Chọn khoa --</option>
					<?php 
						if ($list_khoa && $list_khoa->num_rows > 0) {
							while ($k = $list_khoa->fetch_assoc()) {
								echo "<option value='" . htmlspecialchars($k['ma_khoa']) . "'>";
								echo htmlspecialchars($k['ma_khoa']) . " - " . htmlspecialchars($k['ten_khoa']);
								echo "</option>";
							}
						}
					?>
				</select>

				<label for="ma_lop">Mã lớp:</label>
				<select id="ma_lop" name="ma_lop" required>
					<option value="">-- Chọn khoa trước để hiện danh sách lớp --</option>
				</select>

				<label for="ma_sv">Mã sinh viên:</label>
				<input type="text" id="ma_sv" name="ma_sv" required>

				<label for="ho_ten">Họ và tên:</label>
				<input type="text" id="ho_ten" name="ho_ten" required>

				<label for="gioi_tinh">Giới tính:</label>
				<select id="gioi_tinh" name="gioi_tinh">
					<option value="Nam">Nam</option>
					<option value="Nữ">Nữ</option>
					<option value="Khác">Khác</option>
				</select>

				<label for="ngay_sinh">Ngày sinh:</label>
				<input type="date" id="ngay_sinh" name="ngay_sinh" required>

				<label for="dia_chi">Địa chỉ:</label>
				<input type="text" id="dia_chi" name="dia_chi" required>

				<label for="email">Email:</label>
				<input type="text" id="email" name="email" required>

				<label for="sdt">Số điện thoại:</label>
				<input type="text" id="sdt" name="sdt" required>

				<div class="modal-actions">
					<button type="button" class="btn-secondary" onclick="closeModal()">Hủy</button>
					<button type="submit" id="btnSave" class="btn-primary">Lưu thông tin</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		// Toàn bộ danh sách lớp lấy động từ database
		const allClasses = <?= json_encode($classes_array) ?>;

		// Hàm tự động lọc danh sách lớp theo Khoa đã chọn
		function updateLopDropdown(selectedLop = '') {
			const selectedKhoa = document.getElementById('ma_khoa').value;
			const lopSelect = document.getElementById('ma_lop');
			lopSelect.innerHTML = '<option value="">-- Chọn lớp --</option>';

			const filtered = selectedKhoa 
				? allClasses.filter(item => item.ma_khoa === selectedKhoa)
				: allClasses;

			if (filtered.length === 0 && selectedKhoa !== '') {
				lopSelect.innerHTML = '<option value="">(Khoa này chưa có lớp học nào)</option>';
				return;
			}

			filtered.forEach(item => {
				const opt = document.createElement('option');
				opt.value = item.ma_lop;
				opt.textContent = `${item.ma_lop} - ${item.ten_lop}`;
				if (item.ma_lop === selectedLop) {
					opt.selected = true;
				}
				lopSelect.appendChild(opt);
			});
		}

		// Hàm mở modal thêm mới
		function openAddModal() {
			document.getElementById('studentForm').reset();
			document.getElementById('action_type').value = 'add';
			document.getElementById('ma_sv').readOnly = false; 
			document.getElementById('modalTitle').innerText = 'Thêm sinh viên mới';
			updateLopDropdown();
			document.getElementById('studentModal').style.display = 'flex';
		}

		// Hàm mở modal để sửa
		function openEditModal(ma_sv){
			fetch(`ajax_lay_sv.php?ma_sv=${encodeURIComponent(ma_sv)}`)
			.then(response => response.json())
			.then(res => {
				if(res.status === 'success'){
					const data = res.data;
					document.getElementById('action_type').value = 'edit';
					document.getElementById('ma_khoa').value = data.ma_khoa;
					
					// Đồng bộ danh sách lớp của khoa đó và chọn đúng lớp của sinh viên
					updateLopDropdown(data.ma_lop);

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
			})
			.catch(err => {
				console.error(err);
				alert("Lỗi kết nối khi lấy thông tin sinh viên");
			});
		}

		// Hàm đóng modal
		function closeModal(){
			document.getElementById('studentModal').style.display = 'none';
		}

		// Xử lý khi nhấn nút Lưu trên Form (Thêm hoặc sửa)
		document.getElementById('studentForm').addEventListener('submit', function(e){
			e.preventDefault();

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
						location.reload();
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

		// Hàm xóa sinh viên
		function deleteStudent(ma_sv){
			if(confirm(`Bạn có chắc chắn muốn xóa sinh viên ${ma_sv} không?`)){
				fetch(`ajax_xoa_sv.php?ma_sv=${encodeURIComponent(ma_sv)}`)
				.then(response => response.json())
				.then(res => {
					alert(res.message);
					if(res.status === 'success'){
						location.reload();
					}
				})
				.catch(err => {
					console.error(err);
					alert("Lỗi kết nối khi xóa sinh viên");
				});
			}
		}
	</script>

<?php require 'includes/footer.php'; ?>