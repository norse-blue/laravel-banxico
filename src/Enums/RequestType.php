<?php

namespace NorseBlue\Banxico\Enums;

enum RequestType: string
{
    case Data = 'data';
    case Metadata = 'metadata';
}
