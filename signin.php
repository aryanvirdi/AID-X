<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'aidx_db'); // Your DB name
define('DB_USER', 'root');        // Your DB user
define('DB_PASS', '');            // Your DB password
define('DB_CHARSET', 'utf8mb4');

$errors = [];

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $errors[] = "Database connection failed. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username) {
        $errors[] = "Please enter your username or phone number.";
    }
    if (!$password) {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, role, name, password_hash FROM users WHERE phone = :user OR email = :user");
            $stmt->execute([':user' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}
?>

<?php if (!empty($success_message)): ?>
    <div class="text-green-400 mb-4"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="text-red-400 mb-4">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In to AID-X</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <iframe src="chatbot.html"
        style="position: fixed; bottom: 20px; right: 20px; width: 400px; height: 600px; border: none; z-index: 9999;"></iframe>
    <style>
        .navbar {
            background-color: #0F766E;
            /* primary blue */
            padding: 10px 20px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ffdd57;
            /* theme accent */
        }

        /* --- CSS Styling (style.css content merged here) --- */
        :root {
            --primary-color: #007bff;
            /* Bright blue for accents */
            --primary-hover: #0056b3;
            --background-dark: #1e1e2f;
            /* Dark background (AID-X theme) */
            --surface-dark: #2a2a44;
            /* Slightly lighter surface for the form */
            --text-light: #f0f0f0;
            /* Light text */
            --text-muted: #a0a0b0;
            /* Muted text for links/hints */
            --border-color: #444466;
            /* Subtle border */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-dark);
            color: var(--text-light);
            /* display: flex; */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* --- Container Layout --- */
        .signin-container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* width: 80%;
            max-width: 1200px; */
            margin: 100px 70px;
            background-color: var(--surface-dark);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        /* --- Branding Side (Left) --- */
        .branding-side {
            flex: 1;
            /* background: linear-gradient(135deg, #1e1e2f, #3a3a5a); */
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .logo {
            font-size: 1.5em;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .branding-side h1 {
            font-size: 2.2em;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .branding-side p {
            color: var(--text-muted);
            font-size: 1.1em;
        }

        .illustration-placeholder {
            /* Subtle glow/animation effect for the AI theme */
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-color) 0%, transparent 70%);
            opacity: 0.15;
            animation: pulse 4s infinite alternate;
        }

        @keyframes pulse {
            from {
                transform: scale(0.9);
                opacity: 0.15;
            }

            to {
                transform: scale(1.1);
                opacity: 0.25;
            }
        }


        /* --- Form Side (Right) --- */
        .form-side {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signin-form {
            width: 100%;
            max-width: 380px;
        }

        .signin-form h2 {
            font-size: 2em;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            /* Adjust padding for icon */
            background-color: var(--surface-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .input-group input::placeholder {
            color: var(--text-muted);
        }

        .input-group input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .input-group .icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1em;
        }

        /* Form Options (Remember Me / Forgot Password) */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.9em;
        }

        .form-options label {
            color: var(--text-muted);
            cursor: pointer;
        }

        .form-options a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-options a:hover {
            color: var(--text-light);
        }

        /* Main Sign-In Button */
        .signin-button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }

        .signin-button:hover {
            background-color: var(--primary-hover);
        }

        .signin-button:active {
            transform: scale(0.99);
        }

        /* Social Login */
        .social-login-separator {
            text-align: center;
            margin: 30px 0 20px;
        }

        .social-login-separator p {
            position: relative;
            display: inline-block;
            color: var(--text-muted);
        }

        .social-login-separator p::before,
        .social-login-separator p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 50px;
            /* Length of the line */
            height: 1px;
            background-color: var(--border-color);
        }

        .social-login-separator p::before {
            right: 100%;
            margin-right: 15px;
        }

        .social-login-separator p::after {
            left: 100%;
            margin-left: 15px;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .social-btn {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: transparent;
            color: var(--text-light);
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .social-btn i {
            margin-right: 8px;
        }

        .social-btn:hover {
            background-color: #383857;
            /* Slightly darker hover */
            border-color: var(--primary-color);
        }

        .social-btn.google {
            color: #db4437;
        }

        /* Google brand color for icon */
        .social-btn.github {
            color: #fff;
        }

        /* White for GitHub icon */


        /* Sign Up Link */
        .signup-link {
            text-align: center;
            font-size: 0.95em;
            color: var(--text-muted);
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* --- Responsiveness --- */
        @media (max-width: 900px) {
            .signin-container {
                flex-direction: column;
                width: 90%;
                max-width: 500px;
            }

            .branding-side {
                display: none;
                /* Hide the visual side on smaller screens */
            }

            .form-side {
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="signin.php">Sign In</a></li>
            <li><a href="singup.php">Sign Up</a></li>
            <li><a href="dashboard.html">Dashboard</a></li>
            <li><a href="aidxForm.php">Aid Form</a></li>
            <li><a href="map.html">Map</a></li>
        </ul>
    </nav>

    <div class="signin-container">

        <div class="branding-side">
            <div class="logo">
                <h2>AID-X></h2>
            </div>
            <h1>SMART GIVING TIMELY LIVING</h1>
            <p>Sign in to AID-X to donate,volunteer, or receive suooirt secureky.</p>
            <div class="illustration-placeholder">
            </div>
        </div>

        <div class="form-side">
            <form class="signin-form" method="post" action="">
                <h2>Welcome Back</h2>
                <?php if (!empty($errors)): ?>
                    <div style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px; margin-bottom:15px;">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>


                <div class="input-group">
                    <i class="fas fa-user icon"></i>
                    <input type="text" id="username" placeholder="Username or Email" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" id="password" placeholder="Password" required>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="signin-button">Sign In</button>

                <div class="social-login-separator">

                </div>



                <p class="signup-link">
                    Don't have an account? <a href="#">Create an Account</a>
                </p>
            </form>
        </div>

    </div>
</body>

</html>