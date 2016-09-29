<?php

namespace SimpleFileStorage;

use SimpleFileStorage\Eloquent\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SFSFacadeL52 implements SFSFacade
{
    private $_length;
    private $_chunk_length;
    private $_id = null;
    private $_file = null;

    public function __construct()
    {
        $this->_length = config('sfs.length');
        $this->_chunk_length = config('sfs.chunk_length');
    }

    public function setID($id)
    {
        $this->_id = $id;
    }

    public function setFile(UploadedFile $file)
    {
        if ($file->isValid())
            $this->_file = $file;
        else
            throw new \Exception('Invalid file');
    }

    public function interpolation()
    {
        if (isset($this->_id))
            return (string)sprintf("%0" . $this->_length . "d", $this->_id);
        throw new \Exception('Missed File ID param');
    }

    public function setAttributes($name = null, $size = null, $type = null)
    {
        return [
            'name' => (isset($name)) ? $name : $this->_file->getClientOriginalName(),
            'size' => (isset($size) && (int)$type > 0) ? $size : $this->_file->getClientSize(),
            'type' => (isset($type)) ? $type : $this->_file->getClientMimeType()
        ];
    }

    public function getPath()
    {
        return chunk_split(substr($this->interpolation(), 0, $this->_length - $this->_chunk_length), $this->_chunk_length, "/");
    }

    public function getName($name = null)
    {
        $name = (isset($name)) ? $name : $this->_file->getClientOriginalName();
        return $this->interpolation() . "." . pathinfo($name, PATHINFO_EXTENSION);
    }

    public function saveModel()
    {
        $file = new File($this->setAttributes($this->_file));
        $file->saveOrFail();
        return $file->getKey();
    }

    public function saveFile()
    {
        return $this->_file->move(storage_path(config('sfs.storage_path') . $this->getPath()), $this->getName());
    }

    public function getUrl($id)
    {
        if ((int)$id > 0) {
            $file = File::findOrFail($id);
            $this->setID($file->getKey());
            return storage_path(config('sfs.storage_path') . $this->getPath()) . $this->getName($file->name);
        }
        throw new \Exception('Bad ID param');
    }

    public function save(UploadedFile $file)
    {
        $this->setFile($file);
        $this->setID($this->saveModel());
        if ($this->saveFile())
            return $this->_id;
        throw new \Exception('Something went wrong when file had been saving');
    }
}