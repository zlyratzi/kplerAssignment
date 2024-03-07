<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InvoicesRepository;
use App\Service\RevenueCsvGeneratorService;

class ReportController extends AbstractController
{
    private $revenueCsvGeneratorService;

    public function __construct(RevenueCsvGeneratorService $revenueCsvGeneratorService)
    {
        $this->revenueCsvGeneratorService = $revenueCsvGeneratorService;
    }
    /**
     * @Route("/reports/monthly-revenue", name="monthly_revenue_report")
     */
    public function monthlyRevenueReport(InvoicesRepository $invoicesRepository): Response
    {
        $monthlyRevenueData = $invoicesRepository->getMonthlyRevenueData();

        // format report data
        $reportData = [];
        foreach ($monthlyRevenueData as $year => $monthlyrevenue ) {
            foreach ($monthlyrevenue as $month => $revenue)
            $reportData[] = [
                'year' => $year,
                'month' => $month,
                'revenue' => $revenue,
            ];
        }
        return $this->render('reports/monthly_revenue.html.twig', [
            'reportData' => $reportData,
        ]);
    }

    /**
     * @Route("/reports/monthly-revenue/download-csv", name="monthly_revenue_csv_download")
     */
    public function downloadMonthlyRevenueCsv(InvoicesRepository $invoicesRepository): Response
    {
        $monthlyRevenueData = $invoicesRepository->getMonthlyRevenueData();
        $csvContent = $this->revenueCsvGeneratorService->generateCsv($monthlyRevenueData);

        // Prepare the response
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="monthly_revenue_report.csv"');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Expires', '0');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Content-Length', strlen($csvContent));
        return $response;
    }
}