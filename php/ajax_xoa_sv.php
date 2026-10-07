<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_sv = $_GET['ma_sv'] ?? '';

	if (empty($ma_sv)) {
		echo json_encode(['status' => 'error', 'message' => 'Mã sinh viên không hợp lệ!']);
		exit;
	}

	$ma_sv_safe = $conn->real_escape_string($ma_sv);

	try {
		$sql = "DELETE FROM t_sinhvien WHERE ma_sv = '$ma_sv_safe'";
		$conn->query($sql);

		if ($conn->affected_rows > 0) {
			echo json_encode(['status' => 'success', 'message' => "Xóa sinh viên $ma_sv thành công!"]);
		} else {
			echo json_encode(['status' => 'error', 'message' => "Không tìm thấy sinh viên $ma_sv để xóa!"]);
		}
	} catch (mysqli_sql_exception $e) {
		if ($e->getCode() === 1451) {
			echo json_encode([
				'status' => 'error',
				'message' => "Không thể xóa sinh viên $ma_sv vì có dữ liệu ràng buộc liên quan!"
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
		}
	}

	$conn->close();
?>