<?php

namespace App\Enums;

enum AnimeStatus: string
{
    case FINISHED_AIRING = "Finished Airing";
    case CURRENTLY_AIRING = "Currently Airing";
    case NOT_YET_AIRED = "Not yet aired";
}
