<?php

declare(strict_types=1);

namespace FrankProjects\UltimateWarfare\Controller\Game;

use FrankProjects\UltimateWarfare\Repository\PlayerRepository;
use FrankProjects\UltimateWarfare\Repository\WorldRegionRepository;
use FrankProjects\UltimateWarfare\Repository\WorldSectorRepository;
use Symfony\Component\HttpFoundation\Response;

final class SectorController extends BaseGameController
{
    private WorldSectorRepository $worldSectorRepository;
    private WorldRegionRepository $worldRegionRepository;
    private PlayerRepository $playerRepository;

    public function __construct(
        WorldSectorRepository $worldSectorRepository,
        WorldRegionRepository $worldRegionRepository,
        PlayerRepository $playerRepository
    ) {
        $this->worldSectorRepository = $worldSectorRepository;
        $this->worldRegionRepository = $worldRegionRepository;
        $this->playerRepository = $playerRepository;
    }

    public function sector(int $sectorId): Response
    {
        $player = $this->getPlayer();
        $sector = $this->worldSectorRepository->findByIdAndWorld($sectorId, $player->getWorld());

        if (!$sector) {
            return $this->render('game/sectorNotFound.html.twig', [
                'player' => $player,
            ]);
        }

        $regions = [];
        foreach ($sector->getWorldRegions() as $region) {
            $regions[$region->getY()][$region->getX()] = $region;
        }

        return $this->render('game/sector.html.twig', [
            'sector' => $sector,
            'regions' => $regions,
            'player' => $player,
            'mapSettings' => [
                'searchFound' => true,
                'searchFree' => false,
                'searchPlayerName' => false
            ]
        ]);
    }
}
