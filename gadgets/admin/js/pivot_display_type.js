/**
* @note function is used to update the pivot display type.
* @param integer displayid.
* @param  string displayname.
* @param integer displaystatus.
* @pre displayid and displaystatus must be valid,non-empty integer.displayname must be valid,non-empty string.
* @post boolean.
* return boolean.
*/
function updatePivotDisplayType(displayid,displayname,displaystatus){
	displayname = displayname.replace(/%26/,"&");
	displayname = displayname.replace(/&#039;/,"'");
	displayname = displayname.replace(/%2C/,",");
	displayname = displayname.replace(/%2F/,"/");

	document.getElementById('pivot_display_status').value = displaystatus;
	document.getElementById('pivot_display_name').value = displayname;
	document.getElementById('pivot_display_id').value = displayid;
	document.getElementById('actiontype').value = 'Update';
	return false;
}
/**
* @note function is used to delete the pivot display type.
* @param integer displayid.
* @param  string displayname.
* @pre displayid must be valid,non-empty integer.displayname must be valid,non-empty string.
* @post boolean.
* return boolean.
*/
function deletePivotDisplayType(displayid,displayname){
	displayname = displayname.replace(/%26/,"&");
	displayname = displayname.replace(/&#039;/,"'");
	displayname = displayname.replace(/%2C/,",");
	displayname = displayname.replace(/%2F/,"/");
	document.getElementById('pivot_display_id').value = displayid;
	document.getElementById('actiontype').value = 'Delete';
	var answer = confirm ("Are you sure.Want to delete pivot display type '"+displayname+"'?")
    	if(answer){
         	document.pivot_display_action.submit();
         	return true;
     	}
	return false;
}
/**
* @note function is used to validate the pivot display form.
* @pre not required.
* @post boolean true/false.
* return boolean.
*/
function validatePivotDisplayType(){
	if(document.getElementById('pivot_display_name').value == ''){
		alert("Please add the pivot display type");
		document.getElementById('pivot_display_name').focus();
		return false;
	}
	return true;
}
