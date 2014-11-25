function getProductTopCompetitorDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
		var url = admin_web_url+'ajax/compare_top_competitor_dashboard.php';
		//alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt)
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

function updateProductCompetitor(divid,ajaxloaderid,competitor_id,productid,categoryid,brandid,product_info_id,product_ids,comp_brand_id,comp_model_id){
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
		var url = admin_web_url+'ajax/compare_top_competitor_dashboard.php';
        $.ajax({
			url: url,
			data: 'act=update&catid='+categoryid+'&competitor_product_id='+competitor_id+'&product_id='+productid,
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	document.getElementById('actiontype').value = 'Update';
	getModelByBrand('ajaxloader',productid);
	//getVariantByModel('ajaxloadervariant',productid);
	getModelByBrandCompetitor('ajaxloader1',product_ids);
	//getVariantByModelCompetitor('ajaxloadervariant1',product_ids);
	return true;
}

function deleteProductCompetitor(competitor_product_id){
	   	document.getElementById('actiontype').value = 'Delete';
		document.getElementById('competitor_product_id').value = competitor_product_id;
    	var answer = confirm ("Are you sure.Want to delete competitor?")
    	if (answer){
         	document.product_manage.submit();
         	return true;
     	}
	return false;
}

function getModelByBrand(ajaxloaderid,product_id){
	var brand_id = document.getElementById('select_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_model_variant.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_id='+product_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	var rowCount = 1;
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var product_name = row.insertCell(0);
   	product_name.innerHTML = '<label>Model Name</label>';
	var product_name_value = row.insertCell(1);
	product_name_value.colSpan = 10;
	product_name_value.innerHTML = html;
	return true;
}

function getVariantByModel(ajaxloaderid,product_id){
	var brand_id = document.getElementById('select_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}
	var product_name_id = document.getElementById('select_model_id').value;
	if(product_name_id == '' ||  product_name_id == 0){return false;}
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_variant.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_id='+product_id+'&product_name_id='+product_name_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	var rowCount = 2;
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var product_name = row.insertCell(0);
   	product_name.innerHTML = 'Product Name';
	var product_name_value = row.insertCell(1);
	product_name_value.colSpan = 10;
	product_name_value.innerHTML = html;
	return true;
}

function getModelByBrandCompetitor(ajaxloaderid,product_id){
	var brand_id = document.getElementById('select_comp_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_competitor_model_variant.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_id='+product_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	
	if(document.getElementById('select_brand_id').value!=""){
	/*if(document.getElementById('select_model_id').value!=""){
		var rowCount = 4;
		}else{var rowCount = 3;}*/
		var rowCount = 3;
	}else{var rowCount = 2;}
	
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var product_name = row.insertCell(0);
   	product_name.innerHTML = '<label>Competitor Model Name</label>';
	var product_name_value = row.insertCell(1);
	product_name_value.colSpan = 10;
	product_name_value.innerHTML = html;
	return true;
}

function getVariantByModelCompetitor(ajaxloaderid,product_id){
	var brand_id = document.getElementById('select_comp_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}
	var product_name_id = document.getElementById('select_comp_model_id').value;
	if(product_name_id == '' ||  product_name_id == 0){return false;}
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_competitor_variant.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_id='+product_id+'&product_name_id='+product_name_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	if(document.getElementById('select_brand_id').value!=""){
		if(document.getElementById('select_model_id').value!=""){
			var rowCount = 5;
		}else{var rowCount = 4;}
	}else{var rowCount = 3;}
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var product_name = row.insertCell(0);
   	product_name.innerHTML = 'Competitor Product Name';
	var product_name_value = row.insertCell(1);
	product_name_value.colSpan = 10;
	product_name_value.innerHTML = html;
	return true;
}
function validateProduct(){
	if(document.getElementById('select_comp_brand_id').value == ''){
		alert("Please select the Compare Set.");
		return false;
	}
	return true;
}

function getProductByBrand(ajaxloaderid,productid){
	var brand_id = document.getElementById('select_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_product.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&productid='+productid, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	var rowCount = 1;
	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	row.id = rowId;
	var product_name = row.insertCell(0);
   	product_name.innerHTML = 'Product Name';
	var product_name_value = row.insertCell(1);
	product_name_value.colSpan = 10;
	product_name_value.innerHTML = html;
	return true;
}

function removeTr(rowId){
    var row = document.getElementById(rowId);
	if(row.parentElement){
	       	row.parentElement.removeChild(row);
	}else if(row.parentNode){
		row.parentNode.removeChild(row);
	}
	
	return false;
}
function remove_product_row(rowCount){	
	if(rowCount == 0){return false;}
	if(document.getElementById('product_remove_linkrow_id_'+rowCount)){
		removeTr('product_remove_linkrow_id_'+rowCount);
    	}
	if(document.getElementById('product_status_row_id_'+rowCount)){
		removeTr('product_status_row_id_'+rowCount);
	}
	if(document.getElementById('product_style_row_id_'+rowCount)){
		removeTr('product_style_row_id_'+rowCount);
	}
	if(document.getElementById('product_style_row_id_'+rowCount)){
		removeTr('product_style_row_id_'+rowCount);
	}
	if(document.getElementById('product_desc_row_id_'+rowCount)){
		removeTr('product_desc_row_id_'+rowCount);
	}
	if(document.getElementById('product_group_row_id_'+rowCount)){
		removeTr('product_group_row_id_'+rowCount);
	}
	if(document.getElementById('product_name_row_id_'+rowCount)){
		removeTr('product_name_row_id_'+rowCount);
	}
	return false;
}

function getUploadData (sFrm,sTitle,sId,sPath,mType,sImageCat){
	window.open('get_upload.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=300,left=300,top=300');
}
function getUploadedDataList (sFrm,sTitle,sId,sPath,mType,sImageCat){
	window.open('search_store.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=400,left=300,top=300');
}
function cleartopcompetitor(){
	window.location.reload()
}