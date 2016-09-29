<?php

namespace SimpleFileStorage;

interface SFS
{
    public function getUrl($id);
    public function save(UploadedFile $file);
}