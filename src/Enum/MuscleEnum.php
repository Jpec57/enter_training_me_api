<?php

namespace App\Enum;

class MuscleEnum
{
    const CHEST = "chest";
    const BACK = "back";
    const TRICEPS = "triceps";
    const BICEPS = "biceps";
    const SHOULDERS = "shoulders";
    const FOREARMS = "forearms";
    const QUADRICEPS = "quadriceps";
    const HAMSTRING = "hamstring";
    const CALF = "calf";

    public $availableMuscles = [
        MuscleEnum::CHEST,
        MuscleEnum::BACK,
        MuscleEnum::TRICEPS,
        MuscleEnum::BICEPS,
        MuscleEnum::SHOULDERS,
        MuscleEnum::FOREARMS,
        MuscleEnum::QUADRICEPS,
        MuscleEnum::HAMSTRING,
        MuscleEnum::CALF,
    ];
}
