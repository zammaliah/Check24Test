<?php
// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CheckCommantCommand extends Command
{
    protected static $defaultName = 'app:check-commant';

    public function __construct(CommentRepository $repository, EntityManagerInterface $em){
        $this->repository = $repository;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('Check')
        ->setDescription('Check comment list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->blackListCheck();
        return Command::SUCCESS;
    }

    private function blackListCheck() :void
    {
        $comments = $this->repository->findAll();
        foreach ($comments as $comment) {
            $condition = $this->validate($comment);
            if ($condition)
            {
                $comment->setValidate(true);
                $this->em->persist($comment);
            }
        }
        $this->em->flush();
    }

    private function validate(Comment $comment) :bool
    {
        $text = $comment->getText();
        $blacklists = Comment::BLACKLIST;
        foreach ($blacklists as $blacklist) {
            if ( str_contains($text, $blacklist) ){
                return false;
            }
        }
        return true;
    }
}