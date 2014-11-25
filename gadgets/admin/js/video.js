function tiny(){
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,indicime",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,link,unlink,indicime",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Skin options
		skin : "o2k7",
		skin_variant : "silver",

		// Example content CSS (should be your site CSS)
		content_css : "css/example.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "js/template_list.js",
		external_link_list_url : "js/link_list.js",
		external_image_list_url : "js/image_list.js",
		media_external_list_url : "js/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
		username : "Some User",
		staffid : "991234"
		}
	});
}
function getVideosDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
		var url = admin_web_url+'ajax/add_video_dashboard.php';
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
            setupTinyMCE();
	return true;
}
function getProductLanguageVideosDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/video_language_dashboard.php';
        //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt);return false;
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
        tiny();
        return true;
}
function getLanguageVideoDashboardByType(divid,ajaxloaderid,category_id,startlimit,cnt){
	//alert("called");
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
        if(document.getElementById('view_section_id')){
                var view_section_id = document.getElementById('view_section_id').value;
        }
        document.getElementById(ajaxloaderid).style.display = "block";
    	if(divid == ""){ return false; }
                var url = admin_web_url+'ajax/video_language_dashboard.php';
                //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id)
       		$.ajax({
                        url: url,
                        data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id,
                        success: function(data){
                        //alert(data);
                	document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
			onLoad();
			google.load("elements", "1", {
        packages: "transliteration"
  });
        tiny();
        return true;
}
function getProductVideosDashboardByType(divid,ajaxloaderid,category_id,startlimit,cnt){
	//alert("dsdsdsdsds");
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
	//alert(type_id);
	//alert(divid+','+ajaxloaderid+','+category_id);
	document.getElementById(ajaxloaderid).style.display = "block";
    if(divid == ""){ return false; }
		var url = admin_web_url+'ajax/add_video_dashboard.php';
		//alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&article_type_id='+type_id)
        $.ajax({
			url: url,
			data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&type_id='+type_id,
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	tiny();
	return true;
}

function updateProductVideos(divid,ajaxloaderid,product_video_id,productid,product_info_id,categoryid,brandid,startlimit,cnt){
	
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
	/*if(document.getElementById('video_type_id')!="undefined"){
		var type_id = document.getElementById('video_type_id').value;
	}*/
	//alert(divid+','+ajaxloaderid+','+category_id);
	document.getElementById(ajaxloaderid).style.display = "block";
    if(divid == ""){ return false; }
		var url = admin_web_url+'ajax/add_video_dashboard.php';
        $.ajax({
			url: url,
			data: 'act=update&product_info_id='+product_info_id+'&catid='+categoryid+'&bid='+brandid+'&pid='+productid+'&startlimit='+startlimit+'&cnt='+cnt+'&actiontype=Update',
				
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
		//tiny();
	//getProductByBrand('ajaxloader',productid);
	//if(brandid!=''){
	document.getElementById('select_brand_id').disabled="disabled";
	//}
	if(document.getElementById('select_brand_id')!='0'){
		if(product_info_id){		
			getModelByBrand(ajaxloaderid,product_info_id);
			if(document.getElementById('select_model_id')){
				document.getElementById('select_model_id').disabled="disabled";
				document.getElementById('hd_select_model_id').value = product_info_id;
			}
		}
		/*if(document.getElementById('select_model_id')!='0'){
			if(productid){
				getVariantByModel(ajaxloaderid,productid);
				if(document.getElementById('product_id')){
					document.getElementById('product_id').disabled="disabled";
					document.getElementById('hd_product_id').value = productid;
				}
			}
		}*/
	}

	if(document.getElementById('actiontype')){
		document.getElementById('actiontype').value = 'Update';
	}
	//alert(document.getElementById('actiontype').value);
	setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
	return true;
}

function updateLanguageVideo(divid,ajaxloaderid,video_id,category_id,video_type,startlimit,cnt){

        if(category_id == ''){
                var category_id = document.getElementById('selected_category_id').value;
		if(category_id == ''){
	                alert("Please select category.");
        	        return false;
		}
        }
	var view_section_id = document.getElementById('view_section_id').value;
        document.getElementById(ajaxloaderid).style.display = "block";
	if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/video_language_dashboard.php';
	//alert(url+'act=update&video_id='+video_id+'&catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&video_type='+video_type+'&view_section_id='+view_section_id);
        $.ajax({
                url: url,
                data: 'act=update&video_id='+video_id+'&catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&video_type='+video_type+'&view_section_id='+view_section_id,

                success: function(data){
                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	if(document.getElementById('view_section_id')){
                document.getElementById('hd_view_section_id').value = document.getElementById('view_section_id').value;
        }
	if(document.getElementById('actiontype')){
                document.getElementById('actiontype').value = 'Update';
        }
        //alert(document.getElementById('actiontype').value);
		onLoad();
			google.load("elements", "1", {
        packages: "transliteration"
  });
        tiny();
        return true;
}
function deleteProductVideos(video_id){
	document.getElementById('actiontype').value = 'Delete';
	document.getElementById('video_id').value = video_id;
    	var answer = confirm ("Are you sure.Want to delete video?")
    	if (answer){
         	document.product_manage.submit();
         	return true;
     	}
	return false;
}

function deleteLanguageVideo(language_video_id){
       document.getElementById('actiontype').value = 'Delete';

        document.getElementById('hd_language_video_id').value = language_video_id;
        var answer = confirm ("Are you sure.Want to delete video?")
        if (answer){
                document.product_manage.submit();
                return true;
        }
        return false;
}

function fillVideoList(category_id,startlimit,cnt){
	var category_id = document.getElementById('selected_category_id').value;
        if(category_id == '' ||  category_id == 0){return false;}

	if(document.getElementById('select_type_id')){
                var view_section_id = document.getElementById('select_type_id').value;
		if(view_section_id == "0"){
			if(document.getElementById('select_video_id')){
        	        	document.getElementById('select_video_id').innerHTML = "<option value='0'>--Select Video--</option>";
	        	}
			return false;
		}
        }

	var url = admin_web_url+'ajax/get_videos_by_type.php';
	//alert(url+'category_id='+category_id+'&view_section_id='+view_section_id+'&startlimit='+startlimit+'&cnt='+cnt);
        var html = $.ajax({ url: url, data: 'category_id='+category_id+'&view_section_id='+view_section_id+'&startlimit='+startlimit+'&cnt='+cnt, success: function(data){ }, async: false}).responseText;

	if(document.getElementById('select_video_id')){
                document.getElementById('select_video_id').innerHTML = html;
        }
	return false;
}

function getModelByBrand(ajaxloaderid,product_name_id){
	
	var brand_id = document.getElementById('select_brand_id').value;
	if(brand_id == '' ||  brand_id == 0){return false;}


	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == '' ||  category_id == 0){return false;}

	//if(productid!=''){}
	
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_slideshow_model.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_name_id='+product_name_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	
	var table = document.getElementById("Update");
        rowCount = "1";


	var rowId='product_row_id_'+rowCount;
	if(document.getElementById(rowId)){
		removeTr(rowId);
	}
	var row = table.insertRow(rowCount);
	
	row.id = rowId;
	
	var product_name = row.insertCell(0);

    	product_name.innerHTML = 'Model Name';

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

	//if(productid!=''){}
	
	document.getElementById(ajaxloaderid).style.display = "block";
	var url = admin_web_url+'ajax/select_variant.php';
	var html = $.ajax({ url: url, data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_id='+product_id+'&product_name_id='+product_name_id, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;
	
	var table = document.getElementById("Update");
	var rowCount = "2";


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

function validateLanguageProduct(){
	if(document.getElementById('actiontype')){
		if(document.getElementById('actiontype').value == ""){
	                document.getElementById('actiontype').value = 'Insert';
		}
        }
	if(document.getElementById('select_type_id').value == '0'){
                alert("Please Select Video Type.");
                return false;
        }
	if(document.getElementById("select_video_id").value == '0'){
                alert("Please Select Video.");
                return false;
        }
	if(document.getElementById('language').value == '0'){
                alert("Please Select Language.");
                return false;
        }
	if(document.getElementById('view_section_id')){
                document.getElementById('hd_view_section_id').value = document.getElementById('view_section_id').value;
        }	
        var iTotalRowsCurrent = document.getElementById('display_rows').value;
        return true;
}

function validateProduct(){
	if(document.getElementById('actiontype')){
		document.getElementById('actiontype').value = 'Insert';
	}
	if(document.getElementById('title_1').value == ''){
		alert("Please enter title");
		return false;
	}
	var iTotalRowsCurrent = document.getElementById('display_rows').value;
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

function soVideosPagination(page,startlimit,cnt,filename,divid,category_id,type_selected){

	
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
			data: 'catid='+category_id+'&page='+page+'&startlimit='+startlimit+'&cnt='+cnt+'&video_type_id='+type_selected,
			success: function(data){
				//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
               },
            async:false
        });
	
	return true;
}
