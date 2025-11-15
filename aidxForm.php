<?php

// aid_form.php - Updated with lat/lon and modern Tailwind styling



// --- Database Configuration (Customize these values) ---

define('DB_HOST', 'localhost');

define('DB_NAME', 'aidx_db');

define('DB_USER', 'root');

define('DB_PASS', '');

define('DB_CHARSET', 'utf8mb4');

// --------------------------------------------------------



$message = '';

$errors = [];



// Initialize or capture form data

$formData = [

    'type' => $_POST['type'] ?? '',

    'fullname' => trim($_POST['fullname'] ?? ''),

    'phone' => trim($_POST['phone'] ?? ''),

    'email' => trim($_POST['email'] ?? ''),

    'latitude' => trim($_POST['latitude'] ?? ''),

    'longitude' => trim($_POST['longitude'] ?? ''),

    'aidtype' => $_POST['aidtype'] ?? '',

    'details' => trim($_POST['details'] ?? ''),

    'aadhaar' => trim($_POST['aadhaar'] ?? ''),

];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $formData['type'];



    // --- Validation Logic ---

    if (!$type || !in_array($type, ['request', 'offer', 'volunteer'])) {

        $errors[] = 'Please select a valid engagement type.';

    }

    if (strlen($formData['fullname']) < 3) {

        $errors[] = 'Please enter a valid full name (minimum 3 characters).';

    }

    if (!preg_match('/^\+?\d{7,15}$/', $formData['phone'])) {

        $errors[] = 'Please enter a valid phone number (7-15 digits).';

    }

    if ($formData['email'] && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {

        $errors[] = 'Please enter a valid email address.';

    }

    if (!preg_match('/^\d{12}$/', $formData['aadhaar'])) {

        $errors[] = 'Please enter a valid 12-digit Aadhaar number.';

    }

    if ($type !== 'volunteer' && !$formData['aidtype']) {

        $errors[] = 'Please select a specific type of aid.';

    }

    if ($type === 'request' || $type === 'offer') {

        if ($formData['latitude'] === '' || $formData['longitude'] === '') {

            $errors[] = 'Please provide both latitude and longitude for your location.';

        } elseif (!is_numeric($formData['latitude']) || !is_numeric($formData['longitude']) ||

            $formData['latitude'] < -90 || $formData['latitude'] > 90 ||

            $formData['longitude'] < -180 || $formData['longitude'] > 180) {

            $errors[] = 'Please enter valid latitude (-90 to 90) and longitude (-180 to 180) values.';

        }

    }

    // --- End Validation Logic ---



    if (empty($errors)) {

        // --- Database Insertion Logic ---

        try {

$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;


            $pdo = new PDO($dsn, DB_USER, DB_PASS, [

                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            ]);



            $sql = "INSERT INTO aid_requests (fullname, phone, email, latitude, longitude, aidtype, details, aadhaar, type) 

                    VALUES (:fullname, :phone, :email, :latitude, :longitude, :aidtype, :details, :aadhaar, :type)";

            $stmt = $pdo->prepare($sql);

            

            // Set aidtype to a default value for volunteers if necessary, or use the selected one

            $aidtype = ($type === 'volunteer' && !$formData['aidtype']) ? 'volunteer_support' : $formData['aidtype'];



            $stmt->execute([

                ':fullname' => $formData['fullname'],

                ':phone' => $formData['phone'],

                ':email' => $formData['email'] ?: null,

                // Only save coordinates if they were provided (applies to request/offer)

                ':latitude' => ($type === 'request' || $type === 'offer') ? $formData['latitude'] : null,

                ':longitude' => ($type === 'request' || $type === 'offer') ? $formData['longitude'] : null,

                ':aidtype' => $aidtype,

                ':details' => $formData['details'] ?: null,

                ':aadhaar' => $formData['aadhaar'],

                ':type' => $type,

            ]);



            $message = 'Form submitted successfully! Your request has been registered.';

            // Clear form data on success for a clean slate

            $formData = array_fill_keys(array_keys($formData), '');

        } catch (PDOException $e) {

            error_log('Database Error: ' . $e->getMessage());

            $errors[] = 'We encountered a database error. Please try again later.';

        }

        // --- End Database Insertion Logic ---

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AID-X Support Request Form</title>

    <!-- Load Tailwind CSS -->

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configure Tailwind for the Inter font and custom colors -->
    
    <iframe src="chatbot.html" style="position: fixed; bottom: 20px; right: 20px; width: 400px; height: 600px; border: none; z-index: 9999;"></iframe>
    

  </ul>
</nav>
    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        'primary': '#0F766E', // Teal-like color

                        'secondary': '#14B8A6', // Lighter teal/cyan

                        'dark-bg': '#050709'

                    },

                    fontFamily: {

                        sans: ['Inter', 'sans-serif'],

                    },

                    backgroundImage: {

                        // Background image URL for a humanitarian theme

                        'form-pattern': "url('https://images.unsplash.com/photo-1543269865-cbe426643c99?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')",

                    }

                }

            }

        }

    </script>

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
 <!-- Place this near the top of your <body> in aidxForm.php -->

  .navbar {
      background-color: #007bff;
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
  }
  
  @media (max-width: 600px) {
      .nav-links {
          flex-direction: column;
          align-items: center;
      }
  
      .nav-links li {
          margin: 10px 0;
      }
  }


        /* Custom Styles for Form Background and Layout */

        body {

            font-family: 'Inter', sans-serif;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 2rem 1rem;

            /* Applying the background image and overlay */

            background-image: var(--tw-form-pattern);

            background-size: cover;

            background-position: center;

            background-attachment: fixed;

            position: relative;

        }



        /* Dark overlay for contrast - this creates the blueish/dark backdrop */

        body::before {

            content: '';

            position: absolute;

            top: 0;

            left: 0;

            right: 0;

            bottom: 0;

            /* Using a dark blueish tone for the overlay as requested, maintaining the modern look */

            background-color: rgba(5, 7, 9, 0.75); /* dark-bg with opacity */

            backdrop-filter: blur(2px);

            z-index: 0;

        }



        .form-container {

            position: relative;

            z-index: 10;

        }



        /* Input and Select Styling for focus/hover */

        .form-input-field {

            width: 100%;

            padding: 0.75rem 1rem;

            border: 1px solid #d1d5db; /* gray-300 */

            border-radius: 0.75rem;

            background-color: #f9fafb; /* gray-50 */

            transition: border-color 0.3s ease, box-shadow 0.3s ease;

        }

        .form-input-field:focus {

            border-color: #14B8A6 !important; /* secondary color */

            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.5) !important;

            outline: none !important;

        }



    </style>

</head>

<body>


<nav class="navbar">
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="signin.php">Sign In</a></li>
    <li><a href="singup.php">Sign Up</a></li>
    <li><a href="aidxForm.php">Aid Form</a></li>
    <li><a href="map.html">Map</a></li>
<div class="form-container bg-white bg-opacity-95 backdrop-blur-sm rounded-3xl p-6 md:p-10 w-full max-w-lg shadow-2xl border border-primary/50">

    <div class="text-center mb-8">

        <h1 class="text-4xl font-extrabold text-primary mb-2">

            AID-<span class="text-secondary">X</span> Form

        </h1>

        <p class="text-gray-600 font-medium">

            Connect with aid or offer your support to the community.

        </p>

    </div>



    <!-- PHP Message Handling -->

    <?php if ($message): ?>

        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 font-semibold border border-green-300">

            <?=htmlspecialchars($message)?>

        </div>

    <?php endif; ?>



    <?php if (!empty($errors)): ?>

        <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-semibold border border-red-300">

            <ul class="list-disc pl-5 space-y-1">

                <?php foreach ($errors as $error): ?>

                    <li><?=htmlspecialchars($error)?></li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>

    <!-- End PHP Message Handling -->



    <form method="post" class="space-y-6">

        <!-- Engagement Type -->

        <div>

            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Engagement Type <span class="text-red-600">*</span></label>

            <select id="type" name="type" required class="form-input-field">

                <option value="" disabled <?=empty($formData['type']) ? 'selected' : ''?>>Select Type</option>

                <option value="request" <?=($formData['type'] === 'request' ? 'selected' : '')?>>Seeking Help (Request Aid)</option>

                <option value="offer" <?=($formData['type'] === 'offer' ? 'selected' : '')?>>Offering Aid (Donor)</option>

                <option value="volunteer" <?=($formData['type'] === 'volunteer' ? 'selected' : '')?>>Volunteer</option>

            </select>

        </div>



        <!-- Full Name -->

        <div>

            <label for="fullname" class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-600">*</span></label>

            <input type="text" id="fullname" name="fullname" required class="form-input-field" placeholder="e.g., Jane Doe" value="<?=htmlspecialchars($formData['fullname'])?>">

        </div>



        <!-- Phone & Email -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>

                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number <span class="text-red-600">*</span></label>

                <input type="tel" id="phone" name="phone" required class="form-input-field" placeholder="+91 1234567890" value="<?=htmlspecialchars($formData['phone'])?>">

            </div>

            <div>

                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email (optional)</label>

                <input type="email" id="email" name="email" class="form-input-field" placeholder="you@example.com" value="<?=htmlspecialchars($formData['email'])?>">

            </div>

        </div>



        <!-- Aadhaar ID -->

        <div>

            <label for="aadhaar" class="block text-sm font-semibold text-gray-700 mb-1">Aadhaar ID <span class="text-red-600">*</span></label>

            <input type="text" id="aadhaar" name="aadhaar" maxlength="12" pattern="\d{12}" required class="form-input-field" placeholder="0000 0000 0000" value="<?=htmlspecialchars($formData['aadhaar'])?>">

            <p class="text-xs text-gray-500 mt-1">Verification is mandatory for secure engagement.</p>

        </div>



        <!-- Location Container (Hidden/Shown based on Type) -->

        <div id="location-container" class="space-y-2 hidden">

            <label class="block text-sm font-semibold text-gray-700">Location Coordinates <span class="text-red-600">*</span></label>

            <div class="flex gap-4">

                <input type="number" step="any" name="latitude" placeholder="Latitude (e.g., 28.7)" class="form-input-field flex-1" value="<?=htmlspecialchars($formData['latitude'] ?? '')?>" />

                <input type="number" step="any" name="longitude" placeholder="Longitude (e.g., 77.2)" class="form-input-field flex-1" value="<?=htmlspecialchars($formData['longitude'] ?? '')?>" />

            </div>

            <p class="text-xs text-gray-500 mt-1">Required for dispatching/receiving aid effectively.</p>

        </div>



        <!-- Aid Type Container (Hidden/Shown based on Type) -->

        <div id="aidtype-container" class="hidden">

            <label for="aidtype" class="block text-sm font-semibold text-gray-700 mb-1">Type of Aid <span class="text-red-600">*</span></label>

            <select id="aidtype" name="aidtype" class="form-input-field">

                <option value="" disabled <?=empty($formData['aidtype'])?'selected':''?>>Select Aid Type</option>

                <option value="food" <?=($formData['aidtype']=='food')?'selected':''?>>Food</option>

                <option value="water" <?=($formData['aidtype']=='water')?'selected':''?>>Water</option>

                <option value="medical" <?=($formData['aidtype']=='medical')?'selected':''?>>Medical Supplies</option>

                <option value="shelter" <?=($formData['aidtype']=='shelter')?'selected':''?>>Temporary Shelter</option>

                <option value="financial" <?=($formData['aidtype']=='financial')?'selected':''?>>Financial Aid</option>

                <option value="clothing" <?=($formData['aidtype']=='clothing')?'selected':''?>>Clothing</option>

                <option value="other" <?=($formData['aidtype']=='other')?'selected':''?>>Other</option>

            </select>

        </div>



        <!-- Details -->

        <div>

            <label for="details" class="block text-sm font-semibold text-gray-700 mb-1">Details (Specific needs/offer)</label>

            <textarea id="details" name="details" rows="4" class="form-input-field resize-none" placeholder="Provide a brief description of what you need or what you can offer."><?=htmlspecialchars($formData['details'])?></textarea>

        </div>



        <!-- Submit Button -->

        <button type="submit" class="w-full py-3 bg-primary text-white text-lg font-bold rounded-xl hover:bg-secondary transition duration-300 transform hover:scale-[1.01] shadow-xl shadow-primary/40">

            Submit Form

        </button>

    </form>

</div>



<script>

    document.addEventListener('DOMContentLoaded', () => {

        const typeSelect = document.getElementById('type');

        const locationContainer = document.getElementById('location-container');

        const aidtypeContainer = document.getElementById('aidtype-container');

        const submitButton = document.querySelector('button[type="submit"]');



        function updateFormVisibility() {

            const val = typeSelect.value;

            let buttonText = 'Submit Form';



            // Location is mandatory for Request and Offer, and optional/hidden for Volunteer in the original PHP logic, 

            // but we'll follow the dynamic visibility logic from the last HTML for a better UX:

            // Show location for Request, Offer, and Volunteer (since location is helpful for all)

            // However, based on the PHP validation logic provided, coordinates are only strictly validated for 'request', 

            // so we will only show/require it for 'request' and 'offer' as per the PHP block's implied needs.

            if(val === 'request' || val === 'offer'){

                locationContainer.classList.remove('hidden');

            } else {

                locationContainer.classList.add('hidden');

            }

            

            // Show aid type for Request and Offer, hide for Volunteer

            if(val === 'request' || val === 'offer'){

                aidtypeContainer.classList.remove('hidden');

            } else {

                aidtypeContainer.classList.add('hidden');

            }



            // Update button text based on engagement type

            if (val === 'request') {

                buttonText = 'Request Aid';

            } else if (val === 'offer') {

                buttonText = 'Offer Aid';

            } else if (val === 'volunteer') {

                buttonText = 'Sign Up to Volunteer';

            } else {

                buttonText = 'Submit Form';

            }

            submitButton.textContent = buttonText;

        }



        typeSelect.addEventListener('change', updateFormVisibility);

        // Initial call to set state based on PHP loaded value

        updateFormVisibility();

    });

</script>



</body>

</html>