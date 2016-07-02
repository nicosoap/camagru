<?php
/**
 * Created by PhpStorm.
 * User: Olivier
 * Date: 02/07/2016
 * Time: 05:16
 */
if (isset($_POST["submit"]) && $_POST["submit"] === "SUBMIT!") {
    $password = hash("wirlpool", $_POST["passwd"]);
} else {
    ?>
  <form name="login" action="signin.php" method="post">
      <input type="text" name="login" placeholder="Login:" />
      <input type="password" name="passwd" placeholder="Password:" />
      <input type="submit" value="SUBMIT!" />
      <input type="checkbox" name="save" placeholder="remember me" />
      <a href="forgot.php"><span>forgot password</span></a>
  </form>
  <?php
}
?>