<?php

namespace NorseBlue\LaravelBanxico\Enums;

enum RequestType: string
{
    case Data = 'data';
    case Metadata = 'metadata';
}
