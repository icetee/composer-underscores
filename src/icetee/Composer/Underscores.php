<?php

namespace icetee\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 *  Underscores
 *  @version 1.1.1
 *
 */
class Underscores extends LibraryInstaller {

    public function getInstallPath(PackageInterface $package) {
        return $package->getPrettyName();
    }

    function rcopy($src, $dest, $skipping = array()){

        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
            if (in_array($f->getFilename(), $skipping)) continue;
            if($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->rcopy($f->getRealPath(), "$dest/$f");
            }
        }
    }
}

?>
