<?
if (isset($_GET['pass']) && isset($_GET['salt'])) {
  echo "pass: " . $_GET['pass'];
  echo "<br />";
  echo "salt: " . $_GET['salt'];
  echo "<br />";
  echo "<br />";
  echo "generated password: " . md5( md5( $_GET['pass'] . md5( $_GET['salt'] ) ) );

}
?>