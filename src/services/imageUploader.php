<?php 
namespace App\services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class imageUploader
{
    private $targetDirectory;
    public function __construct(
        $targetDirectory,
        private SluggerInterface $slugger,
    ) {
        $this->targetDirectory = $targetDirectory;

    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $url=$this->targetDirectory.'\\'.$fileName;
        return $url;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}