<?php

namespace App\Services;

use InvalidArgumentException;

class TriageScoringService
{
    public function score(array $vitals): array
    {
        $required = ['resp_rate', 'spo2', 'systolic_bp', 'heart_rate', 'consciousness', 'temperature'];

        foreach ($required as $field) {
            if (! array_key_exists($field, $vitals) || $vitals[$field] === null || trim((string) $vitals[$field]) === '') {
                throw new InvalidArgumentException(sprintf('Missing required triage value: %s.', $field));
            }
        }

        $respRate = $this->requireInteger($vitals['resp_rate'], 'resp_rate', 0, 100);
        $spo2 = $this->requireInteger($vitals['spo2'], 'spo2', 0, 100);
        $systolicBp = $this->requireInteger($vitals['systolic_bp'], 'systolic_bp', 0, 300);
        $heartRate = $this->requireInteger($vitals['heart_rate'], 'heart_rate', 0, 250);
        $consciousness = $this->requireConsciousness($vitals['consciousness']);
        $temperature = $this->requireFloat($vitals['temperature'], 'temperature', 30, 45);

        $score = 0;
        $score += $this->scoreRespiratoryRate($respRate);
        $score += $this->scoreSpo2($spo2);
        $score += $this->scoreSystolicBp($systolicBp);
        $score += $this->scoreHeartRate($heartRate);
        $score += $this->scoreConsciousness($consciousness);
        $score += $this->scoreTemperature($temperature);

        return [
            'score' => $score,
            'category' => $this->categoryForScore($score),
        ];
    }

    private function requireInteger(mixed $value, string $field, int $min, int $max): int
    {
        if (! is_numeric($value) || (float) $value != (int) $value) {
            throw new InvalidArgumentException(sprintf('Triage value %s must be an integer.', $field));
        }

        $intValue = (int) $value;

        if ($intValue < $min || $intValue > $max) {
            throw new InvalidArgumentException(sprintf('Triage value %s is outside the valid range.', $field));
        }

        return $intValue;
    }

    private function requireFloat(mixed $value, string $field, float $min, float $max): float
    {
        if (! is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('Triage value %s must be numeric.', $field));
        }

        $floatValue = (float) $value;

        if ($floatValue < $min || $floatValue > $max) {
            throw new InvalidArgumentException(sprintf('Triage value %s is outside the valid range.', $field));
        }

        return $floatValue;
    }

    private function requireConsciousness(mixed $value): string
    {
        $normalised = strtoupper(trim((string) $value));

        if (! in_array($normalised, ['A', 'V', 'P', 'U'], true)) {
            throw new InvalidArgumentException('Consciousness must be one of A, V, P, or U.');
        }

        return $normalised;
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
        return match ($value) {
            'A' => 0,
            'V' => 1,
            'P' => 2,
            'U' => 3,
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