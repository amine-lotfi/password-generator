<?php require 'config/db_connect.php'; ?>
<?php require './includes/header.php'; ?>

<?php
$username = $email = $password = $confirmed_password = '';

try {

    if (isset($_POST['signup'])) {
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['con-password'])) {
            echo '<script>alert("Enter the required info!")</script>';
        } else {
            $username = htmlspecialchars(trim($_POST['username']));
            $email = htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $password = htmlspecialchars($_POST['password']);
            $confirmed_password = htmlspecialchars($_POST['con-password']);

            if (strcmp($password, $confirmed_password) !== 0) {
                echo '<script>alert("Passwords don\'t match")</script>';
            } else {
                // hashing the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $username, $email, $hashed_password);
                if ($stmt->execute()) {

                    // to fix javascript alert box not showing on php after header redirect
                    echo "<script>alert('Account has been created');
                    window.location.href='index.php';
                    </script>";
                } else {
                    echo '<script>alert("Oops! Something went wrong!")</script>';
                }
            }
        }
    }
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
// close the statement and connection even if the exit() is called
finally {
    // checking if the $stmt is declared and not null to avoid the undefined variable fatal error
    if (isset($stmt) && $stmt !== null) {
        $stmt->close();
    }
    $conn->close();
}
?>
<title>PassGEN | Sign up</title>
<h1 class="mb-2 text-center">Password Generator</h1>
<h4 class="mb-5 text-center">Sign up</h4>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="mb-4">
        <label for="username" class="form-label">Username</label>
        <input name="username" type="text" class="form-control" id="username" placeholder="Choose your username" required>
    </div>
    <div class="mb-4">
        <label for="email" class="form-label">Email</label>
        <input name="email" type="email" class="form-control" id="email" placeholder="Enter your email" required>
    </div>
    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Enter your password"
            required>
    </div>
    <div class="mb-5">
        <input name="con-password" type="password" class="form-control" id="password"
            placeholder="Confirm your password" required>
    </div>
    <button name="signup" type="submit" class="btn btn-warning">Sign up</button>
    </div>
</form>

<?php require './includes/footer.php'; ?>