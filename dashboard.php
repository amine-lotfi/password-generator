<?php require 'config/db_connect.php'; ?>
<?php require './includes/functions.php'; ?>
<?php require './includes/header.php'; ?>

<?php
$social = $generated_password = '';
$saved_passwords = [];

try {
    if (isset($_POST['generate'])) {
        if (empty($_POST['socials'])) {
            echo '<script>alert("Enter the correct info!")</script>';
        } else {
            $social = htmlspecialchars($_POST['socials']);
            $generated_password = bin2hex(random_bytes(40));

            $stmt = $conn->prepare("INSERT INTO `passwords` (`social`, `password`, `user_id`) VALUES (?, ?, ?)");
            $stmt->bind_param('ssi', $social, $generated_password, $_SESSION['user-id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo '<script>alert("Password has been successfully generated and stored!")</script>';
            } else {
                echo "<script>alert('Oops! Something went wront! '" . $stmt->error . ")</script>";
            }
        }
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
<title>PassGEN | Dashboard</title>
<h1 class="mb-3 text-center">Dashboard</h1>
<h5 class="mb-5 text-center">Hello, <?php echo !empty($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>!</h5>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <div class="mb-3">
        <label class="form-label select-label"
            for="socials">Socials</label>
        <select id="socials" name="socials" class="select form-select">
            <option value="1" disabled selected>Socials</option>
            <option value="Facebook">Facebook</option>
            <option value="Instagram">Instagram</option>
            <option value="Twitter">Twitter</option>
            <option value="LinkedIn">LinkedIn</option>
            <option value="TikTok">TikTok</option>
            <option value="Pinterest">Pinterest</option>
            <option value="Snapchat">Snapchat</option>
            <option value="Reddit">Reddit</option>
            <option value="Tumblr">Tumblr</option>
            <option value="WeChat">WeChat</option>
            <option value="Discord">Discord</option>
            <option value="Slack">Slack</option>
            <option value="Telegram">Telegram</option>
            <option value="Vimeo">Vimeo</option>
        </select>
    </div>
    <div class="row text-center mt-5">
        <div class="col-md-12">
            <button name="generate" type="submit" class="btn btn-warning">Generate & store</button>

            <a href="passwords.php" class="btn text-light">Your passwords</a>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="row text-center mt-5">
                    <div class="col-md-12">
                        <button name="signout" type="submit" class="btn btn-danger">Sign out</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>

<?php require './includes/footer.php'; ?>