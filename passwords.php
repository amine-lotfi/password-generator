<?php require './config/db_connect.php'; ?>
<?php require './includes/functions.php'; ?>
<?php require './includes/header.php'; ?>

<?php
$saved_passwords = [];

try {
    if (!empty($_SESSION['user-id'])) {
        $stmt = $conn->prepare("SELECT `social`, `password`, `created_at` FROM `passwords` WHERE `user_id` = ?");
        $stmt->bind_param('i', $_SESSION['user-id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // bind the result to the variables
            $stmt->bind_result($social, $password, $created_at);
            // Fetch each row and add it to the array
            while ($stmt->fetch()) {
                $passwords[] = [
                    'social' => $social,
                    'password' => $password,
                    'created_at' => $created_at
                ];
            }
        }
    } else {
        echo '<script>alert("Hmm..")</script>';
    }
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
} finally {
    if (isset($stmt) && $stmt !== null) {
        $stmt->close();
    }
    $conn->close();
}
?>

<title>PassGEN | Passwords</title>
<h1 class="mb-3 text-center">Your passwords</h1>
<h5 class="mb-5 text-center">Hello, <?php echo !empty($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>!</h5>

<h5 class="mt-5 mb-3 text-center">Your passwords:</h3>
    <div class="mb-3">
        <p class="lead fs-6">
            <?php if (!empty($passwords)): ?>
                <?php foreach ($passwords as $password): ?>
                    <?php echo htmlspecialchars($password['social']); ?>
                    <i class="fa-solid fa-clock"></i>
                    <?php echo htmlspecialchars($password['created_at']); ?>
                    <i class="fa-solid fa-key"></i>
                    <?php echo htmlspecialchars($password['password']); ?> </br>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php echo empty($passwords) ? 'There are no saved passwords' : ''; ?>
        </p>
    </div>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="row text-center mt-5">
            <div class="col-md-12">
                <button name="signout" type="submit" class="btn btn-danger">Sign out</button>
            </div>
        </div>
    </form>


    <?php require './includes/footer.php'; ?>