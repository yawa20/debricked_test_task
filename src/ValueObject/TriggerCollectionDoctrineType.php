<?php

declare(strict_types=1);

namespace App\ValueObject;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use JsonException;

class TriggerCollectionDoctrineType extends JsonType
{

    public function getName()
    {
        return 'triggers_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        $result = [];
        try {
            $data =  json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            foreach ($data as $value) {
                $result[] = new Trigger(
                    type: $data['type'],
                    notificationType: $data['notificationType'],
                    triggerValue: $data['triggerValue'],
                );
            }
        } catch (JsonException $e) {
            throw ConversionException::conversionFailed($value, $this->getName(), $e);
        }

        return $result;
    }
}