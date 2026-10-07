<?php
    $page_title = 'Danh sách khoa';
    $active_menu = 'khoa';
    require 'includes/header.php';

    $keyword = trim($_GET['keyword'] ?? '');

    if ($keyword !== '') {
        $keyword_safe = $conn->real_escape_string($keyword);
        $sql_select = "SELECT * FROM t_khoa WHERE ma_khoa LIKE '%$keyword_safe%' OR ten_khoa LIKE '%$keyword_safe%' ORDER BY ma_khoa ASC";
    } else {
        $sql_select = "SELECT * FROM t_khoa ORDER BY ma_khoa ASC";
    }
    $result = $conn->query($sql_select);
?>
    <h2>Danh sách khoa đào tạo chuyên môn</h2>

    <div class="toolbar">
        <button class="btn-add" onclick="openAddModal()">+ Thêm khoa mới</button>

        <form method="GET" action="ds_khoa.php" class="search-form">
            <input type="text"
                   name="keyword"
                   class="search-input"
                   placeholder="Nhập mã khoa hoặc tên khoa..."
                   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
            >
            <button type="submit" class="btn-search">Tìm kiếm</button>
            <?php if (!empty($_GET['keyword'])): ?>
                <a href="ds_khoa.php" class="btn-reset">[Xóa bộ lọc]</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>Hành động</th>
            <th>STT</th>
            <th>Mã khoa</th>
            <th>Tên khoa</th>
            <th>Số điện thoại khoa</th>
        </tr>
    <?php
        if ($result && $result->num_rows > 0) {
            $stt = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td> 
                    <button onclick=\"openEditModal('" . htmlspecialchars($row['ma_khoa']) . "')\">Sửa</button>
                    <button onclick=\"deleteKhoa('" . htmlspecialchars($row['ma_khoa']) . "')\">Xóa</button>
                </td>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . htmlspecialchars($row["ma_khoa"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["ten_khoa"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["dien_thoai_khoa"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td colspan='5' style='text-align: center; padding: 16px; color: #666;'>";
            if ($keyword !== '') {
                echo "Không tìm thấy khoa nào khớp với từ khóa: <b>" . htmlspecialchars($keyword) . "</b>";
            } else {
                echo "Chưa có khoa nào trong hệ thống.";
            }
            echo "</td>";
            echo "</tr>";
        }
    ?>
    </table>

    <!-- Modal thêm / sửa khoa -->
    <div id="khoaModal" class="modal" style="display: none;">
        <div class="modal-content">
            <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
            <h3 id="modalTitle">Thêm khoa mới</h3>
            <form id="khoaForm">
                <input type="hidden" id="action_type" name="action_type" value="add">

                <label for="ma_khoa">Mã khoa:</label>
                <input type="text" id="ma_khoa" name="ma_khoa" required>

                <label for="ten_khoa">Tên khoa:</label>
                <input type="text" id="ten_khoa" name="ten_khoa" required>

                <label for="dien_thoai_khoa">Số điện thoại khoa:</label>
                <input type="text" id="dien_thoai_khoa" name="dien_thoai_khoa" required>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Hủy</button>
                    <button type="submit" id="btnSave" class="btn-primary">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mở modal thêm khoa
        function openAddModal() {
            document.getElementById('khoaForm').reset();
            document.getElementById('action_type').value = 'add';
            document.getElementById('ma_khoa').readOnly = false;
            document.getElementById('modalTitle').innerText = 'Thêm khoa mới';
            document.getElementById('khoaModal').style.display = 'flex';
        }

        // Mở modal sửa khoa
        function openEditModal(ma_khoa) {
            fetch(`ajax_lay_khoa.php?ma_khoa=${encodeURIComponent(ma_khoa)}`)
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    const data = res.data;
                    document.getElementById('action_type').value = 'edit';
                    document.getElementById('ma_khoa').value = data.ma_khoa;
                    document.getElementById('ma_khoa').readOnly = true;
                    document.getElementById('ten_khoa').value = data.ten_khoa;
                    document.getElementById('dien_thoai_khoa').value = data.dien_thoai_khoa;

                    document.getElementById('modalTitle').innerText = 'Cập nhật khoa';
                    document.getElementById('khoaModal').style.display = 'flex';
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Lỗi kết nối khi lấy thông tin khoa");
            });
        }

        // Đóng modal
        function closeModal() {
            document.getElementById('khoaModal').style.display = 'none';
        }

        // Xử lý gửi Form (Thêm hoặc Sửa)
        document.getElementById('khoaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionType = document.getElementById('action_type').value;
            const url = actionType === 'add' ? 'ajax_them_khoa.php' : 'ajax_sua_khoa.php';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                try {
                    const res = JSON.parse(text);
                    alert(res.message);
                    if (res.status === 'success') {
                        closeModal();
                        location.reload();
                    }
                } catch(err) {
                    console.error("Lỗi dữ liệu từ máy chủ: ", text);
                    alert("Có lỗi từ máy chủ: " + text);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Lỗi kết nối đến máy chủ");
            });
        });

        // Xóa khoa
        function deleteKhoa(ma_khoa) {
            if (confirm(`Bạn có chắc chắn muốn xóa khoa ${ma_khoa} không?`)) {
                fetch(`ajax_xoa_khoa.php?ma_khoa=${encodeURIComponent(ma_khoa)}`)
                .then(response => response.json())
                .then(res => {
                    alert(res.message);
                    if (res.status === 'success') {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Lỗi kết nối khi xóa khoa");
                });
            }
        }
    </script>

<?php require 'includes/footer.php'; ?>
