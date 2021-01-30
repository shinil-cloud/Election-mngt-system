
var base_url = $("#hidBASE_URL").val(); // for delete after saving .
var controller =$("#hidAdminController").val();

$('#dataTable').DataTable({
		"processing": true, //Feature control the processing indicator.
         //Feature control DataTables' server-side processing mode.
		 "ordering": true,
        "searching": false,
        "paging": false,
		
        "order": [], //Initial no order.


        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
			
        },
		
		
        ],
		

	   
	   
	   
   });
  
  
  