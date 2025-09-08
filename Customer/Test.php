<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Card Demo</title>
    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .category-container {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
        }
        .category-name {
            margin: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            max-width: 95%;
            padding-bottom: 30px;
        }


        .menu-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 2rem auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .menu-card h3 {
            margin: 1rem 0 0.5rem 0;
        }

        .menu-card p {
            color: #555;
            margin-bottom: 0.5rem;
        }

        .menu-card strong {
            color: #0d111d;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .menu-card .add-cart-btn {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: #0d111d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="category-container">
        <div class="category-name">
        <center>
            <h2 style="width:100%;text-align:left;">Category Name</h2>
        </center>
        </div>
        <div class="menu-container">
            <div class="menu-card">
                <img src="../Images/Gallery-food-1.webp" alt="Paneer Butter Masala">
                <h3>Paneer Butter Masala</h3>
                <p>Cottage cheese cubes cooked in rich tomato gravy with butter and spices.</p>
                <strong>₹220</strong>
                <button class="add-cart-btn">Add to Cart</button>
            </div>
            <div class="menu-card">
                <img src="../Images/Gallery-food-2.webp" alt="Chicken Biryani">
                <h3>Chicken Biryani</h3>
                <p>Aromatic basmati rice layered with spiced chicken and cooked to perfection.</p>
                <strong>₹250</strong>
                <button class="add-cart-btn">Add to Cart</button>
            </div>
            <div class="menu-card">
                <img src="../Images/Gallery-food-3.webp" alt="Veg Pulao">
                <h3>Veg Pulao</h3>
                <p>Fragrant basmati rice cooked with mixed vegetables and mild spices.</p>
                <strong>₹180</strong>
                <button class="add-cart-btn">Add to Cart</button>
            </div>
            <div class="menu-card">
                <img src="../Images/Gallery-food-4.webp" alt="Butter Naan">
                <h3>Butter Naan</h3>
                <p>Soft and fluffy Indian bread brushed with melted butter.</p>
                <strong>₹40</strong>
                <button class="add-cart-btn">Add to Cart</button>
            </div>

        </div>
    </div>
</body>

</html>