<?php
session_start();
include "conn.php";

// If already logged in, go to dashboard
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

// Prevent browser from caching this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$emailWarning = "";
$passWarning = "";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $Password = $_POST["password"];

    if($email == "admin" && $Password == "admin123"){
        $_SESSION['id'] = $email;
        header("Location: index.php");
    }
    else if($email !== "admin"){
        $emailWarning = "Invalid Username";
    }
    else if($Password !== "admin123"){
        $passWarning = "Incorrect Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lyceum</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="./css/style.css">
    
    
</head>

<body>
     <div class="login-container">
    <div class="login-card">
        <!-- Logo -->
        <img src="img/loalogo.png" alt="Logo" class="login-logo"> 
        <!-- Title -->
        <h2 class="login-title">Lyceum of San Pedro</h2>
        <p class="login-subtitle">Facility Access System</p>

        <!-- Login Form -->
        <form id="loginForm" action="login.php" method="POST">
            <!-- Username -->
            <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter username"  required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                </div>
                <span class="warning-text"><?php echo $emailWarning; ?></span>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Enter password" 
                           required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <span class="warning-text"><?php echo $passWarning; ?></span>
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe" style="font-size: 0.9rem;">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn login-btn" name="login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </button>
        </form>
    </div>
     </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                
                if (type === 'text') {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
            
            // Prevent back button
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.pushState(null, null, location.href);
            };
            
            // Prevent form resubmission
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            
            // Auto-focus on username
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>