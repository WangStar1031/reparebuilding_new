<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="assets/css/auth.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="assets/js/jquery-1.9.1.min.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121704781-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-121704781-1');
</script>

<?php
require_once __DIR__ . "/dbManager.php";
global $db;

$UserEmail = "";
if( isset($_POST['UserEmail'])) $UserEmail = $_POST['UserEmail'];
if( $UserEmail != ""){
	$UserName = $_POST['UserName'];
	$Password = $_POST['password'];

	$sql = "UPDATE user SET UserName = ?, Password = ?, InviteUrl='' WHERE UserEmail = '$UserEmail'";
	$stmt= $db->prepare($sql);
	$stmt->execute([$UserName, $Password]);
?>
<div class="container">
<div class="confirm-registration">
	<div class="gotologin">
	<p>נרשמת בהצלחה</p>
	<a href="login.php">עבור אל התחברות</a>
	</div>
</div>
</div>
<?php
	exit();
}
$key = "";
if(isset($_GET['key'])) $key = $_GET['key'];
if( $key != ""){
	$sql = "SELECT * FROM user WHERE InviteUrl='$key'";
	$result = $db->select($sql);
	if( $result != false){
		$UserEmail = $result[0]['UserEmail'];
?>

<div class="registration-container">
<div class="registration-content">
	<h3>הרשמה</h3>
	<form method="post" onsubmit="return validateFormData()">
		<input type="hidden" name="UserEmail" value="<?=$UserEmail?>">
		<table>
			<tr class="">
				<td><?=$UserEmail?></td>
				<td class="registration-email reg-marg"><label>דוא"ל</label></td>				
			</tr>
			<tr>
				<td><input type="text" class="form-control" name="UserName"></td>
				<td class="registration-user reg-marg"><label>שם משתמש</label></td>				
			</tr>
			<tr>
				<td><input type="password" name="password"></td>
				<td class="registration-password reg-marg"><label>סיסמה</label></td>				
			</tr>
			<tr>
				<td><input type="password" name="confpass"></td>
				<td class="reg-marg"><label>אשר סיסמה</label></td>				
			</tr>
		</table>
		<button class="registration-send">שלח</button>
	</form>
</div>	
</div>
<style type="text/css">
	.required{
		border: 1px solid red;
	}
</style>
<script type="text/javascript">
	function validateFormData(){
		var userName = $("input[name=UserName]").val();
		if( !userName){
			$("input[name=UserName]").addClass("required");
			return false;
		}
		if( userName == "admin"){
			$("input[name=UserName]").addClass("required");
			return false;
		}
		var Password = $("input[name=password]").val();
		var confPass = $("input[name=confpass]").val();
		if( !Password){
			$("input[name=password]").addClass("required");
			return false;
		}
		if(!confPass){
			$("input[name=confpass]").addClass("required");
			return;
		}
		if( Password != confPass){
			$("input[name=password]").addClass("required");
			$("input[name=confpass]").addClass("required");
			return false;
		}
		return true;
	}
</script>
<?php
	} else{
		echo "Invalid key.";
	}
}
?>