<!DOCTYPE html>
<html lang="en">
<?php
require_once 'inc/inc.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']->isAdmin() || $_SESSION['user']->isBanned()) {
  header('Location: login.php');
  exit;
}

$previousLocation = $_SESSION['previous-location'];
$previousAction = $_SESSION['action'];
$_SESSION['action'] = 'adduser';
$_SESSION['status'] = 'none';
$_SESSION['previous-location'] = $_SERVER['REQUEST_URI'];

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['type'])) {
  $result = Manager::addUser($_POST['username'], $_POST['password'], $_POST['type']);
  if ($result == 'success') {
    $_SESSION['status'] = 'success';
  } elseif ($result == 'taken') {
    $_SESSION['status'] = 'taken';
  } else {
    $_SESSION['status'] = 'error';
  }
}
$status = $_SESSION['status'];
?>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/template.css">
    <script src="js/jquery-3.3.1.min.js"></script>
    <title>Add user</title>
  </head>

  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- NAVIGATION -->
        <div class="col-sm-2 bg-light" id="navbar">
          <nav class="nav flex-column">
            <a class="nav-link navitem selected" href="#">Add User</a>
            <a class="nav-link navitem" href="bannedusers.php">Banned Users</a>
            <a class="nav-link navitem" href="userlist.php">User List</a>
            <a class="nav-link navitem" href="#" data-toggle="modal" data-target="#logoutmodal">Logout</a>
          </nav>
        </div>
        <div class="col-sm-10" id="content">
          <!-- FORM FOR ADDING USERS -->
          <form class="mt-2" action="adduser.php" method="POST">
            <div class="form-group">
              <label for="usernameInput">Username</label>
              <input type="username" class="form-control" name="username" id="usernameInput" placeholder="Enter Username">
            </div>
            <div class="form-group">
              <label for="passwordInput">Password</label>
              <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Password">
            </div>
            <div class="form-group">
              <label for="typeInput">Type</label>
              <input type="text" class="form-control" name="type" placeholder="Type">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <div class="alert alert-primary mt-2" role="alert">
              Types are custom, but to make an admin account type in 100. Types must be integers!
            </div>
            <!-- STATUS MESSAGES -->
            <?php
            if ($previousAction == 'adduser') {
              if ($status == 'success') {
                echo '<div class="alert alert-success mt-2" role="alert">Successfully added an user!</div>';
              } elseif ($status == 'taken') {
                echo '<div class="alert alert-danger mt-2" role="alert">The username is taken!</div>';
              } elseif ($status == 'error') {
                echo '<div class="alert alert-danger mt-2" role="alert">Unknown error!</div>';
              }
            }
            ?>
          </form>
          <!-- LOGOUT MODAL -->
          <div class="modal fade" id="logoutmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Ban User</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Do you really want to logout?
                </div>
                <div class="modal-footer">
                  <form action="logout.php" method="post">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input class="btn btn-danger" type="submit" value="Logout">
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
  </body>

</html>