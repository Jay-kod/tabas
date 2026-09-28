<?php

namespace Tests\Unit;

use App\Services\TriageScoringService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TriageScoringServiceTest extends TestCase
{
    public function test_it_scores_low_risk_vitals_as_non_urgent(): void
    {
        $service = new TriageScoringService();

        $result = $service->score([
            'resp_rate' => 16,
            'spo2' => 98,
            'systolic_bp' => 120,
            'heart_rate' => 80,
            'consciousness' => 'A',
            'temperature' => 36.8,
        ]);

        $this->assertSame(0, $result['score']);
        $this->assertSame('Non-urgent', $result['category']);
    }

    public function test_it_scores_high_risk_vitals_as_critical(): void
    {
        $service = new TriageScoringService();

        $result = $service->score([
            'resp_rate' => 32,
            'spo2' => 88,
            'systolic_bp' => 85,
            'heart_rate' => 140,
            'consciousness' => 'U',
            'temperature' => 39.5,
        ]);

        $this->assertGreaterThanOrEqual(9, $result['score']);
        $this->assertSame('Critical', $result['category']);
    }

    public function test_it_rejects_missing_required_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new TriageScoringService())->score([
            'resp_rate' => 18,
            'spo2' => 97,
            'systolic_bp' => 120,
            'heart_rate' => 80,
            'temperature' => 36.8,
        ]);
    }

    public function test_it_rejects_invalid_consciousness_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new TriageScoringService())->score([
            'resp_rate' => 18,
            'spo2' => 97,
            'systolic_bp' => 120,
            'heart_rate' => 80,
            'consciousness' => 'X',
            'temperature' => 36.8,
        ]);
    }
}