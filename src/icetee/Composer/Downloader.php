<?php

namespace icetee\Composer;

use Symfony\Component\Yaml\Yaml;

class Downloader extends Underscores {
    function __construct($params = array()) {
        // Default parameters
        $this->params = array(
            "theme" => array(
    			'name'        => 'Underscores',
    			'slug'        => 'theme-underscores',
    			'uri'         => 'http://underscores.me/',
    			'author'      => 'Underscores.me',
    			'author_uri'  => 'http://underscores.me/',
    			'description' => 'Description',
    			'sass'        => 0,
    			'wpcom'       => 0,
    		),
            "config" => array(
                "path"      => dirname(__FILE__),
                "file"      => 'config.yml'
            )
        );
        $this->dir = dirname(__FILE__) . '/../';
        $this->params = array_replace_recursive($this->params, $params);
        $this->theme = $this->params["theme"];
        $this->init();
    }

    private function init() {
        // Initialize config
        $path = $this->params["config"]["path"] . $this->params["config"]["file"];
        $yml = Yaml::parse(file_get_contents($path));
        if (!is_null($yml)) {
            $this->theme = array_replace(
                $this->params["theme"],
                $yml["underscores"]
            );
        }

        // Add rules
        $this->rules();

        // Normalizer
        $this->normalizer();
    }

    private function rules() {
        // Skipping files
        $this->skipping = array(
            '.travis.yml',
            'CONTRIBUTING.md',
            'codesniffer.ruleset.xml',
            '.git'
        );

        $this->skip_replace = array(
            'README.md',
        );

        if (!$this->params["theme"]["sass"]) {
            array_push($this->skipping, 'sass');
        }

        if (!$this->params["theme"]["wpcom"]) {
            array_push($this->skipping, 'wpcom.php');
        }
    }

    private function normalizer() {
        // Copy folder
        $this->rcopy(
            $this->dir . '_s',
            $this->dir . $this->theme["slug"],
            $this->skipping
        );

        $this->searchAndReplace();
    }

    private function searchAndReplace() {
        // Search and replace
        $di = new RecursiveDirectoryIterator($this->dir . $this->theme["slug"]);
        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            if ($file->isDir() || in_array($file->getFilename(), $this->skip_replace)) { continue; }

            $content = file_get_contents($filename);
            $content = preg_replace("/\'_s\'/i", "'". $this->theme["slug"] ."'", $content);
            $content = preg_replace("/_/i", $this->theme["slug"] . "_", $content);
            $content = preg_replace("/Text Domain: /i", "Text Domain: " . $this->theme["slug"], $content);
            $content = preg_replace("/ /i", " " . $this->theme["name"], $content);
            $content = preg_replace("/-/i", $this->theme["slug"] . "-", $content);
            $content = preg_replace("/\"_s\"/i", '"'. $this->theme["name"] .'"', $content);
            $content = preg_replace("/^_s/i", $this->theme["name"], $content);
            file_put_contents($filename, $content);
        }
    }
}

new Downloader();
?>
