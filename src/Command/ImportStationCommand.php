<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Command;

use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ImportStationCommand.
 */
class ImportStationCommand extends Command
{
    protected static $defaultName = 'app:import:station';

    private $path;

    private $entityManager;

    /**
     * ImportStationCommand constructor.
     *
     * @param ParameterBagInterface  $parameterBag
     * @param EntityManagerInterface $entityManager
     * @param string|null            $name
     */
    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->path = $parameterBag->get('path_json');
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Importations des stations services.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
                'Importations des stations services',
                '========================================',
            ]
        );

        $filename = ['Jovenna.json', 'shell.json', 'total.json', 'galana.json'];

        foreach ($filename as $item) {
            $data = json_decode(file_get_contents($this->path.$item), true);

            if (isset($data['tbody']['tr'])) {
                foreach ($data['tbody']['tr'] as $key => $datum) {
                    $station = new Station();
                    if ($key > 0) {
                        $station->setDistributeur($datum['td'][0] ?? '');
                        $station->setProvince($datum['td'][1] ?? '');
                        $station->setRegion($datum['td'][2] ?? '');
                        $station->setDistrict($datum['td'][3] ?? '');
                        $station->setCommune($datum['td'][4] ?? '');
                        $station->setLocalites($datum['td'][5] ?? '');
                        $station->setNomStation($datum['td'][6] ?? '');
                        $station->setDateAdd(new \DateTime('now'));

                        $this->entityManager->persist($station);

                        $output->writeln('importation '.$station->getNomStation().' éffectué');
                    }
                }

                $this->entityManager->flush();
                $output->writeln('Importation terminé avec success');
            }
        }
    }
}