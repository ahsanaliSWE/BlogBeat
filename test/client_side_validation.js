
function validation(){

var flag = true;
// var flag = false;

/*---------------------Pattern---------------------------*/

	var alpha_pattern =/^[A-Z]{1}[a-z]{2,}$/;
	var email_pattern =/^[a-z]+\d*[@]{1}[a-z]+[.]{1}(com|net|org){1}$/; //example123@gmail.com
	var phone_number_pattern =/^(92){1}\d{3}-\d{7}$/;
	var cnic_number_pattern =/^[0-9]{5}-{1}\d{7}-{1}\d{1}$/;

/*---------------------Pattern---------------------------*/



/*------------------Target Input Values-----------------------*/

	var first_name = document.querySelector("#first_name").value;
	var last_name = document.querySelector("#last_name").value;
	var email = document.querySelector("#email").value;
	var phone_number = document.querySelector("#phone_number").value;
	var cnic_number = document.querySelector("#cnic_number").value;
	var gender = document.querySelector("input[type='radio']:checked");
	var country = document.querySelector("#country").value;
	var policies = document.querySelectorAll(".policies");

	var policies_count = 0;

	for (var loop = 0; loop<policies.length ; loop++) {
		
		if (policies[loop].checked) {
			policies_count++;
		}
	}

		


	// console.log(first_name);
	// console.log(last_name);
/*------------------Target Input Values-----------------------*/


/*----------------Error Msg Span----------------------*/

	var first_name_msg = document.querySelector("#first_name_msg");
	var last_name_msg = document.querySelector("#last_name_msg");
	var email_msg = document.querySelector("#email_msg");
	var phone_number_msg = document.querySelector("#phone_number_msg");
	var cnic_number_msg = document.querySelector("#cnic_number_msg");
	var gender_msg = document.querySelector("#gender_msg");
	var country_msg = document.querySelector("#country_msg");
	var policies_msg = document.querySelector("#policies_msg");



/*----------------Error Msg Span----------------------*/



/*------------------First Name----------------------*/

	if (first_name ==="") {
		 flag = false;
		first_name_msg.innerHTML ="Field Required";
	}else{

		first_name_msg.innerHTML="";

		if (alpha_pattern.test(first_name)=== false) {
			 flag = false;
			first_name_msg.innerHTML ="Pattern Must Be Like eg: Ahmed";
		}
	}

/*------------------First Name----------------------*/



/*------------------Last Name----------------------*/

	if (last_name ==="") {
		 flag = false;
		last_name_msg.innerHTML ="Field Required";
	}else{
		
		last_name_msg.innerHTML="";

		if (alpha_pattern.test(last_name)=== false) {
			 flag = false;
			last_name_msg.innerHTML ="Pattern Must Be Like eg: Khan";
		}
	}

/*------------------Last Name----------------------*/



/*------------------Email----------------------*/

	if (email ==="") {
		 flag = false;
		 email_msg.innerHTML ="Field Required";
	}else{
		email_msg.innerHTML ="";
		if (email_pattern.test(email)=== false) {
			flag = false;
			email_msg.innerHTML ="Pattern Must Be Like eg: example@gmail.com | example123@gmail.com";
		}
	}


/*------------------Email----------------------*/



/*------------------phone----------------------*/

	if (phone_number ==="") {
		 flag = false;
		 phone_number_msg.innerHTML ="Field Required";
	}else{
		phone_number_msg.innerHTML ="";
		if (phone_number_pattern.test(phone_number)=== false) {
			flag = false;
			phone_number_msg.innerHTML ="Pattern Must Be Like eg: 92303-1234567";
		}
	}


/*------------------phone----------------------*/



/*------------------Cnic----------------------*/

	if (cnic_number ==="") {
		 flag = false;
		 cnic_number_msg.innerHTML ="Field Required";
	}else{
		cnic_number_msg.innerHTML ="";
		if (cnic_number_pattern.test(cnic_number)=== false) {
			flag = false;
			cnic_number_msg.innerHTML ="Pattern Must Be Like eg: 41303-1234567-1";
		}
	}


/*------------------cnic----------------------*/


/*------------------Gender---------------*/

	if (!gender) {
		flag = false;
		gender_msg.innerHTML ="Field Required";
	}else{
		gender_msg.innerHTML ="";

	}

/*------------------Gender---------------*/


/*------------------country---------------*/

	if (country ==="") {
		flag = false;
		country_msg.innerHTML ="Field Required";
	}else{
		country_msg.innerHTML ="";

	}

/*------------------country---------------*/

/*------------------Policies---------------*/

	if (policies_count !== policies.length) {
		flag = false;
		policies_msg.innerHTML ="Field Required";
	}else{
		policies_msg.innerHTML ="";
	}


/*------------------Policies---------------*/




if (flag === true) {
	return true;
}else{
	return false;
}


}