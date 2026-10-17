<?php
header('Content-Type: application/json');

// 数据库连接
$conn = new mysqli('localhost', 'username', 'password', 'fd_management');

if ($conn->connect_error) {
    die(json_encode(['error' => '数据库连接失败']));
}

$action = $_POST['action'] ?? '';

switch($action) {
    case 'add':
        addFixedDeposit($conn);
        break;
    case 'edit':
        editFixedDeposit($conn);
        break;
    case 'delete':
        deleteFixedDeposit($conn);
        break;
    case 'renew':
        renewFixedDeposit($conn);
        break;
    case 'premature_close':
        prematureClose($conn);
        break;
    case 'get_list':
        getFdList($conn);
        break;
    default:
        echo json_encode(['error' => '无效操作']);
}

function addFixedDeposit($conn) {
    $data = [
        'amount' => $_POST['amount'],
        'interest_rate' => $_POST['interest_rate'],
        'start_date' => $_POST['start_date'],
        'tenure_months' => $_POST['tenure_months'],
        'bank_name' => $_POST['bank_name'],
        'account_type' => $_POST['account_type'],
        'remarks' => $_POST['remarks']
    ];
    
    // 计算结束日期
    $end_date = date('Y-m-d', strtotime($data['start_date'] . " + {$data['tenure_months']} months"));
    $data['end_date'] = $end_date;
    
    // 生成FD编号
    $data['fd_number'] = 'FD' . date('Ymd') . rand(1000, 9999);
    
    $stmt = $conn->prepare("INSERT INTO fixed_deposits (fd_number, amount, interest_rate, start_date, end_date, tenure_months, bank_name, account_type, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddssdiss", 
        $data['fd_number'],
        $data['amount'],
        $data['interest_rate'],
        $data['start_date'],
        $data['end_date'],
        $data['tenure_months'],
        $data['bank_name'],
        $data['account_type'],
        $data['remarks']
    );
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'fd_number' => $data['fd_number']]);
    } else {
        echo json_encode(['error' => '添加失败']);
    }
}

function renewFixedDeposit($conn) {
    $fd_id = $_POST['id'];
    
    // 获取原存款信息
    $stmt = $conn->prepare("SELECT * FROM fixed_deposits WHERE id = ?");
    $stmt->bind_param("i", $fd_id);
    $stmt->execute();
    $fd = $stmt->get_result()->fetch_assoc();
    
    if($fd) {
        // 创建续存记录
        $new_start_date = $fd['end_date'];
        $new_end_date = date('Y-m-d', strtotime($new_start_date . " + {$fd['tenure_months']} months"));
        
        $renew_stmt = $conn->prepare("INSERT INTO fixed_deposits (fd_number, amount, interest_rate, start_date, end_date, tenure_months, bank_name, account_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $renew_stmt->bind_param("sddssdss",
            $fd['fd_number'] . '-R',
            $fd['amount'],
            $fd['interest_rate'], // 可以根据当前利率调整
            $new_start_date,
            $new_end_date,
            $fd['tenure_months'],
            $fd['bank_name'],
            $fd['account_type']
        );
        
        // 更新原存款状态
        $update_stmt = $conn->prepare("UPDATE fixed_deposits SET status = 'closed' WHERE id = ?");
        $update_stmt->bind_param("i", $fd_id);
        
        if($renew_stmt->execute() && $update_stmt->execute()) {
            echo json_encode(['success' => true]);
        }
    }
}

$conn->close();
?>