<?php
  require_once('vincludes/load.php');
  require_once("database.php");
/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}
/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
function require_login() {
  global $session;
  if (!$session->isUserLoggedIn()) {
      header("Location: admin-login.php");
      exit;
  }
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
function find_by_column($table, $column, $value)
{
    global $db;
    if (tableExists($table)) {
        $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE {$db->escape($column)}='{$db->escape($value)}'");

        // Fetch all rows as an associative array
        $rows = [];
        while ($row = $db->fetch_assoc($sql)) {
            $rows[] = $row; // Add each row to the array
        }

        // Return the array of rows (even if empty)
        return $rows;
    }
    return []; // Return an empty array if the table doesn't exist
}

/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}

function delete_by_id_sp($table, $column, $id) {
  global $db;
  if (tableExists($table)) {
      $sql = "DELETE FROM " . $db->escape($table);
      $sql .= " WHERE " . $db->escape($column) . "=" . $db->escape($id);
      $sql .= " LIMIT 1";
      $db->query($sql);
      return ($db->affected_rows() === 1) ? true : false;
  }
  return false;
}

/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
function count_by_id_mem($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(member_id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
function count_by_id_ins($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(instructor_id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('tbl_users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','This level user has been band!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
function join_product_table(){
  global $db;
  $sql  = "SELECT p.id, p.name, p.item_code, p.description, 
                  p.buy_price, p.sale_price, p.media_id, p.date,
                  b.id AS batch_id, 
                  c.name AS categorie, 
                  m.file_name AS image, 
                  u.name AS uom_name, 
                  
                  p.batch_number, 
                  COALESCE(SUM(b.batch_quantity), 0) AS quantity";  // Calculate total quantity from batches
  
  $sql .= " FROM products p";
  $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql .= " LEFT JOIN uom u ON u.id = p.uom_id"; // Join the uom table
  $sql .= " LEFT JOIN batches b ON b.product_id = p.id";  // Join the batches table to get total quantity
  $sql .= " GROUP BY p.id, p.name, p.item_code, p.description, 
                    p.buy_price, p.sale_price, p.media_id, p.date, c.name, m.file_name, u.name, p.batch_number"; // Group by necessary fields
  $sql .= " ORDER BY p.id ASC, p.batch_number ASC";  // Order by batch_number to group batches together
  
  return find_by_sql($sql);
}

function join_product_table1(){
  global $db;
  $sql  = "SELECT p.id, p.name, p.item_code, p.description, 
                  b.batch_quantity AS quantity, b.expiration_date AS expiration_date, b.id AS batch_id, b.created_at AS product_batch, p.buy_price, p.sale_price, p.media_id, p.date, 
                  c.name AS categorie, 
                  m.file_name AS image, 
                  u.name AS uom_name, 
                  
                  p.batch_number";  // Fetching quantity and expiration_date from batches table
  
  $sql .= " FROM products p"; 
  $sql .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql .= " LEFT JOIN uom u ON u.id = p.uom_id"; // Join the uom table
  $sql .= " LEFT JOIN batches b ON b.product_id = p.id"; // Join the batches table to get quantity and expiration_date
  $sql .= " ORDER BY p.id ASC, p.batch_number ASC";  // Order by batch_number to group batches together
  return find_by_sql($sql);
}



/*----
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
 
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_highest_selling_product($limit) {
  global $db;
  // Select product name, category name, total sold, and total quantity
  $sql  = "SELECT p.name AS product_name, c.name AS category_name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= "LEFT JOIN categories c ON c.id = p.categorie_id "; // Join categories to get category names
  $sql .= "GROUP BY s.product_id ";
  $sql .= "ORDER BY SUM(s.qty) DESC LIMIT " . $db->escape((int)$limit);
  
  return $db->query($sql);
}

 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/



function join_pr_program() {
  global $db;

  // SQL query to join tbl_special_programs with tbl_add_instructors
  $sql = "SELECT p.program_id AS program_id, 
                   p.program_title, 
                   p.program_description, 
                   p.trainer_id, 
                   CONCAT(i.first_name, ' ', i.last_name) AS instructor_name
            FROM tbl_programs p
            LEFT JOIN tbl_add_instructors i ON i.instructor_id = p.trainer_id"; 
  $sql .= " ORDER BY  p.program_title ASC"; 
  return find_by_sql($sql);
}
// Function to get all orders with product and customer details
function get_all_orders() {
  global $db; // Assuming $db is your database connection object

  // SQL query to fetch all orders along with product name and member name
  $orders_query = "SELECT o.order_id, o.customer_id, o.product_id, o.quantity, o.payment_method, o.order_status, 
                          p.name AS product_name, c.first_name AS member_name
                   FROM orders o
                   JOIN products p ON o.product_id = p.id
                   JOIN tbl_add_members c ON o.customer_id = c.member_id";

  // Execute the query
  $result = $db->query($orders_query);

  // Check if the query was successful
  if ($result) {
      $orders = [];
      
      // Fetch all results and store them in an array
      while ($row = $result->fetch_assoc()) {
          $orders[] = $row;
      }
      
      return $orders; // Return the array of orders
  } else {
      // If the query failed, return an empty array
      return [];
  }
}


?>
