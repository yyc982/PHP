<?php
/******************************************************************/
/* PHP example codes:
/* upload multiple photos
/* To use this upload MUST install php54-gd library
/*
/****************************************************************/


require 'core/db/connect.php';

ini_set("memory_limit","256M"); // MUST to allow big files upload


$allowedExts = array("gif", "jpeg", "jpg", "png");
$valid = TRUE;
$output_dir = "images/news/";
$copy_dir = "images/originals/";

// Set a maximum height and width
$width = 1600;
$height = 1600;	


// count
$fileCount = count($_FILES["files"]["name"]);

if (isset($_FILES['files']))
{
	if($_FILES["files"]["size"][0] == 0) 
	{
		//means there is no file uploaded
		//echo '4';
	}
	else
	{
		// there is file uploaded
		//print_r($_FILES["files"]);
		
		if($fileCount>6)
		{
				
			$errors[] = "*You can add up to 6 photos.";
			$valid = FALSE;
		}
		if ($valid = FALSE)
		{
			
		}
		else
		{
			foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name)
			{
				//validation each file
		
				$temp = explode(".", $_FILES["files"]["name"][$key]);
				$extension = strtolower(end($temp));
				if (
						( 
							// type test
							($_FILES["files"]["type"][$key] == "image/gif")
							|| ($_FILES["files"]["type"][$key] == "image/jpeg")
							|| ($_FILES["files"]["type"][$key] == "image/jpg")
							|| ($_FILES["files"]["type"][$key] == "image/pjpeg")
							|| ($_FILES["files"]["type"][$key] == "image/x-png")
							|| ($_FILES["files"]["type"][$key] == "image/png")
						)
						&& 
						// size test
						($_FILES["files"]["size"][$key] < 10485760) // 10mb
						&& 
						//extension fit
						in_array($extension, $allowedExts)
					)
				{		
					//pass validation
					$valid = TRUE;
				}	
				else
				{
					// not validate	
					$errors[] = "*Only .jpg, .jpeg, .png, .gif photos are allowed.";
					$valid = FALSE;
					echo $valid;
				}
			}
			
			if ($valid == TRUE)
			{
		
				$i= 0;			
				$images = array();
				foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name)
				{
					//set variables
	
					$fileName = $_FILES["files"]["name"][$key]; // with extension
					$file_extn = strtolower(end(explode('.', $fileName)));
					$file_temp = $_FILES['files']['tmp_name'][$key]; //with .extension
					$fileNewName = $key.substr(md5(time()),0,100) . '.' . $file_extn;
					$file_path = $output_dir.$fileNewName;
	
					$file_copy_path = $copy_dir.$fileNewName;
					move_uploaded_file($file_temp,$file_copy_path);
					
					if($file_extn=="jpg" || $file_extn=="jpeg" )
					{
						$image = imagecreatefromjpeg($file_copy_path);
					}
					else if($file_extn=="png")
					{
						$image = imagecreatefrompng($file_copy_path);
					}
					else 
					{
						$image = imagecreatefromgif($file_copy_path);
					}
				 
					list($width_orig,$height_orig)=getimagesize($file_copy_path);
					$ratio_orig = $width_orig/$height_orig;
					
					if ($width/$height > $ratio_orig) 
					{
						$width = $height*$ratio_orig;
					} 
					else 
					{
						$height = $width/$ratio_orig;
					}				
					
					$image_p=imagecreatetruecolor($width,$height);
					imagecopyresampled($image_p,$image,0,0,0,0,$width,$height,$width_orig,$height_orig);				
					imagejpeg($image_p,$file_path,100);
					$images[$i] = $file_path;
					$i++;
				}
			}
			else
			{
				echo 'not move';
			}		
			
		}


		
	} // end there is file uploaded

	
} // end of isset
else 
{
	
	
}
	



?>