function getPivotDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
		var url = admin_web_url+'ajax/pivot_dashboard.php';
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
function updatePivot(pivotid,featureid,pivotgroup,pivotdesc,pivotunit,pivotstatus){
	//alert(pivotgroup);
	document.getElementById('select_pivot_group_0').value = pivotgroup;
	document.getElementById('select_pivot_name_0').value = featureid;
	document.getElementById('pivot_description_0').value = pivotdesc;
	document.getElementById('pivot_style_0').value = pivotunit;
	document.getElementById('pivot_status_0').value = pivotstatus;
	document.getElementById('pivot_id').value = pivotid;
	document.getElementById('actiontype').value = 'Update';
	return false;
}
function deletePivot(pivotid,pivotname){
	pivotname = pivotname.replace(/%26/,"&");
	pivotname = pivotname.replace(/&#039;/,"'");
	pivotname = pivotname.replace(/%2C/,",");
	pivotname = pivotname.replace(/%2F/,"/");
	document.getElementById('pivot_id').value = pivotid;
    	document.getElementById('actiontype').value = 'Delete';
    	var answer = confirm ("Are you sure.Want to delete pivot '"+pivotname+"'?")
    	if (answer){
         	document.pivot_manage.submit();
         	return true;
     	}
	return false;
}
function validatePivot(){
	if(isCategorySelected() == false){
                alert("Please select the category.");
                return false;
        }
        if(isLastLvlCategory() == false){
                alert("Please select last level category.");
                return false;
        }
	if(document.getElementById('select_pivot_name_0').value == ''){
		alert("Please add the pivot");
		return false;
	}
	return true;
}
function add_more_pivot(){
	var pivotGroupStr = document.getElementById("pivot_subgroup_str").value;
	var pivotGroupArr = pivotGroupStr.split(",");
	
	var pivotGrpIdsStr = document.getElementById("pivot_subgrp_id_str").value;
	var pivotGrpIdsArr = pivotGrpIdsStr.split(",");

	var featurenameStr = document.getElementById("feature_name_str").value;
	featureNameArr = featurenameStr.split(",");
	var featureidsStr =  document.getElementById("feature_id_str").value;
	featureIdsArr =  featureidsStr.split(",");

	var pivotStyleStr =  document.getElementById("pivot_style_value_str").value;
	pivotStyleArr = pivotStyleStr.split(",");
	var pivotStyleIdsStr =  document.getElementById("pivot_style_id_str").value;
	pivotStyleIdArr =  pivotStyleIdsStr.split(",");

	var cnt = document.getElementById("pivotboxcnt").value;
	var currentCnt = parseInt(cnt)+parseInt(1);
	var table = document.getElementById("Update");
	var rowCount = parseInt(document.getElementById("rowCount").value);

	var row = table.insertRow(rowCount);
	row.id = 'pivot_name_row_id_'+cnt;

	var pivot_name = row.insertCell(0);

        pivot_name.innerHTML = 'Pivot Name#'+currentCnt;

        var pivot_name_value = row.insertCell(1);
	pivot_name_value.colSpan = 10;
	
	pivotnamehtml = '<select name="select_pivot_name_'+cnt+'" id="select_pivot_name_'+cnt+'"><option value="">---Select Pivot---</option>';
	var pivotlength = featureNameArr.length;

	for(i=0;i<pivotlength;i++){
		pivotnamehtml += '<option value="'+featureIdsArr[i]+'">'+featureNameArr[i]+'</option>';
	}
	pivotnamehtml += '</select>';

        pivot_name_value.innerHTML = pivotnamehtml;

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'pivot_group_row_id_'+cnt;

	var pivot_group = row.insertCell(0);
        pivot_group.innerHTML = 'Pivot Group#'+currentCnt;

        var sel_pivot_group = row.insertCell(1);
        grouphtml = '<select name="select_pivot_group_'+cnt+'" id="select_pivot_group_id_'+cnt+'"><option value="">---Select Group---</option>';
	var grouplength = pivotGroupArr.length;
	
	for(i=0;i<grouplength;i++){
		//grouphtml += '<option value="'+pivotGroupIdsArr[i]+'">'+pivotGroupArr[i]+'</option>';
		grouphtml += '<option value="'+pivotGrpIdsArr[i]+'">'+pivotGroupArr[i]+'</option>';
	}
	grouphtml += '</select>';

	sel_pivot_group.innerHTML = grouphtml;

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'pivot_desc_row_id_'+cnt;

	var pivot_desc = row.insertCell(0);
    pivot_desc.innerHTML = 'Pivot Description#'+currentCnt;

    var pivot_desc_value = row.insertCell(1);
	pivot_desc_value.colSpan = 10;
    pivot_desc_value.innerHTML= '<textarea name="pivot_description_'+cnt+'" id="pivot_description_id_'+cnt+'" cols="40" rows="5"></textarea>';


	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'pivot_style_row_id_'+cnt;

	var pivot_name = row.insertCell(0);
    pivot_name.innerHTML = 'Pivot Display Style#'+currentCnt;

    var pivot_name_value = row.insertCell(1);
	pivot_name_value.colSpan = 10;
	var stylelen = pivotStyleArr.length;
	var unithtml = '<select name="pivot_style_'+cnt+'" id="pivot_style_id_'+cnt+'"><option value="">---Select Style---</option>';
	for(i=0;i<stylelen;i++){
		unithtml += '<option value="'+pivotStyleIdArr[i]+'">'+pivotStyleArr[i]+'</option>';
	}
	unithtml +='</select>';	
	pivot_name_value.innerHTML = unithtml;

	rowCount = rowCount+1;
	var row = table.insertRow(rowCount);
        row.id = 'pivot_upload_image_id_'+cnt;
        var pivot_img = row.insertCell(0);
        pivot_img.innerHTML = 'Upload Pivot Image:#'+currentCnt;

        var pivot_img_value = row.insertCell(1);
        pivot_img_value.colSpan = 10;
        pivot_img_value.innerHTML= '<input name="uploadedfile_'+cnt+'" type="file" /><br />';

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'pivot_status_row_id_'+cnt;

	var pivot_stat = row.insertCell(0);
    pivot_stat.innerHTML = 'Pivot Status#'+currentCnt;

    var pivot_stat_value = row.insertCell(1);
	pivot_stat_value.colSpan = 10;
    pivot_stat_value.innerHTML= '<select name="pivot_status_'+cnt+'" id="pivot_status_id_'+cnt+'"><option value="1">Active</option><option value="0">InActive</option></select>';

	
	/*rowCount = rowCount+1;
	var row = table.insertRow(rowCount);
	row.id = 'pivot_remove_linkrow_id_'+cnt;
	var pivot_name =  row.insertCell(0);
	var pivot_name_value = row.insertCell(1);
	pivot_name_value.colSpan = 10;
	pivot_name_value.innerHTML = '<div align="right"><a href="javascript:void(0);" onclick="javascript:remove_pivot_row('+cnt+');" id="pivotlink_"'+cnt+'">Remove Pivot</a></div>';*/
	document.getElementById("pivotboxcnt").value = currentCnt;
	document.getElementById("rowCount").value = parseInt(rowCount)+parseInt(1);
	return true;
}
function removeTr(rowId){
    var row = document.getElementById(rowId);
	if(row.parentElement){
	       	row.parentElement.removeChild(row);
	}else if(row.parentNode){
		row.parentNode.removeChild(row);
	}
	var cnt = document.getElementById("pivotboxcnt").value;
	var rowCount = document.getElementById("rowCount").value;
	//var currentCnt = parseInt(cnt)-parseInt(1);
	//document.getElementById("pivotboxcnt").value = currentCnt;
 	document.getElementById("rowCount").value = parseInt(rowCount)-parseInt(1);
	return false;
}
function remove_pivot_row(){	
	var rowCount = document.getElementById("pivotboxcnt").value;
	rowCount = parseInt(rowCount)-parseInt(1);
	if(rowCount == 0){return false;}
	/*if(document.getElementById('pivot_remove_linkrow_id_'+rowCount)){
		removeTr('pivot_remove_linkrow_id_'+rowCount);
    	}*/
	if(document.getElementById('pivot_status_row_id_'+rowCount)){
		removeTr('pivot_status_row_id_'+rowCount);
	}
	if(document.getElementById('pivot_upload_image_id_'+rowCount)){
		removeTr('pivot_upload_image_id_'+rowCount);
	}
	if(document.getElementById('pivot_style_row_id_'+rowCount)){
		removeTr('pivot_style_row_id_'+rowCount);
	}
	if(document.getElementById('pivot_desc_row_id_'+rowCount)){
		removeTr('pivot_desc_row_id_'+rowCount);
	}
	if(document.getElementById('pivot_group_row_id_'+rowCount)){
		removeTr('pivot_group_row_id_'+rowCount);
	}
	if(document.getElementById('pivot_name_row_id_'+rowCount)){
		removeTr('pivot_name_row_id_'+rowCount);
	}
	document.getElementById("pivotboxcnt").value = rowCount;
	return false;
}

function sPivotOverPagination(page,startlimit,cnt,filename,divid,category_id){

	
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