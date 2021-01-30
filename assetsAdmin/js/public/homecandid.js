
var base_url = $("#hidBASE_URL").val(); // for delete after saving .
var controller =$("#hidAdminController").val();
//BASE_URL + "index.php/" + ADMIN_CONTROLLER +

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
            "url": base_url + "index.php/admin/homeList", //<?php echo site_url('person/ajax_list')?>
            "type": "POST"
			
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        
		
		{ "targets": [1], "searchable": false, "orderable": false, "visible": true },
        ],
		

    });
  