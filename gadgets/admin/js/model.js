function getProductModelDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){

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
                var url = admin_web_url+'ajax/model_dashboard.php';
                //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt)
        $.ajax({
                        url: url,
                        data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt,
                        success: function(data){
                                //alert(data);
                document.getElementById(divid).innerHTML = data;

                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
                                //initCal();
            },
            async:false
        });
        //$(document).ready(function () {
            setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
        //});
        //tiny();
        return true;
}

function updateProductModel(divid,ajaxloaderid,mid,categoryid,brandid,startlimit,cnt,search_status){

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
                var url = admin_web_url+'ajax/model_dashboard.php';
        $.ajax({
                        url: url,
                        data: 'act=update&product_name_id='+mid+'&catid='+categoryid+'&startlimit='+startlimit+'&cnt='+cnt+'&actiontype=Update&search_status='+search_status,

                        success: function(data){
                                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
                                initCal();
            },
            async:false
        });
               // tiny();
        //getProductByBrand('ajaxloader',productid);
        //getModelByBrand(ajaxloaderid,product_info_id);
        //getVariantByModel(ajaxloaderid,productid);
         setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
        return true;
}



function deleteProductModel(mid){
                document.getElementById('actiontype').value = 'Delete';
                document.getElementById('product_name_id').value = mid;
                //alert(document.getElementById('product_name_id').value);return false;
        var answer = confirm ("Are you sure.Want to delete model?")
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
    if(document.getElementById('model_title').value == ''){
        alert("Please add the model");
        return false;
    }
    if(document.getElementById('seo_path').value == ''){
        alert("Please add the seo path");
        return false;
    }
    var model_title = cleanStr(document.getElementById('model_title').value);
    var seo_path = cleanStr(document.getElementById('seo_path').value);
    if(model_title !== seo_path){
        alert('Model name And SEO path must be similar');
        return false;
    }
    return true;
}
function getUploadData (sFrm,sTitle,sId,sPath,mType,sImageCat){
        window.open('get_upload.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=300,left=300,top=300');
}
function getUploadedDataList (sFrm,sTitle,sId,sPath,mType,sImageCat){
        window.open('search_store.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=400,left=300,top=300');
}

function sOProduOverPagination(page,startlimit,cnt,filename,divid,category_id,search_status){


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
                        data: 'catid='+category_id+'&search_status='+search_status+'&page='+page+'&startlimit='+startlimit+'&cnt='+cnt,
                        success: function(data){
                                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
               },
            async:false
        });

        return true;
}

function initCal(){

        var dates = $( "#start_date,#end_date" ).datepicker({
                showOn: "button",
                buttonImage: image_url+"calendar.gif",
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                showOn: 'both',
                numberOfMonths: 2,
                onSelect: function( selectedDate ) {
                                var option = this.id == "start_date" ? "minDate" : "maxDate",
                                        instance = $( this ).data( "datepicker" ),
                                        date = $.datepicker.parseDate(
                                                instance.settings.dateFormat ||
                                                $.datepicker._defaults.dateFormat,
                                                selectedDate, instance.settings );
                                dates.not( this ).datepicker( "option", option, date );
                }
        });
}

function getResultBYStatus(divid){
	if(divid == ""){ return false; }
	var search_status = document.getElementById('search_status').value;
        var url = admin_web_url+'ajax/model_dashboard.php';
	//alert(url+'?search_status='+search_status);
        $.ajax({
        	url: url,
                data: 'search_status='+search_status,
                success: function(data){
	                //alert(data);
                	document.getElementById(divid).innerHTML = data;
                	document.getElementById(divid).style.display="block";
	                initCal();
            },
            async:false
        });
        tiny();
        return true;
}

function AssignColors(color_id,model_color_id,model_id,category_id){
	window.open(admin_web_url+'assign_colors.php?color_id='+color_id+'&model_color_id='+model_color_id+'&model_id='+model_id+'&category_id='+category_id,'mywindow','width=500,height=600,maximize=no,resizable=no,location=no,status=no,menubar=no,scrollbars=yes');
}
