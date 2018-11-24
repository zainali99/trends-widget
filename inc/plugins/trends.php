<?php 
/* TRENDS PLUGIN
Author: Zain Ali
website: zainali.altervista.org
forum: pakclan.com
Software: MyBB
*/




$plugins->add_hook("global_start", "trends");

$plugins->add_hook('admin_config_action_handler', 'trends_action_hand');
$plugins->add_hook('admin_load','trends_load');
$plugins->add_hook('admin_config_menu', 'trends_link');
$plugins->add_hook('xmlhttp', 'trends_ajax');
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
}


function trends_ajax()
{
    global $mybb,$db,$charset;

    if($mybb->get_input('action') == 'twsortbytoday' && $_GET['key'])
    {
        /*
        header("Content-type: application/json; charset={$charset}");
        $data = array('hello' => 'world');
        echo json_encode($data);
        exit;WHERE 
		this month:
        dateline >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE())-1 DAY))
		today:
		dateline >= UNIX_TIMESTAMP(CURDATE()) AND views > 1
        */
        $isvalid= verify_post_check($_GET['key']);

        header("Content-type: application/json; charset={$charset}");
$query = $db->query("SELECT tid,fid,views,subject,lastposter,dateline FROM ".TABLE_PREFIX."threads  WHERE dateline >= UNIX_TIMESTAMP(CURDATE()) AND views > 1 ORDER BY views DESC LIMIT 10");
$data = array("siteInfo"=>array("code"=>$_GET['key'],"seourls"=>$mybb->settings['seourls'], "bburl"=>$mybb->settings['bburl']),"topics" => array());
while($row = $db->fetch_array($query) ) {
 	$data["topics"][] = $row;
}
echo json_encode($data);
exit;


    }
if($mybb->get_input('action') == 'twsortbymonth' && $_GET['key'])
    {
        /*
        header("Content-type: application/json; charset={$charset}");
        $data = array('hello' => 'world');
        echo json_encode($data);
        exit;WHERE 
		this month:
        dateline >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE())-1 DAY))
		today:
		dateline >= UNIX_TIMESTAMP(CURDATE()) AND views > 1
        */
        $isvalid= verify_post_check($_GET['key']);

        header("Content-type: application/json; charset={$charset}");
$query = $db->query("SELECT tid,fid,views,subject,lastposter,dateline FROM ".TABLE_PREFIX."threads  WHERE dateline >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE())-1 DAY)) AND views > 1 ORDER BY views DESC LIMIT 10");
$data = array("siteInfo"=>array("code"=>$_GET['key'],"seourls"=>$mybb->settings['seourls'], "bburl"=>$mybb->settings['bburl']),"topics" => array());
while($row = $db->fetch_array($query) ) {
 	$data["topics"][] = $row;
}
echo json_encode($data);
exit;


    }















}


function trends_action_hand(&$action)
{
	$action['trends'] = array('active' => 'trends');
}

/**
 * add an entry to the ACP Config page menu
 *
 * @param  array menu
 * @return void
 */

function trends_link(&$sub_menu)
{
	global $mybb, $db;
$query2 = $db->query("SELECT * FROM ".TABLE_PREFIX."settinggroups WHERE name ='trends_db_setting'");
		$result3 = $db->fetch_array($query2);
		$token_id = $result3["gid"];



	end($sub_menu);
	$key = (key($sub_menu)) + 10;
	$sub_menu[$key] = array(
		'id' => 'trends',
		'title' => 'Trending Widget',
		'link' => 'index.php?module=config-settings&action=change&gid='.$token_id.''
	);
}



function trends_load(){
	global $lang, $mybb, $db, $page;

	if ($page->active_action != 'trends'){
		return false;
	}

	require_once MYBB_ADMIN_DIR."inc/class_form.php";

		
		
	

	// Create Admin Tabs
	$tabs['trends'] = array
		(
			'title' => 'test',
			'link' =>'#',
			'description'=> '404 page not found...'
		);
	

// No action
	if(!$mybb->input['action'])
	{


		$page->output_header("header");
		$page->add_breadcrumb_item("title");
		$page->output_nav_tabs($tabs,'trends');
		$page->output_footer();
}



}
























function trends_info()
{
	return array(
		"name"			=> "Trends Widget [BETA]",
		"description"	=> "Show the most trending threads",
		"website"		=> "https://pakclan.com/showthread.php?tid=305",
		"author"		=> "Zain Ali",
		"authorsite"	=> "https://zainali.altervista.org",
		"version"		=> "1.2",
		"guid" 			=> "",
		"codename"		=> "trends_widget",
		"compatibility" => "18*"
	);
}


function trends_activate(){
	global $db, $mybb;

$setting_group = array(
    'name' => 'trends_db_setting',
    'title' => 'Trends [BETA] is LIVE !',
    'description' => 'trending threads, fresh data in one widget !',
    'disporder' => 5, // The order your setting group will display
    'isdefault' => 0
);

$gid = $db->insert_query("settinggroups", $setting_group);






$setting_array = array(
    // A text setting
    'wtitle' => array(
        'title' => 'Trending Widget Title',
        'description' => 'Enter the title you want:',
        'optionscode' => 'text',
        'value' => 'Trending...', // Default
        'disporder' => 1
    ),
   'wdev' => array(
        'title' => 'Enable Dev Mode?',
        'description' => 'Do you want the development mode turned on? only for developers',
        'optionscode' => 'yesno',
        'value' => 0,
        'disporder' => 2
    ),
);

foreach($setting_array as $name => $setting)
{
    $setting['name'] = $name;
    $setting['gid'] = $gid;

    $db->insert_query('settings', $setting);
}

// Don't forget this!
rebuild_settings();
}




function trends_deactivate(){
global $db;

$db->delete_query('settings', "name IN ('wtitle','wdev')");
$db->delete_query('settinggroups', "name = 'trends_db_setting'");

// Don't forget this
rebuild_settings();
}

function trends(){
global $mybb, $db, $trends_widget_template;
 
	$query1 = $db->query("SELECT * FROM ".TABLE_PREFIX."threads WHERE views > 1 ORDER BY views DESC LIMIT 10");
	$trends_widget_template = "<style>#trends_widget img {vertical-align:middle;} #trends_widget li a {color: #333;text-decoration: underline;}#trends_widget li{float:none;display:block;border-bottom:1px solid #DDD}#trends_widget ul{height:300px;overflow-y:scroll;}#trends_widget{width:340px;margin:1% auto;max-width:100%;background-color:#fff}</style><div id='trends_widget'><h2><img src='".$mybb->settings['bburl']."/images/trendswidget/arrow.png' width='32px'>".$mybb->settings['wtitle']."</h2><p>sort by: <select onchange='TW_SORTBY(this.selectedIndex);'><option value='0'>views</option><option value='1'>today</option><option value='2'>month</option></select></p><ul id='trends_widget_list'>";

	$html="";
	if($mybb->settings['seourls'] == "yes" ){
	$html = ".html";
	$url = $mybb->settings['bburl']."/thread-";

	}
	else {
	$url = $mybb->settings['bburl']."/showthread.php?tid=";
	$url = str_replace(THIS_SCRIPT,"",$url);
	$html = "";
	}	

	$DEVMODE_R=array();
	$NODATA=false;




if($db->num_rows($query1) <= 0 ){
$NODATA=true;
$trends_widget_template.="no data available</ul><hr>Also the Developer Data isn't available.</div>";
}


else{
while($result2 = $db->fetch_array($query1)){
	$views = $result2["views"];
	$tid = $result2["tid"];
	$fid = $result2["fid"];
	$subject = $result2["subject"];
	$lastposter = $result2["lastposter"];
	$thetime=date("d M Y",$result2["dateline"]);


    $trends_widget_template .= "<li><a href='$url".$result2["tid"]."$html'>".$result2["subject"]."</a><p><span title='views'> <img src='".$mybb->settings['bburl']."/images/trendswidget/arrow.png' width='16px'/> $views</span>| latest: $lastposter |$thetime </p></li>";



    $DEVMODE_R[] = $result2;

}




/*dev mode*/
	if($mybb->settings["wdev"] == 1  ) {
		//log some data
		

		$logdata="<script type='text/javascript' src='".$mybb->settings['bburl']."/jscripts/trends_widget.js'></script><script>var trendswidget_array = '".json_encode($DEVMODE_R)."';console.log(JSON.parse(trendswidget_array));</script>";
		
	}
	/*dev mode*/
	
$trends_widget_template .="<hr>Created by: <a href='https://community.mybb.com/thread-220425.html'>Zain Ali - &copy; Trending Widget 2018</a></ul></div>$logdata<script type='text/javascript' src='".$mybb->settings['bburl']."/jscripts/trends_widget.js'></script>";



 return $trends_widget_template;

}


}





 ?>