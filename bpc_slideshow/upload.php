<?php
 include('../../../wp-load.php');

if (!empty($_FILES)) 
{
    if (file_exists("images/" . $_FILES["file"]["name"]))
    {
      echo $_FILES["file"]["name"] . " already exists. ";
    }
    else
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "images/" . $_FILES["file"]["name"]);     
    }
}

$data=get_option('siteurl').'/wp-admin/options-general.php?page=slideshow';

global $wpdb;
$table=$wpdb->prefix . 'slideshow';

$sql="insert into $table values (null,'".$_FILES['file']['name']."');";
$result = $wpdb->query($sql);
if($result)
{
 header('Location: '.$data); exit;
}
  
?>