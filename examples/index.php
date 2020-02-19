<?php
namespace PHPVideoToolkit;
session_start();
if (!file_exists("output")) {
    mkdir("output", 777, true);
}
if (!file_exists("tmp")) {
    mkdir("tmp", 777, true);
}
if (!file_exists("video")) {
    mkdir("video", 777, true);
}
include("funciones.php");
if(count(check_images("output","jpg"))>=1){
	print '<h1 name="salida">Ya has convertido un video a frames!</h1>';
}

else{
	
	$numimmp4=check_images("video/","mp4");
	$numiflv=check_images("video/","flv");
	$numim3u8=check_images("video/","m3u8");
	$numimkv=check_images("video/","mkv");
	$numimts=check_images("video/",".ts");
	$numiavi=check_images("video/","avi");
	$numi3gp=check_images("video/","3gp");
	$numimov=check_images("video/","mov");
	$numiwmv=check_images("video/","wmv");
	
	$videos=array();
	
	if(count($numimmp4)==1){
		$videos[0]=$numimmp4[0];
	
	}
	
	if(count($numiflv)==1 && $videos[0]==null){
		$videos[0]=$numiflv[0];
	}
	
	if(count($numim3u8)==1 && $videos[0]==null){
		$videos[0]=$numim3u8[0];
	}
	
	if(count($numimkv)==1 && $videos[0]==null){
		$videos[0]=$numimkv[0];
	}
	
	if(count($numimts)==1 && $videos[0]==null){
		$videos[0]=$numimts[0];
	}
		
	if(count($numiavi)==1 && $videos[0]==null){
		$videos[0]=$numiavi[0];
	}
	
	if(count($numi3gp)==1 && $videos[0]==null){
		$videos[0]=$numi3gp[0];
	}
	
	if(count($numimov)==1 && $videos[0]==null){
		$videos[0]=$numimov[0];
	}
	
	if(count($numiwmv)==1 && $videos[0]==null){
		$videos[0]=$numiwmv[0];
	}
	
	if(count($videos)==0){
			print '<h1 name="salida">No tienes videos</h1>';
	}
	
	else{
		
		date_default_timezone_set('Europe/Madrid');
		
		include_once 'includes/bootstrap.php';
		
			$video = new Video('video/'.$videos[0]);
			$process = $video->extractFrames(new Timecode(1), new Timecode(1))
				->save('output/'.substr($videos[0],0,-4).'_frame_%timecode.jpg', null, Media::OVERWRITE_EXISTING);
			$frames = $process->getOutput();
			
			$frame_paths = array();
			
			if(empty($frames) === false){
				
				foreach ($frames as $frame){
					array_push($frame_paths, $frame->getMediaPath());
				}
			}
			
		print '<h1 name="salida">Exito!</h1>';
	
		unlink('video/'.$videos[0]);
	
		if($_SESSION['video2gif']){
		
			$frames=array();
			$frames=check_images("output/","jpg");
		
			for($x=0;$x<count($frames);$x++){
				rename("output/".$frames[$x],"../../Hacer_gif/img/".$frames[$x]);
			}
	
			header("Location: ../../Hacer_gif/crear_gif.php");
	
		}
	}
}

?>
