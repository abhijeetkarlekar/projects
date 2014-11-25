/**
 * Facebook
 */
/**
 * add user details to review
 * facebook user response
 */
function addUserDetailsToReview(FBJsonResponse, GPJsonResponse){

	if(FBJsonResponse){

		//console.log(" addUserDetailsToReview - FBJsonResponse -------------- " + JSON.stringify(FBJsonResponse) );
		var id = FBJsonResponse.id;
		var name = FBJsonResponse.name;
		var email = FBJsonResponse.email;
		var first_name = FBJsonResponse.first_name;
		var last_name = FBJsonResponse.last_name;
		var gender = FBJsonResponse.gender;
	} else if(GPJsonResponse){

		//console.log(" addUserDetailsToReview - GPJsonResponse -------------- " + JSON.stringify(GPJsonResponse) );
		var id = GPJsonResponse.id;
		var name = GPJsonResponse.displayName;
		var email;
		if(GPJsonResponse.emails.length > 0){
			for (var i=0; i < GPJsonResponse.emails.length; i++) {
      				if (GPJsonResponse.emails[i].type === 'account') {
					email = GPJsonResponse.emails[i].value;
				}
    			}
		}
		var first_name = GPJsonResponse.name.givenName;
		var last_name = GPJsonResponse.name.familyName;
		var gender = GPJsonResponse.gender;
		var picture = GPJsonResponse.image.url;
	} else {

		var id = getCookieValue('id');
		var name = getCookieValue('name');
		var email = getCookieValue('email');
		var first_name = getCookieValue('first_name');
		var last_name = getCookieValue('last_name');
	}

	var totalquestion = $('#totalquestion').val();
	var txtrate = $('#txtrate').val();
	var comment_7 = $('#comment_7').val();
	var title = $('#title').val();
	var que_id_1 = $('#que_id_1').val();
	var que_id_7 = $('que_id_7').val();
	var total_ans_ques_1 = $('#total_ans_ques_1').val();
	var ans_1_1 = $('#ans_1_1').val();
	var user_review_1_1 = $('#user_review_1_1').val();
	var add_review = $('#add_review').val();
	var user_review_1_1 = $('#user_review_1_1').val();

	var cat_id = $('#cat_id').val();
	var brand_id = $('#brand_id').val();
	var product_info_id = $('#product_info_id').val();
	var product_id = $('#product_id').val();

	var postData = 'catid='+catid+'&brand_id='+brand_id+'&product_info_id='+product_info_id+'&product_id='+product_id+'&txtrate='+txtrate+'&comment_7='+encodeURIComponent(comment_7)+'&title='+encodeURIComponent(title)+'&que_id_1='+que_id_1+'&total_ans_ques_1='+total_ans_ques_1+'&ans_1_1='+ans_1_1+'&user_review_1_1='+user_review_1_1+'&add_review='+add_review+'&uid='+id+'&totalquestion='+revtotalquestion+'&username='+name+'&fname='+first_name+'&lname='+last_name+'&email='+email+'&que_id_7='+que_id_7;

	//console.log(postData);
	$.ajax({
		type 	: "POST",
		url  	: web_url + 'ajax/add_user_review.php',
		data 	: postData,
		dataType: "html",
		success : function(data) {
                        if(data!==''){
				// console.log(data);
				$('.loginbox .simplemodal-close').trigger("click");
				$('.wreviewbox').modal();
				$('#disp_msg').html(data);
                        }
                }
 
	});
	// res --- {"id":"10205486580914512","email":"abhijeet.net@gmail.com","first_name":"Abhijeet","gender":"male","last_name":"Karlekar","link":"https://www.facebook.com/app_scoped_user_id/10205486580914512/","locale":"en_US","name":"Abhijeet Karlekar","timezone":5.5,"updated_time":"2014-11-20T07:51:12+0000","verified":true}
}
/**
 * show login on failure
function showLoginWindow(response){
	
	console.log(JSON.stringify(response));
}
*/
/**
 * facebook login
$('#fb').click(function(){

	console.log("FB login call.");
	//fbLogin();
});
 */

/* facebook */
$('#fb-login').click(function() {

    fbCheckLoginStatus(
            pageReload, // on connection success
            login // on connection failure
            );
});

/**
 * facebook login
 */
function login() {

    fbLogin(
            loggedIn, // on connection success
            loggedInFailure // on connection failure
            );
}

/**
 * login success
 */
function loggedIn(response) {

	// Facebook response
    	// {"id":"10205486580914512","email":"abhijeet.net@gmail.com","first_name":"Abhijeet","gender":"male",
	// "last_name":"Karlekar","link":"https://www.facebook.com/app_scoped_user_id/10205486580914512/",
    	// "locale":"en_US","name":"Abhijeet Karlekar","timezone":5.5,"updated_time":"2014-11-20T07:51:12+0000","verified":true}
	setLoginCookies(response, 'fb');
    	addUserDetailsToReview(response, "");
}

/**
 * login failure
 */
function loggedInFailure(response) {


}

function pageReload() {

    location.reload();
}

$('#fb-logout').click(function() {

    fbCheckLoginStatus(
            logout, // on connection success
            loggedOutFailure // on connection failure
            );
});

function logout() {

    fbLogout(
            loggedOut // on success
            );
}

function loggedOut(response) {

    //console.log(JSON.stringify(response) + " -- Logged Out successfully.");
    var cookieArr = new Array('service', 'name', 'email', 'time', 'sig', 'id');
    deleteCookies(cookieArr);
}

function loggedOutFailure(response) {

    //console.log(JSON.stringify(response) + " -- Logged Out failure.");
}
/* facebook */
/* Google plus */
$('#gp-login').click(function() {

    gpLogin();
});

function gpLoggedIn(response){
    
	//console.log(JSON.stringify(response));
	setLoginCookies(response, 'gp');
    	addUserDetailsToReview("", response);
}

function gpLoggedInFailure(response) {

    	gpLogout();
}
$('#gp-logout').click(function() {

    	gpLogout();
});
/* Google plus */

/* Twitter */

$('#tw-login').click(function(){

	MyPopUpWin("http://new.gadgets.in/twitter/redirect.php", 500, 500);
});

function MyPopUpWin(url, width, height) {
    var leftPosition, topPosition;
    //Allow for borders.
    leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
    //Allow for title and status bars.
    topPosition = (window.screen.height / 2) - ((height / 2) + 50);
    //Open the window.
    window.open(url, "Window2",
    "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
    + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
    + topPosition + ",toolbar=no,menubar=no,scrollbars=yes,location=no,directories=no");
}

/* Twitter */
