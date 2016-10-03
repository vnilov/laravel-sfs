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

    /**
     * SFSFacadeL52 constructor.
     */
    public function __construct()
    {
        $this->_length = config('sfs.length');
        $this->_chunk_length = config('sfs.chunk_length');
    }

    /**
     * @param $id
     */
    public function setID($id)
    {
        $this->_id = $id;
    }

    /**
     * @param UploadedFile $file
     *
     * @throws \Exception
     */
    public function setFile(UploadedFile $file)
    {
        if ($file->isValid())
            $this->_file = $file;
        else
            throw new \Exception('Invalid file');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function interpolation()
    {
        if (isset($this->_id))
            return (string)sprintf("%0" . $this->_length . "d", $this->_id);
        throw new \Exception('Missed File ID param');
    }

    /**
     * @param null $name
     * @param null $size
     * @param null $type
     *
     * @return array
     */
    public function setAttributes($name = null, $size = null, $type = null)
    {
        return [
            'name' => (isset($name)) ? $name : $this->_file->getClientOriginalName(),
            'size' => (isset($size) && (int)$type > 0) ? $size : $this->_file->getClientSize(),
            'type' => (isset($type)) ? $type : $this->_file->getClientMimeType()
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return chunk_split(substr($this->interpolation(), 0, $this->_length - $this->_chunk_length), $this->_chunk_length, "/");
    }

    /**
     * @param null $name
     *
     * @return string
     */
    public function getName($name = null)
    {
        $name = (isset($name)) ? $name : $this->_file->getClientOriginalName();
        return $this->interpolation() . "." . pathinfo($name, PATHINFO_EXTENSION);
    }

    /**
     * @return mixed
     */
    public function saveModel()
    {
        $file = new File($this->setAttributes($this->_file));
        $file->saveOrFail();
        return $file->getKey();
    }

    /**
     * @return mixed
     */
    public function saveFile()
    {
        return $this->_file->move(storage_path(config('sfs.storage_path') . $this->getPath()), $this->getName());
    }

    /**
     * @param $id
     *
     * @return string
     * @throws \Exception
     */
    public function getUrl($id)
    {
        if ((int)$id > 0) {
            $file = File::findOrFail($id);
            $this->setID($file->getKey());
            return storage_path(config('sfs.storage_path') . $this->getPath()) . $this->getName($file->name);
        }
        throw new \Exception('Bad ID param');
    }

    /**
     * @param UploadedFile $file
     *
     * @return null
     * @throws \Exception
     */
    public function save(UploadedFile $file)
    {
        $this->setFile($file);
        $this->setID($this->saveModel());
        if ($this->saveFile())
            return $this->_id;
        throw new \Exception('Something went wrong when file had been saving');
    }

    /**
     * @param              $id
     * @param UploadedFile $file
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, UploadedFile $file)
    {
        if ((int)$id > 0) {
            // set file variable
            $this->setFile($file);
            // receive instance
            $file = File::findOrFail($id);
            // update model
            return $file->update($this->setAttributes());
        }
        throw new \Exception('Bad ID param');
    }
}