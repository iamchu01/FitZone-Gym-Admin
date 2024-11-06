<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fit Zone - Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <style>
        
      body {
        background-color: rgba(0, 0, 0, 0.8);
        font-family: 'Oswald', sans-serif;
      }
      .login-container {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .login-card {
        background-color: rgba(0, 0, 0, 0.8);
        padding: 30px;
        border-radius: 10px;
        width: 400px;
        color: white;
        position: relative;
        z-index: 1000; 
      }
      .login-card h2 {
        text-align: center;
        color: #44d62c;
      }
      .login-card input {
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
      }
      .btn-login {
        background-color: #44d62c;
        color: white;
        width: 100%;
        font-weight: bold;
      }
      .login-link {
        text-align: center;
        margin-top: 20px;
      }
      .login-link a {
        color: #44d62c;
      }
    </style>
  </head>
  <body>

    <?php include 'layouts/offcanvas-menu.php'; ?>
    <!-- Offcanvas Menu Section End -->

    <!-- Header Section Begin -->
    <?php include 'layouts/header.php';?>
 
    <!-- Header End -->
    <div class="login-container">
      <div class="login-card">
        <h2>Login to Fit Zone</h2>
        <form>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Email" required />
          </div>
          <div class="form-group">
            <input type="password" class="form-control" placeholder="Password" required />
          </div>
          <button type="submit" class="btn btn-login">Login</button>
        </form>
        <div class="login-link">
          <p>Don't have an account? <a href="register.php">Register Here</a></p>
        </div>
      </div>
    </div>

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
