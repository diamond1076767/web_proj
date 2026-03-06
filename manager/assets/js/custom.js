$(document).ready(function(){
	
	 alertify.set('notifier','position', 'top-right');
	
	$(document).on('click','.increment', function(){
		var $quantityInput = $(this).closest('.qtyBox').find('.qty');
		var productId = $(this).closest('.qtyBox').find('.prodId').val();	
		var currentValue = parseInt($quantityInput.val());
		
		if(!isNaN(currentValue)){
			var qtyVal = currentValue + 1;
			$quantityInput.val(qtyVal);
			quantityIncDec(productId, qtyVal);
		}
	});
	
	$(document).on('click','.decrement', function(){
		var $quantityInput = $(this).closest('.qtyBox').find('.qty');
		var productId = $(this).closest('.qtyBox').find('.prodId').val();
		
		var currentValue = parseInt($quantityInput.val());
		
		if(!isNaN(currentValue) && currentValue>1){
			var qtyVal = currentValue - 1;
			$quantityInput.val(qtyVal);
			quantityIncDec(productId, qtyVal);
		}
	});
	
	function quantityIncDec(prodId, qty){
		$.ajax({
			type: "POST",
			url: "order-code.php",
			data: {
				'productIncDec': true,
				'product_id': prodId,
				'quantity': qty,
			},
			success: function (response) {			
				var res = JSON.parse(response);
				
				if(res.status == 200){
					location.reload();
					alertify.success(res.message);
				}else{
					alertify.error(res.message);
				}
			}
		});
	}
	
	//proceed to place order button click
	$(document).on('click','.proceedToPlace', function(){
		
		var cphone = $("#cphone").val();
		var payment_mode = $("#payment_mode").val();
		
		if(payment_mode == ''){
			swal("Select Payment Mode","Select your payment mode","warning");
			return false;
		}
		
		if(cphone == '' && !$.isNumeric(cphone)){
			
			swal("Enter Phone Number","Enter Valid Phone Number","warning");
			return false;
		}
		
		var data = {
			'proceedToPlaceBtn': true,
			'cphone': cphone,
			'payment_mode': payment_mode,
		};
		
		$.ajax({
			type: "POST",
			url: "order-code.php",
			data: data,
			success: function(response){
				var res = JSON.parse(response);
				if(res.status == 200){
					window.location.href = 'order-summary.php';
				}else if(res.status == 404){
					swal(res.message, res.message,res.status_type,{
						buttons: {
							catch: {
								text: "Add Customer",
								value: "catch"
							},
							cancel: "Cancel"
						}
					})
					.then((value) => {
						switch(value){
							case "catch":
								$('#c_phone').val(cphone);
								$('#addCustomerModal').modal('show');
								//console.log('Pop the customer add modal');
								break;
							default:
						}
					});
				}else{
					swal(res.message, res.message,res.status_type);
				}
			}
				
		});
		
	});
	
		// Add Customer to customers table
$(document).on('click', '.saveCustomer', function() {
    var c_name = $('#c_name').val();
    var c_company = $('#c_company').val();
    var c_phone = $('#c_phone').val();
    var c_email = $('#c_email').val();

    if (c_name !== '' && c_company !== '' && c_phone !== '' && c_email !== '') {

		if (!isAlphabeticName(c_name)){
			swal("Please enter alphabetic name", "", "warning");
		}

        if ($.isNumeric(c_phone)) {
			checkExistingPhone(c_phone);
			// Regular expression for validating an Email address
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(c_email)) {
				checkExistingEmail(c_email);
				
				var data = {
		        'saveCustomerBtn': true,
		        'name': $('#c_name').val(),
		        'company': $('#c_company').val(),
		        'phone': c_phone,
		        'email': c_email,
		    };
		
		    $.ajax({
		        type: "POST",
		        url: "order-code.php",
		        data: data,
		        success: function(response) {
		            var res = JSON.parse(response);
		            if (res.status == 200) {
		                swal(res.message, res.message, res.status_type);
		                $('#addCustomerModal').modal('hide');
		            } else if (res.status == 422) {
		                swal(res.message, res.message, res.status_type);
		            } else {
		                swal(res.message, res.message, res.status_type);
		            }
		        }
		    });
	            } else {
	                swal("Enter Valid Email Address", "", "warning");
	            }
	        } else {
	            swal("Enter Valid Phone Number", "", "warning");
	        }
	    } else {
	        swal("Please Fill Required Fields", "", "warning");
	    }
	});
	
	function checkExistingEmail(email) {
    $.ajax({
        type: "POST",
        url: "order-code.php",
        data: {
            'checkExistingEmail': true,
            'email': email,
        },
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status == 409) {
                // Email already exists, show warning
                swal(res.message, res.message, res.status_type);
            } else if (res.status == 200) {
                // Email does not exist, proceed with saving
                saveCustomer();
            } else {
                // Handle other cases if needed
                swal(res.message, res.message, res.status_type);
            }
        }
    });
}

function checkExistingPhone(phone) {
    $.ajax({
        type: "POST",
        url: "order-code.php",
        data: {
            'checkExistingPhone': true,
            'phone': phone,
        },
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status == 409) {
                // Phone already exists, show warning
                swal(res.message, res.message, res.status_type);
            } else if (res.status == 200) {
                // Phone does not exist, proceed with saving
                saveCustomer();
            } else {
                // Handle other cases if needed
                swal(res.message, res.message, res.status_type);
            }
        }
    });
}

	$(document).on("click", "#saveOrder", function(){
		$.ajax({
			type: "POST",
			url: "order-code.php",
			data: {
				'saveOrder': true
			},
			success: function(response){
				var res = JSON.parse(response);
				
				if(res.status == 200){
					swal(res.message,res.message,res.status_type);					
					$('#orderPlaceSuccessMessage').text(res.message);
					$('#orderSuccessModal').modal('show');
				}else{
					swal(res.message,res.message,res.status_type);
				}
			}
		});
	});
	
	
});

function printMyBillingArea(){
	var divContents = document.getElementById("myBillingArea").innerHTML;
	var a = window.open('','');
	a.document.write('<html><title>Sales Inventory Management</title>');
	a.document.write('<body style="font-family: fangsong;">');
	a.document.write(divContents);
	a.document.write('</body></html>');
	a.document.close();
	a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo){
	var elementHTML = document.querySelector("#myBillingArea");
	docPDF.html(elementHTML, {
		callback: function(){
			docPDF.save(invoiceNo+'.pdf');
		},
		x: 15,
		y: 15,
		width: 170,
		windowWidth: 650
	});
}


$(document).ready(function() {
    // Assuming you have a button with the ID 'approveButton'
    $('.approveButton').click(function(event) {
		event.preventDefault();

        // Get the values from the current row (assuming you have a table)
        var username = $(this).closest('tr').find('td:eq(0)').text(); // Adjust the index based on the column order
        var fullName = $(this).closest('tr').find('td:eq(1)').text();
        var roleID = $('.hidden-roleid').val();
        var phone = $(this).closest('tr').find('td:eq(3)').text();
        var email = $(this).closest('tr').find('td:eq(4)').text();

        // Fetch the role name based on role ID (you need to implement this function)
        var roleName = getRoleName(roleID);

        // Set the values in the modal
        $('#role_id').val(roleName).prop('disabled', true); // Disable the input field
        $('#fullname').val(fullName).prop('disabled', true); // Disable the input field
        $('#username').val(username).prop('disabled', true); // Disable the input field
        $('#phone').val(phone).prop('disabled', true); // Disable the input field
        $('#email').val(email).prop('disabled', true); // Disable the input field

        // Save the roleID in a hidden field for later use
        $('#hidden_role_id').val(roleID);

     	// Trigger the modal with the ID 'addUserModal'
        $('#addUserModal').modal('show');
    });

    // Function to fetch role name based on role ID
    function getRoleName(roleID) {
        // You need to implement this function to fetch the role name from your data source
        // Example: Use AJAX to make a request to the server and get the role name
        // For now, let's assume the role name is fetched synchronously (replace this with your implementation)
        var roles = {
            '1': 'Admin', // Replace with actual role names and IDs
            '2': 'Manager',
            '3': 'Staff',
        };

        return roles[roleID] || 'Unknown Role';
    }

    // Add User to user table
    $(document).on('click','.saveUser', function(event) {
		event.preventDefault();
		
        var role_id = $('.hidden-roleid').val(); // Get the roleID from the hidden field
        var username = $('#username').val();
        var fullname = $('#fullname').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var conpassword = $('#conpassword').val();
        var id = $('.hidden-id').val();
        
		if(role_id != '' && username != '' && fullname != '' && phone != '' && email != '' && password != '' && conpassword != ''){
			
			// Check if password match
                if (password != conpassword) {
                    swal("Password does not match","","warning");
                }
                
                // Check if the password meets the strength criteria
                if (!isPasswordStrong(password)) {
				    swal("Passwords do not meet the requirements", "", "warning");

                }

				if (!isAlphabeticName(fullname)){
					swal("Please enter alphabetic name", "", "warning");
				}
			
			if($.isNumeric(phone)){
				
				var data = {
					'saveUserBtn': true,
					'roleID': role_id,
					'username': username,
					'fullname': fullname,
					'phone': phone,
					'email': email,
					'password': password,
					'conpassword': conpassword,
					'id': id
				};
				
				$.ajax({
					type: "POST",
					url: "manager-code.php",
					data: data,
					success: function (response){
						var res = JSON.parse(response);
						
							
							if (res.status == 200) {
							    swal(res.message, res.message, res.status_type)
							        .then(function() {
							            $('#addUserModal').modal('hide');
							            location.reload(true);
							        });

						}else if(res.status == 422){
							swal(res.message, res.message, res.status_type);
						}else{
							swal(res.message, res.message, res.status_type);
						}
					},
					error: function (error) {
                    // Handle AJAX errors
                    console.error(error);
                    swal("An error occurred while processing your request", "", "error");
                }
            });
			}else{
				swal("Enter Valid Phone Number","","warning");
			}
		}else{
			swal("Please Fill Required Fields","","warning");
		}
	});
	
		// Function to check if the password is strong
	function isPasswordStrong(password) {
	    // Define password strength criteria
	    const minLength = 8;
	    const uppercaseRequired = true;
	    const lowercaseRequired = true;
	    const numberRequired = true;
	    const specialCharRequired = true;
	    
	    // Check minimum length
	    if (password.length < minLength) {
	        return false;
	    }
	    
	    // Check uppercase letters
	    if (uppercaseRequired && !/[A-Z]/.test(password)) {
	        return false;
	    }
	    
	    // Check lowercase letters
	    if (lowercaseRequired && !/[a-z]/.test(password)) {
	        return false;
	    }
	    
	    // Check numbers
	    if (numberRequired && !/\d/.test(password)) {
	        return false;
	    }
	    
	    // Check special characters
	    if (specialCharRequired && !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
	        return false;
	    }
	    
	    // Password meets all criteria
	    return true;
	}

	function isAlphabeticName(name) {
		// Regular expression to check if the name contains only alphabetic characters
		var nameRegex = /^[a-zA-Z]+$/;
	
		// Test the name against the regular expression
		return nameRegex.test(name);
	}
});
