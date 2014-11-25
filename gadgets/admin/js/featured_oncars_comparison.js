function getFeaturedOncarsComparisonDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        var url = admin_web_url+'ajax/featured_oncars_comparison_dashboard.php';
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
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
        return true;
}

function getFeaturedOncarsComapreSetDashboardByType(divid,ajaxloaderid,category_id,startlimit,cnt,selected_section_id){
        //alert("dsdsdsdsds");
        if(category_id == ''){
                var category_id = document.getElementById('selected_category_id').value;
        }
	if(selected_section_id == ""){
	        if(document.getElementById('view_section_id')!="undefined"){
        	        selected_section_id = document.getElementById('view_section_id').value;
        	}
	}
        //alert(divid+','+ajaxloaderid+','+category_id);
        document.getElementById(ajaxloaderid).style.display = "block";
	if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/featured_oncars_comparison_dashboard.php';
        //alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id);
        $.ajax({
		url: url,
                data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+selected_section_id,
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

function getComapreSetList(divid,category_id,startlimit,cnt){
	if(category_id == ''){ 
                var category_id = document.getElementById('selected_category_id').value;
        }
	var view_section_id = "0";
        if(document.getElementById('select_section_id')){
                var view_section_id = document.getElementById('select_section_id').value;
        }
	var length="1";
	var html = "";
	if(view_section_id != "0"){
		var url = admin_web_url+'ajax/select_compare_set_list.php';
        	//alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id);
	        $.ajax({
        	        url: url,
                	data: 'catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id,
	                success: function(data){
        	                //alert(data);
                	        document.getElementById(divid).innerHTML = data;
				length = document.getElementById("select_compare_set_id").length;
	                },
        	        async:false
        	});
	}else{
		var data = "<select name='select_compare_set_id' id='select_compare_set_id'><option value='0'>---Select Compare List---</option></select>";
		document.getElementById(divid).innerHTML = data;
		
	}
	html+= "<select name='ordering' id='ordering'><option value=''>---Select Ordering---</option>";
	for(var i='1'; i<= length; i++){
		html+= "<option value='"+i+"'>"+i+"</option>";
		
	}
	html+= "</select>";
	document.getElementById('select_ordering').innerHTML = html;
        return true;
}

function validateProduct(){
	if(document.getElementById('actiontype')){
                if(document.getElementById('actiontype').value == ""){
                        document.getElementById('actiontype').value = 'Insert';
                }
        }
        if(document.getElementById('select_section_id')!="undefined"){
		if((document.getElementById('select_section_id').value) == "0"){
                        alert("Please select Featured Compare Set Section.");
                        return false;
		}
	}
        if(document.getElementById('select_compare_set_id')!="undefined"){
		if((document.getElementById('select_compare_set_id').value) == "0"){
                        alert("Please select Compare Set from Compare Set List.");
                        return false;
		}
	}
        if(document.getElementById('view_section_id')!="undefined"){
        	var view_section_id = document.getElementById('view_section_id').value;
                document.getElementById('hd_view_section_id').value=view_section_id;
        }
	if(document.getElementById('ordering')){
                if(document.getElementById('ordering').value == ''){
                        alert("Please select ordering.");
                        return false;
                }
        }
        return true;
}

function updateFeaturedOncarsCompareSet(divid,ajaxloaderid,featured_compare_id,category_id){

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
        if(document.getElementById('view_section_id')!="undefined"){
                var view_section_id = document.getElementById('view_section_id').value;
                document.getElementById('hd_view_section_id').value=view_section_id;
        }
        //alert(divid+','+ajaxloaderid+','+category_id);
        var categoryid='';
        document.getElementById(ajaxloaderid).style.display = "block";
	if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/featured_oncars_comparison_dashboard.php';
	//alert(url+'?act=update&featured_compare_id='+featured_compare_id+'&catid='+category_id+'&view_section_id='+view_section_id);
	$.ajax({
        	url: url,
                data: 'act=update&featured_compare_id='+featured_compare_id+'&catid='+category_id+'&view_section_id='+view_section_id,
                success: function(data){
                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	document.getElementById('actiontype').value = 'Update';
        return true;
}

function deleteFeaturedOncarsCompareSet(featured_compare_id){
        if(document.getElementById('view_section_id')!="undefined"){
        	var view_section_id = document.getElementById('view_section_id').value;
                document.getElementById('hd_view_section_id').value=view_section_id;
        }
        document.getElementById('actiontype').value = 'Delete';
	document.getElementById('hd_featured_compare_id').value = featured_compare_id;
        var answer = confirm ("Are you sure.Want to delete this featured compare set?")
        if (answer){
                document.product_manage.submit();
                return true;
        }
        return false;
}
	
function svideoGalleryPagination(page,startlimit,cnt,filename,divid,category_id,view_section_id){

	
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
		data: 'catid='+category_id+'&page='+page+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id=FEATURED_VIDEOS',
		success: function(data){
	    //alert(data);
        document.getElementById(divid).innerHTML = data;
        document.getElementById(divid).style.display="block";
       },
            async:false
     });
	
	return true;
}
