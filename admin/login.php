<?php
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Default admin credentials (in a real application, use a database and proper password hashing)
$admin_username = 'admin';
$admin_password = 'password123'; // Change this to a secure password

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($username === $admin_username && $password === $admin_password) {
        // Set session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        
        // Redirect to dashboard
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF5722;
            --orange-light: #FF7F50;
            --orange-dark: #E64A19;
            --charcoal: #333333;
            --charcoal-light: #444444;
            --charcoal-dark: #222222;
            --white: #FFFFFF;
            --off-white: #F5F5F5;
            --gradient: linear-gradient(135deg, var(--orange) 0%, #FF9800 100%);
            --gradient-dark: linear-gradient(135deg, var(--orange-dark) 0%, #E65100 100%);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--charcoal-dark);
            color: var(--white);
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .login-logo-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient);
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .login-logo-text {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .login-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .error-message {
            background-color: rgba(255, 0, 0, 0.1);
            color: #FF5252;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: var(--white);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.2);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .btn:hover {
            background: var(--gradient-dark);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--orange);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <div class="login-logo-icon">AYS</div>
            <div class="login-logo-text">Admin Panel</div>
        </div>
        <h1 class="login-title">Login to Dashboard</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        
        <a href="../index.html" class="back-link">Back to Website</a>
    </div>
</body>
</html>