<?php
include_once("../config.php");
include_once("dbopen.php");
header('Content-Type: application/json');
echo '{';
?>
<?php
if(isset($_REQUEST['username']) && isset($_REQUEST['password']))
{
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];

	$loginquery=mysqli_query($con,"select * from userprofile where username='".$username."' and password='".$password."'");
	$loginrow = mysqli_fetch_assoc($loginquery);
	$logincount = mysqli_num_rows($loginquery);

	if($logincount > 0)
	{
		$userid=$loginrow['userid'];
		$status='Y';
		
		$logquery=mysqli_query($con,"select * from log where userid='".$userid."'");
		$logrow = mysqli_fetch_assoc($logquery);
		$logcount =mysqli_num_rows($logquery);
		
		if($logcount > 0)
		{
//			if( strtoupper($logrow["status"]) == "Y")
//			{
//				echo '"code" : 504,';
//				echo '"status" : "User already logged in. Please close your previous session and try again."';
//			}
//			else
//			{
				$updatelog=mysqli_query($con,"update log set status='".$status."' where userid='".$userid."'");

					$_SESSION['userid'] = $loginrow['userid'];
					$_SESSION['username'] = $loginrow['username'];
					
					echo '"code" : 200,';
					echo '"status" : "Login successful!!",';
					echo '"user" : {';
					echo ' 	"userId" : "'.$loginrow['userid'].'",';
					echo '	"username" : "'.$loginrow['username'].'",';					
					echo '	"email" : "'.$loginrow['email'].'",';
					echo '	"gender" : "'.$loginrow['gender'].'",';
					echo '	"country" : "'.$loginrow['country'].'"';
					echo '}';
//			}
		}
		else
		{
			if(!mysqli_query($con,"INSERT INTO log (userid, logtime, status)VALUES('".$userid."', now(), '".$status."')"))
			{
				echo '"code" : 505,';
				echo '"status" : "Internal Error. Please reach system administrator at '.WEBMASTER.'"';					
			}
			else
			{
				$_SESSION['userid'] = $loginrow['userid'];
				$_SESSION['username'] = $loginrow['username'];
				
				echo '"code" : 200,';
				echo '"status" : "Login successful!!",';
				echo '"user" : {';
				echo ' 	"userId" : "'.$loginrow['userid'].'",';
				echo '	"username" : "'.$loginrow['username'].'",';					
				echo '	"email" : "'.$loginrow['email'].'",';
				echo '	"gender" : "'.$loginrow['gender'].'",';
				echo '	"country" : "'.$loginrow['country'].'"';
				echo '}';
			}
		}
	}
	else
	{
		echo '"code" : 503,';
		echo '"status" : "Invalid Username or Password. Please check your credentials."';
	}
}
else
{
	echo '"code" : 502,';
	echo '"status" : "Invalid Login Request. Please check the details."';	
}
?>
<?php
echo '}';
include_once("dbclose.php");
?>