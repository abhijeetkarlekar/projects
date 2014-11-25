function getPopularBrandDashboard(divid,ajaxloaderid,category_id,startlimit,cnt,brand_id){
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

	if(brand_id == ''){
		if(document.getElementById("brand_id")){
			brand_id = document.getElementById("brand_id").value;
		}
	}
	
        var url = admin_web_url+'ajax/popular_brand_dashboard.php';
        //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+brand_id);
        $.ajax({
                url: url,
                data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+brand_id,
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

function updatePopularBrand(divid,ajaxloaderid,popular_id,brand_id,popular_brand_id,category_id,selected_brand_id){
	var startlimit = "";
	var cnt = "";
	if(category_id == ''){
                var category_id = document.getElementById('selected_category_id').value;
                if(category_id == ''){
                        alert("Please select category.");
                        return false;
                }
        }
        document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/popular_brand_dashboard.php';
        //alert(url+'?act=update&popular_id='+popular_id+'&catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+selected_brand_id);
        $.ajax({
                url: url,
                data: 'act=update&popular_id='+popular_id+'&catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&selected_brand_id='+selected_brand_id,

                success: function(data){
                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	if(document.getElementById('popular_id')){
                document.getElementById('popular_id').value = popular_id;
        }
        if(document.getElementById('actiontype')){
                document.getElementById('actiontype').value = 'Update';
        }
        return true;
}

function deletePopularBrand(popular_id,brand_id){
	document.getElementById('popular_id').value = popular_id;
	document.getElementById('selected_brand_id').value = brand_id;
        var answer = confirm ("Are you sure.Want to delete this popular brand?")
	if(document.getElementById('actiontype')){
		document.getElementById('actiontype').value = 'Delete';
        }
        if (answer){
                document.product_manage.submit();
                return true;
        }
        return false;
}

function validateProduct(){
	if(document.getElementById('actiontype')){
		if(document.getElementById('actiontype').value != 'Update'){
	                document.getElementById('actiontype').value = 'Insert';
		}
        }
	if(document.getElementById('select_brand_id').value == ''){
                alert("Please Select Brand.");
                return false;
        }
       	if((document.getElementById('select_popular_model_id').value == "") || (document.getElementById('select_popular_model_id').value == "0")){
        	alert("Please Select Popular Model");
	        return false;
 	}	
        return true;
}
function getModelByBrand(ajaxloaderid,product_name_id,divid){
        if(document.getElementById('select_brand_id')){
                var brand_id = document.getElementById('select_brand_id').value;
        }
        if(brand_id == '' ||  brand_id == 0){
		if(document.getElementById(divid)){
			document.getElementById(divid).innerHTML = "<option value=''>---Select Brand---</option>";
		}
		return false;
	}


        var category_id = document.getElementById('selected_category_id').value;
        if(category_id == '' ||  category_id == 0){return false;}

        //if(productid!=''){}

        document.getElementById(ajaxloaderid).style.display = "block";
        var url = admin_web_url+'ajax/select_popular_model.php';
        var html = $.ajax({url: url,data: 'category_id='+category_id+'&brand_id='+brand_id+'&product_name_id='+product_name_id, success: function(data)
{document.getElementById(ajaxloaderid).style.display = "none";},async: false}).responseText;
	if(document.getElementById(divid)){
		document.getElementById(divid).innerHTML = html;
	}
        return true;
}

