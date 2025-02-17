<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            box-shadow: 0 2px 5px rgba(0,0,0,.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-header {
            background-color: #f8f8f8;
            border-radius: 8px 8px 0 0;
        }
        .order-items {
            padding: 15px;
        }
        .drink-item {
            text-align: center;
            padding: 10px;
        }
        .drink-price {
            background-color: #eee;
            border-radius: 50%;
            padding: 5px 8px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .date-picker {
            max-width: 180px;
        }
        .pagination {
            justify-content: center;
        }
        .status-badge {
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-delivery {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-done {
            background-color: #d4edda;
            color: #155724;
        }
        .action-btn {
            border-radius: 4px;
            font-size: 14px;
            padding: 4px 10px;
        }
        .drink-icon {
            font-size: 28px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#"><strong>Food Delivery</strong></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" href="#">My Orders</a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <img src="/api/placeholder/40/40" alt="User Avatar" class="rounded-circle" width="30">
                    <span class="ms-2">Islam Askar</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-4">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">My Orders</h2>
                

                <div class="row mb-4 align-items-end">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <label for="dateFrom" class="form-label">Date from</label>
                        <div class="input-group date-picker">
                            <input type="date" class="form-control" id="dateFrom">
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <label for="dateTo" class="form-label">Date to</label>
                        <div class="input-group date-picker">
                            <input type="date" class="form-control" id="dateTo">
                            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <button class="btn btn-primary">Filter</button>
                    </div>
                </div>
                

                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">2015/02/02 10:30 AM</span>
                                        <i class="fa-solid fa-plus text-primary"></i>
                                    </div>
                                </td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>55 EGP</td>
                                <td><button class="btn btn-sm btn-outline-danger action-btn">CANCEL</button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">2015/02/01 11:30 AM</span>
                                        <i class="fa-solid fa-plus text-primary"></i>
                                    </div>
                                </td>
                                <td><span class="status-badge status-delivery">Out for delivery</span></td>
                                <td>20 EGP</td>
                                <td><button class="btn btn-sm btn-outline-secondary action-btn" disabled>DELIVERED</button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">2015/01/01 11:35 AM</span>
                                        <i class="fa-solid fa-minus text-secondary"></i>
                                    </div>
                                </td>
                                <td><span class="status-badge status-done">Done</span></td>
                                <td>29 EGP</td>
                                <td><button class="btn btn-sm btn-outline-success action-btn" disabled>COMPLETED</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="order-details mt-4">
                    <div class="card">
                        <div class="order-items">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="drink-item">
                                        <div class="drink-price mb-2">5 LE</div>
                                        <i class="fa-solid fa-mug-hot drink-icon"></i>
                                        <h6 class="mb-1">Tea</h6>
                                        <p class="mb-0">1</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="drink-item">
                                        <div class="drink-price mb-2">6 LE</div>
                                        <i class="fa-solid fa-mug-saucer drink-icon"></i>
                                        <h6 class="mb-1">Coffee</h6>
                                        <p class="mb-0">1</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="drink-item">
                                        <div class="drink-price mb-2">8 LE</div>
                                        <i class="fa-solid fa-mug-saucer drink-icon"></i>
                                        <h6 class="mb-1">Nescafe</h6>
                                        <p class="mb-0">1</p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="drink-item">
                                        <div class="drink-price mb-2">10 LE</div>
                                        <i class="fa-solid fa-bottle-water drink-icon"></i>
                                        <h6 class="mb-1">Cola</h6>
                                        <p class="mb-0">1</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <h5>Total: <span class="ms-2 fw-bold">EGP 104</span></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>