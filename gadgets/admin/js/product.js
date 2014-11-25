function getProductDashboard(divid,ajaxloaderid,category_id,startlimit,cnt,selected_brand_id,selected_model_id,selected_variant_id){
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
                var url = admin_web_url+'ajax/product_dashboard.php';
        $.ajax({
                        url: url,
                        data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+selected_brand_id+'&selected_model_id='+selected_model_id+'&selected_variant_id='+selected_variant_id,
                        success: function(data){
                                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
                                initCal();
            },
            async:false
        });
        if(selected_brand_id != ""){
                getModelByBrandDashboard(selected_model_id,'get_model_detail.php','Model','');
        }
        if((selected_brand_id != "") && (selected_model_id != "")){
                document.getElementById("Model").value = selected_model_id;
                getVariantByBrandModelDashboard(selected_variant_id,'get_variants.php','Variant','');
        }
        if(selected_variant_id != ""){
                document.getElementById("Variant").value = selected_variant_id;
        }
        setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
        return true;
}
var newwin=null;
function popup(url)
{
         location.href=url;
         return true;
         params  = 'width='+screen.width;
         params += ', height='+screen.height;
         params += ', top=0, left=0'
         params += ', fullscreen=yes';
         params += ', scrollbars=yes';

         newwin=window.open(url,'windowname4', params);
         newwin.opener = self;
        return true;
 if (window.focus) {newwin.focus()}
 return false;
}
function updateSubmit(){
//      document.product_manage.submit();
        if(document.getElementById('select_brand_id').value == ''){
                alert("Please select the brand.");
                return false;
        }
        if(document.getElementById('product_name').value == ''){
                alert("Please add the product");
                return false;
        }


        if(document.getElementById('varient').value != ''){
            if(document.getElementById('seo_path').value == ''){
                alert("Please add the seo path");
                return false;
            }
            var variant = cleanStr(document.getElementById('varient').value);
            var seo_path = cleanStr(document.getElementById('seo_path').value);
            if(variant !== seo_path){
                alert('Variant name And SEO path must be similar');
                return false;
            }
        }
        document.product_update_manage.submit();
        return true;
     //   var category_id = document.getElementById('selected_category_id').value;
        //      window.opener.getProductDashboard('product_dashboard','productajaxloader',category_id,'','');
        //if(window){
        //      window.close();
        //}
//      alert(category_id);

        //newwin.close();
}


function getModelByBrand(ajaxloaderid,product_name_id){
        var brand_id = document.getElementById('select_brand_id').value;
        if(brand_id == '' ||  brand_id == 0){return false;}
        var category_id = document.getElementById('selected_category_id').value;
        if(category_id == '' ||  category_id == 0){return false;}
        document.getElementById(ajaxloaderid).style.display = "block";
        var url = admin_web_url+'ajax/get_model.php';
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
        product_name.innerHTML = 'Product Name';
        var product_name_value = row.insertCell(1);
        product_name_value.colSpan = 1;
        product_name_value.innerHTML = html;
        return true;
}

function getModelByBrandDashboard(iModelId,surl,divname,param){
        var iBrndId=document.getElementById('select_brand').value;
	var cat_id = document.getElementById('selected_category_id').value;
        //alert(iBrndId);
        if(iBrndId == ""){
                $('#Model').empty().append('<option value="">--All Models--</option>');
                $('#Variant').empty().append('<option value="">--All Variants--</option>');
                return false;
        }
        var str="catid="+cat_id+"&action=model&brand_id="+iBrndId+"&product_name_id="+iModelId+"&Rand="+Math.random();
        $('#Variant').empty().append('<option value="">--All Variants--</option>');
        var url = admin_web_url+'ajax/'+surl;
        //alert("url==="+url);
        $.ajax({
                url: url,
                data: str,
                success: function(data){
                        $('#Model').empty().append(data);
                },
                async:false
        });
        $('#Variant').empty().append('<option value="">--All Variants--</option>');
}

function getVariantByBrandModelDashboard(product_id,surl,divname,param){
        var iBrndId=document.getElementById('select_brand').value;
        var iModelId=document.getElementById('Model').value;
	var cat_id = document.getElementById('selected_category_id').value;
        //alert(iBrndId);
        //alert(admin_web_url+'ajax/'+surl+"?action=model&brand_id="+iBrndId+"&product_name_id="+iModelId+"&product_id="+product_id+"&Rand="+Math.random());
        var str="catid"+cat_id+"&action=model&brand_id="+iBrndId+"&product_name_id="+iModelId+"&product_id="+product_id+"&Rand="+Math.random();
        var url = admin_web_url+'ajax/'+surl;
        //alert("url==="+url);
        $.ajax({
            url: url,
            data: str,
            success: function(data){
                $('#Variant').empty().append(data);
            },
            async:false
        });
}

function submitResearchForm(){
        var iBrndId=document.getElementById('select_brand').value;
        var iModelId=document.getElementById('Model').value;
        var iVariantId=document.getElementById('Variant').value;

        /*if(iBrndId == ""){
                alert("Please Select Brand.");
                return false;
        }*/

        document.product_manage_dashboard.submit();
        return true;
}


function updateProduct(productid,brandid,categoryid,ajaxloaderid,divid){
        //alert(productid+','+brandid+','+categoryid+','+ajaxloaderid+','+divid+'&'+html);return false;
        var selected_brand_id="";var selected_model_id = "";var selected_variant_id="";
        //selected_brand_id = document.getElementById('select_brand').value;
        //selected_model_id = document.getElementById('Model').value;
        //selected_variant_id = document.getElementById('Variant').value;

        var url = admin_web_url+'ajax/product_update_ajax.php?'+'catid='+categoryid+'&product_id='+productid+'&brand_id='+brandid+'&selected_brand_id='+selected_brand_id+'&selected_model_id='+selected_model_id+'&selected_variant_id='+selected_variant_id;
        popup(url);
        return true;
        //$("#"+divid).hide();
        var html = $.ajax({ url: url, data: 'catid='+categoryid+'&product_id='+productid+'&brand_id='+brandid, success: function(data){ document.getElementById(ajaxloaderid).style.display = "none";}, async: false}).responseText;

        //document.getElementById(divid).innerHTML = html;
        //$("#"+divid).show();
        //return true;entById('actiontype').value = 'Update';
        setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
        return false;
}
function clearForm(divid){
         document.getElementById(divid).innerHTML = "";
        $("#"+divid).hide();
        return true;
}
function deleteProduct(productid,productname){
        productname = productname.replace(/%26/,"&");
        productname = productname.replace(/&#039;/,"'");
        productname = productname.replace(/%2C/,",");
        productname = productname.replace(/%2F/,"/");
        document.getElementById('product_id').value = productid;
        document.getElementById('actiontype').value = 'Delete';
        var answer = confirm ("Are you sure.Want to delete product '"+productname+"'?")
        if (answer){
                document.product_manage.submit();
                return true;
        }
        return false;
}
function validateProduct(){
        if(isCategorySelected() == false){
                alert("Please select the category.");
                return false;
        }
        if(isLastLvlCategory() == false){
                alert("Please select last level category.");
                return false;
        }
        if(document.getElementById('select_brand_id').value == ''){
                alert("Please select the brand.");
                return false;
        }
        if(document.getElementById('product_name').value == ''){
                alert("Please add the product");
                return false;
        }

        if(document.getElementById('varient').value != ''){

            if(document.getElementById('seo_path').value == ''){
                alert("Please add the seo path");
                return false;
            }
            var variant = cleanStr(document.getElementById('varient').value);
            var seo_path = cleanStr(document.getElementById('seo_path').value);
            if(variant !== seo_path){
                alert('Variant name And SEO path must be similar');
                return false;
            }
        }
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

/*
function removeTr(rowId){
    var row = document.getElementById(rowId);
        if(row.parentElement){
                row.parentElement.removeChild(row);
        }else if(row.parentNode){
                row.parentNode.removeChild(row);
        }
        var cnt = document.getElementById("productboxcnt").value;
        var rowCount = document.getElementById("rowCount").value;
        var currentCnt = parseInt(cnt)-parseInt(1);
        document.getElementById("productboxcnt").value = currentCnt;
        document.getElementById("rowCount").value = parseInt(rowCount)-parseInt(1);
        return false;
}*/
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
        window.open(admin_web_url+'get_upload.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=300,left=300,top=300');
}
function getUploadedDataList (sFrm,sTitle,sId,sPath,mType,sImageCat){
        window.open(admin_web_url+'search_store.php?rfrm='+sFrm+'&rtitle='+sTitle+'&rpath='+sPath+'&rid='+sId+'&rtype='+mType+'&rimgcat='+sImageCat,'mywindow','width=600,height=400,left=300,top=300');
}


function sProductPagination(page,startlimit,cnt,filename,divid,category_id){

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

        var selected_brand_id="";var selected_model_id = "";var selected_variant_id="";
        selected_brand_id = document.getElementById('select_brand').value;
        selected_model_id = document.getElementById('Model').value;
        selected_variant_id = document.getElementById('Variant').value;
        if(divid == ""){ return false; }
                var url = admin_web_url+filename;

                $.ajax({
                        url: url,
                        data: 'catid='+category_id+'&page='+page+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+selected_brand_id+'&selected_model_id='+selected_model_id+'&selected_variant_id='+selected_variant_id,
                        success: function(data){
                                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
               },
            async:false
        });
        if(selected_brand_id != ""){
                getModelByBrandDashboard(selected_model_id,'get_model_detail.php','Model','');
        }
        if((selected_brand_id != "") && (selected_model_id != "")){
                document.getElementById("Model").value = selected_model_id;
                getVariantByBrandModelDashboard(selected_variant_id,'get_variants.php','Variant','');
        }
        if(selected_variant_id != ""){
                document.getElementById("Variant").value = selected_variant_id;
        }
        return true;
}

function initCal(){
        var dates = $( "#start_date,#end_date,#announced_date" ).datepicker({
                showOn: "button",
                buttonImage: image_url+"calendar.gif",
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                minDate: 0,
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
function AssignVariantColors(color_id,variant_color_id,variant_id,category_id){
	window.open(admin_web_url+'assign_colors.php?color_id='+color_id+'&variant_color_id='+variant_color_id+'&variant_id='+variant_id+'&category_id='+category_id,'mywindow','width=500,height=600,maximize=no,resizable=no,location=no,status=no,menubar=no,scrollbars=yes');
}

function setFeatureValue(setfeaturevalue){
	if(document.getElementById(setfeaturevalue).checked == true){
		document.getElementById(setfeaturevalue).value ="Yes";
	}else{
		document.getElementById(setfeaturevalue).value ="No";
	}
	return true;
}
