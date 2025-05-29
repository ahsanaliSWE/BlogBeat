<?php
	require_once("serverside_validation.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>..:: Client Side Validation ::..</title>
	<style>
		span{
			color: red;
		}
	</style>
	<script src="client_side_validation.js"></script>
</head>
<body>
<center>
		<h1 style="color: red;">..:: Client Side Validation ::..</h1>
		<hr/>

		<fieldset>
			<legend style="color:red;">Form Validation</legend>
			<form action="" method="POST" onsubmit="return validation()">
				<table border="0" cellpadding="10">
					<tr>
						<th><label>First Name : <span>*</span></label></th>
						<td>
							<input id="first_name" type="text" name="first_name" value="<?= $first_name??""; ?>">
							<span id="first_name_msg"><?= $first_name_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Last Name : <span>*</span></label></th>
						<td>
							<input id="last_name" type="text" name="last_name" value="<?= $last_name??""; ?>">
							<span id="last_name_msg"><?= $last_name_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Email : <span>*</span></label></th>
						<td>
							<input id="email" type="email" name="email" value="<?= $email??""; ?>">
							<span id="email_msg"><?= $email_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Phone Number : <span>*</span></label></th>
						<td>
							<input id="phone_number" type="text" name="phone_number" value="<?= $phone_number??""; ?>">
							<span id="phone_number_msg"><?= $phone_number_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Cnic Number : <span>*</span></label></th>
						<td>
							<input id="cnic_number" type="text" name="cnic_number" value="<?= $cnic_number??""; ?>">
							<span id="cnic_number_msg"><?= $cnic_number_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Gender : <span>*</span></label></th>
						<td>
							<input id="gender" type="radio" name="gender" value="Male" <?php echo(isset($gender) && $gender == 'Male')?"checked":"";  ?> >Male
							<input id="gender" type="radio" name="gender" value="Female" <?php echo(isset($gender) && $gender == 'Female')?"checked":"";   ?> >Female
							<span id="gender_msg"><?= $gender_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Country : <span>*</span></label></th>
						<td>
							<select name="country" id="country">
								<option value="">-------Select Country--------</option>
								<option value="Pak" <?php echo (isset($country) && $country == "Pak") ? "selected":"";  ?>>Pak</option>
								<option value="Aus" <?php echo (isset($country) && $country == "Aus") ? "selected":"";  ?>>Aus</option>
								<option value="Eng" <?php echo (isset($country) && $country == "Eng") ? "selected":"";  ?>>Eng</option>			
							</select>
							<span id="country_msg"><?= $country_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<th><label>Policies : <span>*</span></label></th>
						<td>
							<input class="policies" type="checkbox" name="policies[]" value="Assignment">Assignment
							<br/>
							<input class="policies" type="checkbox" name="policies[]" value="Attendancene">Attendance
							<br/>
							<input class="policies" type="checkbox" name="policies[]" value="Test">Test
							<br/>
							<input class="policies" type="checkbox" name="policies[]" value="Stipend">Stipend
							<span id="policies_msg"><?= $policies_msg??""; ?></span>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
							<input type="submit" name="submit" value="Register">
							<input type="reset" name="Reset" value="Cancel">

						</td>
					</tr>
				</table>
			</form>
		</fieldset>
	
</center>
</body>
</html>