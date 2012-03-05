// Ajax loading icon




// Update account form submit ajax
 $(document).ready(function(){
	$("#update-button").click(function() {
		var sname = $("#sname").val();
		var mname = $("#mname").val(); 
		var fname = $("#fname").val(); 
		var email = $("#email").val(); 
		var hphone = $("#hphone").val(); 
		var mphone = $("#mphone").val(); 
		var wphone = $("#wphone").val(); 
		var address = $("#address").val(); 
		var dob = $("#dob").val(); 
							
		var dataString = 'sname='+ sname + '&mname=' + mname + '&fname=' + fname + '&email=' + email + '&hphone=' + hphone + '&wphone=' + wphone + '&mphone=' + mphone + '&address=' + address + '&dob=' + dob;
		$.ajax({
			   type: "POST",
			   url:  "staff.php?mode=account&action=update",
			   data: dataString,
			   success: function() {
					$('#ability-message').hide();
					$('#update-wrapper').html("<div class='alert alert-success' id='message'></div>");
					$('#message').html("Your personal details have been successfully updated");
			   },
			   error: function() {
					$('#ability-message').hide();
					$('#update-wrapper').html("<div class='alert alert-error' id='message'></div>");
					$('#message').html("Your personal details could not be updated at this time. Please try again later");
			   }
		});
		return false;
	});
  });

// Change password ajax call
$(document).ready(function() {
				  $("#change-button").click(function() {
											var oldpass = $("").val();
											var newpass = $("").val();
											var conpass = $("").val();
											
											var dataString = 'oldpass=' + oldpass + '&newpass=' + newpass + '&conpass=' + conpass;
											
											$.ajax({
												   type: "POST",
												   url: "user.php?mode=account&action=changepassword",
												   data: dataString,
												   success: function() {
														$('#change-wrapper').html("<div class='alert alert-success' id='change-message'></div>");
														$('#change-message').html("Your password has been successfully changed. You will need to use it when you log in again");
												   },
												   error: function() {
													$('#change-wrapper').html("<div class='alert alert-error' id='change-message'></div>");
													$('#change-message').html("Your password could not be changed at this time. Please try again later.");
												   }
											});
											return false;
											});
				  });