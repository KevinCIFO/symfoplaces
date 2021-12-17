<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Filesystem\Filesystem;

class FileService {
    public $targetDirectory;

    public function __construct(String $targetDirectory) {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file):String {
        $extension = $file->guessExtension();
        $fichero = uniqid().".$extension";

        try {
            $file->move($this->targetDirectory, $fichero);
        } catch(FileException $e) {
            return NULL;
        }

        return $fichero;
    }

    public function replace(UploadedFile $file, ?String $anterior):String {
        $extension = $file->guessExtension();
        $fichero = uniqid().".$extension";

        try {
            $file->move($this->targetDirectory, $fichero);

            if($anterior){
                $filesystem = new Filesystem();
                $filesystem->remove("$this->targetDirectory/$anterior");
            }
        } catch(FileException $e) {
            return $anterior;
        }

        return $fichero;
    }

    public function delete(String $fichero) {
        $filesystem = new Filesystem();
        $filesystem->remove("$this->targetDirectory/$fichero");
    }
}