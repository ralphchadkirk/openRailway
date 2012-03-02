//
//------------------------------------
//		OPENRAILWAY DEFAULT THEME JS  |
//------------------------------------
//


// Set Bootstrap alert message
$('.alert-message').alert();

// Set bootstrap dropdown toggle
$('.dropdown-toggle').dropdown();

// Set bootstrap modal toggle
$('#activity-pane').modal();

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
			   url:  "user.php?mode=account&action=update",
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
