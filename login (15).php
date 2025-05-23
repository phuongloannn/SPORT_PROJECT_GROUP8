<?php
// Kết nối cơ sở dữ liệu
$host = "sql106.infinityfree.com";
$user = "if0_38779493";
$password = "14061999Tzuyuu";
$db = "if0_38779493_db_supermarket";

$conn = new mysqli($host, $user, $password, $db);

// Kiểm tra kết nối
if ($conn->connect_error) {
    $error = "Kết nối cơ sở dữ liệu thất bại: " . $conn->connect_error;
    error_log($error);
    die($error);
}

session_start();

// Kiểm tra và redirect nếu đã đăng nhập
if (isset($_SESSION['auth_user'])) {
    header("Location: homepage.php");
    exit();
}

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    if (empty($email) || empty($pass)) {
        $error = "Vui lòng điền đầy đủ email và mật khẩu!";
    } else {
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $error = "Lỗi chuẩn bị câu lệnh SQL: " . $conn->error;
            error_log($error);
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $error = "Lỗi thực thi câu lệnh SQL: " . $stmt->error;
                error_log($error);
            } else {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($pass, $user['password'])) {
                        $_SESSION['auth_user'] = $user;
                        $_SESSION['role'] = $user['role'];
                        header("Location: homepage.php");
                        exit();
                    } else {
                        $error = "Mật khẩu không đúng!";
                    }
                } else {
                    $error = "Email không tồn tại!";
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - LOTTE MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://mms.img.susercontent.com/361b3edc5766004fbb9d60e6943959a1@resize_bs700x700') no-repeat center center/cover;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .logo-container p {
            font-size: 1.1rem;
            color: #fff;
            opacity: 0.9;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 450px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background-color: #e60000;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .login-body {
            padding: 30px;
        }

        .login-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .login-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            transition: all 0.3s;
        }

        .login-tab.active {
            color: #e60000;
            border-bottom: 2px solid #e60000;
        }

        .login-tab:hover {
            color: #e60000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #e60000;
            outline: none;
            box-shadow: 0 0 0 2px rgba(230, 0, 0, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #e60000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #c50000;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            color: #e60000;
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
            font-size: 0.9rem;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider::before {
            margin-right: 10px;
        }

        .divider::after {
            margin-left: 10px;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .social-btn i {
            font-size: 1.2rem;
        }

        .social-btn.zalo {
            color: #0068ff;
        }

        .social-btn.google {
            color: #db4437;
        }

        .social-btn.kakao {
            color: #ffcd00;
        }

        .social-btn.apple {
            color: #000;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
            color: #666;
        }

        .register-link a {
            color: #e60000;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: #e60000;
            background-color: #ffebee;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @media (max-width: 768px) {
            .logo-container h1 {
                font-size: 2rem;
            }
            
            .login-container {
                max-width: 90%;
            }
            
            .login-body {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .logo-container h1 {
                font-size: 1.8rem;
            }
            
            .login-header {
                font-size: 1.3rem;
                padding: 15px;
            }
            
            .login-tabs {
                flex-direction: column;
            }
            
            .login-tab {
                padding: 8px;
                border-bottom: 1px solid #eee;
            }
            
            .social-login {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <h1>Q-FASHION</h1>
            <p>Kênh mua sắm Online của Q-Fashion</p>
        </div>
        
        <div class="login-container">
            <div class="login-header">
                Đăng nhập
            </div>
            
            <div class="login-body">
                <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
                
                <div class="login-tabs">
                    <div class="login-tab active">Email/Số điện thoại</div>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Tên đăng nhập <span style="color: #e60000;">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email hoặc số điện thoại" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mật khẩu <span style="color: #e60000;">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="#">Quên mật khẩu?</a>
                    </div>
                    
                    <button type="submit" name="login" class="btn-login">Đăng nhập</button>
                </form>
                
                <div class="divider">Hoặc đăng nhập với</div>
                
                <div class="social-login">
                    <a href="#" class="social-btn zalo"><i class="fab fa-zalo"></i></a>
                    <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-btn kakao"><i class="fab fa-kakao"></i></a>
                    <a href="#" class="social-btn apple"><i class="fab fa-apple"></i></a>
                </div>
                
                <div class="register-link">
                    Quý khách chưa có tài khoản? <a href="register.php">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                event.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin!');
                console.log('Validation failed: Email or password missing');
            } else if (!email.includes('@') && !/^\d+$/.test(email)) {
                event.preventDefault();
                alert('Vui lòng nhập email hoặc số điện thoại hợp lệ!');
                console.log('Validation failed: Invalid email/phone format');
            } else if (password.length < 6) {
                event.preventDefault();
                alert('Mật khẩu phải dài ít nhất 6 ký tự!');
                console.log('Validation failed: Password too short');
            }
        });

        // Debug session and POST data
        console.log('Session:', <?php echo json_encode($_SESSION); ?>);
        console.log('POST Data:', <?php echo json_encode($_POST); ?>);
    </script>

    <?php $conn->close(); ?>
</body>
</html>