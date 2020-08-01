<?php

namespace Module\Application\FileSystem\Types\Strategy;

use Module\Application\FileSystem\File;
use Module\Application\FileSystem\Pdf;

class PdfStrategy implements StrategyInterface
{
    protected $file;

    private $validMimeTypes = [
        "application/pdf",
    ];

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getType()
    {
        return self::TYPE_PDF;
    }

    public function match(): bool
    {
        if (in_array($this->file->getMetadata()->getMimeType(), $this->validMimeTypes)) {
            return true;
        }

        return false;
    }

    public function preview($output = null): File
    {
        if (!$this->match()) {
            return $this->file;
        }

        if (!$output) {
            $output = $this->file->getTmpDir();
        }

        $pdf     = new Pdf($this->file->getPath());
        $preview = $pdf->saveImage($output);
        
        return  $this->file->setPreview($preview ? $preview : null);
    }
}
