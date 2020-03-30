<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Command;

use App\Entity\NumeroUtils;
use App\Entity\Station;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ImportNumeroUtilsCommand.
 */
class ImportNumeroUtilsCommand extends Command
{
    protected static $defaultName = 'app:import:numero';

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
        $this->setDescription('Importations des numeros utiles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
                'Importations des numeros utiles services',
                '========================================',
            ]
        );

        $filename = ['phone.json'];

        foreach ($filename as $item) {
            $data = json_decode(file_get_contents($this->path.$item), true);

            if (isset($data['tbody']['tr'])) {
                foreach ($data['tbody']['tr'] as $key => $datum) {
                    $numero = new NumeroUtils();
                    if ($key > 0) {
                        $numero->setNom($datum['td'][0] ?? '');
                        $numero->setType($datum['td'][1] ?? '');
                        $numero->setNumero($datum['td'][2] ?? '');

                        $this->entityManager->persist($numero);

                        $output->writeln('importation '.$numero->getNom().' éffectué');
                    }
                }

                $this->entityManager->flush();
                $output->writeln('Importation terminé avec success');
            }
        }
    }
}