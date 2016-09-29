<?php

namespace SimpleFileStorage;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface SFSFacade
{
    public function setID($id);
    public function setFile(UploadedFile $file);
    public function setAttributes($name, $size, $type);
    public function getPath();
    public function getName($name);
    public function interpolation();
    public function saveModel();
    public function saveFile();
}