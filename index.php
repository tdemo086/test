
<?php
// Start session
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "earnify_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create database and table if they don't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
$sql_create_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    referrer VARCHAR(255),
    photo VARCHAR(255)
)";

$conn->query($sql_create_db);
$conn->query("USE $dbname");
$conn->query($sql_create_table);

// Handle user registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $reg_username = $_POST['reg_username'];
    $reg_password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT); // Hash password
    $referrer = $_POST['referrer'];
    $photo = $_FILES['reg_photo']['name'];
    $photo_tmp = $_FILES['reg_photo']['tmp_name'];

    // Define upload directory
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($photo);

    // Move the uploaded file to the desired directory
    move_uploaded_file($photo_tmp, $upload_file);

    // Insert data into the database
    $sql = "INSERT INTO users (username, password, referrer, photo) VALUES ('$reg_username', '$reg_password', '$referrer', '$photo')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Handle user login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    // Fetch user from database
    $sql = "SELECT * FROM users WHERE username='$login_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($login_password, $row['password'])) {
            // Save user data to session
            $_SESSION['username'] = $login_username;
            $_SESSION['photo'] = $row['photo'];
            echo "<script>alert('Login successful');</script>";
        } else {
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        echo "<script>alert('No user found');</script>";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type='text/javascript' src='//pl23875528.highrevenuenetwork.com/8b/d8/ba/8bd8ba490bdf38fda668a66cb9102854.js'></script>
    <script type='text/javascript' src='//pl23875519.highrevenuenetwork.com/50/59/98/505998e904901bff945f5e66666480ec.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EarniFy-Earn A Lot</title>
    <link rel="stylesheet" href="index.css"> <!-- Link to your external CSS -->
</head>
<body>
    <header>
        <h1>EarniFy</h1>
        <nav>
            <ul>
                <li><a href="#" onclick="showSection('home')"><i class="fa-solid fa-house"></i></a></li>
                <li><a href="#" onclick="showSection('tasks')"><i class="fa-solid fa-list-check"></i></a></li>
                <li><a href="#" onclick="showSection('profile')"><i class="fa-solid fa-user"></i></a></li>
                <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout=true"><i class="fa-solid fa-right-from-bracket"></i></a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <?php if (!isset($_SESSION['username'])): ?>
            <!-- Login Section -->
            <section id="login">
                <h2>Login <i class="fa-solid fa-right-to-bracket"></i></h2>
                <form id="login-form" method="post">
                    <input class="in" type="text" id="username" name="username" placeholder="Username" required>
                    <input class="in" type="password" id="password" name="password" placeholder="Password" required>
                    <button class="but" type="submit" name="login">Login <i class="fa-solid fa-right-to-bracket"></i></button>
                    <button class="but" type="button" onclick="showSection('register')">Register <i class="fa-solid fa-address-card"></i></button>
                </form>
            </section>

            <!-- Registration Section -->
            <section id="register" class="hidden">
                <h2>Register <i class="fa-solid fa-address-card"></i></h2>
                <form id="register-form" method="post" enctype="multipart/form-data">
                    <input class="in" type="text" id="reg-username" name="reg_username" placeholder="Username" required>
                    <input class="in" type="password" id="reg-password" name="reg_password" placeholder="Password" required>
                    <input class="in block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" type="file" id="reg-photo" name="reg_photo" accept="image/*" required>
                    <input class="in " type="text" id="referrer" name="referrer" placeholder="Referrer Username (optional)">
                    <button class="but" type="submit" name="register">Register <i class="fa-solid fa-address-card"></i></button>
                    <button class="but" type="button" onclick="showSection('login')">Login <i class="fa-solid fa-right-to-bracket"></i></button>
                </form>
            </section>
        <?php else: ?>
            <!-- Home Section -->
            <section id="home">
                <h2>Welcome <i class="fa-solid fa-door-open"></i>, <?php echo $_SESSION['username']; ?>!</h2>
                <div class="grid grid-cols-7 divide-x flex items-center justify-center">
                    <div><a href="https://youtube.com/@earnify321?si=UxHi3wmC4dIMWg4l" target="_blank"><i class="fa-brands fa-youtube"></i></a></div>
                    <div><a href="https://www.facebook.com/profile.php?id=61562863912740" target="_blank"><i class="fa-brands fa-facebook"></i></a></div>
                    <div><a href="https://t.me/earnify321" target="_blank"><i class="fa-brands fa-telegram"></i></a></div>
                    <div><a href="#" target="_blank"><i class="fa-solid fa-phone"></i></a></div>
                    <div><a href="https://wa.me/message/2VAQEZPK4BCSN1" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></div>
                    <div><a href="#" target="_blank"><i class="fa-brands fa-tiktok"></i></a></div>
                </div>
                <br>
                <div class="1st" id="1stimg">
                    <!-- Any additional content -->
                </div>
                <div class="1st" id="1sttxt" align="center">
                    EarniFy- The World Of Income Money From Online
                </div>
                <br>
                <button class="wheelfy"><a href="https://earnifypages321.blogspot.com/">WheeliFy <i class="fa-solid fa-compass"></i></a></button>
            </section>

            <!-- Tasks Section -->
            <section id="tasks" class="hidden">
                <h2>Tasks</h2>
                <div class="task">
                    <button id="task1" onclick="completeTask(1)">Task 1 - Visit Facebook</button>
                </div>
                <div class="task">
                    <button id="task2" onclick="completeTask(2)">Task 2 - Visit Google</button>
                </div>
                <div class="task">
                    <button id="task3" onclick="completeTask(3)">Task 3 - Visit Microsoft</button>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile" class="hidden">
                <h2>Profile</h2>
                <img id="profile-photo" class="profile-photo" src="uploads/<?php echo $_SESSION['photo']; ?>" alt="Profile Photo">
                <div class="profile-info">
                    <p>Username <i class="fa-solid fa-signature"></i>: <span id="profile-username"><?php echo $_SESSION['username']; ?></span></p>
                    <p>Points <i class="fa-solid fa-coins"></i>: <span id="profile-points">1000</span></p>
                </div>
                <div id="referral-link">Referral Link: <span id="ref-link">your-referral-link-here</span></div><br>
                <div class="wit">
                    <div class="sorto">
                        <i class="fa-solid fa-money-bill-transfer"></i><br>
                        *শর্ত প্রযোজ্য<br>
                        1. পয়েন্ট 1000 বা তার বেশি হতে হবে<br>
                        2, প্রতি 1000 কয়েন এ 10 টাকা পাবেন <br>
                        3. যেকোনো সমস্যা হলে টেলিগ্রাম এ মেসেজ দিবেন 
                    </div>
                    <button class="but"><a href="https://earnifywithdraw.blogspot.com/?m=1">Withdraw <i class="fa-solid fa-money-bill-transfer"></i></a></button>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <footer>
        <p><i class="fa-solid fa-copyright"></i>2024 EarniFy. All rights reserved.</p>
    </footer>

    <!-- External JavaScript File -->
    <script src="index.js"></script>

    <script>
        // Function to show/hide sections
        function showSection(section) {
            document.getElementById('home').style.display = 'none';
            document.getElementById('tasks').style.display = 'none';
            document.getElementById('profile').style.display = 'none';
            document.getElementById('login').style.display = 'none';
            document.getElementById('register').style.display = 'none';

            document.getElementById(section).style.display = 'block';
        }

        // Show login section by default if user is not logged in
        <?php if (!isset($_SESSION['username'])): ?>
        showSection('login');
        <?php else: ?>
        showSection('home');
        <?php endif; ?>
    </script>
</body>
</html>
