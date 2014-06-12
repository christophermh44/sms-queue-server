<?php
	class JsonData {
		static $path = '/../data/';
		static $ext = '.json';
		static $emptyData = '{}';
		static $jsonContentType = 'Content-type: application/json';

		static function read($name) {
			$file = __DIR__.self::$path.$name.self::$ext;
			if (!file_exists($file)) {
				file_put_contents($file, self::$emptyData);
			}
			$content = file_get_contents($file);
			$object = json_decode($content, true);
			return $object;
		}

		static function write($name, $object) {
			$file = __DIR__.self::$path.$name.self::$ext;
			$content = json_encode($object);
			file_put_contents($file, $content);
		}

		static function out($object) {
			header(self::$jsonContentType);
			echo json_encode($object);
		}
	}
?>
