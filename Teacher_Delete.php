<?php
	$con=mysqli_connect("localhost","root","","sm") or die("not connect");
	$user_id=$_GET['user_id'];

	$res=mysqli_query($con,"DELETE FROM users WHERE ID='".$id."'");
	if($res)
	{
		echo'
			<script>
				alert("Deleted");
				window.location.href="Manage_Teacher.php";
			</script>
		';
	}
	else
	{
		echo'
			<script>
				alert("Not Deleted");
				window.location.href="Manage_Teacher.php";
			</script>
		';
	}
?>