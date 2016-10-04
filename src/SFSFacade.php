<?php

namespace SimpleFileStorage;

use \Illuminate\Http\UploadedFile;

abstract class SFSFacade
{
    private $_length;
    private $_chunk_length;
    private $_id = null;
    private $_file = null;

    protected function __construct()
    {
        $this->_length = config('sfs.length');
        $this->_chunk_length = config('sfs.chunk_length');
    }

    /**
     * @param $id
     */
    protected function setID($id)
    {
        $this->_id = $id;
    }

    /**
     * @return bool|null
     */
    protected function getID()
    {
        return $this->_id;
    }
    
    /**
     * @param UploadedFile $file
     *
     * @throws \Exception
     */
    protected function setFile(UploadedFile $file)
    {
        $this->_file = $file;
    }

    /**
     * @return null
     */
    protected function getFile()
    {
        return $this->_file;
    }
    
    /**
     * @return string
     * @throws \Exception
     */
    protected function interpolation()
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
    protected function setAttributes($name = null, $size = null, $type = null)
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
    protected function getPath()
    {
        return chunk_split(substr($this->interpolation(), 0, $this->_length - $this->_chunk_length), $this->_chunk_length, "/");
    }

    /**
     * @param null $name
     *
     * @return string
     */
    protected function getName($name = null)
    {
        return $this->interpolation() . "." . pathinfo(
            (isset($name)) ?
                $name :
                $this->_file->getClientOriginalName(),
            PATHINFO_EXTENSION);
    }

    /**
     * @return mixed
     */
    protected function saveModel()
    {
        $file = new File($this->setAttributes($this->_file));
        $file->saveOrFail();
        return $file->getKey();
    }
    
    /**
     * @return mixed
     */
    abstract protected function saveFile();
    
    /**
     * @param $id
     *
     * @return mixed
     */
    abstract public function getUrl($id);

    /**
     * @param UploadedFile $file
     *
     * @return mixed
     */
    abstract public function save(UploadedFile $file);

    /**
     * @param              $id
     * @param UploadedFile $file
     *
     * @return mixed
     */
    abstract public function update($id, UploadedFile $file);

    /**
     * @param $id
     *
     * @return mixed
     */
    abstract public function destroy($id);
}