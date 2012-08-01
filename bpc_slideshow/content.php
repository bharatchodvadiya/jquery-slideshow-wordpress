<?php
$pluginDir=get_option('siteurl').'/wp-content/plugins/bpc_slideshow/';
global $wpdb;
$table=$wpdb->prefix . 'slideshow';

$sql="select * from $table";
$data=$wpdb->get_results($sql,ARRAY_A);
$a = count($data);
$i=0;
foreach($data as $image)
{
$i++;
if($a==$i)
   echo ''.$pluginDir.'images/'.$image["image_name"];
else 
   echo ''.$pluginDir.'images/'.$image["image_name"].',';
  
}
   echo '[IMAGES]';		 
