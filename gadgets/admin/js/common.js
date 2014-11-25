function removeTr(rowId){
    var row = document.getElementById(rowId);
	if(row.parentElement){
	       	row.parentElement.removeChild(row);
	}else if(row.parentNode){
		row.parentNode.removeChild(row);
	}

	return false;
}
function unique(a)
{
   var r = new Array();
   o:for(var i = 0, n = a.length; i < n; i++) {
      for(var x = i + 1 ; x < n; x++)
      {
         if(a[x]==a[i]) continue o;
      }
      r[r.length] = a[i];
   }
   return r;
}
/**
* @note function is used to check selected category is last level or not.
* @author Rajesh Ujade.
* @created 4-12-2010.
* @pre not required.
* @post boolean true/false.
* return boolean.
*/
function isLastLvlCategory(){
	var catboxcnt = document.getElementById("catboxcnt").value;
	var selectedboxcnt = document.getElementById("selectedboxcnt").value;
	if(catboxcnt == selectedboxcnt){
		return true;
	}
	return false;
}
/**
* @note function is used to check category is selected or not.
* @author Rajesh Ujade.
* @created 4-12-2010.
* @pre not required.
* @post boolean true/false.
* return boolean.
*/
function isCategorySelected(){
	var category_id = document.getElementById('selected_category_id').value;
	if(category_id == ''){
		return false;
	}else{
		return true;
	}
}
function load_menudetails(divid,ajaxloaderid){
	var category_id = document.getElementById('selected_category_id').value;
        document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/menu_details.php';
        $.ajax({
         url: url,
         data: 'divid='+divid+'&ajaxloaderid='+ajaxloaderid,
         success: function(data){
                         //alert(data);
                         document.getElementById(divid).innerHTML = data;
                         document.getElementById(divid).style.display="block";
                         document.getElementById(ajaxloaderid).style.display = "none";
			 menu_details(category_id,'menu_ajax1','menuajaxloader');
                 },
                 async:false
        });
}
/**
* @note function is used to get category details using ajax call.
* @param integer category_id.
* @pre category_id and category_level must be non-empty valid integer.
* @post string html
* return html.
*/
function category_details(category_id,divid,ajaxloaderid){
	document.getElementById(ajaxloaderid).style.display = "block";
	if(divid == ""){ return false; }
	var url = admin_web_url+'ajax/select_category.php';
	$.ajax({
         url: url,
         data: 'catid='+category_id+'&divid='+divid+'&ajaxloaderid='+ajaxloaderid,
         success: function(data){
			 //alert(data);
			 document.getElementById(divid).innerHTML = data;
			 document.getElementById(divid).style.display="block";
			 document.getElementById(ajaxloaderid).style.display = "none";
		 },
		 async:false
	});
}
function category_level(id,divid,ajaxloaderid){
	var category_id = document.getElementById(id).value;
	if(category_id && divid){
		category_details(category_id,divid,ajaxloaderid);
		return true;
	}
	//alert('category_id = '+category_id+' & divid = '+divid+' not found.');
	return false;
}

/**
* @note function is used to get imenu details using ajax call.
* @param integer menu_id.
* @pre menu_id and menu_level must be non-empty valid integer.
* @post string html
* return html.
*/
function menu_details(menu_id,divid,ajaxloaderid){
	var category_id = document.getElementById('selected_category_id').value;
        document.getElementById(ajaxloaderid).style.display = "block";
        if(divid == ""){ return false; }
        var url = admin_web_url+'ajax/select_menu.php';
        $.ajax({
         url: url,
         data: 'catid='+category_id+'&menuid='+menu_id+'&divid='+divid+'&ajaxloaderid='+ajaxloaderid,
         success: function(data){
                         //alert(data);
                         document.getElementById(divid).innerHTML = data;
                         document.getElementById(divid).style.display="block";
                         document.getElementById(ajaxloaderid).style.display = "none";
                 },
                 async:false
        });
}

function menu_level(id,divid,ajaxloaderid){
        var menu_id = document.getElementById(id).value;
        if(menu_id && divid){
                menu_details(menu_id,divid,ajaxloaderid);
                return true;
        }
        //alert('menu_id = '+menu_id+' & divid = '+divid+' not found.');
        return false;
}
function cleanStr(str){
    str= str.replace('-','');
    str= str.replace(new RegExp('[^a-zA-Z0-9]',"gm"), '');
    str= str.replace(new RegExp('/\s+/'),"gm", '');
    str = str.toLowerCase();
    return str;
}
