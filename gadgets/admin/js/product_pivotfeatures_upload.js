function getTopSellingCarDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
	if(category_id == ''){
		var category_id = document.getElementById('selected_category_id').value;

		if(isCategorySelected() == false){
			alert("Please select the category.");
			return false;
		}
		if(isLastLvlCategory() == false){
			alert("Please select last level category.");
			return false;
		}
	}
        document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/product_pivotfeatures_upload_dashboard.php';
        //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt);
        $.ajax({
		url: url,
		data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt,
		success: function(data){
                    //alert(data);
                    document.getElementById(divid).innerHTML = data;
                    document.getElementById(divid).style.display="block";
                    document.getElementById(ajaxloaderid).style.display = "none";
                },
                async:false
        });
        setupLeftMenu();
    $('.datatable').dataTable();
    setSidebarHeight();
        return true;
}

function downloadTopSellingCarDashboard(){
    var URL = admin_web_url+'ajax/product_pivotfeatures_upload_download.php';
    window.open(URL);
}

function validatefile(){
    var fileName = document.getElementById('xls_file').value;
    var extension = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
    //alert(extension);
    /*if(extension!="xls"){
        alert("Please upload only .xls file");
        return false;
    }*/
    return true;
}
