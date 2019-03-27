<?php

namespace Tomodomo\Packages\Installer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreFileDownloadEvent;

class Installer implements PluginInterface, EventSubscriberInterface
{

    protected $composer;
    protected $io;
    protected $downloadUrl;

    /**
     * Activate plugin.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    /**
     * Set subscribed events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::PRE_PACKAGE_INSTALL => 'getDownloadUrl',
            PackageEvents::PRE_PACKAGE_UPDATE  => 'getDownloadUrl',
            PluginEvents::PRE_FILE_DOWNLOAD    => 'onPreFileDownload',
        ];
    }

    /**
     * Get package from operation.
     *
     * @param OperationInterface $operation
     *
     * @return mixed
     */
    protected function getPackageFromOperation(OperationInterface $operation)
    {
        if ('update' === $operation->getJobType()) {
            return $operation->getTargetPackage();
        }

        return $operation->getPackage();
    }

    /**
     * Get download URL for our plugins.
     *
     * @param PackageEvent $event
     *
     * @return void
     */
    public function getDownloadUrl(PackageEvent $event) {
        $package     = $this->getPackageFromOperation($event->getOperation());
        $packageName = $package->getName();

        // @todo Implement a router here.

        $this->downloadUrl = $plugin->getDownloadUrl();

        return;
    }

    /**
     * Process our plugin downloads.
     *
     * @param PreFileDownloadEvent $event
     *
     * @return void
     */
    public function onPreFileDownload(PreFileDownloadEvent $event)
    {
        if (empty($this->downloadUrl)) {
            return;
        }

        // This is where the magic happens to pull in the remote file.
        $rfs = $event->getRemoteFilesystem();

        $customRfs = new RemoteFilesystem(
            $this->downloadUrl,
            $this->io,
            $this->composer->getConfig(),
            $rfs->getOptions(),
            $rfs->isTlsDisabled()
        );

        $event->setRemoteFilesystem( $customRfs );

        return;
    }
}
