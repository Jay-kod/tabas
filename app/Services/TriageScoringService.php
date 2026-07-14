<?php

namespace App\Services;

class TriageScoringService
{
    public function score(array $vitals): array
    {
        $score = 0;

        $score += $this->scoreRespiratoryRate((int) $vitals['resp_rate']);
        $score += $this->scoreSpo2((int) $vitals['spo2']);
        $score += $this->scoreSystolicBp((int) $vitals['systolic_bp']);
        $score += $this->scoreHeartRate((int) $vitals['heart_rate']);
        $score += $this->scoreConsciousness((string) $vitals['consciousness']);
        $score += $this->scoreTemperature((float) $vitals['temperature']);

        return [
            'score' => $score,
            'category' => $this->categoryForScore($score),
        ];
    }

    private function scoreRespiratoryRate(int $value): int
    {
        return match (true) {
            $value <= 8 || $value >= 30 => 3,
            $value <= 11 || $value >= 25 => 2,
            $value <= 20 => 0,
            default => 1,
        };
    }

    private function scoreSpo2(int $value): int
    {
        return match (true) {
            $value <= 90 => 3,
            $value <= 93 => 2,
            $value <= 95 => 1,
            default => 0,
        };
    }

    private function scoreSystolicBp(int $value): int
    {
        return match (true) {
            $value <= 90 || $value >= 220 => 3,
            $value <= 100 || $value >= 180 => 2,
            $value <= 110 => 1,
            default => 0,
        };
    }

    private function scoreHeartRate(int $value): int
    {
        return match (true) {
            $value <= 40 || $value >= 131 => 3,
            $value <= 50 || $value >= 111 => 2,
            $value <= 100 => 0,
            default => 1,
        };
    }

    private function scoreConsciousness(string $value): int
    {
        return match (strtoupper(trim($value))) {
            'A' => 0,
            'V' => 1,
            'P' => 2,
            'U' => 3,
            default => 1,
        };
    }

    private function scoreTemperature(float $value): int
    {
        return match (true) {
            $value <= 35.0 || $value >= 39.1 => 3,
            $value <= 36.0 || $value >= 38.6 => 2,
            $value <= 36.4 || $value >= 38.1 => 1,
            default => 0,
        };
    }

    private function categoryForScore(int $score): string
    {
        return match (true) {
            $score >= 9 => 'Critical',
            $score >= 6 => 'Urgent',
            $score >= 3 => 'Standard',
            default => 'Non-urgent',
        };
    }
}