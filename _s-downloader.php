<?php
// Load vendor
$loader = require __DIR__ . '/vendor/autoload.php';

/**
 *  UnderscoresDownloader
 *  @version 1.0
 *
 */
use Symfony\Component\Yaml\Yaml;

class UnderscoresDownloader {
    function __construct() {
        $this->theme = array(
			'name'        => 'Underscores',
			'slug'        => 'theme-underscores',
			'uri'         => 'http://underscores.me/',
			'author'      => 'Underscores.me',
			'author_uri'  => 'http://underscores.me/',
			'description' => 'Description',
			'sass'        => false,
			'wpcom'       => false,
		);
        $this->config();
    }

    function config() {
        $yml = Yaml::parse(file_get_contents(dirname(__FILE__) . '/../_s.yml'));
        print_r($yml);
    }
}

// Load auto
new UnderscoresDownloader();
?>
