<?php 
session_start();

$conn = new mysqli("localhost","root","","wolfmania");
$msg="";

if(isset($_POST['login'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	$password = sha1($password);
	$userType = $_POST['userType'];

	$sql = "SELECT * FROM users WHERE username=? AND password=?
	AND user_type=?";

	$stmt=$conn->prepare($sql);
	$stmt->bind_param("sss",$username,$password,$userType);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	session_regenerate_id();

	$_SESSION['username'] = $row['username'];
	$_SESSION['role'] = $row['user_type'];
	session_write_close();

	if($result->num_rows==1 && $_SESSION['role']=="student"){
		header("location:student/student.php");
	}

	else if($result->num_rows==1 && $_SESSION['role']=="teacher"){
		header("location:teacher/teacher.php");
	}

	else if($result->num_rows==1 && $_SESSION['role']=="admin"){
		header("location:admin/admin.php");
	}

	else{
		$msg = "Username or Password is Incorrect";
	}


}

 ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">


	<title>Kaims Portal</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>


<body> 

<img src="k2.png" alt="logo">

<div class="container">
<div class="row justify-content-center">
<div class="col-lg-5 bg-light mt-5 px-0">
	<h3 class="text-center text-light bg-info p-3">User Login</h3>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="p-4">
		<div class="form-group">
			<input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
		</div>
		<div class="form-group">
			<input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
		</div>
		<div class="form-group lead">
			<label for="usertype">I'm a</label>
			<input type="radio" name="userType" value="student" class="custom-radio" required>&nbsp;Student |
			<input type="radio" name="userType" value="teacher" class="custom-radio" required>&nbsp;Teacher |
			<input type="radio" name="userType" value="admin" class="custom-radio" required>&nbsp;Admin |
		</div>
		<div class="form-group">
			<input type="submit" name="login" class="btn btn-info btn-block">
		</div> 
		<h5 class="text-danger text-center"><?= $msg; ?></h5>
	</form>
</div>
</div>
</div>  

</body>
</html>