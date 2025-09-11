<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333333;
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #0d111d;
            color: white;
            padding: 20px 0;
        }
        
        .brand {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .brand h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu li.active {
            background: #3498db;
            border-left: 4px solid white;
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Icons */
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            text-align: center;
        }
        
        .icon-dashboard::before { content: "📊"; }
        .icon-menu::before { content: "🍽️"; }
        .icon-orders::before { content: "📋"; }
        .icon-customers::before { content: "👥"; }
        .icon-reports::before { content: "📈"; }
        .icon-settings::before { content: "⚙️"; }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .header h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eeeeee;
        }
        
        .card-header h3 {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }
        
        .card-header .icon {
            color: #3498db;
            font-size: 18px;
        }
        
        .stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0d111d;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .stat-change {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #28a745;
        }
        
        /* Orders Table */
        .orders-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-header h3 {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .orders-table th {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            font-weight: 600;
        }
        
        .orders-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .orders-table tr:last-child td {
            border-bottom: none;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-completed {
            background: #e8f5e9;
            color: #28a745;
        }
        
        .status-pending {
            background: #fff8e1;
            color: #ffc107;
        }
        
        .status-canceled {
            background: #ffebee;
            color: #dc3545;
        }
        
        .btn {
            background: #0d111d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #2c3e50;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 10px;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                gap: 10px;
            }
            
            .sidebar-menu li {
                padding: 10px 15px;
                white-space: nowrap;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="brand">
                <h1>GourmetAdmin</h1>
            </div>
            <ul class="sidebar-menu">
                <li class="active"><a href="#"><span class="icon icon-dashboard"></span> Dashboard</a></li>
                <li><a href="#"><span class="icon icon-menu"></span> Menu</a></li>
                <li><a href="#"><span class="icon icon-orders"></span> Orders</a></li>
                <li><a href="#"><span class="icon icon-customers"></span> Customers</a></li>
                <li><a href="#"><span class="icon icon-reports"></span> Reports</a></li>
                <li><a href="#"><span class="icon icon-settings"></span> Settings</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Dashboard Overview</h2>
                <div class="user-info">
                    <div class="user-avatar">AJ</div>
                    <span>Admin User</span>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Today's Orders</h3>
                        <span class="icon">📦</span>
                    </div>
                    <div class="stat">
                        <div class="stat-value">42</div>
                        <div class="stat-change">
                            <span>↑</span>
                            <span>12% from yesterday</span>
                        </div>
                    </div>
                    <div class="stat-label">Total orders placed today</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Revenue</h3>
                        <span class="icon">💰</span>
                    </div>
                    <div class="stat">
                        <div class="stat-value">₹12,845</div>
                        <div class="stat-change">
                            <span>↑</span>
                            <span>8% from yesterday</span>
                        </div>
                    </div>
                    <div class="stat-label">Total revenue today</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Active Tables</h3>
                        <span class="icon">🪑</span>
                    </div>
                    <div class="stat">
                        <div class="stat-value">16/24</div>
                        <div class="stat-change">
                            <span>↓</span>
                            <span>2% from yesterday</span>
                        </div>
                    </div>
                    <div class="stat-label">Currently occupied tables</div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Customer Satisfaction</h3>
                        <span class="icon">😊</span>
                    </div>
                    <div class="stat">
                        <div class="stat-value">94%</div>
                        <div class="stat-change">
                            <span>↑</span>
                            <span>3% from last week</span>
                        </div>
                    </div>
                    <div class="stat-label">Based on 38 reviews</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="orders-table-container">
                <div class="table-header">
                    <h3>Recent Orders</h3>
                    <button class="btn"><span>+</span> New Order</button>
                </div>
                
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD-2189</td>
                            <td>Rahul Sharma</td>
                            <td>2</td>
                            <td>₹620</td>
                            <td><span class="status status-completed">Completed</span></td>
                            <td>12:30 PM</td>
                        </tr>
                        <tr>
                            <td>#ORD-2190</td>
                            <td>Priya Patel</td>
                            <td>3</td>
                            <td>₹1,150</td>
                            <td><span class="status status-pending">Preparing</span></td>
                            <td>12:45 PM</td>
                        </tr>
                        <tr>
                            <td>#ORD-2191</td>
                            <td>Vikram Singh</td>
                            <td>1</td>
                            <td>₹350</td>
                            <td><span class="status status-pending">Pending</span></td>
                            <td>1:05 PM</td>
                        </tr>
                        <tr>
                            <td>#ORD-2192</td>
                            <td>Anjali Mehta</td>
                            <td>4</td>
                            <td>₹1,840</td>
                            <td><span class="status status-completed">Completed</span></td>
                            <td>1:20 PM</td>
                        </tr>
                        <tr>
                            <td>#ORD-2193</td>
                            <td>Sanjay Kumar</td>
                            <td>2</td>
                            <td>₹780</td>
                            <td><span class="status status-canceled">Canceled</span></td>
                            <td>1:35 PM</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Quick Actions -->
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>Menu Management</h3>
                        <span class="icon">🍽️</span>
                    </div>
                    <p>Update your menu items, prices, and categories</p>
                    <button class="btn" style="margin-top: 15px;"><span>✏️</span> Manage Menu</button>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h3>Staff Management</h3>
                        <span class="icon">👥</span>
                    </div>
                    <p>View and manage your staff schedules and roles</p>
                    <button class="btn" style="margin-top: 15px;"><span>👤</span> Manage Staff</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple active menu item handling
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.sidebar-menu li');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>