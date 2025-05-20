<?php 
session_start(); // Bắt đầu session
include("./includes/header.php");

// Database connection configuration for MySQL
$host = "sql106.infinityfree.com";
$user = "if0_38779493";
$password = "14061999Tzuyuu";
$db = "if0_38779493_db_supermarket";  

// Initialize MySQLi connection
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register-btn']) && $_POST['register-btn'] == "check") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alertify.set('notifier','position', 'top-right'); alertify.success('Email đã tồn tại! Vui lòng sử dụng email khác.');</script>";
    } else {
        // Insert new user into database
        $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Đăng ký thành công, chuyển hướng đến trang đăng nhập
            echo "<script>alertify.set('notifier','position', 'top-right'); alertify.success('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alertify.set('notifier','position', 'top-right'); alertify.success('Đăng ký thất bại: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - LOTTE MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
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
            max-width: 900px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-container {
            text-align: left;
            color: #fff;
            padding: 20px;
        }

        .logo-container img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .logo-container h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .logo-container p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .register-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 450px;
            display: flex;
            flex-direction: column;
        }

        .register-header {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #e60000;
            margin-bottom: 20px;
        }

        .register-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .register-tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            transition: all 0.3s;
        }

        .register-tab.active {
            color: #e60000;
            border-bottom: 2px solid #e60000;
        }

        .register-tab:hover {
            color: #e60000;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 45px;
            color: #666;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 40px;
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

        .btn-register {
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

        .btn-register:hover {
            background-color: #c50000;
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

        .social-register {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
            color: #666;
        }

        .login-link a {
            color: #e60000;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
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
            .container {
                flex-direction: column;
                max-width: 90%;
            }

            .logo-container {
                text-align: center;
                margin-bottom: 20px;
            }

            .register-container {
                width: 100%;
                margin-top: 20px;
            }
        }

        @media (max-width: 576px) {
            .logo-container h1 {
                font-size: 1.8rem;
            }

            .register-header {
                font-size: 1.3rem;
                padding: 15px;
            }

            .register-tabs {
                flex-direction: column;
            }

            .register-tab {
                padding: 8px;
                border-bottom: 1px solid #eee;
            }

            .social-register {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <h1>Q-FASHION</h1>
            <p>Kênh mua sắm Online của Q-FASHION</p>
        </div>
        
        <div class="register-container">
            <div class="register-header">
                Đăng ký
            </div>
            
            <div class="register-body">
                <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
                
                <div class="register-tabs">
                    <div class="register-tab active">Bằng OTP</div>
                    <div class="register-tab">Email/Số điện thoại</div>
                </div>
                
                <form action="" method="POST" id="register-account">
                    <div class="form-group">
                        <label for="name">Họ tên <span style="color: #e60000;">*</span></label>
                        <i class="fas fa-user"></i>
                        <input type="text" required name="name" class="form-control" id="name" placeholder="Nhập họ tên của bạn">
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span style="color: #e60000;">*</span></label>
                        <i class="fas fa-envelope"></i>
                        <input type="email" required name="email" class="form-control" id="InputEmail" placeholder="Nhập Email của bạn">
                    </div>
                    <div class="form-group">
                        <label for="phone">SĐT <span style="color: #e60000;">*</span></label>
                        <i class="fas fa-phone"></i>
                        <input type="number" required name="phone" class="form-control" id="InputPhone" placeholder="Nhập số điện thoại của bạn">
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu <span style="color: #e60000;">*</span></label>
                        <i class="fas fa-lock"></i>
                        <input type="password" required name="password" id="InputPassword1" class="form-control" placeholder="Nhập mật khẩu">
                    </div>
                    <div class="form-group">
                        <label for="cpassword">Xác nhận mật khẩu <span style="color: #e60000;">*</span></label>
                        <i class="fas fa-lock"></i>
                        <input type="password" required name="cpassword" id="InputPassword2" class="form-control" placeholder="Xác nhận mật khẩu">
                    </div>
                    <input type="hidden" name="register-btn" value="check">
                    <button type="submit" class="btn-register">Đăng Ký</button>
                </form>
                
                <div class="divider">Hoặc đăng ký với</div>
                
                <div class="social-register">
                    <a href="#" class="social-btn zalo"><i class="fab fa-zalo"></i></a>
                    <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-btn kakao"><i class="fab fa-kakao"></i></a>
                    <a href="#" class="social-btn apple"><i class="fab fa-apple"></i></a>
                </div>
                
                <div class="login-link">
                    Quý khách đã có tài khoản? <a href="login.php">Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab switching functionality
        const tabs = document.querySelectorAll('.register-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                console.log('Switched to ' + tab.textContent + ' tab');
            });
        });

        // Hàm validate email
        const validateEmail = (email) => {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        };

        // Hàm validate số điện thoại
        const validatePhone = (phone) => {
            return String(phone).match(/^[0-9]{10,11}$/);
        };

        // Xử lý form submit
        document.getElementById("register-account").addEventListener('submit', function(e) {
            let email = document.getElementById("InputEmail").value;
            let phone = document.getElementById("InputPhone").value;
            let password1 = document.getElementById("InputPassword1").value;
            let password2 = document.getElementById("InputPassword2").value;

            if (!validateEmail(email)) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success('Lỗi email không hợp lệ');
                e.preventDefault();
            } else if (!validatePhone(phone)) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success('Số điện thoại không hợp lệ (10-11 số)');
                e.preventDefault();
            } else if (password1 !== password2) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success('Mật khẩu chưa khớp');
                e.preventDefault();
            } else if (password1.length <= 6) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.success('Vui lòng nhập mật khẩu nhiều hơn 6 ký tự');
                e.preventDefault();
            }
        });

        // Hiệu ứng khi focus input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#e60000';
            });
            input.addEventListener('blur', function() {
                this.style.borderColor = '#ddd';
            });
        });
    </script>

    <?php 
    $conn->close();
    include("./includes/footer.php");
    ?>
</body>
</html>