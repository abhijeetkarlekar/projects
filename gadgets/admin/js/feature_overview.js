function getFeatureOverviewDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/feature_overview_ajax.php';
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
function deleteFeatureOverview(overview_id,overview_name){
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
function validateFeatureOverview(){
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

function sFeatureOverPagination(page,startlimit,cnt,filename,divid,category_id){

	
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
function updateUpPos(category_id,overview_id,pos,divid,group_id){
	var url = admin_web_url+'ajax/feature_overview_ajax.php';
	document.getElementById('featureoverviewajaxloader').style.display = "block";
	 $.ajax({
                        url: url,
                        data: 'catid='+category_id+'&overview_id='+overview_id+'&pos='+pos+'&type=up&group_id='+group_id,
			success: function(data){
			 	document.getElementById(divid).innerHTML = data;
		                document.getElementById(divid).style.display="block";
			},
			async:false
	});
	document.getElementById('featureoverviewajaxloader').style.display = "none";
}
function updateDownPos(category_id,overview_id,pos,divid,group_id){
	 var url = admin_web_url+'ajax/feature_overview_ajax.php';
        document.getElementById('featureoverviewajaxloader').style.display = "block";
         $.ajax({
                        url: url,
                        data: 'catid='+category_id+'&overview_id='+overview_id+'&pos='+pos+'&type=down&group_id='+group_id,
                        success: function(data){
                                document.getElementById(divid).innerHTML = data;
                                document.getElementById(divid).style.display="block";
                        },
                        async:false
        });
        document.getElementById('featureoverviewajaxloader').style.display = "none";
}

