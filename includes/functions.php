<?php
session_start();
if (empty($_SESSION['username'])) {
    // to fix javascript alert box not showing on php after header redirect
    echo "<script>alert('You need to sign in!');
            window.location.href='index.php';
            </script>";
    exit;
}

if (isset($_POST['signout'])) {
    // unsett all session variables
    $_SESSION = [];

    // destroy the session
    session_destroy();

    header("Location: index.php");
    exit();
}
