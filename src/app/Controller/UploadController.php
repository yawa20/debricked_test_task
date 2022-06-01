<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UploadEntity;
use App\Messages\UploadProcessMessage;
use App\Repository\UploadEntityRepository;
use App\RequestDTO\UploadFilesRequestDTO;
use App\Utils\ClientFileUploader;
use App\ValueObject\Step;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UploadController
{
    public function __construct(
        private ClientFileUploader $fileUploader,
        private UploadEntityRepository $repository,
        private MessageBusInterface $messageBus
    )
    {}

    #[Route('/api/upload', name: 'api_upload', methods: ['POST'])]
    public function upload(UploadFilesRequestDTO $DTO): JsonResponse
    {
        $uploadEntities = $this->generateMapEntities($DTO);
        $count = 0;
        foreach ($uploadEntities as $entity) {
            $count++;
            $this->messageBus->dispatch(new UploadProcessMessage($entity->getId(), Step::START_UPLOAD));
        }

        return new JsonResponse(['result'=>'ok', 'files_uploaded'=>$count]);
    }

    /**
     * @param UploadFilesRequestDTO $DTO
     *
     * @return UploadEntity[]
     */
    private function generateMapEntities(UploadFilesRequestDTO $DTO): iterable
    {
        foreach ($DTO->files as $file)
        {
            $uploadEntity = UploadEntity::fromRequestUpload(
                filename: $this->fileUploader->upload(
                    file: $file,
                    prefix: $DTO->repositoryName.'_'.$DTO->commitName.'_',
                ),
                DTO: $DTO,
            );
            $this->repository->add($uploadEntity, true);
            yield $uploadEntity;
        }
    }
}