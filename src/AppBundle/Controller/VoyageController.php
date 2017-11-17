<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Voyage;
use AppBundle\Service\Stats\VoyageStats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class VoyageController extends Controller
{

    public function statisticsAction(Voyage $voyage)
    {
        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');
        $stages = $voyage->getStages();
        $stats = $voyageStats->calculateAllStats($voyage, $stages);

        return new JsonResponse($stats);
    }
}
