
var base_url = $("#hidBASE_URL").val(); // for delete after saving .
var controller =$("#hidAdminController").val();


var save_method; //for save method string

    //datatables
    table = $('#dataTable').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
		 "ordering": true,
        "searching": true,
		
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + "index.php/admin/termsList", //<?php echo site_url('person/ajax_list')?>
            "type": "POST"
			
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
			
        },
		
		
        ],
		

    });

   function addData()
	{
  
	save_method = 'add';
	$('#btnSave').text('Save'); //change button text
    $('#btnSave').removeAttr('disabled'); //set button disable
	$('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add New'); // Set Title to Bootstrap modal title
    
    //showing image upload area when add data.
    $('#hideWhenEdit').show();
	$('#hidID').val("0");
	$("#divError").hide();
    $("#divMessage").hide();

  }
  
  //------------------------------------------- DATA VALIDATION AND SUBMIT ---------------------------------------------------------
  $("#btnSave").click(function(){
	
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
	  
	 
	  
	//----------------- submitting the form data : AJAX POST ----------------
		save();
		
  }) // button save click ending	
  
  
  function reloadTable()
{
   
    table.ajax.reload(null,false); //reload datatable ajax 
}

  
  function save()
  {
			
		$(".alert").hide(); // hiding all the message alert.
		$('#btnSave').text('Saving...'); //change button text
		$('#btnSave').attr('disabled',true); //set button disable 

		
	    var url = base_url + "index.php/admin/saveTerms";
    

  // ajax adding data to database
  // FormData is using for ajax file uploading.
  
 if (typeof FormData == 'undefined')
  {
      alert("Oops,Your Browser Don't support FormData API! Use IE 10 or Above!");
      return false;
  }
  
         var formData = new FormData("#form");
         formData.append('Terms', $("#Terms").val());
         formData.append('Status', $("#Status").val());
		 formData.append('hidID', $("#hidID").val());
		 
		 
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
          if(res.status== false)
		  {
				var errorMsg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
					errorMsg		+='<strong style="color:white;">' + res.msg +'</strong>';
					
				$('#divError').show();
				$('#divError').html(errorMsg);
				
				
                                //formUtils.clearForm();               
                                //reloadTable();
                               
								// For message alert closing in 2 sec.
			$("#divError").fadeTo(2000, 500).slideUp(500, function(){
				
				$(".alert").hide();
				//$("#form")[0].reset(); // reseting form
			
			});
							

							   $("#hidBASE_URL").val(base_url); // for delete after load table.
                                $("#hidAdminController").val("admin");
							    $('#btnSave').text('Save'); //change button text
                                $('#btnSave').attr('disabled',false); //set button enable 
						
								 $("#form")[0].reset(); // reseting form         
                
                
                
                                return false;
		
		  }
                  
                  
     
		if(res.status)
			{		
                var saveOrEdit ="saved";
                var EDIT_ID = $("#hidID").val();
				if(EDIT_ID>0)
                    var saveOrEdit ="edited";
			
			reloadTable();
			var msg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
				msg	+='<strong style="color:white;">Success! Data has been ' + saveOrEdit  + ' successfully!.</strong>';
				$('#divMessage').html(msg);
				$('#divMessage').show();
				
			// For message alert closing in 2 sec.
			$("#divMessage").fadeTo(2000, 500).slideUp(500, function(){
				
				$(".alert").hide();
				//$("#form")[0].reset(); // reseting form
			
			});
                
                if(EDIT_ID==0)
                    $("#form")[0].reset(); // reseting form         
                if(EDIT_ID!=0)
					$('#modal_form').modal('hide');
                //reloadTable();
                $("#hidBASE_URL").val(base_url); // for delete after load table.
                
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
			
		}	


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            var errorMsg		= '<button style="color:white;" type="button" class="close"  aria-hidden="true" onclick="$(\'.alert\').hide()"> &times; </button>';
			errorMsg	+='<strong style="color:white;">Error ! Error while saving data. Please check your internet connection</strong>';
						
			$('#divError').show();
			$('#divError').html(errorMsg);
				
			// For message alert closing in 2 sec.
			$("#divError").fadeTo(2000, 500).slideUp(500, function(){
				
				$("#divError").hide();
				
			});
            
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
		
    }); // ajax end
			
			

	
  }
  
  
  function editData(id)
{
 	
	var url = base_url + "index.php/admin/getEditTerms";
	
	
	save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    
    // hiding image upload area when editing data.
    $('#hideWhenEdit').hide();
	
    //Ajax Load data from ajax
    $.ajax({
        url : url + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $("#id").val(data.Id);
			$("#hidID").val(data.Id);
			$("#Terms").val(data.Terms);
            $("#Status").val(data.Status);
			
			$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Rules'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
  
  function deleteData(id)
{
    
    bootbox.confirm("Are you sure to delete this rule?", function(result) {
		
     if(result)
	{
        // ajax delete data to database
		var url = base_url + "index.php/admin/deleteTerms/" + id;
        $.ajax({
            url : url,
            type: "POST",
            dataType: "text",
            success: function(res)
            {
                 
                reloadTable();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                bootbox.alert('Error deleting data');
            }
        });

        }
    }); // bootbox confirm ending
}
 