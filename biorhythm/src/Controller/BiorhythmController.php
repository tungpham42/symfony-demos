<?php

namespace App\Controller;

use App\Service\BiorhythmCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BiorhythmController extends AbstractController
{
    private $calculator;

    public function __construct(BiorhythmCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    #[Route('/', name: 'biorhythm')]
    public function index(Request $request): Response
    {
        $data = [];
        $dates = [];
        $birthdate = null;

        if ($request->isMethod('POST')) {
            $birthdate = new \DateTime($request->request->get('birthdate'));
            $startDate = new \DateTime('-14 days'); // 14 days before today
            $endDate = new \DateTime('+14 days');   // 14 days after today
            $interval = new \DateInterval('P1D');
            $dateRange = new \DatePeriod($startDate, $interval, $endDate);

            foreach ($dateRange as $date) {
                $dates[] = $date->format('Y-m-d');
                $physical = $this->calculator->calculateBiorhythm($birthdate, $date)['physical'];
                $emotional = $this->calculator->calculateBiorhythm($birthdate, $date)['emotional'];
                $intellectual = $this->calculator->calculateBiorhythm($birthdate, $date)['intellectual'];

                // Convert from -1 to 1 range to 0 to 100 range
                $data['physical'][] = round(($physical + 1) * 50);
                $data['emotional'][] = round(($emotional + 1) * 50);
                $data['intellectual'][] = round(($intellectual + 1) * 50);
            }
        }

        return $this->render('biorhythm/index.html.twig', [
            'data' => $data,
            'dates' => $dates,
            'birthdate' => $birthdate ? $birthdate->format('Y-m-d') : null,
        ]);
    }
}
