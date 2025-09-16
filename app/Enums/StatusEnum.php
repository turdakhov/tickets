<?php

namespace App\Enums;

enum StatusEnum
{
    case Incomplete;
    case New;
    case Pending;
    case Resolved;
    case Closed;
}
