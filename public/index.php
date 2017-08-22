<?php
header('Content-Type:applicayion/json');
$dbhost="localhost";
$dbuser="root";
$dbpass="";
$dbname="lendi";

$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);		
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST))
{

	extract($_POST);
	if($submit!='login' && $submit!='register' && !empty($my_token)){
		$tokencorrect=verifytoken($conn,$my_token);

		if($tokencorrect==false){
			$result=array(
			"status"=>"401",
			"error"=>"token incorrect",
			"msg"=>"token mismatch"
			);
			echo json_encode($result);
		}	
	}else if($submit!='login' && $submit!='register' && empty($my_token)){
		$result=array(
			"status"=>"401",
			"error"=>"token not found",
			"msg"=>"Please send user token "
			);
		echo json_encode($result);
	}
	

	switch ($submit) {
		case 'login':
		login($conn,$_POST);
			break;

		case 'register':
		register($conn,$_POST);
			break;		

		case 'updateuser':
		updateuser($conn,$_POST);
			break;

		case 'object':
		saveobject($conn,$_POST);
			break;

		case 'request':
		saverequest($conn,$_POST);
			break;

		case 'requestphoto':
		requestphoto($conn,$_POST,$_FILES);
			break;

		case 'neighborrequest':
		neighborrequest($conn,$_POST);
			break;

		default:
			# code...
			break;		
	}
	
}

///////////   User Login ///////////////
function login($conn,$data){

	extract($data);
	if(empty($email) || empty($password)){
		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Please enter both email and password"
			);
		
	}else{
		$password=md5($password);
		$query=mysqli_query($conn,"select * from user where email='$email' and password='$password'");
		if(mysqli_num_rows($query)>0){
			$data=mysqli_fetch_assoc($query);
			$result=array(
				"status"=>"200",
				"msg"=>"success",
				"user"=>$data
				);	
		}else{
			$result=array(
			"status"=>"401",
			"error"=>"Login Failed",
			"msg"=>"Email or Password invalid"
			);
		}
		
	}
	echo json_encode($result);
}
/////////  User Register /////////////

function register($conn,$data){

	extract($data);
	if(empty($email) || empty($password) || empty($name) || empty($last_name) ||empty($lat) || empty($lng)){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Please fill all Fields"
			);
	}else{
		$isemailunique = checkemail($conn,$email);
		if($isemailunique==false){
			$result=array(
			"status"=>"401",
			"error"=>"Email Not Unique",
			"msg"=>"This email is already registry please use another one"
			);
		}else{
			$createddate=date('m-d-Y H:i:s.u');
			$my_token=md5($createddate);
			$password=md5($password);
			$query=mysqli_query($conn,"insert into user set first_name='$name', email='$email', last_name='$last_name',lat='$lat', lng='$lng',token='$my_token',createdat='$createddate',password='$password'");
			if($query){

				$result=array(
				"status"=>"200",
				"usertoken"=>$my_token
				);
			}else{

				$result=array(
				"status"=>"401",
				"error"=>"Error while insertion",
				"msg"=>"Something went wrong please try again"
				);
			}

		}
	}	
	echo json_encode($result);
}

/////////////// update user /////////////////////////////////////////

function updateuser($conn,$data){
	extract($data);

	if( empty($phone_primery) || empty($phone_secondry) || empty($name) || empty($last_name) ||empty($other_request_notification) || empty($my_request_notification)){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Please fill all Fields"
			);
	}else{
			$query=mysqli_query($conn,"update user set 
				first_name='$name',
				last_name='$last_name',
				phone_number_1='$phone_primery',
				phone='$phone_secondry',
				other_request_notification='$other_request_notification',
				my_request_notification='$my_request_notification' where token='$my_token'");
			if($query){

				$result=array(
				"status"=>"200",
				"usertoken"=>$my_token,
				"msg"=>"User record updated successfully",
				"user"=>$data
				);
			}else{

				$result=array(
				"status"=>"401",
				"error"=>"Error while updating",
				"msg"=>"Something went wrong please try again"
				);
			}
	}

	echo json_encode($result);

}

/////////////////// store request ///////////////////////////

function saverequest($conn,$data){
	extract($data);
	if(empty($object_name) || empty($why) || empty($state) ){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Please set object name"
			);
		echo json_encode($result);
	}else{	

			$query=mysqli_query($conn,"select id from user where token='$my_token'");
			if($query){
				$insertobject=mysqli_query($conn,"insert into object set name='$object_name' ");
				if($insertobject)
				{
					$user=mysqli_fetch_assoc($query);
					$userid=$user['id'];
					$objectid=mysqli_insert_id($conn);

			$createddate=date('m-d-Y H:i:s.u');
					$insertrequest=mysqli_query($conn,"insert into request set why='$why',object_id='$objectid',user_request_id='$userid', state='$state',created_at='$createddate'");
					if ($insertrequest) {
						$result=array(
							"status"=>"200",
							"result"=>'successfully inserted'
							);	
							echo json_encode($result);	
					}


				}
			}
		}
			
}

//////////////////  insert request photo ////////////////////

function requestphoto($conn,$data,$file){
	extract($data);
	if(count($file['image'])==0 || empty($request_id) ){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Request image or id is not found please send both "
			);
		echo json_encode($result);
	}else{
		$img=$_FILES['image'];
		$image=uploadimage($img);
		if($image==true){
				$query=mysqli_query($conn,"update request set 
				pic_url='images/".str_replace(" ","_",$img['name'])."' where id=$request_id");
			if($query){

				$result=array(
				"status"=>"200",
				"image_url"=>"images/".str_replace(" ","_",$img['name']),
				"msg"=>"Request image updated successfully"
				);
			}
			echo json_encode($result);
		}else{
			$result=array(
			"status"=>"401",
			"error"=>"upload failed",
			"msg"=>"File must be image of type jpeg or png "
			);
		echo json_encode($result);	
		}
	}
}

////////////////// get neighbor request ////////////////////

function neighborrequest($conn,$data){
	extract($data);

	if($page==null){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Page no not found"
			);
	}else{

		$query=mysqli_query($conn,"select * from user where token='$my_token'");
		$user=mysqli_fetch_assoc($query);
		$lat=$user['lat'];
		$lng=$user['lng'];
		$limitstart=30*$page;
		$limitend=$limitstart+30;
		/*$neighbors=mysqli_query($conn, " select id, (3959 * acos(cos(radians('".$lat."')) * cos(radians(lat)) * cos( radians(lng) - radians('".$lng."')) + sin(radians('".$lat."')) * sin(radians(lat)))) AS distance FROM user where token!='$my_token' having distance <= 10 ORDER BY distance DESC LIMIT $limitstart , $limitend ");*/

		$neighbors=mysqli_query($conn, "select `user`.*,`request`.*,`object`.`name` as object_name,(6371 * acos(cos(radians($lat)) * cos(radians(`user`.`lat`)) *
            cos(radians(`user`.`lng`)-radians($lng)) +
            sin(radians($lat)) * sin(radians(`user`.`lat`)))) as `distance` FROM `user` join `request` on `user`.`id`=`request`.`user_request_id`
            join `object` on `request`.`object_id`=`object`.`id`
             where `user`.`token`!='$my_token' HAVING `distance` <= 10 ORDER BY `distance`
			LIMIT $limitstart,$limitend");

		if($neighbors){
			while($data=mysqli_fetch_assoc($neighbors)){
				$request[]=$data;
			}
			$result=array(
			"status"=>"200",
			"All_neighbors_requests"=>$request
			);

		}else{
			echo mysqli_error($conn);
		}
			
		}
		echo json_encode($result);
	}


/////////////////// store object ////////////////////////////
/*
function saveobject($conn,$data){
	extract($data);

	if(empty($name)){

		$result=array(
			"status"=>"401",
			"error"=>"validation",
			"msg"=>"Please set object name"
			);
	}else{	
		$createddate=date('m-d-Y H:i:s.u');
			$my_token=md5($createddate);
			$password=md5($password);
			$query=mysqli_query($conn,"insert into user set first_name='$name', email='$email', last_name='$last_name',lat='$lat', lng='$lng',token='$my_token',createdat='$createddate',password='$password'");
			if($query){

				$result=array(
				"status"=>"200",
				"usertoken"=>$my_token
				);
			}else{

				$result=array(
				"status"=>"401",
				"error"=>"Error while insertion",
				"msg"=>"Something went wrong please try again"
				);
			}
}
*/		

///////// checking email to sure that email is unique /////////

function checkemail($conn, $email){

$query=mysqli_query($conn,"select email from user where email='$email'");
	if(mysqli_num_rows($query)>0){
		return false;
	}
	else{
		return true;
	}
}

//////////////  To sure that received token is valid ///////////////

function verifytoken($conn, $token){
$query=mysqli_query($conn,"select * from user where token='$token'");
	if(mysqli_num_rows($query)>0){
		return true;
	}
	else{
		return false;
	}
}

///////////// upload images ///////////////////////
function uploadimage($image){
	$move=false;
	$ext=$image['type'];
	if($ext=='image/jpeg' || $ext=='image/png'){
		$move=move_uploaded_file($image['tmp_name'], 'images/'.str_replace(" ","_",$image['name']));	
	}
	
	return $move;
}
?>