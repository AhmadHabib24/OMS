<?php

namespace App\Services;

class AttendancePrivacyService
{
    public function calculateDistanceInMeters($lat1, $lon1, $lat2, $lon2): ?float
    {
        if (
            is_null($lat1) || is_null($lon1) ||
            is_null($lat2) || is_null($lon2)
        ) {
            return null;
        }

        $earthRadius = 6371000;

        $latFrom = deg2rad((float) $lat1);
        $lonFrom = deg2rad((float) $lon1);
        $latTo = deg2rad((float) $lat2);
        $lonTo = deg2rad((float) $lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($earthRadius * $angle, 2);
    }

    public function isOutsideAllowedRadius(?float $distance, int $allowedRadius): bool
    {
        if (is_null($distance)) {
            return true;
        }

        return $distance > $allowedRadius;
    }
}