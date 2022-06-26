<?php

namespace App\Services;

use App\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageService extends AbstractController{

    public function moveImage($file, Vehicule $vehicule ){
        $dossier_upload = $this->getParameter("upload_directory");
        $photo = md5(uniqid()) . "." . $file->guessExtension(); // .jpg
        $file->move( $dossier_upload, $photo);
        $vehicule->setPhoto($photo);
    }
    public function deleteImage(Vehicule $vehicule){
        $dossier_upload = $this->getParameter("upload_directory");
        $photo = $vehicule->getPhoto();
        $oldPhoto = $dossier_upload . "/" . $photo; 
        if(file_exists($oldPhoto)){
            unlink($oldPhoto);
        }
    }
    public function updateImage($file, Vehicule $vehicule){

        $this->deleteImage($vehicule);
        $this->moveImage($file, $vehicule);
    }
}