<?php

namespace SimpleFileStorage;

use SimpleFileStorage\Eloquent\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SFSL52 implements SFS
{
    private $facade;
    
    public function __construct(\SimpleFileStorage\SFSFacade $facade)
    {
        $this->facade = $facade;
    }

    public function getUrl($id)
    {
        if ((int)$id > 0) {
            $file = File::findOrFail($id);
            $this->facade->setID($file->getKey());
            return storage_path(config('sfs.storage_path') . $this->facade->getPath()) . $this->facade->getName($file->name);
        }
        throw new \Exception('Bad ID param');
    }
    public function save(UploadedFile $file)
    {
        $this->facade->setFile($file);
        $this->facade->setID($this->facade->saveModel());
        if ($this->facade->saveFile())
            return;
        throw new \Exception('Something went wrong when file had been saving');
    }
}