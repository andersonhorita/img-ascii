<?php
/* #######################################################################

IMG ASCII CLASS
Author: Anderson Horita
NOTES: use only images up to 200 px

######################################################################### */

class ImgAscii{	
	
	private $locate = 'test2.jpg';
	private $colored = true; // Bool: true or false
	
	public function __construct(){		
		$this->generateImg();
	}
	
	private function generateImg(){		
		$image = imagecreatefromjpeg($this->locate);
		if($image) {		
			if($this->colored){
				$this->imgColored($image);
			} else{
				$this->imgMonochromatic($image);
			}
		} else {
			echo "Image doesn't exist.";
		}
	}		
	
	public function imgMonochromatic($image){		
		echo '<pre style="font: 6px/4px Courier New;">';		
		$asciichars = array("#", "$", "+", "*", ";", ":", ",", ".", "`", " ");
		$width = imagesx($image);
		$height = imagesy($image);
		for($y = 0; $y < $height; ++$y) {
			for($x = 0; $x < $width; ++$x) {
				$thiscol = imagecolorat($image, $x, $y);
				$rgb = imagecolorsforindex($image, $thiscol);
				$brightness = $rgb['red'] + $rgb['green'] + $rgb['blue'];
				$brightness = round($brightness / 85);
				$char = $asciichars[$brightness];
				echo $char;
			}
			echo "\n";
		}
		echo '</pre>';
	}
	
	private function imgColored($image){
		$width = imagesx($image);
		$height = imagesy($image);
		echo "<pre style='font: 6px/4px Courier New;'>\n";
		for($h=0;$h<$height;$h++){
			for($w=0;$w<=$width;$w++){
				if($w == $width){
					echo "\n";
					continue;
				}
				$rgb = ImageColorAt($image, $w, $h);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8 ) & 0xFF;
				$b = $rgb & 0xFF;
				echo '<b style="color:rgb('.$r.','.$g.','.$b.');">#</b>';				
			}
		}
	}
}
?>