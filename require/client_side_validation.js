


	function first_name_check(){

		var first_name_flag = false;
		
		var alpha_pattern =/^[A-Z]{1}[a-z]{2,}$/;
		var first_name = document.querySelector("#first_name").value;
		var first_name_msg = document.querySelector("#first_name_msg");

		if (first_name === "") {

        	first_name_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Field Required</span>';
      	
		} else if (!alpha_pattern.test(first_name)) {
        	
			first_name_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Must be like "Ahmed"</span>';
      	
		} else {
        	
			first_name_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid</span>';
			first_name_flag = true;
		}

		return first_name_flag;
    }



	function last_name_check(){

		var last_name_flag = false;
		
		var alpha_pattern =/^[A-Z]{1}[a-z]{2,}$/;
		var last_name = document.querySelector("#last_name").value;
		var last_name_msg = document.querySelector("#last_name_msg");

		if (last_name === "") {
        	
			last_name_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Field Required</span>';
      	
		} else if (!alpha_pattern.test(last_name)) {
        
			last_name_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Must be like "Khan"</span>';
      	
		} else {
       	
			last_name_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid</span>';
			last_name_flag = true;
		}

		return last_name_flag;
	}


	function check_email($registered = false) {

		var email_flag = false;
    	var email = document.querySelector("#email").value;
    	var email_msg = document.querySelector("#email_msg");
    	var email_pattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;

		var ajax_request = null;

    	
		if (!email) {
    	
			email_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Email is required</span>';
    	
		} else if (!email_pattern.test(email)) {
    	
			email_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Invalid email format</span>';
    	
		} else {
				
				if ($registered) {

					if (window.XMLHttpRequest) {
            		    ajax_request = new XMLHttpRequest;
            		} else {
            		    ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
            		}
				
            		ajax_request.onreadystatechange = function() {
            		    if (ajax_request.readyState == 4 && ajax_request.status == 200) {
            		       	email_msg.innerHTML = ajax_request.responseText;
						
							if (ajax_request.responseText.includes("Valid Email")) {
            					email_flag = true;
        					} else {
            					email_flag = false;
        					}
            		    }
            		}
				
					ajax_request.open("POST", "ajax/register_process.php");
            		ajax_request.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            		ajax_request.send("action=check_email&email=" + email);
				}else {
					// If not registered, just check the format
					email_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid Email</span>';
					email_flag = true;

				}
    
		}

		return email_flag;
    }

	function check_password() {
		var password_flag = false;
    	
		var password = document.querySelector("#password").value;
    	var password_msg = document.querySelector("#password_msg");

      	if (password.length < 8) {
    
			password_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Minimum 8 characters</span>';
    
		} else if (!/[a-zA-Z]/.test(password)) {
    
			password_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Must contain a letter</span>';
    
		} else if (!/[0-9]/.test(password)) {
    
			password_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Must contain a number</span>';
    
		} else {
    
			password_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid Password</span>';
			password_flag = true;
		}

		return password_flag;
    }

	function check_confirm_password(){
		var confirm_password_flag = false;

    	var password = document.querySelector("#password").value;
    	var confirm_password = document.querySelector("#confirm_password").value;
    	var confirm_password_msg = document.querySelector("#confirm_password_msg");

      	if (password === confirm_password && confirm_password !== "") {
      
			confirm_password_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Passwords Match</span>';
			confirm_password_flag = true;

		} else {
       	 	
			confirm_password_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Passwords do not match</span>';
			
		}

		return confirm_password_flag;
    }

	function check_dob() {
		var dob_flag = false;
      	var dob = document.querySelector("#dob").value;
      	var dob_msg = document.querySelector("#dob_msg");

      	if (!dob) {
      		dob_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Date of Birth is required</span>';
      		return;
      	}

    	var birthDate = new Date(dob);
    	var ageDiff = Date.now() - birthDate.getTime();
    	var ageDate = new Date(ageDiff);
    	var age = Math.abs(ageDate.getUTCFullYear() - 1970);

      if (age < 13) {
    	dob_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Must be 13 or older</span>';
      } else {
    	dob_msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid age</span>';
		dob_flag = true;
      }
	  
	  	return dob_flag;
    }

	function check_gender() {
		
		var gender_flag = false;

		var gender = document.querySelector("input[type='radio']:checked");
		var gender_msg = document.querySelector("#gender_msg");

		if (!gender) {
			gender_msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Field Required</span>';
    	
		}else{
			gender_flag = true;
			gender_msg.innerHTML ="";
		}

		return gender_flag;
	}

	function validate_profile_pic() {
		var profile_pic_flag = false;

    	var fileInput = document.querySelector("#profile_pic");
    	var msg = document.querySelector("#profile_pic_msg");
    	var file = fileInput.files[0];
		var maxSize = 1 * 1024 * 1024;

      	msg.innerHTML = "";
		

		if (!file) {
			msg.innerHTML = '<i class="fas fa-info-circle text-muted"></i> <span class="text-muted">Optional</span>';
			return true;
		}

      	var validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    	
		if (!validTypes.includes(file.type)) {
    		msg.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Only JPG, PNG, Jpeg files allowed</span>';
		}else if (file.size > maxSize) {
        	msg.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> <span class="text-warning">File size must be under 1 MB</span>';
    	} else {
			msg.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Valid file selected</span>';
			profile_pic_flag = true;
      	}
		
		return profile_pic_flag;
    }


	function validation() {

	var address = document.querySelector("#address").value;
	var address_msg = document.querySelector("#address_msg");

	
    let first = first_name_check();
    let last = last_name_check();
    let emailValid = check_email(true);
    let password = check_password();
    let confirm = check_confirm_password();
    let dob = check_dob();
    let gender = check_gender();
    let pic = validate_profile_pic();

	if (address === "") {
		address_msg.innerHTML = '<i class="fas fa-info-circle text-muted"></i> <span class="text-muted">Optional</span>';
	} 

    setTimeout(() => {
        if (first && last && password && confirm && dob && gender && pic && emailValid) {
            document.querySelector("form").submit();
        } else {
            alert("Errors! Please check the form.");

        }
    }, 300);
}


