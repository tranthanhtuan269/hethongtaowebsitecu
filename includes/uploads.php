<?php



if ((!defined('CMS_SYSTEM')) && (!defined('CMS_ADMIN'))) die('Stop!!!');



class Upload {

	var $file;

	var $maxSize;

	var $path;

	var $mode;

	var $newSize;



	function Upload() {

		$numArgs = func_num_args();

		$args = func_get_args();

		$this->maxSize = 0;

		$this->mode = "";

		$this->newSize = 0;

		if ($numArgs >= 2) {

			$this->file = $args[0];

			$this->path = $args[1];

			if ($numArgs >= 3) {

				$this->setMaxSize($args[2]);

				if ($numArgs >= 4) {

					$this->setMode($args[3]);

					if ($numArgs == 5) {

						$this->setNewSize($args[4]);

					}

				}

			}

		} else Common::debug("Wrong number of arguments");

	}



	function setMaxSize($maxSize) {

		$this->maxSize = intval($maxSize);

	}



	function setMode($mode) {

		$this->mode = $mode;

	}



	function setNewSize($newSize) {

		$this->newSize = intval($newSize);

	}



	function setSaveThumb($saveThumb) {

		$this->saveThumb = intval($saveThumb);

	}



	function setThumbPath($thumbPath) {

		if ($this->saveThumb) $this->thumbPath = $thumbPath;

	}



	function send() {

		global $extAllow, $mimeAllow;



		$fup_name = "";

		$realName = $_FILES[$this->file]['name'];

		$fileSize = $_FILES[$this->file]['size'];

		$fileType = $_FILES[$this->file]['type'];

		$extension = Common::getExt($realName);

		$acceptableExt = explode(",", $extAllow);

		$acceptableMIME = explode(",", $mimeAllow);



		if ((in_array($extension, $acceptableExt)) && (in_array($fileType, $acceptableMIME))) {

			if (($this->maxSize > 0) && ($fileSize > $this->maxSize)) {

				$sizekb = intval($this->maxSize / 1024);

				info_exit("<br><br>"._UPLOAD_ERROR1." $sizekb KB", _GOBACK);

			}



			$data = date('U');			

			$datakod_random = substr($data, 8, 11);

			//$datakod .= generate_code(6);
			$datakod = "";
			
			$datakod .= utf8_to_ascii(url_optimization($realName));
			
			$fname = explode(".", $datakod);
			$fname = $fname[0]."_".$datakod_random;			

			if (!empty($this->mode)) $fup_name = "{$this->mode}_$fname.$extension";

			else $fup_name = "$fname.$extension";

			if (!@move_uploaded_file($_FILES[$this->file]['tmp_name'], RPATH."{$this->path}/$fup_name")) info_exit("<br><br>"._UPLOAD_ERROR."!<br>", _GOBACK);

		} else info_exit("<br><b>"._UPLOAD_ERROR2." <font color=\"red\">$extension.</font><br><br>"._UPLOAD_ERROR3.": <font color=\"red\">$extAllow</font></b>", _GOBACK);



		if ($this->newSize > 0) doResizeImg(RPATH."$path/$fup_name", $this->newSize);



		return $fup_name;

	}

}



function resizeImg($fname, $path, $newSize, $thumbPath = "", $prefix = "", $replace = false) {

	if (empty($thumbPath)) $thumbPath = $path;

	if (empty($prefix)) $prefix = "thumb";

	$realPath = RPATH."$path/$fname";

	if ($replace) $destPath = $realPath;

	else $destPath = RPATH."$thumbPath/{$prefix}_$fname";

	doResizeImg($destPath, $newSize, $realPath);

}



function doResizeImg($path, $newSize, $realPath) {

	$imgExtension = array("gif", "jpeg", "jpg", "png");

	$size = getimagesize($realPath);

	$extension = Common::getExt($realPath);

	if (($size[0] > $newSize) && in_array($extension, $imgExtension)) {

		if ($path != $realPath) copy($realPath, $path);

		if (($extension == "jpeg") || ($extension == "jpg"))  {

			$createFromFunction = 'imagecreatefromjpeg';

			$createFunction = 'imagejpeg';

		} elseif ($extension == "gif") {

			$createFromFunction = 'imagecreatefromgif';

			$createFunction = 'imagegif';

		} elseif ($extension == "png") {

			$createFromFunction = 'imagecreatefrompng';

			$createFunction = 'imagepng';

		}

		$src_img = $createFromFunction($path);

		$src_width = imagesx($src_img);

		$src_height = imagesy($src_img);

		$dest_width = $newSize;

		$dest_height = $src_height/($src_width/$dest_width);

		$quality = 90;

		if ($extension == "png") $quality = 0;

		$dest_img = imagecreatetruecolor($dest_width, $dest_height);

		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);

		$createFunction($dest_img, $path, $quality);

	}

}



?>