<?php

namespace App\Controller;

use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class MediaController extends AbstractController
{
    public function __invoke(Request $request): Media
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $media = new Media();
        $media->setFile($uploadedFile);
        $media->setTitle($request->request->get('title'))
            ->setDescription($request->request->get('description'))
            ->setLicense($request->request->get('license'))
            ->setModifiedBy($request->request->get('modifiedBy'))
            ->setCreatedBy($request->request->get('createdBy'));

        // Note that the extra information about the uploaded file is added in the MediaPrePersistEventListener as first
        // there the file is on disk.
        return $media;
    }
}
