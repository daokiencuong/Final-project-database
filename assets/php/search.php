<?php
include 'connect.php';

$search_term = $_POST['search_term'] ?? '';

if (!empty($search_term)) {
    $query = "SELECT * FROM hotels WHERE hotel_name LIKE '%$search_term%'";
    $result = pg_query($connection, $query);

    if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            // Lấy thông tin về địa chỉ của khách sạn từ bảng location
            $location_query = "SELECT * FROM location WHERE location_id = '{$row['location_id']}'";
            $location_result = pg_query($connection, $location_query);
            $location_row = pg_fetch_assoc($location_result);

            // Lấy danh sách các dịch vụ của khách sạn từ bảng service
            $service_query = "SELECT * FROM service WHERE hotel_id = '{$row['hotel_id']}'";
            $service_result = pg_query($connection, $service_query);

            $price = htmlspecialchars($row['cost']);

            // Định dạng giá tiền
            $formatted_price = number_format($price, 0, '', '.');

            // Thêm đơn vị tiền tệ VNĐ vào cuối
            $formatted_price .= ' VNĐ/Night';

            $formatted_avg = htmlspecialchars($row['average_rating']) . "/5";

            $formatted_phone = "0". htmlspecialchars($row['phone_number']);

            $location = htmlspecialchars($location_row['number'] . ' ' . $location_row['street'] . ', ' . $location_row['district'] . ', ' . $location_row['city']);

            echo "<div class=\"content-box\">";
            echo "<div class = \"danh_gia\">";
            echo "<div class=\"hotel-name\">" . htmlspecialchars($row['hotel_name']) . "</div>";
            echo "<div class=\"overall-rating\">(" . $formatted_avg . ")</div>";
            echo "<div class=\"reviews\">" . htmlspecialchars($row['reviews_count']) . " Reviews</div>";
            echo "</div>";
            echo "<a class=\"location\" href=\"https://www.google.com/maps/search/$location;\" target=\"_blank\"><i class=\"fa-solid fa-location-dot\"></i> " . $location . "</a>";
            echo "<div class = \"desc\">";
            echo "<div class=\"cost\">" . $formatted_price . "</div>";
            echo "<a class=\"phone-number\" href=\"tel:$formatted_phone\">Contact: " . $formatted_phone . "</a>";
            echo "</div>";


            // Hiển thị danh sách các dịch vụ của khách sạn
            $service_query = "SELECT * FROM service WHERE hotel_id = '{$row['hotel_id']}'";
            $service_result = pg_query($connection, $service_query);
            include"./service_process.php";

            if ($service_result) { // Kiểm tra xem truy vấn có thành công hay không
                $service_row = pg_fetch_assoc($service_result); // Lấy dòng dữ liệu đầu tiên từ kết quả truy vấn
                if ($service_row) { // Kiểm tra xem dữ liệu có tồn tại hay không
                    // Hiển thị danh sách các dịch vụ của khách sạn
                    echo "<ul class = \"list_service\">";
                    // Lặp qua từng dịch vụ
                    foreach ($service_row as $service => $value) {
                        // Kiểm tra xem dịch vụ có được cung cấp hay không (value = TRUE)
                        if ($value == 't') {
                            // Hiển thị dịch vụ
                            $service_name = $service_names[$service];
                            // Hiển thị dịch vụ
                            echo "<li class = \"service\">" . htmlspecialchars($service_name) . "</li>";
                        }
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No services found for this hotel.</p>";
                }
            } else {
                echo "<p>Error retrieving services.</p>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>No hotels found that match your search criteria.</p>";
    }
} else {
    echo "<p>Please enter a search term.</p>";
}
?>



