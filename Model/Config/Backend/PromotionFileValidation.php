<?php

declare(strict_types=1);

namespace Pointeger\PromotionPopup\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

class PromotionFileValidation extends File
{
    const UPLOAD_DIR = 'promotion/post'; // Folder save image

    /**
     * @return File
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $file = $this->getFileData();
        $deleteFlag = is_array($value) && !empty($value['delete']);

        if (empty($file)) {
            if ($this->getOldValue() && $deleteFlag) {
                $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
            }
            return parent::beforeSave();
        }

        $fileTmpName = $file['tmp_name'];

        if ($this->getOldValue() && ($fileTmpName || $deleteFlag)) {
            $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
        }
        return parent::beforeSave();
    }

    /**
     * Return path to directory for upload file
     *
     * @return string
     * @throw \Magento\Framework\Exception\LocalizedException
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    /**
     * Makes a decision about whether to add info about the scope.
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png', 'pdf'];
    }
}
