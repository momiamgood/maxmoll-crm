<?php

namespace App\Enums;

enum HistoryChangeReasonsEnum: string
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const RESUME = 'resume';

    const CANCEL = 'cancel';
}
