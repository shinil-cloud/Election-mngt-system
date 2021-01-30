var base_url = $("#hidBASE_URL").val(); // for delete after saving .
var controller =$("#hidAdminController").val();

 $("#btnLogin").text("Login");
    $("#btnLogin").removeAttr('disabled');
    
	$("#btnLogin").click(function(){
	
	  var uid=	$("#uId").val();
	  var pass = $("#password").val();
	  var msg = "<strong>Error!</strong><br/> Both fields are mandatory.";
	  
	  if(uid=="" || uid==null)
	  {
	  		$("#divError").html(msg);
			$("#divError").show();
			return false;
	  }
	  
	  if(pass=="" || pass==null)
	  {
	  		$("#divError").html(msg);
			$("#divError").show();
			return false;
	  }
	  	$("#divError").hide();
		
		$("#btnLogin").text("Login...");
		$("#btnLogin").attr('disabled',true);
		
	
		//Ajax submit start 
	    $.ajax({
		url : base_url+ "admin/adminLoginCheck",
		type: "POST",
		data: $("#loginForm").serialize(),
		dataType: "text",
		success: function(response)
		{
			
			
			var URL = base_url + "admin/adminhome";
			
			//alert(response);
			//return;
			if(response.indexOf("TRUE")>=0)
			  window.location=URL;
			  
		    else{
				var msg = "<strong>Error!</strong><br/> Invalid user id or password.";
				$("#divError").html(msg);
				$("#divError").show();
				document.forms[0].reset();
				$("#btnLogin").text("Login");
				$("#btnLogin").removeAttr('disabled');
		
				return false;
	  
			}		
		   
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
		     alert('Error get data from ajax' + textStatus);
			 $("#btnLogin").removeAttr('disabled');
			 $("#btnLogin").text("Login");
		}
	    }); //---- AJAX ending
	
		
		
	
	});
	
	
	
   
   
   
   $("#btnChangePass").click(function(){
	
   var hasErrors = false;
	
	//------ removing all error borders ....
   $('#form input,#form select').each(function() {
          var $this = $(this);
          $($this).removeClass('error-border');
		  $( ".error" ).remove();
	
     });
   
	
	$('#form input,#form select').not('.optional').each(function() {
          var $this = $(this);
          if (!$this.val()) {
            hasErrors = true;
            $($this).addClass('error-border');
			$( "<div class='error'><span class='error'><i class='fa fa-hand-pointer-o' aria-hidden='true'></i> Error! Please fill up this field.</span></div>" ).insertAfter($this);
		
          }
     });
	 
	//----- validating email
	
		if (hasErrors) {
		
		var errorMsg	 = '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
                    errorMsg	+='<strong style="color:white;">Error ! Missing required fields.</strong>';
						
		$('#divError').show();
		$('#divError').html(errorMsg);
        //$btn.val('reset');
        
        // error msg alert closing in 2 sec.
        $("#divError").fadeTo(2000, 200).slideUp(500, function(){
				$(".alert").hide();
          });                       
        
        return false;
      }
	  
	 if ($('#NewPass').val()!=$('#ConfPass').val()) {
		
		var errorMsg	 = '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
                    errorMsg	+='<strong style="color:white;">Error ! Password Mismatch.</strong>';
						
		$('#divError').show();
		$('#divError').html(errorMsg);
        //$btn.val('reset');
        
        // error msg alert closing in 2 sec.
        $("#divError").fadeTo(2000, 200).slideUp(500, function(){
				$(".alert").hide();
          });  
			 return false;
        
      }
	  
	//----------------- submitting the form data : AJAX POST ----------------
		save();
		
  }); // button save click ending	
  
  

  
  function save()
  {
			
		
		$('#btnChangePass').attr('disabled',true); //set button disable 

		
	    var url = base_url + "index.php/admin/changeAdminPassword";
    

  // ajax adding data to database
  // FormData is using for ajax file uploading.
  
 if (typeof FormData == 'undefined')
  {
      alert("Oops,Your Browser Don't support FormData API! Use IE 10 or Above!");
      return false;
  }
  
  
         var formData = new FormData("#form");
         formData.append('UserId', $("#UserId").val());
		 formData.append('OldPass', $("#OldPass").val());
		 formData.append('NewPass', $("#NewPass").val());
		 
   // alert(formData);
    //return;
    $.ajax({
        url : url,
        type: "POST",
        data:  formData,
        dataType: "json",
        processData: false,
        contentType: false,
    	cache: false,
        enctype: 'multipart/form-data',
        success: function(res)
        {
                  // if false
          if(res.status)
		  {
				var msg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
					msg		+='<strong style="color:white;">' + res.msg +'</strong>';
					
				$('#divMessage').show();
				$('#divMessage').html(msg);
				
				
								// For message alert closing in 2 sec.
			$("#divMessage").fadeTo(2000, 500).slideUp(500, function(){
				
				$(".alert").hide();
			});
			$("#changeform")[0].reset();
		  }
			else
			{
					var msg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
					msg		+='<strong style="color:white;">' + res.msg +'</strong>';
					
				$('#divError').show();
				$('#divError').html(msg);
				
				
			//For message alert closing in 2 sec.
			$("#divError").fadeTo(2000, 500).slideUp(500, function(){
				
				$(".alert").hide();
			});
				
			}
							

						
			       
             $('#btnChangePass').attr('disabled',false); //set button enable 
		
		 
		},
        error: function (jqXHR, textStatus, errorThrown)
        {
            var errorMsg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
			errorMsg	+='<strong style="color:white;">Error ! Please check your internet connection.</strong>';
						
			$('#divError').show();
			$('#divError').html(errorMsg);
				
			// For message alert closing in 2 sec.
			$("#divError").fadeTo(2000, 500).slideUp(500, function(){
				
				$("#divError").hide();
				
			});
            
            $('#btnChangePass').attr('disabled',false); //set button enable 

        }
		
    }); // ajax end
			
			

	
  }
  
  
  
  
  
   $("#btnForgotPass").click(function(){
	   $('#modal_form').modal('show'); // show bootstrap modal
	  

   });
   
   
   $("#btnResetPass").click(function(){
		var email	= $('#Email').val();
		var userId	= $('#UserId').val();
		
		
		
		
		
		
		$("#btnResetPass").attr("disabled", true);
		
		
		var url = base_url + "index.php/admin/resetAdminPassword" ;
        $.ajax({
            url : url,
            type: "POST",
            dataType: "json",
			data:$('#form').serialize(),
            success: function(res)
		    {
			     if(res.status)
                 {
					 var msg ='<span style="color:green; font-weight:bold;">' + res.msg +'<span>'; 
                     bootbox.alert(msg);
					 $("#btnResetPass").attr("disabled", false);
                 }
				 else{
					 
					 var errorMsg ='<span style="color:red; font-weight:bold;">' + res.msg +'<span>'; 
                     bootbox.alert(errorMsg);
                     $("#btnResetPass").attr("disabled", false);
				 }
                 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				 $("#btnResetPass").attr("disabled", false);
				bootbox.alert('Error while sending password reset email, please try again later.');
            }
        }); // ajax ending

   });
	  
   
	
	
	
	
	
	
 
