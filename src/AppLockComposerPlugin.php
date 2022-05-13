<?php

namespace ebitkov\AppLockComposerPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class AppLockComposerPlugin implements PluginInterface, EventSubscriberInterface
{
    private IOInterface $io;
    private Composer $composer;

    private ?string $rootDir;


    public static function getSubscribedEvents(): array
    {
        return [
            'pre-install-cmd' => 'lockApp',
            'pre-update-cmd' => 'lockApp',
            'post-install-cmd' => 'unlockApp',
            'post-update-cmd' => 'unlockApp'
        ];
    }


    public function lockApp()
    {
        file_put_contents($this->rootDir . '/.app_lock', 'APP_LOCKED=true');
        $this->io->info('app locked for update');
    }

    public function unlockApp()
    {
        file_put_contents($this->rootDir . '/.app_lock', 'APP_LOCKED=false');
        $this->io->info('app unlocked');
    }


    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;
        $this->composer = $composer;

        $this->rootDir = dirname($composer->getConfig()->get('vendor-dir'));
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}