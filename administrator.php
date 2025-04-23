<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // or your MySQL password
$dbname = "projectlogin"; // your actual DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products from the product table
$products = [];
$sql = "SELECT * FROM product"; // adjust column names if different
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Victorian Bloom</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-white shadow-md py-6 px-10 flex justify-between items-center sticky top-0 z-10">
        <div class="text-4xl font-bold name">Victorian Bloom</div>
        <div class="space-x-8 text-2xl">
            <a href="home.html" class="hover:text-blue-500" class="name">Navigate to homepage</a>
        </div>
    </div>

    <div class="main flex justify-between items-start w-full px-8 py-6 gap-4 font-bold border-b shadow-md">
        <div class="box border-b">
            <div class="inner w-full h-12 pt-4">
                <p class="text-white text-center">ORDERS</p>
            </div>

            <div class="order-container overflow-y-auto max-h-[500px] bg-white rounded p-4">
                <?php
                $orderSql = "SELECT * FROM orders";
                $orderResult = $conn->query($orderSql);

                if (!$orderResult) {
                    echo "<p class='text-red-500'>Error in order query: " . $conn->error . "</p>";
                } elseif ($orderResult->num_rows > 0) {
                    while($order = $orderResult->fetch_assoc()) {
                        echo "
                            <div class='order-card border-b py-2'>
                                <p><strong>Name:</strong> {$order['name']}</p>
                                <p><strong>Phone:</strong> {$order['contact']}</p>
                                <p><strong>Address:</strong> {$order['address']}</p>
                                <p><strong>Product:</strong>{$order['product_name']}</p>
                            </div>
                        ";
                    }
                } else {
                    echo "<p>No orders yet.</p>";
                }
                ?>
            </div>
        </div>

        <div class="box border-b">
            <div class="inner w-full h-12 pt-4">
                <p class="text-white text-center">STOCK</p>
            </div>

            <!-- Stock Section: Displaying products dynamically -->
            <div class="stock-container overflow-y-auto max-h-[500px]">
                <?php foreach ($products as $product): ?>
                    <div class="product-card flex bg-white rounded-lg m-2 shadow">
                        <div class="product-image w-1/2 h-24">
                            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="product-info w-1/2 text-center flex items-center justify-center">
                            <h3 class="product-title font-semibold m-2"><?= $product['name'] ?></h3>
                            <p class="text-sm text-gray-500">₹<?= $product['price'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="box border-b">
            <div class="inner w-full h-12 pt-4">
                <p class="text-white text-center">QUERIES</p>
            </div>  

            <!-- Testimonial Section inside Queries Div -->
            <section class="max-w-7xl px-4 md:px-6 stock-container overflow-y-auto max-h-[500px] ">

                <!-- Flex Container for Testimonials -->
                <div class="gap-8 pb-12 pt-2">
                    <div class="testimonial-card flex bg-white rounded-lg shadow-lg p-6 mb-2">
                        <div class="quote-icon text-4xl text-[#b98d86]">❝</div>
                        <p class="testimonial-text text-gray-700 mt-4 mb-4">The arrangement I received for my anniversary was absolutely stunning. The roses were fresh and beautifully arranged. Will definitely order again!</p>
                        <div class="testimonial-author font-semibold">- Priya Sharma</div>
                        <div class="testimonial-rating text-yellow-500">★★★★★</div>
                    </div>

                    <div class="testimonial-card flex bg-white rounded-lg shadow-lg p-6 mb-2">
                        <div class="quote-icon text-4xl text-[#b98d86]">❝</div>
                        <p class="testimonial-text text-gray-700 mt-4 mb-4">Victorian Bloom's orchids lasted for weeks! The attention to detail in their arrangements is unmatched. My mother was thrilled with her birthday gift.</p>
                        <div class="testimonial-author font-semibold">- Raj Patel</div>
                        <div class="testimonial-rating text-yellow-500">★★★★★</div>
                    </div>

                    <div class="testimonial-card flex bg-white rounded-lg shadow-lg p-6 mb-2">
                        <div class="quote-icon text-4xl text-[#b98d86]">❝</div>
                        <p class="testimonial-text text-gray-700 mt-4 mb-4">I've been ordering from Victorian Bloom for all special occasions. Their customer service is exceptional and the flowers are always fresh and gorgeous.</p>
                        <div class="testimonial-author font-semibold">- Meera Kapoor</div>
                        <div class="testimonial-rating text-yellow-500">★★★★★</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
