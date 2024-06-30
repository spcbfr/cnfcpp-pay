<?php

namespace App;

enum CourseType: string
{
    case CC = 'CC';
    case CAP = 'CAP';
    case BTP = 'BTP';
    case BTS = 'BTS';
    case License = 'Licence';
    case Master = 'Master';
    case FMT = 'Formation Modulaire Technique';
    case FMG = 'Formation Modulaire Gestion';

}
