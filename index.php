<?php require './config/db_connect.php'; ?>
<?php require './includes/header.php'; ?>

<?php
$username = $password = '';

try {

    if (isset($_POST['signin'])) {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            echo '<script>alert("Enter your username and password!")</script>';
        } else {

            $username = htmlspecialchars(trim($_POST['username']));
            $password = htmlspecialchars($_POST['password']);

            $stmt = $conn->prepare("SELECT `id`, `password` FROM `users` WHERE `username` = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // starting a session if the password is correct
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['user-id'] = $id;

                    // fix javascript alert box not showing on php after header redirect
                    echo "<script>alert('You have been signed in successfully!');
                    window.location.href='dashboard.php';
                    </script>";
                    exit();
                } else {
                    echo '<script>alert("Password incorrect!")</script>';
                }
            } else {
                echo '<script>alert("User doesnt exist!")</script>';
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
<title>PassGEN | Sign in</title>

<h1 class="mb-2 text-center">Password Generator</h1>
<h4 class="mb-5 text-center">Sign in</h4>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input name="username" type="text" class="form-control" id="username"
            placeholder="Enter your username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password"
            placeholder="Enter your password" required>
    </div>
    <div class="row text-center mt-5">
        <div class="col-md-6">
            <button name="signin" type="submit" class="btn btn-warning">Sign in</button>
        </div>
        <div class="col-md-6">
            <a href="make-account.php" class="btn text-light">Make account</a>
        </div>
    </div>
</form>

<?php require './includes/footer.php'; ?>