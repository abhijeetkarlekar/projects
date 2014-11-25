function getPivotGroupDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
	//alert(divid+','+ajaxloaderid+','+category_id);
	document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/pivot_sub_group_dashboard_ajax.php';
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
function updatePivotGroup(pivotgroupid,pivotgroup,pivotgroupstatus){
	pivotgroup = pivotgroup.replace(/%26/,"&");
	pivotgroup = pivotgroup.replace(/&#039;/,"'");
	pivotgroup = pivotgroup.replace(/%2C/,",");
	pivotgroup = pivotgroup.replace(/%2F/,"/");

	document.getElementById('main_group_status').value = pivotgroupstatus;
	document.getElementById('main_group_name').value = pivotgroup;
	document.getElementById('sub_group_id').value = pivotgroupid;
	document.getElementById('actiontype').value = 'Update';
	return false;
}
function deletePivotGroup(pivotgroupid,pivotgroup){
	pivotgroup = pivotgroup.replace(/%26/,"&");
	pivotgroup = pivotgroup.replace(/&#039;/,"'");
	pivotgroup = pivotgroup.replace(/%2C/,",");
	pivotgroup = pivotgroup.replace(/%2F/,"/");
	document.getElementById('sub_group_id').value = pivotgroupid;
    	document.getElementById('actiontype').value = 'Delete';
    	var answer = confirm ("Are you sure.Want to delete pivot group '"+pivotgroup+"'?")
    	if (answer){
         	document.pivot_group_action.submit();
        	return true;
     	}
    	return false;
}
function validatePivotGroup(){
	if(isCategorySelected() == false){
		alert("Please select the category.");
		return false;
	}
	if(isLastLvlCategory() == false){
        	alert("Please select last level category.");
                return false;
        }

	if(document.getElementById('main_group_name').value == ''){
		alert("Please add the main pivot group");
		document.getElementById('main_group_name').focus();
		return false;
	}
	return true;
}
