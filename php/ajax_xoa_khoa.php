<?php
	require 'connection.php';
	header('Content-Type: application/json');

	$ma_khoa = $_GET['ma_khoa'] ?? '';

	if (empty($ma_khoa)) {
		echo json_encode(['status' => 'error', 'message' => 'Mã khoa không hợp lệ!']);
		exit;
	}

	$ma_khoa_safe = $conn->real_escape_string($ma_khoa);

	try {
		$sql = "DELETE FROM t_khoa WHERE ma_khoa = '$ma_khoa_safe'";
		$conn->query($sql);

		if ($conn->affected_rows > 0) {
			echo json_encode(['status' => 'success', 'message' => "Xóa khoa $ma_khoa thành công!"]);
		} else {
			echo json_encode(['status' => 'error', 'message' => "Không tìm thấy khoa $ma_khoa để xóa!"]);
		}
	} catch (mysqli_sql_exception $e) {
		// Mã lỗi 1451: Vi phạm khóa ngoại
		if ($e->getCode() === 1451) {
			echo json_encode([
				'status' => 'error',
				'message' => "Không thể xóa khoa $ma_khoa vì vẫn còn lớp học hoặc sinh viên thuộc khoa này! Vui lòng xóa các lớp và sinh viên liên quan trước."
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
		}
	}

	$conn->close();
?>