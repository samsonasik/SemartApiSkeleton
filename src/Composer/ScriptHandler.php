<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Composer;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use KejawenLab\ApiSkeleton\Util\Encryptor;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ScriptHandler
{
    public static function preInstall(Event $event)
    {
        $composer = $event->getComposer();
        $rootPath = (string) realpath(sprintf('%s/../', $composer->getConfig()->get('vendor-dir')));
        $io = $event->getIO();

        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>Checking Environment Variable File</>');
        $io->write('<comment>===========================================================</comment>');

        $envPath = sprintf('%s/.env', $rootPath);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($envPath)) {
            $io->write('<info>Creating new environment variable file</info>');

            $template = (string) file_get_contents(sprintf('%s/.env.template', $rootPath));

            static::createEnvironment($io, $envPath, $template);
        } else {
            $io->write('<info>Environment variable file is already exist</info>');
        }
    }

    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
        $io = $event->getIO();
        $rootPath = (string) realpath(sprintf('%s/../', $composer->getConfig()->get('vendor-dir')));
        $lock = sprintf('%s/.semart', $rootPath);
        if (file_exists($lock) && 1 === (int) file_get_contents($lock)) {
            return 0;
        }

        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>KejawenLab Core Installation is finished</>');
        $io->write('<comment>===========================================================</comment>');
        $io->write('<comment>Please create private dan public key use <info>openssl library</info></comment>');
        $io->write('<comment>Visit <info>https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#generate-the-ssh-keys</info> for more information</comment>');

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($lock, 1);
    }

    private static function createEnvironment(IOInterface $io, string $envPath, string $template): void
    {
        $io->write('<comment>===========================================================</comment>');
        $io->write('<options=bold>Environment Setup</>');
        $io->write('<comment>===========================================================</comment>');

        $environment = $io->ask('Please enter your application environment [default: <info>dev</info>]: ', 'dev');
        $redisUlr = $io->ask('Please enter your redis url [default: <info>localhost</info>]: ', 'localhost');
        $dbDriver = $io->ask('Please enter your database driver [default: <info>pdo_mysql</info>]: ', 'pdo_mysql');
        $dbVersion = $io->ask('Please enter your database version [default: <info>5.7</info>]: ', '5.7');
        $dbCharset = $io->ask('Please enter your database charset [default: <info>utf8mb4</info>]: ', 'utf8mb4');
        $dbUser = $io->ask('Please enter your database user [default: <info>root</info>]: ', 'root');
        $dbPassword = $io->ask('Please enter your database password [default: <info>null</info>]: ', '');
        $dbName = $io->ask('Please enter your database name [default: <info>kejawenlab_core</info>]: ', 'kejawenlab_core');
        $dbHost = $io->ask('Please enter your database host [default: <info>localhost</info>]: ', 'localhost');
        $dbPort = $io->ask('Please enter your database port [default: <info>3306</info>]: ', '3306');
        $appTitle = $io->ask('Please enter your application title [default: <info>KejawenLab Core</info>]: ', 'KejawenLab Core');
        $appDescription = $io->ask('Please enter your application description [default: <info>KejawenLab Core Application</info>]: ', 'KejawenLab Core Application');
        $appVersion = $io->ask('Please enter your application version [default: <info>1@dev</info>]: ', '1@dev');
        $passPhrase = $io->ask('Please enter your PKI pass phrase [default: <info>null</info>]: ', '');

        $search = [
            '{{ENV}}',
            '{{SECRET}}',
            '{{REDIS_URL}}',
            '{{DB_DRIVER}}',
            '{{DB_VERSION}}',
            '{{DB_CHARSET}}',
            '{{DB_USER}}',
            '{{DB_PASSWORD}}',
            '{{DB_NAME}}',
            '{{DB_HOST}}',
            '{{DB_PORT}}',
            '{{APP_TITLE}}',
            '{{APP_DESCRIPTION}}',
            '{{APP_VERSION}}',
            '{{JWT_PASSPHRASE}}',
        ];

        $secret = Encryptor::encrypt(date('YmdHis'), sha1(date('YmdHis')));
        $replace = [$environment, $secret, $redisUlr, $dbDriver, $dbVersion, $dbCharset, $dbUser, Encryptor::encrypt((string) $dbPassword, $secret), $dbName, $dbHost, $dbPort, $appTitle, $appDescription, $appVersion, $passPhrase];

        $envString = str_replace($search, $replace, $template);

        $io->write('<options=bold>Dumping Environment Variables</>');

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($envPath, $envString);
    }
}
