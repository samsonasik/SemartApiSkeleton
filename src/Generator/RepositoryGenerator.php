<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Generator;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class RepositoryGenerator extends AbstractGenerator
{
    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        $shortName = $class->getShortName();
        $repositoryFile = sprintf('%s/src/Repository/%sRepository.php', $this->kernel->getProjectDir(), $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\Repository\%sRepository"</info></comment>', $shortName));
        if (!$this->fileSystem->exists($repositoryFile)) {
            $repository = $this->twig->render('generator/repository.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($repositoryFile, $repository);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $repositoryFile));
        }

        $repositoryModelFile = sprintf('%s/src/%s/Model/%sRepositoryInterface.php', $this->kernel->getProjectDir(), $shortName, $shortName);
        $output->writeln(sprintf('<comment>Generating class <info>"KejawenLab\ApiSkeleton\%s\Model\%sRepositoryInterface"</info></comment>', $shortName, $shortName));
        if (!$this->fileSystem->exists($repositoryModelFile)) {
            $repositoryModel = $this->twig->render('generator/repository_model.php.twig', ['entity' => $shortName]);
            $this->fileSystem->dumpFile($repositoryModelFile, $repositoryModel);
        } else {
            $output->write(sprintf('<warning>File "%s" is exists. Skipped</warning>', $repositoryModelFile));
        }
    }
}
