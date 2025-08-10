<?php

namespace App\Enums;

enum AnimeRating: string
{
    case R_17 = 'R - 17+ (violence & profanity)';
    case PG_13 = 'PG-13 - Teens 13 or older';
    case PG = 'PG - Children';
    case R_PLUS = 'R+ - Mild Nudity';
    case G = 'G - All Ages';
    case RX = 'Rx - Hentai';
}