<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>定期存款管理系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-bg: #f8f9fa;
            --dark-text: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-text);
        }
        
        .sidebar {
            background: linear-gradient(180deg, #2b2d42 0%, #1a1b2e 100%);
            color: white;
            min-height: 100vh;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .main-content {
            padding: 20px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .icon-blue { background: linear-gradient(135deg, #4361ee, #3f37c9); }
        .icon-green { background: linear-gradient(135deg, #4cc9f0, #4895ef); }
        .icon-purple { background: linear-gradient(135deg, #7209b7, #560bad); }
        .icon-pink { background: linear-gradient(135deg, #f72585, #b5179e); }
        
        .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        
        .status-matured {
            background-color: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }
        
        .status-closed {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            transition: all 0.2s;
            border: none;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-edit {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }
        
        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .btn-renew {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .modal-header {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-left: 45px;
            border-radius: 25px;
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- 侧边栏 -->
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky pt-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">💰 FD Manager</h4>
                        <small class="text-muted">定期存款管理系统</small>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>仪表板
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="fd_list.php">
                                <i class="fas fa-list me-2"></i>存款列表
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="new_fd.php">
                                <i class="fas fa-plus-circle me-2"></i>新增存款
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                <i class="fas fa-chart-bar me-2"></i>统计报表
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog me-2"></i>系统设置
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- 主内容区 -->
            <main class="col-md-10 ms-sm-auto main-content">
                <!-- 顶部导航 -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold">定期存款管理</h2>
                        <p class="text-muted">管理您的定期存款投资组合</p>
                    </div>
                    <div class="d-flex">
                        <div class="search-box me-3">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="搜索存款...">
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newFdModal">
                            <i class="fas fa-plus me-2"></i>新增存款
                        </button>
                    </div>
                </div>

                <!-- 统计卡片 -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <div class="stat-icon icon-blue">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <h4 class="fw-bold">₹ 15,42,000</h4>
                            <p class="text-muted">总投资金额</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <div class="stat-icon icon-green">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <h4 class="fw-bold">₹ 1,25,400</h4>
                            <p class="text-muted">预计利息</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <div class="stat-icon icon-purple">
                                <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <h4 class="fw-bold">12</h4>
                            <p class="text-muted">活跃存款</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="dashboard-card">
                            <div class="stat-icon icon-pink">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <h4 class="fw-bold">3</h4>
                            <p class="text-muted">即将到期</p>
                        </div>
                    </div>
                </div>

                <!-- 存款表格 -->
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">存款列表</h5>
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary btn-sm">全部</button>
                            <button class="btn btn-outline-secondary btn-sm">活跃</button>
                            <button class="btn btn-outline-secondary btn-sm">已到期</button>
                            <button class="btn btn-outline-secondary btn-sm">已关闭</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>金额</th>
                                    <th>利率</th>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>期限</th>
                                    <th>状态</th>
                                    <th>银行</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i = 1; $i <= 8; $i++): ?>
                                <tr>
                                    <td>FD00<?php echo $i; ?></td>
                                    <td>₹ <?php echo number_format(rand(50000, 500000)); ?></td>
                                    <td><?php echo rand(5, 8); ?>.<?php echo rand(0, 99); ?>%</td>
                                    <td>2024-0<?php echo rand(1, 9); ?>-<?php echo rand(10, 28); ?></td>
                                    <td>2025-0<?php echo rand(1, 9); ?>-<?php echo rand(10, 28); ?></td>
                                    <td><?php echo rand(6, 60); ?> 个月</td>
                                    <td>
                                        <?php 
                                        $status = ['active', 'matured', 'closed'][rand(0,2)];
                                        $status_text = [
                                            'active' => '活跃',
                                            'matured' => '已到期',
                                            'closed' => '已关闭'
                                        ][$status];
                                        ?>
                                        <span class="status-badge status-<?php echo $status; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>银行 <?php echo $i; ?></td>
                                    <td>
                                        <button class="action-btn btn-edit" title="编辑">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn btn-renew" title="续存">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                        <button class="action-btn btn-delete" title="删除">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- 新增存款模态框 -->
    <div class="modal fade" id="newFdModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新增定期存款</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newFdForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">存款金额</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" placeholder="输入金额" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">年利率 (%)</label>
                                <input type="number" step="0.01" class="form-control" placeholder="利率" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">开始日期</label>
                                <input type="date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">期限</label>
                                <select class="form-select" required>
                                    <option value="">选择期限</option>
                                    <option value="6">6 个月</option>
                                    <option value="12">1 年</option>
                                    <option value="24">2 年</option>
                                    <option value="36">3 年</option>
                                    <option value="60">5 年</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">银行名称</label>
                                <select class="form-select" required>
                                    <option value="">选择银行</option>
                                    <option value="SBI">印度国家银行</option>
                                    <option value="HDFC">HDFC银行</option>
                                    <option value="ICICI">ICICI银行</option>
                                    <option value="Axis">Axis银行</option>
                                    <option value="PNB">旁遮普国家银行</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">账户类型</label>
                                <select class="form-select" required>
                                    <option value="individual">个人账户</option>
                                    <option value="joint">联名账户</option>
                                    <option value="senior">老年人账户</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">备注</label>
                                <textarea class="form-control" rows="3" placeholder="添加备注（可选）"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" form="newFdForm" class="btn btn-primary">创建存款</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 表格操作功能
        document.addEventListener('DOMContentLoaded', function() {
            // 编辑按钮点击
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    // 这里添加编辑逻辑
                    alert('编辑功能');
                });
            });

            // 续存按钮点击
            document.querySelectorAll('.btn-renew').forEach(btn => {
                btn.addEventListener('click', function() {
                    if(confirm('确定要续存这笔定期存款吗？')) {
                        // 这里添加续存逻辑
                        alert('续存成功！');
                    }
                });
            });

            // 删除按钮点击
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    if(confirm('确定要删除这笔定期存款吗？此操作不可撤销。')) {
                        const row = this.closest('tr');
                        row.style.opacity = '0.5';
                        // 这里添加删除逻辑
                        setTimeout(() => {
                            row.remove();
                            alert('删除成功！');
                        }, 500);
                    }
                });
            });
        });
    </script>
</body>
</html>