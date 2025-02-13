<?php
session_start();
require_once dirname(__DIR__) . '/db.php';  // ✅ Correct path

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container { 
            width: 60%; 
            margin: auto; 
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 { color: #333; }
        select, input {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 250px; /* Adjust dropdown width */
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            width: 200px;
        }
        button:hover {
            background: #218838;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .cart { margin-top: 20px; }
        .dashboard-btn {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .dashboard-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <a href="../dashboard.php" class="dashboard-btn">⬅ Back to Dashboard</a>

    <div class="container">
        <h2>POS Terminal - Sales Transaction</h2>

        <label for="product">Select Product:</label>
        <select id="product">
            <option value="">-- Select Product --</option>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <option value="<?= $row['id'] ?>" data-name="<?= $row['name'] ?>" data-price="<?= $row['price'] ?>">
                    <?= $row['name'] ?> - ₱<?= $row['price'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" min="1" value="1">

        <button onclick="addToCart()">Add to Cart</button>

        <div class="cart">
            <h3>Cart</h3>
            <table id="cartTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <h3>Total Amount: ₱<span id="totalAmount">0.00</span></h3>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method">
                <option value="cash">Cash</option>
                <option value="gcash">Gcash</option>
            </select>

            <button onclick="processSale()">Process Sale</button>
        </div>
    </div>

    <script>
        let cart = [];

        function addToCart() {
            let product = $("#product option:selected");
            let productId = product.val();
            let productName = product.data("name");
            let price = parseFloat(product.data("price"));
            let quantity = parseInt($("#quantity").val());

            if (!productId || quantity < 1) {
                alert("Please select a valid product and quantity.");
                return;
            }

            let existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += quantity;
                existingItem.total = existingItem.quantity * price;
            } else {
                cart.push({ id: productId, name: productName, price, quantity, total: price * quantity });
            }

            updateCart();
        }

        function updateCart() {
            let cartTable = $("#cartTable tbody");
            cartTable.empty();
            let totalAmount = 0;

            cart.forEach((item, index) => {
                totalAmount += item.total;
                cartTable.append(`
                    <tr>
                        <td>${item.name}</td>
                        <td>₱${item.price.toFixed(2)}</td>
                        <td>${item.quantity}</td>
                        <td>₱${item.total.toFixed(2)}</td>
                        <td><button onclick="removeItem(${index})" style="background:red; color:white; border:none; padding:5px 10px;">Remove</button></td>
                    </tr>
                `);
            });

            $("#totalAmount").text(totalAmount.toFixed(2));
        }

        function removeItem(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function processSale() {
            if (cart.length === 0) {
                alert("No items in cart!");
                return;
            }

            let payment_method = $("#payment_method").val();

            $.post("process_sale.php", { cart: JSON.stringify(cart), payment_method }, function(response) {
                alert(response);
                cart = [];
                updateCart();
            });
        }
    </script>

</body>
</html>
