<?php
session_start();

// Database connection
include '../Config/db_connection.php';
// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'load') {
        // Fetch menu items grouped by category
        $sql = "SELECT * FROM menu ORDER BY category, name";
        $result = $conn->query($sql);
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[$row['category']][] = $row;
        }
        // Output HTML for each category and item
        foreach ($categories as $cat => $items) {
            echo '<div id="menu-item-category"><h2 style="width:100%;text-align:center;">' . htmlspecialchars($cat) . '</h2></div>';
            foreach ($items as $item) {
                echo '<div class="menu-card">';
                if (!empty($item['image'])) {
                    echo '<img src="../Images/menu_img/' . htmlspecialchars($item['image']).".webp" . '" alt="' . htmlspecialchars($item['name']) . '">';
                }
                echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($item['description']) . '</p>';
                echo '<strong>BDT ' . htmlspecialchars($item['price']) . '</strong>';
                echo '<button class="add-cart-btn" data-id="' . $item['menu_item_id'] . '">Add to Cart</button>';
                echo '</div>';
            }
        }
        exit;
    }
    elseif ($action === 'add' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        // Add item to cart in session
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!in_array($id, $_SESSION['cart'])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
        echo 'added';
        exit;
    }
}
$conn->close();
?>
