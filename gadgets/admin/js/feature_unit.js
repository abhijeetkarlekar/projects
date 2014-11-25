function updateFeatureUnit(unitid,unitname,unitstatus){
	unitname = unitname.replace(/%26/,"&");
	unitname = unitname.replace(/&#039;/,"'");
	unitname = unitname.replace(/%2C/,",");
	unitname = unitname.replace(/%2F/,"/");

	document.getElementById('unit_status').value = unitstatus;
	document.getElementById('unit_name').value = unitname;
	document.getElementById('unit_id').value = unitid;
	document.getElementById('actiontype').value = 'Update';
	return false;
}
function deleteFeatureUnit(unitid,unitname){
	unitname = unitname.replace(/%26/,"&");
	unitname = unitname.replace(/&#039;/,"'");
	unitname = unitname.replace(/%2C/,",");
	unitname = unitname.replace(/%2F/,"/");
	document.getElementById('unit_id').value = unitid;
	document.getElementById('actiontype').value = 'Delete';
	var answer = confirm ("Are you sure.Want to delete feature unit '"+unitname+"'?")
    	if (answer){
         	document.feature_unit_action.submit();
         	return true;
     	}
	return false;
}
function validateFeatureUnit(){
	if(document.getElementById('unit_name').value == ''){
		alert("Please add the feature unit");
		document.getElementById('unit_name').focus();
		return false;
	}
	return true;
}
function getFeatureUnitDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/feature_unit_ajax.php';
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