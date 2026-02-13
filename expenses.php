<?php
// Database Configuration
$host = 'localhost';
$dbname = 'attendance_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create expenses table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expense_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    payment_method ENUM('UPI', 'Bank Transfer', 'Cash', 'Card', 'Scanner') NOT NULL,
    reference_number VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$pdo->exec($create_table);

// Handle form submission
if ($_POST && isset($_POST['add_expense'])) {
    $expense_date = $_POST['expense_date'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $payment_method = $_POST['payment_method'];
    $reference_number = $_POST['reference_number'];
    
    $sql = "INSERT INTO expenses (expense_date, amount, description, category, payment_method, reference_number) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$expense_date, $amount, $description, $category, $payment_method, $reference_number])) {
        $success_message = "Expense added successfully!";
    } else {
        $error_message = "Error adding expense!";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $sql = "DELETE FROM expenses WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$delete_id])) {
        $success_message = "Expense deleted successfully!";
    }
}

// Get filter values
$selected_month = $_GET['month'] ?? date('Y-m');
$selected_year = $_GET['year'] ?? date('Y');
$category_filter = $_GET['category'] ?? '';
$payment_filter = $_GET['payment_method'] ?? '';

// Get categories for filter
$cat_sql = "SELECT DISTINCT category FROM expenses ORDER BY category";
$categories = $pdo->query($cat_sql)->fetchAll(PDO::FETCH_COLUMN);

// Build expenses query with filters
$expense_sql = "SELECT * FROM expenses WHERE 1=1";
$params = [];

if ($selected_month) {
    $expense_sql .= " AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
    $params[] = $selected_month;
}

if ($category_filter) {
    $expense_sql .= " AND category = ?";
    $params[] = $category_filter;
}

if ($payment_filter) {
    $expense_sql .= " AND payment_method = ?";
    $params[] = $payment_filter;
}

$expense_sql .= " ORDER BY expense_date DESC, created_at DESC";
$stmt = $pdo->prepare($expense_sql);
$stmt->execute($params);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate monthly total
$monthly_total = 0;
foreach ($expenses as $expense) {
    $monthly_total += $expense['amount'];
}

// Calculate yearly total
$yearly_sql = "SELECT SUM(amount) as yearly_total FROM expenses WHERE YEAR(expense_date) = ?";
$stmt = $pdo->prepare($yearly_sql);
$stmt->execute([$selected_year]);
$yearly_total = $stmt->fetchColumn() ?: 0;

// Get monthly breakdown for current year
$monthly_breakdown_sql = "SELECT DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total 
                         FROM expenses WHERE YEAR(expense_date) = ? 
                         GROUP BY DATE_FORMAT(expense_date, '%Y-%m') 
                         ORDER BY month";
$stmt = $pdo->prepare($monthly_breakdown_sql);
$stmt->execute([$selected_year]);
$monthly_breakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get category wise breakdown for selected month
$category_breakdown_sql = "SELECT category, SUM(amount) as total, COUNT(*) as count 
                          FROM expenses WHERE DATE_FORMAT(expense_date, '%Y-%m') = ? 
                          GROUP BY category ORDER BY total DESC";
$stmt = $pdo->prepare($category_breakdown_sql);
$stmt->execute([$selected_month]);
$category_breakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Expenses Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }
        .sidebar .nav-link i { width: 20px; }
        .sidebar-brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-content { background-color: #f8f9fa; min-height: 100vh; }
        .card-header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
        .table th { background-color: #f8f9fa; }
        .btn-primary { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; }
        .form-control:focus, .form-select:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); }
        .stats-card { border-left: 4px solid #28a745; }
        .expense-item { border-left: 3px solid #dee2e6; }
        .expense-item:hover { border-left-color: #28a745; background-color: #f8f9fa; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -250px; width: 250px; z-index: 1000; transition: left 0.3s ease; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2">
                <div class="sidebar">
                    <div class="sidebar-brand">
                        <i class="fas fa-building me-2"></i>Company Panel
                    </div>
                    <nav class="nav flex-column mt-3">
                        <a class="nav-link" href="attendance.php">
                            <i class="fas fa-calendar-check me-2"></i>Attendance
                        </a>
                        <a class="nav-link active" href="expenses.php">
                            <i class="fas fa-money-bill-wave me-2"></i>Expenses
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-2"></i>Employees
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="text-light mx-3">
                        <a class="nav-link" href="#">
                            <i class="fas fa-file-invoice me-2"></i>Invoices
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-wallet me-2"></i>Payments
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-calculator me-2"></i>Payroll
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <!-- Mobile Menu Button -->
                    <button class="btn btn-outline-secondary d-md-none mb-3" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i> Menu
                    </button>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Company Expenses Management</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">Monthly Total</h5>
                                        <h3 class="text-success">₹<?= number_format($monthly_total, 2) ?></h3>
                                        <small class="text-muted"><?= date('F Y', strtotime($selected_month . '-01')) ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">Yearly Total</h5>
                                        <h3 class="text-info">₹<?= number_format($yearly_total, 2) ?></h3>
                                        <small class="text-muted"><?= $selected_year ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <h5 class="card-title text-warning">Total Expenses</h5>
                                        <h3 class="text-warning"><?= count($expenses) ?></h3>
                                        <small class="text-muted">This month</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stats-card">
                                    <div class="card-body">
                                        <h5 class="card-title text-danger">Avg per Day</h5>
                                        <h3 class="text-danger">₹<?= $monthly_total > 0 ? number_format($monthly_total / date('j'), 2) : '0.00' ?></h3>
                                        <small class="text-muted">Current month</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Expense Form -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Expense</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="expense_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Amount (₹)</label>
                                            <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Category</label>
                                            <input type="text" name="category" class="form-control" placeholder="e.g., Office Supplies, Travel" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Payment Method</label>
                                            <select name="payment_method" class="form-select" required>
                                                <option value="">Select Method</option>
                                                <option value="UPI">UPI</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Scanner">Scanner</option>
                                                <option value="Card">Card</option>
                                                <option value="Cash">Cash</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Description</label>
                                            <input type="text" name="description" class="form-control" placeholder="What was this expense for?" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Reference Number</label>
                                            <input type="text" name="reference_number" class="form-control" placeholder="Transaction ID, Receipt No.">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" name="add_expense" class="btn btn-primary d-block w-100">
                                                <i class="fas fa-plus me-1"></i>Add Expense
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Month</label>
                                <input type="month" name="month" class="form-control" value="<?= $selected_month ?>" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" onchange="this.form.submit()">
                                    <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y ?>" <?= $selected_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $category_filter === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Methods</option>
                                    <option value="UPI" <?= $payment_filter === 'UPI' ? 'selected' : '' ?>>UPI</option>
                                    <option value="Bank Transfer" <?= $payment_filter === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                    <option value="Scanner" <?= $payment_filter === 'Scanner' ? 'selected' : '' ?>>Scanner</option>
                                    <option value="Card" <?= $payment_filter === 'Card' ? 'selected' : '' ?>>Card</option>
                                    <option value="Cash" <?= $payment_filter === 'Cash' ? 'selected' : '' ?>>Cash</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-outline-primary d-block w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Category Breakdown -->
                        <?php if (!empty($category_breakdown)): ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Category Breakdown - <?= date('F Y', strtotime($selected_month . '-01')) ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($category_breakdown as $cat): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                                    <div>
                                                        <strong><?= $cat['category'] ?></strong><br>
                                                        <small class="text-muted"><?= $cat['count'] ?> expenses</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h6 class="text-success mb-0">₹<?= number_format($cat['total'], 2) ?></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Expenses List -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Expenses List - <?= date('F Y', strtotime($selected_month . '-01')) ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($expenses)): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No expenses found for the selected period.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Category</th>
                                                    <th>Amount</th>
                                                    <th>Payment Method</th>
                                                    <th>Reference</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($expenses as $expense): ?>
                                                    <tr class="expense-item">
                                                        <td><?= date('d M Y', strtotime($expense['expense_date'])) ?></td>
                                                        <td><?= htmlspecialchars($expense['description']) ?></td>
                                                        <td><span class="badge bg-secondary"><?= $expense['category'] ?></span></td>
                                                        <td class="text-success fw-bold">₹<?= number_format($expense['amount'], 2) ?></td>
                                                        <td>
                                                            <i class="fas fa-<?= $expense['payment_method'] === 'UPI' ? 'mobile-alt' : ($expense['payment_method'] === 'Bank Transfer' ? 'university' : ($expense['payment_method'] === 'Scanner' ? 'qrcode' : ($expense['payment_method'] === 'Card' ? 'credit-card' : 'money-bill'))) ?> me-1"></i>
                                                            <?= $expense['payment_method'] ?>
                                                        </td>
                                                        <td><?= $expense['reference_number'] ?: '-' ?></td>
                                                        <td>
                                                            <a href="?delete=<?= $expense['id'] ?>&month=<?= $selected_month ?>&year=<?= $selected_year ?>&category=<?= $category_filter ?>&payment_method=<?= $payment_filter ?>" 
                                                               class="btn btn-sm btn-outline-danger" 
                                                               onclick="return confirm('Are you sure you want to delete this expense?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success">
                                                    <th colspan="3">Total</th>
                                                    <th class="text-success">₹<?= number_format($monthly_total, 2) ?></th>
                                                    <th colspan="3"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Monthly Breakdown Chart -->
                        <?php if (!empty($monthly_breakdown)): ?>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Breakdown - <?= $selected_year ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($monthly_breakdown as $month): ?>
                                            <div class="col-md-2 mb-3">
                                                <div class="text-center p-3 border rounded">
                                                    <h6><?= date('M', strtotime($month['month'] . '-01')) ?></h6>
                                                    <h5 class="text-primary">₹<?= number_format($month['total'], 0) ?></h5>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                const menuBtn = document.querySelector('button[onclick="toggleSidebar()"]');
                if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>