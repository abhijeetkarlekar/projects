function getCarFinderFeatureOverviewDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/car_finder_feature_overview_ajax.php';
        $.ajax({
         url: url,
         data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&group_id='+group_id,
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
function deleteCarFinderFeatureOverview(overview_id,overview_name){
	overview_name = overview_name.replace(/%26/,"&");
	overview_name = overview_name.replace(/&#039;/,"'");
	overview_name = overview_name.replace(/%2C/,",");
	overview_name = overview_name.replace(/%2F/,"/");
	document.getElementById('overview_id').value = overview_id;
	document.getElementById('actiontype').value = 'Delete';
	var answer = confirm ("Are you sure.Want to delete Feature Overview '"+overview_name+"'?")
	if (answer){
		document.brand_action.submit();
		return true;
	}
	return false;
}
function validateCarFinderFeatureOverview(){
	if(isCategorySelected() == false){
                alert("Please select the category.");
                return false;
        }
        if(isLastLvlCategory() == false){
                alert("Please select last level category.");
                return false;
        }
	if(document.getElementById('feature_id').value == ''){
		alert("Please Select Feature");
		document.getElementById('feature_id').focus();
		return false;
	}
	return true;
}

function sCarFinderFeatureOverPagination(page,startlimit,cnt,filename,divid,category_id){

	
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
		
	if(divid == ""){ return false; }
		var url = admin_web_url+filename;

		$.ajax({
			url: url,
			data: 'catid='+category_id+'&page='+page+'&startlimit='+startlimit+'&cnt='+cnt,
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
               },
            async:false
        });
	
	return true;
}
