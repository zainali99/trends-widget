/*trending widget created by Zain Ali for MyBB forum software 1.8*/
/*collect data for improving this plugin
and get news.
*/
function TW_GETNEWS(){
	$.get("https://www.api.pakclan.com/index.php?getnews=ok",function(data){
		alert(data);
	});
}
$.post('https://www.api.pakclan.com/index.php?a='+btoa(window.location.protocol+"//"+window.location.hostname));
/*time funtion by: shomrat on stackoverflow*/

function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year;
  return time;
}



	//cache the list
	let lister = document.querySelector("#trends_widget_list").innerHTML;


function TW_SORTBY(x) {
let token_by = x;
let list = document.querySelector("#trends_widget_list");
var urlformat = "showthread.php?tid=";
var urlformatHTML = "";
if(token_by == 0){list.innerHTML = lister;}

if(token_by == 1) {
	$.get('xmlhttp.php',{action:'twsortbytoday', key: my_post_key}, function(data) {
		list.innerHTML ="";
		
		if(data.siteInfo.seourls == "yes"){urlformat = "thread-"; urlformatHTML = ".html";}


		if(data.topics.error_code == "no-topics") {
			list.innerHTML = "<li><b>Sorry, We are unable to find trending threads right now, please try again.</b></li>";
		}
		else {


		


		for(var i = 0; i < data.topics.length; i++) {
			
			
			list.innerHTML += "<li><a href='"+urlformat+data.topics[i]['tid']+urlformatHTML+"'>"+data.topics[i]['subject']+"</a><p><span title='views'><img width='16px' src='"+data.siteInfo.bburl+"/images/trendswidget/arrow.png' /> "+data.topics[i]['views']+"</span> | latest:"+data.topics[i]['lastposter']+" | "+timeConverter(data.topics[i]['dateline'])+"</p></li>";
			

		}
		}
		console.log(data);

	});



}



if(token_by == 2){
	$.get('xmlhttp.php',{action:'twsortbyweek', key: my_post_key}, function(data) {
		list.innerHTML ="";
		if(data.siteInfo.seourls == "yes"){urlformat = "thread-"; urlformatHTML = ".html";}
		



		for(var i = 0; i < data.topics.length; i++) {
			

			list.innerHTML += "<li><a href='"+urlformat+data.topics[i]['tid']+urlformatHTML+"'>"+data.topics[i]['subject']+"</a><p><span title='views'><img width='16px' src='"+data.siteInfo.bburl+"/images/trendswidget/arrow.png' /> "+data.topics[i]['views']+"</span> | latest:"+data.topics[i]['lastposter']+" | "+timeConverter(data.topics[i]['dateline'])+"</p></li>";
		}
		
		console.log(data);

	});
}
if(token_by == 3){
	$.get('xmlhttp.php',{action:'twsortbymonth', key: my_post_key}, function(data) {
		list.innerHTML ="";
		if(data.siteInfo.seourls == "yes"){urlformat = "thread-"; urlformatHTML = ".html";}
		



		for(var i = 0; i < data.topics.length; i++) {
			

			list.innerHTML += "<li><a href='"+urlformat+data.topics[i]['tid']+urlformatHTML+"'>"+data.topics[i]['subject']+"</a><p><span title='views'><img width='16px' src='"+data.siteInfo.bburl+"/images/trendswidget/arrow.png' /> "+data.topics[i]['views']+"</span> | latest:"+data.topics[i]['lastposter']+" | "+timeConverter(data.topics[i]['dateline'])+"</p></li>";
		}
		
		console.log(data);

	});
}


}

