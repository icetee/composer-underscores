<?php

require_once(__DIR__ . '/_s-downloader.php');

new Downloader(array(
    "config" => array(
        "path" => dirname(__FILE__) . '/../'
    )
));

?>
