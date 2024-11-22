<?php include 'layouts/session.php'; ?> 
<?php include 'layouts/head-main.php'; ?>

<head>
  <title>Login - HRMS admin template</title>
  <?php require_once('vincludes/load.php'); ?>  
  <?php include 'layouts/title-meta.php'; ?>
  <?php include 'layouts/head-css.php'; ?>
</head>

<?php include 'layouts/body.php'; ?>

<!-- Main Wrapper -->
<div class="main-wrapper">
  <div class="account-content">
    <div class="container">

      <!-- Account Logo -->
      <div class="account-logo">
        <a href="#"><img src="assets/img/fzlogo.png" alt="Fitzone"></a>
      </div>
      <!-- /Account Logo -->

      <div class="account-box">
        <div class="account-wrapper">
          <h3 class="account-title">FITZONE GYM</h3>
          <p class="account-subtitle">ADMIN</p>

          <!-- Account Form -->
          <form action="backend-add-authenticate/admin-login-auth.php" method="post">
            <div class="form-group">
              <label>Username</label>
              <input class="form-control" type="text" name="username" required>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label>Password</label>
                </div>
                <div class="col-auto">
                  <a class="text-muted" href="forgot-password.php">
                    Forgot password?
                  </a>
                </div>
              </div>
              <div class="position-relative">
                <input class="form-control" type="password" name="password" id="password" required>
                <span class="fa fa-eye-slash" id="toggle-password"></span>
              </div>
            </div>
            <div class="form-group text-center">
              <button class="btn btn-primary account-btn" type="submit">Login</button>
            </div>
          </form>
          <!-- /Account Form -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Main Wrapper -->

<!-- Bootstrap Modal for Alerts -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alertModalLabel">Warning</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="alertModalBody">
        <!-- Message will be dynamically inserted here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<?php include 'layouts/vendor-scripts.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
      let message = '';
      if (params.get('error') === 'invalid_credentials') {
        message = 'Invalid username or password. Please try again.';
      } else if (params.get('error') === 'empty_fields') {
        message = 'Please fill in all required fields.';
      }

      if (message) {
        document.getElementById('alertModalBody').textContent = message;
        var modal = new bootstrap.Modal(document.getElementById('alertModal'));
        modal.show();
      }
    }
  });
</script>

</body>

</html>
