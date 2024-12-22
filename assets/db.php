<?php 
    $con = new mysqli('localhost','root','','mybank');
    define('bankname', 'UP Bank');
    $uid = '';
    if(isset($_SESSION['userId']))
    $uid = $_SESSION['userId'];
    if(isset($_SESSION['adminId']))
    $uid = $_SESSION['adminId'];
    $userData = [];
    if(!empty($uid)){
      $ar = $con->query("select * from userAccounts,branch where id = '{$uid}' AND userAccounts.branch = branch.branchId");
      $userData = $ar->fetch_assoc();
    }
?>
<script type="text/javascript">
	$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>