<?php
    $page_title = 'Danh sách lớp';
    $active_menu = 'lop';
    require 'includes/header.php';

    $keyword = trim($_GET['keyword'] ?? '');

    if ($keyword !== '') {
        $keyword_safe = $conn->real_escape_string($keyword);
        $sql_select = "SELECT * FROM t_lop 
                       WHERE ma_lop LIKE '%$keyword_safe%' OR ten_lop LIKE '%$keyword_safe%' OR ma_khoa LIKE '%$keyword_safe%' 
                       ORDER BY ma_lop ASC";
    } else {
        $sql_select = "SELECT * FROM t_lop ORDER BY ma_lop ASC";
    }
    $result = $conn->query($sql_select);

    // Lấy danh sách khoa để đưa vào dropdown của Modal
    $list_khoa = $conn->query("SELECT ma_khoa, ten_khoa FROM t_khoa ORDER BY ma_khoa ASC");
?>
    <h2>Danh sách lớp học</h2>

    <div class="toolbar">
        <button class="btn-add" onclick="openAddModal()">+ Thêm lớp mới</button>

        <form method="GET" action="ds_lop.php" class="search-form">
            <input type="text"
                   name="keyword"
                   class="search-input"
                   placeholder="Nhập mã lớp, tên lớp hoặc mã khoa..."
                   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>"
            >
            <button type="submit" class="btn-search">Tìm kiếm</button>
            <?php if (!empty($_GET['keyword'])): ?>
                <a href="ds_lop.php" class="btn-reset">[Xóa bộ lọc]</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <tr>
            <th>Hành động</th>
            <th>STT</th>
            <th>Mã khoa</th>
            <th>Mã lớp</th>
            <th>Tên lớp</th>
            <th>Mã GVCN</th>
        </tr>
    <?php
        if ($result && $result->num_rows > 0) {
            $stt = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td> 
                    <button onclick=\"openEditModal('" . htmlspecialchars($row['ma_lop']) . "')\">Sửa</button>
                    <button onclick=\"deleteLop('" . htmlspecialchars($row['ma_lop']) . "')\">Xóa</button>
                </td>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . htmlspecialchars($row["ma_khoa"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["ma_lop"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["ten_lop"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["ma_gvcn"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td colspan='6' style='text-align: center; padding: 16px; color: #666;'>";
            if ($keyword !== '') {
                echo "Không tìm thấy lớp nào khớp với từ khóa: <b>" . htmlspecialchars($keyword) . "</b>";
            } else {
                echo "Chưa có lớp nào trong hệ thống.";
            }
            echo "</td>";
            echo "</tr>";
        }
    ?>
    </table>

    <!-- Modal thêm / sửa lớp -->
    <div id="lopModal" class="modal" style="display: none;">
        <div class="modal-content">
            <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
            <h3 id="modalTitle">Thêm lớp mới</h3>
            <form id="lopForm">
                <input type="hidden" id="action_type" name="action_type" value="add">

                <label for="ma_khoa">Thuộc khoa:</label>
                <select id="ma_khoa" name="ma_khoa" required>
                    <option value="">-- Chọn khoa --</option>
                    <?php 
                        if ($list_khoa && $list_khoa->num_rows > 0) {
                            while ($khoa = $list_khoa->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($khoa['ma_khoa']) . "'>";
                                echo htmlspecialchars($khoa['ma_khoa']) . " - " . htmlspecialchars($khoa['ten_khoa']);
                                echo "</option>";
                            }
                        }
                    ?>
                </select>

                <label for="ma_lop">Mã lớp:</label>
                <input type="text" id="ma_lop" name="ma_lop" required>

                <label for="ten_lop">Tên lớp:</label>
                <input type="text" id="ten_lop" name="ten_lop" required>

                <label for="ma_gvcn">Mã GVCN:</label>
                <input type="text" id="ma_gvcn" name="ma_gvcn" required>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Hủy</button>
                    <button type="submit" id="btnSave" class="btn-primary">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mở modal thêm lớp
        function openAddModal() {
            document.getElementById('lopForm').reset();
            document.getElementById('action_type').value = 'add';
            document.getElementById('ma_lop').readOnly = false;
            document.getElementById('modalTitle').innerText = 'Thêm lớp mới';
            document.getElementById('lopModal').style.display = 'flex';
        }

        // Mở modal sửa lớp
        function openEditModal(ma_lop) {
            fetch(`ajax_lay_lop.php?ma_lop=${encodeURIComponent(ma_lop)}`)
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    const data = res.data;
                    document.getElementById('action_type').value = 'edit';
                    document.getElementById('ma_khoa').value = data.ma_khoa;
                    document.getElementById('ma_lop').value = data.ma_lop;
                    document.getElementById('ma_lop').readOnly = true;
                    document.getElementById('ten_lop').value = data.ten_lop;
                    document.getElementById('ma_gvcn').value = data.ma_gvcn;

                    document.getElementById('modalTitle').innerText = 'Cập nhật lớp';
                    document.getElementById('lopModal').style.display = 'flex';
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Lỗi kết nối khi lấy thông tin lớp");
            });
        }

        // Đóng modal
        function closeModal() {
            document.getElementById('lopModal').style.display = 'none';
        }

        // Xử lý gửi Form (Thêm hoặc Sửa)
        document.getElementById('lopForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionType = document.getElementById('action_type').value;
            const url = actionType === 'add' ? 'ajax_them_lop.php' : 'ajax_sua_lop.php';

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

        // Xóa lớp
        function deleteLop(ma_lop) {
            if (confirm(`Bạn có chắc chắn muốn xóa lớp ${ma_lop} không?`)) {
                fetch(`ajax_xoa_lop.php?ma_lop=${encodeURIComponent(ma_lop)}`)
                .then(response => response.json())
                .then(res => {
                    alert(res.message);
                    if (res.status === 'success') {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Lỗi kết nối khi xóa lớp");
                });
            }
        }
    </script>

<?php require 'includes/footer.php'; ?>