<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_lop = $_GET['ma_lop'] ?? '';

	if (empty($ma_lop)) {
		echo json_encode(['status' => 'error', 'message' => 'Mã lớp không hợp lệ!']);
		exit;
	}

	$ma_lop_safe = $conn->real_escape_string($ma_lop);

	try {
		$sql = "DELETE FROM t_lop WHERE ma_lop = '$ma_lop_safe'";
		$conn->query($sql);

		if ($conn->affected_rows > 0) {
			echo json_encode(['status' => 'success', 'message' => "Xóa lớp $ma_lop thành công!"]);
		} else {
			echo json_encode(['status' => 'error', 'message' => "Không tìm thấy lớp $ma_lop để xóa!"]);
		}
	} catch (mysqli_sql_exception $e) {
		// Mã lỗi 1451: Vi phạm khóa ngoại
		if ($e->getCode() === 1451) {
			echo json_encode([
				'status' => 'error',
				'message' => "Không thể xóa lớp $ma_lop vì vẫn còn sinh viên đang học ở lớp này! Vui lòng xóa hoặc chuyển sinh viên sang lớp khác trước."
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
		}
	}

	$conn->close();
?>