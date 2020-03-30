<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CreateUserCommand.
 */
class CreateUserCommand extends Command
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * AmalivreCreateUserCommand constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $manager
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;

        parent::__construct();
    }

    /**
     * @var string
     */
    protected static $defaultName = 'dev-techzara:create:user';

    /**
     * configuration
     */
    protected function configure() : void
    {
        $this
            ->setDescription('Création SuperAdmin Dev Techzara.')
            ->setHelp('Ce command vous permet de créer un SuperAdmin');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $output->writeln(
            [
                'Création utilisateur superAdmin Dev Techzara',
                '========================================',
            ]
        );

        // Question list
        $username = new Question('Entrer le nom de l\'utilisateur (email) : ');
        $password = new Question('Mots de passe de l\'utilisateur: ');
        $confirmation = new Question('Voulez vous vraiment ajouter cet utilisateur ? [O/N]: ', 'O');
        // Ask question
        $username = $helper->ask($input, $output, $username);
        $password = $helper->ask($input, $output, $password);
        $confirmation = $helper->ask($input, $output, $confirmation);

        if ('O' === $confirmation || 'o' === $confirmation || 0 === $confirmation) {
            $user = new User();
            $user->setEmail($username);
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $user->setRoles(['ROLE_SUPER_ADMIN']);

            $this->manager->persist($user);
            $this->manager->flush();

            $io->success('Ajout '.$user->getUsername().' avec success');

            return;
        }

        if ('N' === $confirmation) {
            $io->warning('Annulation ajout utilisateur');

            return;
        }

        $io->error('Argument invalidés');
    }
}