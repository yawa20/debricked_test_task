<?php

declare(strict_types=1);

namespace App\ValueObject;

enum Step: string
{
    case START_UPLOAD = 'start_upload';
    case QUEUE_ITEM = 'queue_item';
    case GET_STATUS = 'get_status';
}