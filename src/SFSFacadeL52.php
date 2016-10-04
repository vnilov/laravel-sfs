<?php

namespace SimpleFileStorage;

use SimpleFileStorage\Eloquent\File;
use \Illuminate\Http\UploadedFile;

class SFSFacadeL52 extends SFSFacade
{
    /**
     * @return mixed
     */
    protected function saveFile()
    {
        return $this->getFile()->move(storage_path(config('sfs.storage_path') . $this->getPath()), $this->getName());
    }
    
    /**
     * @param $id
     *
     * @return string
     * @throws \Exception
     */
    public function getUrl($id)
    {
        $file = File::findOrFail($id);
        $this->setID($file->getKey());
        return storage_path(config('sfs.storage_path') . $this->getPath()) . $this->getName($file->name);
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
            return $this->getID();
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
        // set file variable
        $this->setFile($file);
        // receive instance
        $file = File::findOrFail($id);
        // update model
        return $file->update($this->setAttributes());
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        return File::destroy($id);
    }
}