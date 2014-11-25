function getFeatureGroupDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/feature_sub_group_dashboard_ajax.php';
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
function updateFeatureGroup(featuregroupid,featuregroup,maingroupid,featuregroupstatus){
	featuregroup = featuregroup.replace(/%26/,"&");
	featuregroup = featuregroup.replace(/&#039;/,"'");
	featuregroup = featuregroup.replace(/%2C/,",");
	featuregroup = featuregroup.replace(/%2F/,"/");

	document.getElementById('main_group_status').value = featuregroupstatus;
	document.getElementById('main_group_name').value = featuregroup;
	oSel=document.getElementById('select_main_group');
	document.getElementById('select_main_group').value = maingroupid;
	
	document.getElementById('sub_group_id').value = featuregroupid;
	document.getElementById('actiontype').value = 'Update';
	return false;
}
function deleteFeatureGroup(featuregroupid,featuregroup){
	featuregroup = featuregroup.replace(/%26/,"&");
	featuregroup = featuregroup.replace(/&#039;/,"'");
	featuregroup = featuregroup.replace(/%2C/,",");
	featuregroup = featuregroup.replace(/%2F/,"/");
	document.getElementById('sub_group_id').value = featuregroupid;
    	document.getElementById('actiontype').value = 'Delete';
    	var answer = confirm ("Are you sure.Want to delete feature group '"+featuregroup+"'?")
    	if (answer){
         	document.feature_group_action.submit();
        	return true;
     	}
    	return false;
}
function validateFeatureGroup(){
	if(isCategorySelected() == false){
		alert("Please select the category.");
		return false;
	}
	if(isLastLvlCategory() == false){
        	alert("Please select last level category.");
                return false;
        }

	if(document.getElementById('main_group_name').value == ''){
		alert("Please add the main feature group");
		document.getElementById('main_group_name').focus();
		return false;
	}
	return true;
}
