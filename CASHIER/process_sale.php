<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cart = json_decode($_POST['cart'], true);
    $payment_method = $_POST['payment_method'];
    $total_amount = 0;

    foreach ($cart as $item) {
        $total_amount += $item['total'];
    }

    $stmt = $conn->prepare("INSERT INTO sales (total_amount, payment_method, sale_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ds", $total_amount, $payment_method);
    $stmt->execute();
    $sale_id = $stmt->insert_id;
    $stmt->close();

    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $sale_id, $item['id'], $item['quantity'], $item['total']);
        $stmt->execute();
        $stmt->close();
    }

    echo "Sale Processed Successfully!";
}
?>
