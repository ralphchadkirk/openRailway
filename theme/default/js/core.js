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
		var sname = $("input#sname").val();
		var mname = $("input#mname").val(); 
		var fname = $("input#fname").val(); 
		var email = $("input#email").val(); 
		var hphone = $("input#hphone").val(); 
		var mphone = $("input#mphone").val(); 
		var wphone = $("input#wphone").val(); 
		var address = $("textarea#address").val(); 
		var dob = $("input#dob").val(); 
							
		var dataString = '&sname='+ sname + '&mname=' + mname + '&fname=' + fname + '&email=' + email + '&hphone=' + hphone + '&wphone=' + wphone + '&mphone=' + mphone + '&address=' + address + '&dob=' + dob;
		$.ajax({
			   type: "POST",
			   url: "user.php?mode=account&action=update",
			   data: dataString,
			   success: function() {
					$('#ability-message').hide();
					$('#update-wrapper').html("<div class='alert alert-success' id='message'></div>");
					$('#message').html("Your personal details have been successfully updated")
			   }
		});
		return false;
	});
  });
