// initiate FB app as SDK loads
window.fbAsyncInit = function() {
	FB.init({
        	appId   : '295232564018856', // Your application ID
                oauth   : true, // session is replaced by the authorization response. The session is a Facebook-specific structure enclosing an access token and a session key, either of which could be used to make API calls. The authorization response encapsulates OAuth2-compliant data and includes an access token, the user ID, a signed request, and an expiration time 
                status  : false, // Determines whether the current login status of the user is freshly retrieved on every page load. If this is disabled, that status will have to be manually retrieved using .getLoginStatus(). Defaults to false
                cookie  : true, // Determines whether a cookie is created for the session or not. If enabled, it can be accessed by server-side code. Defaults to false
                xfbml   : true, // Determines whether XFBML tags used by social plugins are parsed, and therefore whether the plugins are rendered or not. Defaults to false
                version : 'v2.1' // version of the Graph API and any API dialogs or plugins
        });
/* site login
	FB.getLoginStatus(function(response) {
        	statusChangeCallback(response);
       	});
*/
};

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response, onSuccess, onFailure) {

    // console.log('statusChangeCallback' + ' onSuccess - ' + onSuccess + ' onFailure - ' + onFailure);
    // console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      	// Logged into your app and Facebook.
	console.log('Logged into your app and Facebook.');
      	FbDataFetch(onSuccess);
    } else if (response.status === 'not_authorized') {
      	// The person is logged into Facebook, but not your app.
      	// document.getElementById('status').innerHTML = 'Please log into this app.';
	console.log("Please log into this app.");
    } else {
      	// The person is not logged into Facebook, so we're not sure if
      	// they are logged into this app or not.
      	// document.getElementById('status').innerHTML = 'Please log into Facebook.';
	console.log("Please log into Facebook.");
	onFailure(response);	
    }
}
/**
 * checks login status
 */
function checkLoginState(onSuccess, onFailure) {
	
	FB.getLoginStatus(function(response) {

      		statusChangeCallback(response, onSuccess, onFailure);
    	});
}

function FbDataFetch(onSuccess){
        
        FB.api('/me', function(res){
                
                //var str = JSON.stringify(res);
		onSuccess(res);
                // console.log(res);
		
        });
}

function fbLogin(){

	FB.login(function(response) {
    		if (response.authResponse) {

			//FbDataFetch('');	
    		} else {

        		//user cancelled login or did not grant authorization
    		}
	}, {scope:'email,user_birthday,status_update,publish_stream,user_about_me'});
}

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
    	if (d.getElementById(id)) return;
    	js = d.createElement(s); js.id = id;
    	js.src = "//connect.facebook.net/en_US/sdk.js";
    	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
