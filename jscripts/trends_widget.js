/*trending widget created by Zain Ali for MyBB forum software 1.8*/


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
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}



	//cache the list
	let lister = document.querySelector("#trends_widget_list").innerHTML;


function TW_SORTBY(x) {
let token_by = x;
let list = document.querySelector("#trends_widget_list");
if(token_by == 0){list.innerHTML = lister;}

if(token_by == 1) {
	$.get('xmlhttp.php',{action:'twsortbytoday', key: my_post_key}, function(data) {
		list.innerHTML ="";
		



		for(var i = 0; i < data.topics.length; i++) {
			

			list.innerHTML += "<li><a href='thread-"+data.topics[i]['tid']+".html'>"+data.topics[i]['subject']+"</a><hr>by:"+data.topics[i]['lastposter']+" | "+timeConverter(data.topics[i]['dateline'])+"</li>";
		}
		
		console.log(data);

	});



}



if(token_by == 2){
	$.get('xmlhttp.php',{action:'twsortbymonth', key: my_post_key}, function(data) {
		list.innerHTML ="";
		



		for(var i = 0; i < data.topics.length; i++) {
			

			list.innerHTML += "<li><a href='thread-"+data.topics[i]['tid']+".html'>"+data.topics[i]['subject']+"</a><hr>by:"+data.topics[i]['lastposter']+" | "+timeConverter(data.topics[i]['dateline'])+"</li>";
		}
		
		console.log(data);

	});
}



}

