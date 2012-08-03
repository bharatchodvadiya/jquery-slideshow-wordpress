<?php
 include('../../../wp-load.php');

 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
 
if (!empty($_FILES)) 
{
    if (file_exists("images/" . $_FILES["file"]["name"]))
    {
      echo $_FILES["file"]["name"] . " already exists. ";
    }
    else
    {
		$name = $_FILES["file"]["name"];
		$uploadedfile = $_FILES['file']['tmp_name'];
		$ext = getExtension($name);
 		$ext = strtolower($ext);
					
		if($ext=="jpg" || $ext=="jpeg" )
							{
								$src = imagecreatefromjpeg($uploadedfile);
							}
							else if($ext=="png")
							{
								$src = imagecreatefrompng($uploadedfile);
							}
							else 
							{
								$src = imagecreatefromgif($uploadedfile);
							}
							
		
		list($width,$height)=getimagesize($uploadedfile);
					
		if(get_option('slideshow_image_width') != '')
		{
		$newwidth=get_option('slideshow_image_width');
		}
		else
		{
		$newwidth=240;
		}
		
		if(get_option('slideshow_image_height') != '')
		{
		$newheight=get_option('slideshow_image_height');
		}
		else
		{
		$newheight=160;
		}
		
		
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		$filename = "images/".$_FILES["file"]["name"];
		
		if(imagejpeg($tmp,$filename))
			echo "done";
									     
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