/**
* @note function is used to update the category details.
* @param integer catid.
* @param string catname.
* @param integer catstatus.
* @pre catid must be valid,non-empty,non-zero integer.catname and catstatus must be valid,non-emtpy string.
* @post a form.
* return submit form
*/
function updateCategory(catid,catname,catstatus,category_level,seo_path){
	catname = catname.replace(/%26/,"&");
	catname = catname.replace(/&#039;/,"'");
	catname = catname.replace(/%2C/,",");
	catname = catname.replace(/%2F/,"/");

	document.getElementById('category_name').value = catname;
	document.getElementById('category_status').value = catstatus;
	document.getElementById('category_id').value = catid;
	document.getElementById('seo_path').value = seo_path;
	document.getElementById('actiontype').value = 'Update';
	return true;
}
/**
* @note function is used to delete the category.
* @param integer catid.
* @pre catid must be valid,non-empty,non-zero integer.
* @post a form.
* return submit form
*/
function deleteCategory(catid,catname){
	catname = catname.replace(/%26/,"&");
	catname = catname.replace(/&#039;/,"'");
	catname = catname.replace(/%2C/,",");
	catname = catname.replace(/%2F/,"/");

        document.getElementById('category_name').value = catname;

	document.getElementById('category_id').value = catid;
        document.getElementById('actiontype').value = 'Delete';
	var answer = confirm ("Are you sure.Wants to delete category '"+catname+"'?")
	if (answer){
		document.category_action.submit();
		return true;
	}else{
		return false;
	}
}
function validateCategory(){
	if(document.getElementById('category_name')){
		if(document.getElementById('category_name').value == ''){
			alert('Category must not be empty');
			document.getElementById('category_name').focus();
			return false;
		}
	}
	if(document.getElementById('seo_path')){
		if(document.getElementById('seo_path').value == ''){
			alert('seo path must not be empty');
			document.getElementById('seo_path').focus();
			return false;
		}
	}
	return true;

}
