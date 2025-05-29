<?php 
	
	if (isset($_POST['submit'])) {
		
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		extract($_POST);

		$flag = true;

/*---------------------Pattern---------------------------*/

	 $alpha_pattern ='/^[A-Z]{1}[a-z]{2,}$/';
	 $email_pattern ='/^[a-z]+\d*[@]{1}[a-z]+[.]{1}(com|net|org){1}$/'; //example123@gmail.com
	 $phone_number_pattern ='/^(92){1}\d{3}-\d{7}$/';
	 $cnic_number_pattern ='/^[0-9]{5}-{1}\d{7}-{1}\d{1}$/';

/*---------------------Pattern---------------------------*/


/*-------Variabales Error msges---------------*/

	$first_name_msg = null;
	$last_name_msg = null;
	$email_msg = null;
	$phone_number_msg = null;
	$cnic_number_msg = null;
	$gender_msg = null;
	$country_msg = null;
	$policies_msg = null;

/*-------Variabales Error msges---------------*/

	
/*-------First Name---------------*/

	if ($first_name ==="") {
		$flag = false;
		$first_name_msg = "Required Field";
	}else{
		$first_name_msg = "";
		if (!(preg_match($alpha_pattern, $first_name))) {
			$flag = false;
			$first_name_msg = "Pattern Must Be Like eg: Ahmed";
		}
	}

/*-------First Name---------------*/


/*-------Last Name---------------*/

	if ($last_name ==="") {
		$flag = false;
		$last_name_msg = "Required Field";
	}else{
		$last_name_msg = "";
		if (!(preg_match($alpha_pattern, $last_name))) {
			$flag = false;
			$last_name_msg = "Pattern Must Be Like eg: Khan";
		}
	}

/*-------Last Name---------------*/


/*-------email---------------*/

	if ($email ==="") {
		$flag = false;
		$email_msg = "Required Field";
	}else{
		$email_msg = "";
		if (!(preg_match($email_pattern, $email))) {
			$flag = false;
			$email_msg = "Pattern Must Be Like eg: ahmed123@gmail.com | ahmed@gmail.com";
		}
	}

/*-------email---------------*/



/*-------Phone Number---------------*/

	if ($phone_number ==="") {
		$flag = false;
		$phone_number_msg = "Required Field";
	}else{
		$phone_number_msg = "";
		if (!(preg_match($phone_number_pattern, $phone_number))) {
			$flag = false;
			$phone_number_msg = "Pattern Must Be Like eg:92303-1234567";
		}
	}

/*-------Phone Number---------------*/


/*-------Cnic Number---------------*/

	if ($cnic_number ==="") {
		$flag = false;
		$cnic_number_msg = "Required Field";
	}else{
		$cnic_number_msg = "";
		if (!(preg_match($cnic_number_pattern, $cnic_number))) {
			$flag = false;
			$cnic_number_msg = "Pattern Must Be Like eg:41303-9901563-7";
		}
	}

/*-------Cnic Number---------------*/


/*-------Gender---------------*/

	if (!isset($gender)) {
		$flag = false;
		$gender_msg = "Field Required";
	}else{
		$gender_msg ="";
	}

/*-------Gender---------------*/


/*-------country---------------*/

	if ($country=="") {
		$flag = false;
		$country_msg = "Field Required";
	}else{
		$country_msg ="";
	}

/*-------country---------------*/



/*-------policies---------------*/
	
	if (!isset($policies)) {
		$flag = false;
		$policies_msg = "Required Field";

	}else{
		if (count($policies)!== 4) {
			$flag = false;
		    $policies_msg = "Required Field";
	}else{
			$policies_msg = "";
		}
	}	
	
	
/*-------policies---------------*/

		if ($flag === true) {
			// echo "Form Ok";
			show_data($_POST);
		}

	}


	function show_data($data){
		extract($data);
		?>
		<h1 style="text-align: center; color: #f00;">..:: Validate Data ::..</h1>
		<br/><hr/>
		<table border="1" cellpadding="10" cellspacing="5" align="center">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
				<th>Phone Number</th>
				<th>Cnic Number</th>
				<th>Gender</th>
				<th>Country</th>
				<th>Policies</th>
			</tr>
			<tr>
				<td><?= $first_name; ?></td>
				<td><?= $last_name ;?></td>
				<td><?= $email ;?></td>
				<td><?= $phone_number; ?></td>
				<td><?= $cnic_number; ?></td>
				<td><?= $gender;?></td>
				<td><?= $country; ?></td>
				<td>
					<?php
					     foreach ($policies as $key => $policy) {
					     	echo $policy.""."<br/>";
					     }
					?>
				</td>

			</tr>
		</table>


		<?php
		die;
	}






 ?>