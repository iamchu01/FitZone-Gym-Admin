<?php
session_start();

class Session {

    public $msg;
    private $user_is_logged_in = false; // Default is false

    function __construct(){
        $this->flash_msg();
        $this->userLoginSetup();
        $this->redirectIfNotLoggedIn(); // Check login status on every page load
    }

    public function isUserLoggedIn(){
        return $this->user_is_logged_in;
    }

    public function login($user_id){
        $_SESSION['user_id'] = $user_id;
    }

    private function userLoginSetup()
    {
        if(isset($_SESSION['user_id']))
        {
            $this->user_is_logged_in = true;
        } else {
            $this->user_is_logged_in = false;
        }
    }

    public function logout(){
        unset($_SESSION['user_id']);
    }

    public function msg($type ='', $msg =''){
        if(!empty($msg)){
            if(strlen(trim($type)) == 1){
                $type = str_replace( array('d', 'i', 'w','s'), array('danger', 'info', 'warning','success'), $type );
            }
            $_SESSION['msg'][$type] = $msg;
        } else {
            return $this->msg;
        }
    }

    private function flash_msg(){
        if(isset($_SESSION['msg'])) {
            $this->msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        } else {
            $this->msg;
        }
    }

    // Add this method to redirect if user is not logged in
    private function redirectIfNotLoggedIn(){
      if (!$this->user_is_logged_in && basename($_SERVER['PHP_SELF']) !== 'admin-login.php') {
          header("Location: admin-login.php"); // Redirect to login if not logged in
          exit;
      }
  }
}

$session = new Session();
$msg = $session->msg();
?>
