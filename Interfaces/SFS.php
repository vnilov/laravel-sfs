<?php

namespace SimpleFileStorage\Interfaces;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface SFS
{
    public function getUrl($id);
    public function save(UploadedFile $file);
}