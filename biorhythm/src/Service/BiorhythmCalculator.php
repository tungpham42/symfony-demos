<?php

namespace App\Service;

class BiorhythmCalculator
{
    public function calculateBiorhythm(\DateTime $birthdate, \DateTime $targetDate): array
    {
        $daysLived = $birthdate->diff($targetDate)->days;

        return [
            'physical' => sin(2 * pi() * $daysLived / 23),
            'emotional' => sin(2 * pi() * $daysLived / 28),
            'intellectual' => sin(2 * pi() * $daysLived / 33),
        ];
    }
}