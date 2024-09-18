<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            echo "<p class='message success'>Registration successful!</p>";
        } else {
            echo "<p class='message error'>Error: " . $stmt->error . "</p>";
        }
    } elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                header("Location: form.php");
                exit();
            } else {
                echo "<p class='message error'>Invalid credentials.</p>";
            }
        } else {
            echo "<p class='message error'>No user found.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <img src="img/CV.png" alt="Brand Logo" class="navbar-brand-icon">
            </a>
            <button class="navbar-toggler" onclick="toggleDropdown()">
                <div class="bars">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </button>
            <div class="dropdown-menu" id="dropdown-menu">
                <form method="POST" class="form-group">
                    <h3>Login</h3>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
                <form method="POST" class="form-group">
                    <h3>Register</h3>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Register</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="header bg-bright">
    <div class="container">
        <div class="header-content text-center">
            <h6 class="text-uppercase text-blue-dark fs-14 fw-6 ls-1">Online Resume Builder</h6>
            <h1 class="lg-title">Only 2% of resumes make it past the first round. Be in the top 2%</h1>
            <button class="btn btn-primary text-uppercase mt-3" onclick="toggleDropdown()">Create My Resume</button>
            <img src="img/BAN.jpg" alt="Resume Templates" class="header-image" style="width: 100%; max-width: 100%; height: auto; margin: 20px auto; display: block; border-radius: 10px;">
        </div>
    </div>
</div>


    <<section class="section-one">
    <div class="container">
        <div class="section-one-content">
            <div class="section-one-l">
                <img src="img/BAN1.jpg" alt="Resume Templates" class="header-image">
            </div>
            <div class="section-one-r text-center">
                <h2 class="lg-title">Use the Best Resume Maker as Your Guide!</h2>
                <p class="text">Getting that dream job can seem like an impossible task. We're here to change that. Give yourself a real advantage with the best online resume maker: created by experts, improved by data, trusted by millions of professionals.</p>
                <div class="btn-group">
                <a href="https://www.youtube.com/watch?v=5uhmS8nzxM4" class="btn btn-primary text-uppercase" target="_blank" rel="noopener noreferrer">Watch Video</a>

                </div>
            </div>
        </div>
    </div>
</section>

    

    <footer class="footer">
    <div class="container">
        <div class="footer-content">
            <p>&copy; 2024 Your Company. All rights reserved.</p>
            <ul class="footer-links">
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </div>
</footer>


    <script>
        function toggleDropdown() {
            document.getElementById('dropdown-menu').classList.toggle('show');
        }
    </script>
</body>
</html>
