<?php
function listDir($dir,$pos) {
    header('Content-type:text/html; charset=utf-8');
	if(is_dir($dir)) {
     	if ($open = opendir($dir)) {
        	while (($file = readdir($open)) !== false) {
     			if((is_dir($dir."/".$file)) && $file!="." && $file!="..") {
     				listDir($dir.$file."/",$pos);
     			} else {
         			if($file!="." && $file!="..") {
         				$file_content = file_get_contents($dir.$file);
						if(strpos($file_content,$pos) !== false) {
							echo $dir.$file."<br>";
						}
      				}
     			}
        	}
        	closedir($open);
     	}
   	}
}



listDir('./Modules/','全球');
//listDir('./ThinkPHP_3.2.3/','121');
//listDir('./Public/Static/ueditor/','32'); spec  specification
//listDir('./Modules/','start_date');123456789
//listDir('./ThinkPHP_3.2.3/','toocms');