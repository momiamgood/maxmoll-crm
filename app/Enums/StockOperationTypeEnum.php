<?php

namespace App\Enums;

enum StockOperationTypeEnum: string
{
    case INCREMENT = 'increment';
    case DECREMENT = 'decrement';
}
