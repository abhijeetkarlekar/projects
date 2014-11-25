function tiny(){
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		theme_advanced_buttons1 : "bold,italic,underline,link,unlink",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		skin : "o2k7",
		skin_variant : "silver",
		content_css : "css/example.css",
		template_external_list_url : "js/template_list.js",
		external_link_list_url : "js/link_list.js",
		external_image_list_url : "js/image_list.js",
		media_external_list_url : "js/media_list.js",
		template_replace_values : {
		username : "Some User",
		staffid : "991234"
		}
	});
}
function getSlideshowDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
		var url = admin_web_url+'ajax/slideshow_dashboard.php';
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
            setupTinyMCE();
            setDatePicker('date-picker');
		return true;
}
function getProductVideosDashboardByType(divid,ajaxloaderid,category_id,startlimit,cnt){
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
	if(document.getElementById('video_type_id')!="undefined"){
		var type_id = document.getElementById('video_type_id').value;
	}
	document.getElementById(ajaxloaderid).style.display = "block";
    if(divid == ""){ return false; }
		var url = admin_web_url+'ajax/add_video_dashboard.php';
		$.ajax({
			url: url,
			data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&type_id='+type_id,
			success: function(data){
				document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
		return true;
}
function updateProductSlideshow(divid,ajaxloaderid,slideshow_id,categoryid,productid,product_info_id,brandid,product_slide_id,startlimit,cnt){
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
	var url = admin_web_url+'ajax/slideshow_dashboard.php';
    $.ajax({
			url: url,
			data: 'act=update&slideshow_id='+slideshow_id+'&catid='+categoryid+'&product_slide_id='+product_slide_id+'&actiontype=Update',
			success: function(data){
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
        if(document.getElementById('select_brand_id')!='0'){
                if(product_info_id){
                        getModelByBrand(ajaxloaderid,product_info_id);
                        
                }
                /*if(document.getElementById('select_model_id')!='0'){
                        if(productid){
                                getVariantByModel(ajaxloaderid,productid);
                                
                        }
                }*/
        }
	if(document.getElementById('actiontype')){
		document.getElementById('actiontype').value = 'Update';
	}
	 setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
	return true;
}

function deleteProductSlideshow(product_slide_id){
	document.getElementById('actiontype').value = 'Delete';
	//document.getElementById('slideshow_id').value = slideshow_id;
	document.getElementById('product_slideshow_id').value = product_slide_id;
	
    	var answer = confirm ("Are you sure.Want to delete slideshow?")
    	if (answer){
         	document.product_manage.submit();
         	return true;
     	}
	return false;
}

function getModelByBrand(ajaxloaderid,product_name_id){
        var brand_id = document.getElementById('select_brand_id').value;
        if(brand_id == '' ||  brand_id == 0){return false;}
        var category_id = document.getElementById('selected_category_id').value;
        if(category_id == '' ||  category_id == 0){return false;}
        document.getElementById(ajaxloaderid).style.display = "block";
        var url = admin_web_url+'ajax/select_slideshow_model.php';
        var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_name_id='+product_name_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
        if(document.getElementById('updateproduct')){
                document.getElementById('updateproduct').innerHTML = "";
                document.getElementById('updateproduct').style.display = "none";
        }
        var table = document.getElementById("Update");
        var rowCount = 1;
        var rowId='product_row_id_'+rowCount;

        if(document.getElementById(rowId)){
                removeTr(rowId);
        }

        
        var row = table.insertRow(rowCount);
        row.id = rowId;
        var product_name = row.insertCell(0);
        product_name.innerHTML = '<label>Product Name</label>';
        var product_name_value = row.insertCell(1);
        product_name_value.colSpan = 1;
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

	//if(productid!=''){}
	
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



function validateProduct(){
	if(document.getElementById('actiontype')){
		document.getElementById('actiontype').value = 'Insert';
	}
	if(document.getElementById('slide_title').value == ''){
		alert("Please enter slide title");
		return false;
	}
	/*if(document.getElementById('title_1').value == ''){
		alert("Please enter title");
		return false;
	}*/
	var iTotalRowsCurrent = document.getElementById('display_rows').value;
	for(i=1;i<=iTotalRowsCurrent;i++){
                var chk_flag = document.getElementById('box_'+i).checked;
                if(chk_flag == true){
                        document.getElementById('check_flag_'+i).value = "1";
                }else{
                        document.getElementById('check_flag_'+i).value = "0";
                }
        }
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
