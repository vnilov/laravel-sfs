<?php

namespace SimpleFileStorage\Interfaces;

interface SFS
{
    public function getUrl($id);
    public function save(UploadedFile $file);
}