<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'aidx_users');  // Your database name
define('DB_USER', 'root');         // Your database username
define('DB_PASS', '');             // Your database password
define('DB_CHARSET', 'utf8mb4');

$errors = [];
$success_message = '';

try {
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $errors[] = "Database connection failed. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat-password'] ?? '';

    // Basic validations
    if (!$username) {
        $errors[] = "Username is required.";
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password !== $repeat_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors[] = "Username or email already taken. Please choose another.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password_hash' => $password_hash,
                ]);
                $success_message = "Registration successful! You can now log in.";
            }
        } catch (PDOException $e) {
            $errors[] = "A database error occurred. Please try again later.";
            // Optionally log error:
            // error_log($e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <iframe src="chatbot.html" style="position: fixed; bottom: 20px; right: 20px; width: 400px; height: 600px; border: none; z-index: 9999;"></iframe>
   
  </ul>
</nav>
    <style>
         .navbar {
    background-color: #0F766E; /* primary blue */
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
    color: #ffdd57; /* theme accent */
  }
        /* Custom styles to replicate the background image with the dark blue overlay */
        .auth-background {
            /* Using a placeholder URL for the /building background */
            background-size: cover;
            background-position: center;
            /* Applying a dark blue overlay using a linear gradient */
            background-color: #1F2E4D; /* Base dark blue color */
            position: relative;
        }

        .auth-background::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            /* Dark semi-transparent overlay to match the image */
            background: rgba(31, 46, 77, 0.8);
        }

        /* Ensure the content sits above the overlay */
        .auth-container {
            position: relative;
            z-index: 10;
        }

        /* Style for input fields to match the rounded, semi-transparent look */
        .form-input-custom {
            background-color: rgba(255, 255, 255, 0.1); /* Slightly visible white background */
            border: none;
            color: #ffffff; /* White text input */
            padding: 1rem 1.5rem;
            border-radius: 9999px; /* Full rounded pill shape */
            width: 100%;
            outline: none;
            transition: background-color 0.2s;
            font-size: 1rem;
        }

        .form-input-custom:focus {
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); /* Blue glow on focus */
        }
        
        /* Placeholder styling for visibility */
        .form-input-custom::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Hide the default number controls for password/text inputs */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans min-h-screen auth-background flex items-center justify-center p-4">
 <nav class="navbar">
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="signin.php">Sign In</a></li>
    <li><a href="singup.php">Sign Up</a></li>
    <li><a href="dashboard.html">Dashboard</a></li>
    <li><a href="aidxForm.php">Aid Form</a></li>
    <li><a href="map.html">Map</a></li>

    <!-- Authentication Container (Form Card) -->
    <div class="auth-container w-full max-w-md mx-auto p-8 sm:p-10 rounded-xl shadow-2xl bg-black bg-opacity-30 backdrop-blur-sm">

        <!-- Header Tabs -->
        <div class="flex text-lg font-bold mb-8 text-white">
            
            
            <!-- Active Sign Up Tab -->
            <div class="py-2 border-b-2 border-blue-500">SIGN UP</div>
        </div>

        <!-- Sign Up Form -->
        <form id="signup-form" method="post" action="">


            <!-- Username Field -->
            <div>
                <label for="username" class="text-sm font-medium text-gray-300 block mb-2">USERNAME</label>
                <input type="text" id="username" name="username" class="form-input-custom" placeholder="Enter your username" required>
            </div>

            <!-- Email Address Field -->
            <div>
                <label for="email" class="text-sm font-medium text-gray-300 block mb-2">EMAIL ADDRESS</label>
                <input type="email" id="email" name="email" class="form-input-custom" placeholder="Enter your email address" required>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="text-sm font-medium text-gray-300 block mb-2">PASSWORD</label>
                <input type="password" id="password" name="password" class="form-input-custom" placeholder="Enter your password" required>
            </div>

            <!-- Repeat Password Field -->
            <div>
                <label for="repeat-password" class="text-sm font-medium text-gray-300 block mb-2">REPEAT PASSWORD</label>
                <input type="password" id="repeat-password" name="repeat-password" class="form-input-custom" placeholder="Repeat your password" required>
            </div>


            <!-- Sign Up Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-full transition duration-300 shadow-lg shadow-blue-500/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    SIGN UP
                </button>
            </div>
            
            <!-- Message Box (Instead of alert()) -->
            <div id="message-box" class="mt-4 text-center text-sm text-yellow-300 hidden"></div>
        </form>

    </div>

    <script>
        function handleSignUp() {
            const messageBox = document.getElementById('message-box');
            const password = document.getElementById('password').value;
            const repeatPassword = document.getElementById('repeat-password').value;
            
            messageBox.classList.add('hidden');
            messageBox.textContent = '';

            if (password !== repeatPassword) {
                messageBox.textContent = 'Error: Passwords do not match!';
                messageBox.classList.remove('hidden');
                return;
            }

            // In a real application, you would send the form data to a server here.
            
            messageBox.textContent = `Success! User data submitted for: ${document.getElementById('username').value}. (Check console for full data)`;
            messageBox.classList.remove('hidden');
            messageBox.classList.remove('text-yellow-300');
            messageBox.classList.add('text-green-300');


            // Log form data to console for demonstration
            const formData = new FormData(document.getElementById('signup-form'));
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            console.log('Form Data:', data);
        }
    </script>
</body>
</html>
