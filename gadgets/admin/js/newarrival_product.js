function getProductArticleDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){

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
		var url = admin_web_url+'ajax/newarrival_product_dashboard.php';
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

function updateArrivalProduct(divid,ajaxloaderid,latprdid,productid,categoryid,brandid,model_id,startlimit,cnt){
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
		var url = admin_web_url+'ajax/newarrival_product_dashboard.php';
		//alert(url+'?act=update&lpid='+latprdid+'&catid='+categoryid+'&bid='+brandid+'&pid='+productid+'&startlimit='+startlimit+'&cnt='+cnt)
        $.ajax({
			url: url,
			data: 'act=update&lpid='+latprdid+'&catid='+categoryid+'&bid='+brandid+'&pid='+productid+'&startlimit='+startlimit+'&cnt='+cnt,
				
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
		getModelByBrandDashboard(model_id,'select_model_variant.php','Model','',productid);
            },
            async:false
        });
		setupLeftMenu();
	$('.datatable').dataTable();
	setSidebarHeight();

	
	
	return true;
}


function deleteArrivalProduct(ltPrdid){
	   	document.getElementById('actiontype').value = 'Delete';
		document.getElementById('newarrival_product_id').value = ltPrdid;
    	var answer = confirm ("Are you sure.Want to delete Arrival product?")
    	if (answer){
         	document.product_manage.submit();
         	return true;
     	}
	return false;
}

function validateProduct(){
	if(document.getElementById('select_brand_id').value == ''){
		alert("Please select the brand.");
		return false;
	}
	/*if(document.getElementById('select_model_id').value == ''){
		alert("Please select model");
		return false;
	}*/	
	//alert("ENETER");
	return true;
}


function getProductByBrand(ajaxloaderid,productid){
	
	var brand_id = document.getElementById('select_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}


	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}

	//if(productid!=''){}
	
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



function city_details(ajaxloaderid){
	var state_id = document.getElementById('state_id').value;
	if(state_id == '' ||  state_id == 0){return false;}

	
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/city_ajax.php';
	var html = $.ajax({ url: url, data: 'state_id='+state_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	var table = document.getElementById("Update");
	var rowCount = 6;	
	var row = table.insertRow(rowCount);
	row.id = 'city_row_id_'+rowCount;

	var city_name = row.insertCell(0);

    	city_name.innerHTML = 'Show room city'+rowCount;

	var city_name_value = row.insertCell(1);
	city_name_value.colSpan = 10;
	city_name_value.innerHTML = html;
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


function getUploadData (sFrm,sTitle,sId,mType,sImageCat){
	window.open('get_upload.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=300,left=300,top=300');
}
function getUploadedDataList (sFrm,sTitle,sId,mType,sImageCat){
	window.open('search_store.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=400,left=300,top=300');
}

function getModelByBrandDashboard(iModelId,surl,divname,param,pid){
        var iBrndId=document.getElementById('select_brand_id').value;
        if(iBrndId == ""){
                $('#Model').empty().append("<select name='model' id='model'><option value=''>--All Models--</option></select>");
                return false;
        }
        var str="action=model&brand_id="+iBrndId+"&product_name_id="+iModelId+"&product_id="+pid+"&Rand="+Math.random();
        var url = admin_web_url+'ajax/'+surl;
        $.ajax({
                url: url,
                data: str,
                success: function(data){
                        $('#Model').empty().append(data);
                },
                async:false
        });
}

function getVariantByModel(ajaxloaderid,product_id){

	var iBrndId=document.getElementById('select_brand_id').value;
        if(iBrndId == ""){
                $('#Model').empty().append("<select name='model' id='model'><option value=''>--All Models--</option></select>");
                return false;
        }

	var modelid = document.getElementById('select_model_id').value;
	if(modelid == ""){
		$('#Variant').empty().append("<select name='variant' id='variant'><option value=''>--All Models--</option></select>");
                return false;

	}

        var brand_id = document.getElementById('select_brand_id').value;
        if(brand_id == '' ||  brand_id == 0){return false;}

        var product_name_id = document.getElementById('select_model_id').value;
        if(product_name_id == '' ||  product_name_id == 0){return false;}


	var str="brand_id="+iBrndId+"product_id="+product_id+"&product_name_id="+product_name_id+"&Rand="+Math.random();
        var url = admin_web_url+'ajax/select_variant.php';
        $.ajax({
                url: url,
                data: str,
                success: function(data){
                        $('#Variant').empty().append(data);
                },
                async:false
        });
}
