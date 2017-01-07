<?php
class ImgAscii{
	
	private function mainColor($img){
		global $img_tipo;
		if($img_tipo == "png"){
			$image = imagecreatefrompng($img);
		} else {
			$image = imagecreatefromjpeg($img);
		}
			$thumb=imagecreatetruecolor(1,1); imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
		$mainColor=dechex(imagecolorat($thumb,0,0));
		//return $mainColor;
		$thiscol = imagecolorat($thumb, 0, 0);
		$rgb = imagecolorsforindex($thumb, $thiscol);
		return $rgb;
	}
	
	privatefunction mainColor($img){
		global $img_tipo;
		if($img_tipo == "png"){
			$image = imagecreatefrompng($img);
		} else {
			$image = imagecreatefromjpeg($img);
		}
			$thumb=imagecreatetruecolor(1,1); imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
		$mainColor=dechex(imagecolorat($thumb,0,0));
		//return $mainColor;
		$thiscol = imagecolorat($thumb, 0, 0);
		$rgb = imagecolorsforindex($thumb, $thiscol);
		return $rgb;
	}

	private function HexToRGB($hex) {
		$hex = ereg_replace("#", "", $hex);
		$color = array();

		if(strlen($hex) == 3) {
			$color['red'] = hexdec(substr($hex, 0, 1) . $r);
			$color['green'] = hexdec(substr($hex, 1, 1) . $g);
			$color['blue'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['red'] = hexdec(substr($hex, 0, 2));
			$color['green'] = hexdec(substr($hex, 2, 2));
			$color['blue'] = hexdec(substr($hex, 4, 2));
		}

		return $color;
	}

	private function RGBToHex($r, $g, $b) {
		//String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
		$hex = "#";
		$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

		return $hex;
	}

	private function listdir_by_date($path){
		global $ext_ok;
		$aberto = opendir($path);
		$list = array();
		while($file = readdir($aberto)){
			if( $file != "." && $file != ".." && (preg_match("/^[a-zA-Z0-9 ._-]+(".$ext_ok.")$/i", $file))) {
				// add the filename, to be sure not to
				// overwrite a array key
				$ctime = filemtime($path . $file) . ',' . $file;
				$list[$ctime] = $file;
			}
		}
		closedir($aberto);
		krsort($list);
		return $list;
	}

	private function corSimilar($rgb1,$rgb2){
		$sensib = 40; // valor maior = menor sensibilidade
		$r = $rgb1['red'] ;
		$g = $rgb1['green'];
		$b = $rgb1['blue'];	
		$r2 = $rgb2['red'];
		$g2 = $rgb2['green'];
		$b2 = $rgb2['blue'];
		$redOk = false;
		$redOk = false;
		$blueOk = false;
		$difR = max($r,$r2) - min($r,$r2);
		$difG = max($g,$g2) - min($g,$g2);
		$difB = max($b,$b2) - min($b,$b2);
		if($difR < $sensib){		
			$redOk = true;
		}	
		if($difG < $sensib){		
			$greenOk = true;
		}	
		if($difB < $sensib){		
			$blueOk = true;
		}
		if($redOk == true and $redOk == true and $blueOk == true){
			return true;
		} else {
			return false;
		}	
	}

}

// !!!suporte somente a imagens monocromaticas!!!
// img "grande" até 250px
$locate= 'sp4.jpg';
$tam_px = 60;
$img_tipo = "jpg";

$qtd_img = 0;
$arr_imgs = array();
$pasta_raiz    =   "pessoas/";
$aberto = opendir($pasta_raiz);

while($arq = readdir($aberto)) {
	//DESCONSIDERA DIRETÓRIOS E SUBDIRETÓRIOS
	if($arq != "." && $arq != ".." && (preg_match("/^[a-zA-Z0-9._-]+(jpg|jpeg|png)$/i", $arq))) {
		//DIVIDE A STRING
		$ext  =  explode(".",$arq);			  
		//VERIFICA SE É UMA EXTENSÃO DE IMAGEM
		if(!preg_match("/^image\/(pjpeg|jpeg|png)$/i", $ext[1])) {
			//ignora arquivos PHP e DB
			if($ext[1] == "php" or $ext[1] == "db" ){ /*nada acontece */ }
			else{ // LÊ IMG
				$arr_imgs[count($arr_imgs)]['nome'] = $arq;
				$arr_imgs[count($arr_imgs)-1]['rgb'] =  mainColor($pasta_raiz.$arq);
				//echo("$arq ".mainColor($pasta_raiz.$arq)."\n");		
				//$str_retun .= "<img src='{$pasta_raiz}{$arq}' />";					
			}
		}
	}
}
//print_r($arr_imgs);
//die;

$image = imagecreatefromjpeg("$locate");

if ($image) {
/*
	echo '<PRE STYLE="font: 6px/4px Courier New;">';
	$asciichars = array("@", "#", "+", "*", ";", ":", ",", ".", "`", " ");
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
	echo '</PRE>';
*/
	//$asciichars = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
	$width = imagesx($image);
	$height = imagesy($image);
	$str_retun='';
	$fill_px;
	$n = 0;
	
	for($y = 0; $y < $height; ++$y) {
		for($x = 0; $x < $width; ++$x) {
			$thiscol = imagecolorat($image, $x, $y);
			$rgb = imagecolorsforindex($image, $thiscol);
			$brightness = $rgb['red'] + $rgb['green'] + $rgb['blue'];
			
			$fill_px = false;
			foreach($arr_imgs as $value){
				//echo "{$value['nome']} RGB {$value['rgb']}"."\n<br>";
				//print_r($rgb);
				//print_r($value['rgb']);
				if(corSimilar($rgb,$value['rgb'])){
					//echo "{$value['nome']} similar\n<br>";
					$str_retun .= "<img class='pixel' src='{$pasta_raiz}{$value['nome']}' />";
					$fill_px = true;
					break;
				}
			}
			if(!$fill_px){
				$str_retun .= "<img class='pixel' src='{$pasta_raiz}x.jpg' />";
			}
			
			//$brightness = round($brightness / 85);
			//$char = $asciichars[$brightness];
			//$str_retun .= '<span class="cell tom'.$char.'">&nbsp;</span>';
			//$str_retun .= '<img src="tons/'.$char.'.jpg" />';
			//$n++;
			//echo $n;
		}
		$str_retun .= "\n";
	}
	
}
?>
<html>
<style>
.pixel{width:<?php echo $tam_px; ?>px; height:<?php echo $tam_px; ?>px;}
.cell{width:<?php echo $tam_px; ?>px; height:<?php echo $tam_px; ?>px; font-size:1px; display:inline-block; margin:0; padding:0}
.tom0{background:#000}
.tom1{background:#222}
.tom2{background:#333}
.tom3{background:#444}
.tom4{background:#555}
.tom5{background:#666}
.tom6{background:#777}
.tom7{background:#888}
.tom8{background:#999}
.tom9{background:#ccc}
</style>
<?php 
echo "<div style='width:".$width*$tam_px . "px'>";
echo $str_retun; 
echo "</div>";
?>
