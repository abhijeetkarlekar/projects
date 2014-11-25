(function($){
  $.fn.isocial = function( options ) {

    // Create some defaults, extending them with any options that were provided

    var st = $.extend( {

      url  : '',		// current url of window

      title : '', 				// title text required for twitter share

	  socialz_count : 0, 				// Set initial count to zero

	  counter : 0,						// set initial couter to zero

	  icount : 0,	   

	}, options);

	ic = st.icount;

	var url = null;

	var title = null;

	var socialId = "";

	var shareArr = {

		'fb':'https://www.facebook.com/sharer/sharer.php?u=',

		'tw':'https://twitter.com/share?url=',

		'gp':'https://plus.google.com/share?url=',
		
	};

	var shareImgArr = {

		'fb':'http://st2.india.com/wp-content/themes/indiacom/images/fb.jpg',

		'tw':'http://st2.india.com/wp-content/themes/indiacom/images/tw.jpg',

		'gp':'http://st2.india.com/wp-content/themes/indiacom/images/gp.jpg',

	};

	var shareAlt = {

		'fb': 'Facebook share',

		'tw': 'Twitter share',

		'gp': 'Share on Google+',

	};

	

	return this.each(function() { 

		var cel = $(this);		

		ic++;

		cid = cel.attr('class')+ic;

		cel.attr('id',cid);

		//console.log(cu+'----'+ct);

		var apiArr = {

			'fb' : 'http://api.facebook.com/restserver.php?method=links.getStats&format=json&v=1.0&urls=',

			'tw' : 'http://urls.api.twitter.com/1/urls/count.json?url=',

			'gp': 'https://clients6.google.com/rpc'


		};



		function printLog(msg){

			console.log(msg);

		}

		

		function templateExists(){

			if($('.isocial .ssbxwrp').length > 0){		

				if(validateTemplate() == true){

					return true;

				}

			}

			socialId = cel.attr('id');						

			return false;

		}

		

		function validateTemplate(){

			if($('.isocial .ssbtnwrp li').length == Object.keys(apiArr).length){		

				return true;

			}

			return false;

		}

		

		function createResult(id,key,count){	

			//$(mid+' '+).html(count);	

			createTotal(id,count);		

		}

		

		function createTotal(id,count){

			var total = $(id+' .sstotal span').text();
			total = total.replace('K','');
			total = (total && total >= 0) ? parseInt(total) : 0;

			count = (count >= 0) ? parseInt(count) : 0;

			total = total+count;
			total = parseInt(total);
			printLog(total+'---'+count);
			$(id+' .sstotal span').text(total);

		}
		function convertTotal(total){
			total = total.toString();
			total = total.replace('K','');
			total = parseInt(total);
                        if(total >= 1000){
                                total  = total>1000?(total/1000).toFixed(0)+"k":total
                        }
			return total;
		}
		function addCount(service,total){
			$('#'+service+'-cnt').text(convertTotal(total));
		}
		

		function getcount(mid,url,title){

			$.each(apiArr,function(service, apiUrl){

				if(service == 'gp'){
					createResult(mid,service,gplus);
					addCount(service,gplus);
				}else{

					$.get(apiUrl+url,function(response){	

						switch(service){

							case 'fb':

							  var total = (response && typeof response[0].total_count !== 'undefined') ? response[0].total_count : 0;					


							  createResult(mid,service,total);
							  

							  break;

							

							case 'tw':

								var total = (response && response.count !== 'undefined') ? response.count : 0

								createResult(mid,service,total);

								break;

						}

							  addCount(service,total);

					},'jsonp');	

				}

			});			

		}

		  

		function createTemplate(){

			var mid = '#'+socialId;

			var url = cel.attr('data-url');
			//url = encodeURIComponent(url);

			var title = cel.attr('data-title');
			title = encodeURIComponent(title);
			var template = '';
			//template += '<div class="ssbxwrp"><div class="sstotal"><span></span> Shares /</div>';
			
			template += '<section class="isocial-in">';
			$.each(shareArr,function(service,shareUrl){
				switch(service){
					case 'fb':
						template += '<a href="'+shareUrl+url+'" onClick="PopupCenter(this.href,';
						template += "'"+title+"'"+',560,360,\''+mid+'\');return false;">';
						template += '<span class="fb-r" alt="'+shareAlt[service]+'"><i class="fb-i"></i> <span style="color:#fff;font-size:20px;" id="fb-cnt">0</span></span></a>';
						break;
					case 'tw':
						template += '<a href="'+shareUrl+url+'&text='+title+'" onClick="PopupCenter(this.href,';
						template += "'"+title+"'"+',650,460,\''+mid+'\');return false;">';
						template += '<span class="tw-r" alt="'+shareAlt[service]+'"><i class="tw-i"></i> <span style="color:#fff;font-size:20px;" id="tw-cnt">0</span></span></a>';
						break;
					case 'gp':
						template += '<a href="'+shareUrl+url+'" onClick="PopupCenter(this.href,';
						template += "'"+title+"'"+',650,460,\''+mid+'\');return false;">';
						template += '<span class="gp-r" alt="'+shareAlt[service]+'"><i class="gp-i"></i> <span style="color:#fff;font-size:20px;" id="gp-cnt">0</span></span></a>';
						break;
				}
			});
			template += '<div class="clear"></div>';
			template += '</section>';

			/*template += '<ul class="ssbtnwrp">';

			$.each(shareArr,function(service,shareUrl){

				template += '<li class="btn_'+service+'">';

				switch(service){

					case 'fb':

						template += '<a href="'+shareUrl+url+'" onClick="PopupCenter(this.href,';

						template += "'"+title+"'"+',560,360,\''+mid+'\');return false;">';

						break;

					case 'tw':

						template += '<a href="'+shareUrl+url+'&text='+title+'" onClick="PopupCenter(this.href,';

						template += "'"+title+"'"+',650,460,\''+mid+'\');return false;">';

						break;

					case 'gp':

						template += '<a href="'+shareUrl+url+'" onClick="PopupCenter(this.href,';

						template += "'"+title+"'"+',650,460,\''+mid+'\');return false;">';

						break;

				}				

				template += '<img src="'+shareImgArr[service]+'" alt="'+shareAlt[service]+'" />';

				template += '</a>';

				template += '</li>';

			});

			template += '</ul>';*/

			template += '</div>';

			if(socialId && socialId != "" && socialId != null){

				$(mid).html(template);

				getcount(mid,url,title);

			}

			return true;

		}

		

		

		

		function init_count(){

			if(!templateExists()){

				createTemplate();				

			}

		}

		init_count();

	});

	

	

  };

})( jQuery );



var newWindow = null;

function PopupCenter(url, title, w, h, id){

	// Fixes dual-screen position Most browsers Firefox  

	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  

	var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  



	width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  

	height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  



	var left = ((width / 2) - (w / 2)) + dualScreenLeft;  

	var top = ((height / 2) - (h / 2)) + dualScreenTop;  

	newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left+' ');  



	// Puts focus on the newWindow  

	if (window.focus) {  

		newWindow.focus();  

	} 

	 function countreset(){

		if (newWindow.closed) {

			clearInterval(timer);

			$(id).html("");

			$(id+'.isocial').isocial();

		}

	 }

	var timer = setInterval(function(){countreset()},1000);

};
