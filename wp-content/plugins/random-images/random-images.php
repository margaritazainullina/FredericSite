<?php
/**
 * Plugin Name: Random Images
 * Description: Display a set of random attached images with the [random_images] shortcode.
 * Version: 0.7
 * Author: Sheri Bigelow
 * Author URI: http://designsimply.com/
 * License: GPLv2 or later
 */

class Random_Images_Plugin {

	static function load() {
		$path   = 'wp-content/uploads/photo-gallery/';
$result = array('files' => array(), 'directories' => array());

$DirectoryIterator = new RecursiveDirectoryIterator($path);
$IteratorIterator  = new RecursiveIteratorIterator($DirectoryIterator, RecursiveIteratorIterator::SELF_FIRST);
foreach ($IteratorIterator as $file) {

    $path = $file->getRealPath();
    if ($file->isDir()) {
        $result['directories'][] = $path;
    } elseif ($file->isFile()) {
        $result['files'][] = $path;
    }

}

echo '123';
	}

}
Random_Images_Plugin::load();
