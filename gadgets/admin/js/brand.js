function getBrandDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/brand_dashboard.php';
	   //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt);
        $.ajax({
         url: url,
         data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt,
         success: function(data){
                         //alert(data);
                         document.getElementById(divid).innerHTML = data;
                         document.getElementById(divid).style.display="block";
                         document.getElementById(ajaxloaderid).style.display = "none";
						 initCal();
                 },

                 async:false

        });
        
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
       
	return true;
}
/*function updateBrand(brandid,brandname,brandstatus,short_desc){
	brandname = brandname.replace(/%26/,"&");
	brandname = brandname.replace(/&#039;/,"'");
	brandname = brandname.replace(/%2C/,",");
	brandname = brandname.replace(/%2F/,"/");

	document.getElementById('brand_status').value = brandstatus;
	document.getElementById('brand_name').value = brandname;
	document.getElementById('brand_id').value = brandid;
	document.getElementById('short_desc').value = short_desc;
	document.getElementById('actiontype').value = 'Update';
	return false;
}*/

function updateBrand(divid,ajaxloaderid,brand_id,categoryid,startlimit,cnt,seo_path){
        if(category_id == ''){
                var category_id = document.getElementById('selected_category_id').value;

                if(isCategorySelected() == false){
                        alert("Please select the category.");
                        return false;
                }
        }

        //alert(divid+','+ajaxloaderid+','+category_id);
        document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/brand_dashboard.php';
        //alert(url+'?act=update&brand_id='+brand_id+'&catid='+categoryid);
        $.ajax({
                url: url,
                data: 'act=update&brand_id='+brand_id+'&catid='+categoryid,

                success: function(data){
                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
				initCal();
            },
            async:false
        });
        if(document.getElementById('seo_path')){
                document.getElementById('seo_path').value = seo_path;
        }
        if(document.getElementById('brand_id')){
                document.getElementById('brand_id').value = brand_id;
        }
        if(document.getElementById('actiontype')){
                document.getElementById('actiontype').value = 'Update';
        }
         setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
        return true;
}
function deleteBrand(brandid,brandname){
	brandname = brandname.replace(/%26/,"&");
	brandname = brandname.replace(/&#039;/,"'");
	brandname = brandname.replace(/%2C/,",");
	brandname = brandname.replace(/%2F/,"/");
	document.getElementById('brand_id').value = brandid;
    	document.getElementById('actiontype').value = 'Delete';
    	var answer = confirm ("Are you sure.Want to delete brand '"+brandname+"'?")
    	if (answer){
         	document.brand_action.submit();
        	return true;
     	}
    	return false;
}
function validateBrand(){
    if(document.getElementById('seo_path').value == ''){
        alert("Please add the seo path");
        document.getElementById('seo_path').focus();
        return false;
    }
	if(isCategorySelected() == false){
        alert("Please select the category.");
        return false;
    }
    if(isLastLvlCategory() == false){
        alert("Please select last level category.");
        return false;
    }
	if(document.getElementById('brand_name').value == ''){
		alert("Please add the brand");
		document.getElementById('brand_name').focus();
		return false;
	}
    var brandname = cleanStr(document.getElementById('brand_name').value);
    var seo_path = cleanStr(document.getElementById('seo_path').value);
    if(brandname !== seo_path){
        alert('Brand name And SEO path must be similar');
        return false;
    }
    document.getElementById("brand_action").submit(); 
	return true;
}


function sBrandPagination(page,startlimit,cnt,filename,divid,category_id){


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

	//var selected_brand_id="";
	//selected_brand_id = document.getElementById('select_brand').value;
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
