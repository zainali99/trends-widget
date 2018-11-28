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
// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
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
	end($sub_menu);
	$key = (key($sub_menu)) + 10;
	$sub_menu[$key] = array(
		'id' => 'trends',
		'title' => 'Trending Widget',
		'link' => 'index.php?module=config-trends'
	);
}



function trends_load(){
	global $lang, $mybb, $db, $page;

	if ($page->active_action != 'trends'){
		return false;
	}

	require_once MYBB_ADMIN_DIR."inc/class_form.php";

		/*
		https://HOST.COM/admin/index.php?module=config-settings&action=change&gid=
		build link of setting dynamically, ignore the code of this plugin
		im just having fun and testing plugin development :D
		*/
		$query2 = $db->query("SELECT * FROM ".TABLE_PREFIX."settinggroups WHERE name ='trends_db_setting'");
		$result3 = $db->fetch_array($query2);
		$token_id = $result3["gid"];


	// Create Admin Tabs
	$tabs['trends'] = array
		(
			'title' => 'test',
			'link' =>'index.php?module=config/trends',
			'description'=> 'Sorry, this is alpha version of trends widget, so click here for setting of the plugin: <a href="index.php?module=config-settings&action=change&gid='.$token_id.'">Go To Trends Setting !</a><hr>Alpha Version - 1.0</a>'
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
		"name"			=> "Trends Widget [ALPHA]",
		"description"	=> "Show the most trending threads [ALPHA]",
		"website"		=> "https://pakclan.com",
		"author"		=> "Zain Ali",
		"authorsite"	=> "https://zainali.altervista.org",
		"version"		=> "1.0",
		"guid" 			=> "",
		"codename"		=> "trends_widget",
		"compatibility" => "18*"
	);
}


function trends_activate(){
	global $db, $mybb;

$setting_group = array(
    'name' => 'trends_db_setting',
    'title' => 'Trends [ALPHA] is LIVE !',
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
        'value' => 'trending', // Default
        'disporder' => 1
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

$db->delete_query('settings', "name IN ('wtitle')");
$db->delete_query('settinggroups', "name = 'trends_db_setting'");

// Don't forget this
rebuild_settings();
}

function trends(){
global $mybb, $db, $trends_widget_template;
	$query1 = $db->query("SELECT * FROM ".TABLE_PREFIX."threads WHERE views > 1 ORDER BY views DESC LIMIT 10");
	$trends_widget_template = "<div id='trends_widget'><h2><img src='https://cdn2.iconfinder.com/data/icons/iconza/iconza_32x32_df086d/line_graph.png'>".$mybb->settings['wtitle']."</h2><ul>";
	$url = "http://".$_SERVER['HTTP_HOST']."/showthread.php?tid=";
while($result2 = $db->fetch_array($query1)){
    $result2["subject"] = htmlspecialchars_uni($result2["subject"]);	
    $trends_widget_template .= "<li><b>+".$result2["views"]."</b> - <a href='$url".$result2["tid"]."'>".$result2["subject"]."</a></li>";
}
$trends_widget_template .="</ul></div><style>#trends_widget li{float:none;display:block}#trends_widget ul{position:unset}#trends_widget{width:300px;margin:1% auto;max-width:100%;min-height:150px;background-color:#fff}</style>";
 return $trends_widget_template;
}





 ?>
