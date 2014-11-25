function getUpcomingProductDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){

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
		var url = admin_web_url+'ajax/upcoming_product_dashboard_ajax.php';
		//alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt)
	        $.ajax({
			url: url,
			data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt,
			success: function(data){
				//alert(data);
        	        document.getElementById(divid).innerHTML = data;
                	document.getElementById(divid).style.display="block";
	                document.getElementById(ajaxloaderid).style.display = "none";
			//timeout = setTimeout('initCalender()', 10);		
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
function updateUpComingProduct(divid,ajaxloaderid,upcoming_product_id,product_name_id,categoryid,startlimit,cnt){
	if(document.getElementById('actiontype')){
                document.getElementById('actiontype').value = 'Update';
        }
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
	var url = admin_web_url+'ajax/upcoming_product_dashboard_ajax.php';
	//alert(url+'?act=update&lpid='+latprdid+'&catid='+categoryid+'&bid='+brandid+'&pid='+productid+'&startlimit='+startlimit+'&cnt='+cnt)
        $.ajax({
		url: url,
		data: 'act=update&actiontype=Update&upcoming_product_id='+upcoming_product_id+'&product_name_id='+product_name_id+'&catid='+categoryid+'&startlimit='+startlimit+'&cnt='+cnt,
		success: function(data){
		//alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
		//timeout = setTimeout('initCalender()', 10);		
            },
            async:false
        });
	//tiny();
	setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
	return true;
}


function deleteUpComingProduct(upcoming_product_id){
   	document.getElementById('actiontype').value = 'Delete';
	document.getElementById('upcoming_product_id').value = upcoming_product_id;
    	var answer = confirm ("Are you sure.Want to delete this upcoming product?")
    	if (answer){
         	document.product_manage.submit();
         	return true;
     	}
	return false;
}

function validateProduct(){
	if(document.getElementById('actiontype')){
                if((document.getElementById('actiontype').value) == ''){
                        document.getElementById('actiontype').value = 'Insert';
                }
        }
	if(document.getElementById('select_model_id').value == ''){
		alert("Please select upcoming product.");
		return false;
	}
	if(document.getElementById('select_feature_id').value == ''){
		alert("Please select body style.");
		return false;
	}
	/*if(document.getElementById('exp_price').value == ''){
		alert("Please enter expected price.");
		return false;
	}*/
	if(document.getElementById('exp_launch_text').value == ''){
		alert("Please enter expected launch date.");
		return false;
	}
	if(document.getElementById('select_exp_month').value == ''){
                alert("Please select expected launch month.");
                return false;
        }
	if(document.getElementById('select_exp_year').value == ''){
                alert("Please select expected launch year.");
                return false;
        }
	/*if(document.getElementById('short_desc')){
		if(document.getElementById('short_desc').value == ''){
        	        alert("Please enter short description.");
                	return false;
	        }
	}
	if(document.getElementById('content')){
		if(document.getElementById('content').value == ''){
	                alert("Please enter content.");
        	        return false;
        	}
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
