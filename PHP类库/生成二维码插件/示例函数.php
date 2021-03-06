<?php 
		//直接调用函数即可
		function gen_qrcode($str,$logo,$size = 5)
		{
			$root_dir = APP_ROOT_PATH."public/images/qrcode/";
			if (!is_dir($root_dir)) {
					@mkdir($root_dir);
					@chmod($root_dir, 0777);
			 }
			 $filename = md5($str."|".$size);
			 $hash_dir = $root_dir. '/c' . substr(md5($filename), 0, 1)."/";
			 if (!is_dir($hash_dir))
			 {
				@mkdir($hash_dir);
				@chmod($hash_dir, 0777);
			 }
			$filesave = $hash_dir.$filename.'.png';
			if(!file_exists($filesave))
			{
					require_once APP_ROOT_PATH."system/phpqrcode/qrlib.php";
					QRcode::png($str, $filesave, 'Q', $size, 2);
			}
			$QR = APP_ROOT_PATH."/public/images/qrcode/c". substr(md5($filename), 0, 1)."/".$filename.".png";
			if($logo !== FALSE)
			{
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				$logo_qr_width = $QR_width / 5;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;

				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			}
			//带logo的二维码
			$new_logo = APP_ROOT."/public/images/qrcode/c". substr(md5($filename), 0, 1)."/".$filename.".png";
			imagepng($QR, $new_logo);
			return $new_logo;
		}
