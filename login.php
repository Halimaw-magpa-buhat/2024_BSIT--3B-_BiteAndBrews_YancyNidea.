<?php
session_start();
include('server/connection.php');

// Function to verify user credentials
function verify_user($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT user_id, fname, lname, user_email, user_password FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);

    if($stmt->execute()) {
        $stmt->bind_result($user_id, $fname, $lname, $user_email, $hashed_user_password);
        $stmt->store_result();
        if($stmt->num_rows() == 1) {
            $stmt->fetch();
            if (password_verify($password, $hashed_user_password)) {
                return [
                    'user_id' => $user_id,
                    'fname' => $fname,
                    'lname' => $lname,
                    'user_email' => $user_email
                ];
            }
        }
    }
    return false;
}

// If the user is already logged in, redirect to the account page
if(isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit();
}

// Handle login form submission
if(isset($_POST['login_btn'])) {
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // Verify user credentials
    $user = verify_user($conn, $user_email, $user_password);

    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['fname'] = $user['fname'];
        $_SESSION['lname'] = $user['lname'];
        $_SESSION['user_email'] = $user['user_email'];
        $_SESSION['logged_in'] = true;

        header('location:account.php?login_success=Logged in successfully');
    } else {
        header('location:login.php?error=Could not verify your account');
    }
}
?>
<?php include('layouts/header.php');?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2>Login</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="login-form" method="POST" action="login.php">
            <p style="color:red" class="text-center"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
            <p style="color:red" class="text-center"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" id="login-email" name="user_email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="login-password" name="user_password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login">
            </div>
            <div class="form-group">
                <a href="register.php" id="register-url" class="btn">Don't have account? Register</a>
            </div>
            <div class="form-group">
                <a href="change_password.php" id="register-url" class="btn">Forgot Password?</a>
            </div>
        </form>
    </div>
</section>

<?php include('layouts/footer.php');?>
