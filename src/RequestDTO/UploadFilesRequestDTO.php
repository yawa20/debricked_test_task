<?php

declare(strict_types=1);

namespace App\RequestDTO;

use App\RequestResolver\RequestDTOInterface;
use App\ValueObject\Trigger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UploadFilesRequestDTO implements RequestDTOInterface
{

    public function __construct(
        #[Assert\NotNull]
        #[Assert\Type('string')]
        public readonly mixed $repositoryName,
        #[Assert\NotNull]
        #[Assert\Type('string')]
        public readonly mixed $commitName,
        #[Assert\Email()]
        public readonly mixed $email,
        #[Assert\Type('string')]
        public readonly mixed $slack,
        #[Assert\NotNull(message: "Files are required to upload")]
        #[Assert\Count(min: 1)]
        // I know, this attribute looks very strange, but it is correct attribute, and it works fine
        #[Assert\All([
            new Assert\NotBlank,
            new Assert\File,
        ])]
        /** @var UploadedFile[] */
        public readonly mixed $files,

        #[Assert\All([
            new Assert\Collection(
                fields: [
                    'type' => [
                        new Assert\NotBlank,
                        new Assert\Choice(
                            choices: [Trigger::TYPE__VUL_COUNT, Trigger::TYPE__UPL_FAILED],
                            message: "allowed only 'vul_count', 'upl_failed' types"
                        ),
                    ],
                    'notificationType' => [
                        new Assert\NotBlank,
                        new Assert\Choice(
                            choices: [Trigger::NOTIFICATION_TYPE_EMAIL, Trigger::NOTIFICATION_TYPE_SLACK],
                            message: "allowed only 'email', 'slack' notifications",
                        ),
                    ],
                    'triggerValue' => [
                        new Assert\PositiveOrZero(),
                    ]
                ],
            ),
        ])]
        public readonly mixed $triggers,
    ) {
    }

    public static function fromRequest(Request $request): RequestDTOInterface
    {
        return new self(
            repositoryName: $request->get('repositoryName'),
            commitName: $request->get('commitName'),
            email: $request->get('email'),
            slack: $request->get('slack'),
            files: $request->files,
            triggers: $request->get('triggers')
        );
    }
}