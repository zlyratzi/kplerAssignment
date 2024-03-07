<?php

namespace App\Service;

class RevenueCsvGeneratorService
{

    public function generateCsv(array $data): string
    {
        $reportData = [];
        foreach ($data as $year => $monthlyRevenue) {
            foreach ($monthlyRevenue as $month => $revenue) {
                $reportData[] = [
                    'year' => $year,
                    'month' => $month,
                    'revenue' => $revenue,
                ];
            }
        }
        $csvContent = "Year,Month,Revenue\n";
        foreach ($reportData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }
        return $csvContent;
    }
}
