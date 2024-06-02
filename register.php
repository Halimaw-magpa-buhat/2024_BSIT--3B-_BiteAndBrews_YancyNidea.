<?php
include('layouts/header.php');
session_start();

include('server/connection.php');

// If user already created an account they can't enter the register form
if(isset($_SESSION['logged_in'])){
    header('location:account.php');
    exit;
}

if(isset($_POST['register'])){

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Confirm if password matches confirmPassword
    if($user_password !== $confirmPassword){
        header('location: register.php?error=Passwords don\'t match');
    } elseif(strlen($user_password) < 6) {
        header('location: register.php?error=Password should be at least 6 characters');
    } else {
        // Check whether there is a user with this email
        $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
        $stmt1->bind_param('s', $user_email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();
        
        if($num_rows != 0){
            header('location: register.php?error=User with this email already exists');
        } else {
            // Hash the password
            $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

            // Insert inputted data in the register form to the database
            $stmt = $conn->prepare("INSERT INTO users (fname, lname, user_email, user_password) VALUES(?,?,?,?)");
            $stmt->bind_param('ssss', $fname, $lname, $user_email, $hashed_password);

            if($stmt->execute()){
                // If account was created successfully
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['fname'] = $fname;
                $_SESSION['logged_in'] = true;
                header('location: account.php?register_success=You registered successfully');
            } else {
                header('location: register.php?error=Could not create an account at the moment');
            }
        }
    }
}
?>

<!-- Register -->
<section class="my-5 py-5">
  <div class="container text-center mt-3 pt-5">
    <h2 class="form-weight-bold">Register</h2>
    <hr class="mx-auto">
  </div>
  <div class="mx-auto container">
    <form id="register-form" method="POST" action="register.php">
      <!-- to get and display error -->
      <p style="color:red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>

      <div class="form-group">
        <label>First Name</label>
        <input type="text" class="form-control" id="register-name" name="fname" placeholder="First Name" required/>
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <input type="text" class="form-control" id="register-name" name="lname" placeholder="Last Name" required/>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" id="register-email" name="email" placeholder="Email" required/>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required/>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
      </div>
      <div class="form-group">
        <input type="submit" class="btn" id="register-btn" name="register" value="Register"/>
      </div>
      <div class="form-group">
        <a href="login.php" id="login-url" class="btn">Do you have an account? Login</a>
      </div>
    </form>
  </div>
</section>

<?php include('layouts/footer.php');?>
