function getFeatureDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/feature_dashboard.php';
        $.ajax({
         url: url,
         data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt,
         success: function(data){
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
function updateFeature(featureid,featurename,featuregroup,featuredesc,featureunit,featurestatus,mainfeaturegroup,seo_path){
	seo_path = seo_path.replace(/%26/,"&");
	seo_path = seo_path.replace(/&#039;/,"'");
	seo_path = seo_path.replace(/%2C/,",");
	seo_path = seo_path.replace(/%2F/,"/");
	featurename = featurename.replace(/%26/,"&");
	featurename = featurename.replace(/&#039;/,"'");
	featurename = featurename.replace(/%2C/,",");
	featurename = featurename.replace(/%2F/,"/");
	featuredesc = decodeURIComponent((featuredesc + '').replace(/\+/g, '%20'));;
	if(mainfeaturegroup == 0){ mainfeaturegroup = ""; }
	document.getElementById('select_main_group_0').value = mainfeaturegroup;
	document.getElementById('feature_name_0').value = featurename;
	document.getElementById('seo_path_0').value = seo_path;
	document.getElementById('feature_description_0').value = featuredesc;
	document.getElementById('feature_unit_0').value = featureunit;
	document.getElementById('feature_status_0').value = featurestatus;
	document.getElementById('feature_id').value = featureid;
	document.getElementById('actiontype').value = 'Update';
	getSubGroupByMainGroup('ajaxloadermaingroup','select_main_group_0','select_feature_group_0',mainfeaturegroup,featuregroup);
	return false;
}


function deleteFeature(featureid,featurename){
	featurename = featurename.replace(/%26/,"&");
	featurename = featurename.replace(/&#039;/,"'");
	featurename = featurename.replace(/%2C/,",");
	featurename = featurename.replace(/%2F/,"/");
	document.getElementById('feature_id').value = featureid;
    	document.getElementById('actiontype').value = 'Delete';
    	var answer = confirm ("Are you sure.Want to delete feature '"+featurename+"'?")
    	if (answer){
         	document.feature_manage.submit();
         	return true;
     	}
	return false;
}
function validateFeature(){
	if(isCategorySelected() == false){
                alert("Please select the category.");
                return false;
        }
        if(isLastLvlCategory() == false){
                alert("Please select last level category.");
                return false;
        }
	var cnt = document.getElementById("featureboxcnt").value;
	for(i=0;i<cnt;i++){
		if(document.getElementById('feature_name_'+i).value == ''){
			alert("Please add the feature");
			document.getElementById('feature_name_'+i).focus();
			return false;
		}
		if(document.getElementById('select_main_group_'+i).value == ''){
			alert("Please select the main feature group");
			return false;
		}
		if(document.getElementById('select_feature_group_'+i)){
			var td_val = document.getElementById('td_select_feature_group_'+i).innerHTML;
			var str='<select name="select_feature_group_0" id="select_feature_group_0"><option value="">---Select Group---</option></select>';
			if((td_val != str) && (document.getElementById('select_feature_group_'+i).value == '')){
				alert("Please select the feature sub group");
				return false;
			}
		}
	}
	return true;
}
function add_more_feature(){
	var cnt = document.getElementById("featureboxcnt").value;
	var currentCnt = parseInt(cnt)+parseInt(1);
	var table = document.getElementById("Update");
	var rowCount = parseInt(document.getElementById("rowCount").value);

	var row = table.insertRow(rowCount);
	row.id = 'feature_name_row_id_'+cnt;

	var feature_name = row.insertCell(0);

    	feature_name.innerHTML = 'Feature Name#'+currentCnt;

    	var feature_name_value = row.insertCell(1);
	feature_name_value.colSpan = 10;
    	feature_name_value.innerHTML= '<input type="text" name="feature_name_'+cnt+'" id="feature_name_id_'+cnt+'" size="50"/>';
	
	rowCount = rowCount+1;

        var row = table.insertRow(rowCount);
        row.id = 'main_group_row_id_'+cnt;

        var main_feature_group = row.insertCell(0);
        main_feature_group.innerHTML = 'Main Feature Group#'+currentCnt;

        var sel_main_feature_group = row.insertCell(1);
        
        var maingrouplength = featureMainGroupArr.length;
	var maingrouphtml = '<select name="select_main_group_'+cnt+'" id="select_main_group_'+cnt+'" onchange="getSubGroupByMainGroup(\'ajaxloadermaingroup\',\'select_main_group_'+cnt+'\',\'select_feature_group_'+cnt+'\',\'\');"><option value="">---Select Main Feature Group---</option>';
        for(i=0;i<maingrouplength;i++){
                maingrouphtml += '<option value="'+featureMainGroupIdsArr[i]+'">'+featureMainGroupArr[i]+'</option>';
        }
        maingrouphtml += '</select>';
        sel_main_feature_group.innerHTML = maingrouphtml;

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'feature_group_row_id_'+cnt;

	var feature_group = row.insertCell(0);
    	feature_group.innerHTML = 'Feature Group#'+currentCnt;

    	var sel_feature_group = row.insertCell(1);
	sel_feature_group.colSpan = 10;
	sel_feature_group.id = "td_select_feature_group_"+cnt;
    	grouphtml = '<select name="select_feature_group_'+cnt+'" id="select_feature_group_id_'+cnt+'"><option value="">---Select Group---</option>';
	/*var grouplength = featureGroupArr.length;
	for(i=0;i<grouplength;i++){
		grouphtml += '<option value="'+featureGroupIdsArr[i]+'">'+featureGroupArr[i]+'</option>';
	}*/
	grouphtml += '</select>';

	sel_feature_group.innerHTML = grouphtml;

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'feature_desc_row_id_'+cnt;

	var feature_desc = row.insertCell(0);
        feature_desc.innerHTML = 'Feature Description#'+currentCnt;

        var feature_desc_value = row.insertCell(1);
	feature_desc_value.colSpan = 10;
        feature_desc_value.innerHTML= '<textarea name="feature_description_'+cnt+'" id="feature_description_id_'+cnt+'" cols="40" rows="5"></textarea>';


	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'feature_unit_row_id_'+cnt;

	var feature_name = row.insertCell(0);
	feature_name.innerHTML = 'Feature Unit#'+currentCnt;

    	var feature_name_value = row.insertCell(1);
	feature_name_value.colSpan = 10;
	var unitlen = featureUnitArr.length;
	var unithtml = '<select name="feature_unit_'+cnt+'" id="feature_unit_id_'+cnt+'"><option value="NULL" id="feature_unit_id_'+cnt+'">---Select Unit---</option>';
	for(i=0;i<unitlen;i++){
		unithtml += '<option value="'+featureUnitIdArr[i]+'">'+featureUnitArr[i]+'</option>';
	}
	unithtml +='</select>';	
	feature_name_value.innerHTML = unithtml;

	rowCount = rowCount+1;
        var row = table.insertRow(rowCount);
        row.id = 'feature_upload_image_id_'+cnt;
        var feature_img = row.insertCell(0);
        feature_img.innerHTML = 'Upload Feature Image:#'+currentCnt;

        var feature_img_value = row.insertCell(1);
        feature_img_value.colSpan = 10;
        feature_img_value.innerHTML= '<input name="uploadedfile_'+cnt+'" type="file" /><br />';

	rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'feature_status_row_id_'+cnt;

	var feature_name = row.insertCell(0);
    	feature_name.innerHTML = 'Feature Status#'+currentCnt;

    	var feature_name_value = row.insertCell(1);
	feature_name_value.colSpan = 10;
    	feature_name_value.innerHTML= '<select name="feature_status_'+cnt+'" id="feature_status_id_'+cnt+'"><option value="1">Active</option><option value="0">InActive</option></select>';

	
	/*rowCount = rowCount+1;

	var row = table.insertRow(rowCount);
	row.id = 'feature_remove_linkrow_id_'+cnt;
	
	var emptyspace =  row.insertCell(0);
	emptyspace.colSpan = 11;
	emptyspace.innerHTML = '<div align="right"><a href="javascript:void(0);" onclick="javascript:remove_feature_row('+cnt+');" id="featurelink_"'+cnt+'">Remove Features</a></div>';*/
	document.getElementById("featureboxcnt").value = currentCnt;
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
	var cnt = document.getElementById("featureboxcnt").value;
	var rowCount = document.getElementById("rowCount").value;
 	document.getElementById("rowCount").value = parseInt(rowCount)-parseInt(1);
	return false;
}
function remove_feature_row(){	
	var rowCount = document.getElementById("featureboxcnt").value;
        rowCount = parseInt(rowCount)-parseInt(1);
	if(rowCount == 0){return false;}
	/*if(document.getElementById('feature_remove_linkrow_id_'+rowCount)){
		removeTr('feature_remove_linkrow_id_'+rowCount);
    	}*/
	if(document.getElementById('feature_status_row_id_'+rowCount)){
		removeTr('feature_status_row_id_'+rowCount);
	}
	if(document.getElementById('feature_upload_image_id_'+rowCount)){
		removeTr('feature_upload_image_id_'+rowCount);
	}
	if(document.getElementById('feature_unit_row_id_'+rowCount)){
		removeTr('feature_unit_row_id_'+rowCount);
	}
	if(document.getElementById('feature_desc_row_id_'+rowCount)){
		removeTr('feature_desc_row_id_'+rowCount);
	}
	if(document.getElementById('main_group_row_id_'+rowCount)){
		removeTr('main_group_row_id_'+rowCount);
	}
	if(document.getElementById('feature_group_row_id_'+rowCount)){
		removeTr('feature_group_row_id_'+rowCount);
	}
	if(document.getElementById('feature_name_row_id_'+rowCount)){
		removeTr('feature_name_row_id_'+rowCount);
	}
	document.getElementById("featureboxcnt").value = rowCount;
	return false;
}

function getSubGroupByMainGroup(ajaxloaderid,maingroupnameid,featuregroupnameid,featuredid,featuregroup){
	var main_group_id = document.getElementById(maingroupnameid).value;
	if(main_group_id == ""){
		var html='<select name="'+featuregroupnameid+'" id="'+featuregroupnameid+'"><option value="">---Select Group---</option></select>';
		
	}else{
		var category_id = document.getElementById('selected_category_id').value;
		if(category_id == '' ||  category_id == 0){return false;}
		document.getElementById(ajaxloaderid).style.display = "block";
		var url = admin_web_url+'ajax/select_main_group.php';
		var html = $.ajax({ 
					url: url,
					data: 'category_id='+category_id+'&featuregroupnameid='+featuregroupnameid+'&main_group_id='+main_group_id+'&featured_id='+featuredid+'&featuregroup='+featuregroup,
					success:
					function(data){
						document.getElementById(ajaxloaderid).style.display = "none";
					},
					async: false
					}).responseText;
	}
	/*var table = document.getElementById("Update");
	var rowCount = 2;
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var feature_group = row.insertCell(0);
	feature_group.innerHTML = 'Feature Group#';
	var feature_group_value = row.insertCell(1);
	feature_group_value.colSpan = 10;
	feature_group_value.innerHTML = html;
	document.getElementById(featuregroupnameid).style.display = "block";*/
	var td_id = "td_"+featuregroupnameid;
	document.getElementById(td_id).innerHTML = html;
	return true;
}

function sFeatureDetailsOverPagination(page,startlimit,cnt,filename,divid,category_id){

	
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