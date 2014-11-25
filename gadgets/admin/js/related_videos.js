function getProductVideosDashboard(divid,ajaxloaderid,category_id,startlimit,cnt){

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
        var url = admin_web_url+'ajax/related_video_dashboard.php';
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
            setupTinyMCE();
            setDatePicker('date-picker');
        return true;
}

function getProductVideoDashboardByType(divid,ajaxloaderid,category_id,startlimit,cnt){
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
        if(document.getElementById('view_section_id')!="undefined"){
                var view_section_id = document.getElementById('view_section_id').value;
        }
        //alert(divid+','+ajaxloaderid+','+category_id);
        document.getElementById(ajaxloaderid).style.display = "block";
	if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/related_video_dashboard.php';
       // alert(url+'?catid='+category_id+'&startlimit='+startlimit+'&cnt='+cnt+'&view_section_id='+view_section_id);
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
        //tiny();
        return true;
}

function validateProduct(){
        if(document.getElementById('select_section_id')!="undefined"){
		if((document.getElementById('select_section_id').value) == "0"){
                        alert("Please select video section.");
                        return false;
		}
	}
        if(document.getElementById('select_video_id')!="undefined"){
		if((document.getElementById('select_video_id').value) == "0"){
                        alert("Please select video from video list.");
                        return false;
		}
	}
        if(document.getElementById('view_section_id')!="undefined"){
        	var view_section_id = document.getElementById('view_section_id').value;
                document.getElementById('hd_view_section_id').value=view_section_id;
        }
        return true;
}

function updateProductVideo(divid,ajaxloaderid,videoid,category_id){

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
        var url = admin_web_url+'ajax/related_video_dashboard.php';
	//alert(url+'?act=update&vid='+videoid+'&catid='+category_id+'&view_section_id='+view_section_id);
	$.ajax({
        	url: url,
                data: 'act=update&vid='+videoid+'&catid='+category_id+'&view_section_id='+view_section_id,
                success: function(data){
                //alert(data);
                document.getElementById(divid).innerHTML = data;
                document.getElementById(divid).style.display="block";
                document.getElementById(ajaxloaderid).style.display = "none";
            },
            async:false
        });
	document.getElementById('actiontype').value = 'Update';
        setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
            setupTinyMCE();
            setDatePicker('date-picker');
        return true;
}

function deleteProductVideo(videoid){
        if(document.getElementById('view_section_id')!="undefined"){
        	var view_section_id = document.getElementById('view_section_id').value;
                document.getElementById('hd_view_section_id').value=view_section_id;
        }
        document.getElementById('actiontype').value = 'Delete';
	document.getElementById('hd_video_id').value = videoid;
        var answer = confirm ("Are you sure.Want to delete video?")
        if (answer){
                document.product_manage.submit();
                return true;
        }
        return false;
}
	
