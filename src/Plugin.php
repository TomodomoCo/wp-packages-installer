<?php

namespace Tomodomo\Packages\Installer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\OperationInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface as ComposerPackageInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PreFileDownloadEvent;
use Stringy\Stringy as S;
use Tomodomo\Packages\Installer\Framework\Package;
use Tomodomo\Packages\Installer\Framework\RemoteFilesystem;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * The virtual Composer package's namespace.
     *
     * @var string
     */
    const NAMESPACE = 'wp-packages';

    /**
     * The Composer instance.
     *
     * @var Composer
     */
    protected $composer;

    /**
     * The IO interface.
     *
     * @var IOInterface
     */
    protected $io;

    /**
     * The modified download URL.
     *
     * @var string
     */
    protected $downloadUrl;

    /**
     * Activate the plugin.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
        $this->auth     = $this->composer->getConfig()->get('wp-packages') ?? [];

        return;
    }

    /**
     * Subscribe to certain events.
     *
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            PackageEvents::PRE_PACKAGE_INSTALL => 'getDownloadUrl',
            PackageEvents::PRE_PACKAGE_UPDATE  => 'getDownloadUrl',
            PluginEvents::PRE_FILE_DOWNLOAD    => 'onPreFileDownload',
        ];
    }

    /**
     * Get the package that is being installed or updated.
     *
     * @param OperationInterface $operation
     *
     * @return ComposerPackageInterface
     */
    protected function getPackageFromOperation(OperationInterface $operation) : ComposerPackageInterface
    {
        if ($operation->getJobType() === 'update') {
            return $operation->getTargetPackage();
        }

        return $operation->getPackage();
    }

    /**
     * Get the download URL for the package.
     *
     * @param PackageEvent $event
     *
     * @return void
     */
    public function getDownloadUrl(PackageEvent $event)
    {
        // Get the package and package name
        $composerPackage     = $this->getPackageFromOperation($event->getOperation());
        $composerPackageName = $composerPackage->getName();

        // Exit early if we should not act on the package.
        if (!S::create($composerPackageName)->startsWith($this::NAMESPACE, false)) {
            return;
        }

        // Set up our representation of the package
        $package       = new Package($composerPackage);
        $package->auth = $this->auth[$composerPackageName] ?? [];

        // Get the download URL for the package.
        $this->downloadUrl = $package->getDownloadUrl();

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

        // Get the existing remote filesystem representation, so we can
        // copy some of its properties to our replacement
        $rfs = $event->getRemoteFilesystem();

        // Build a replacement that uses our new download URL
        $customRfs = new RemoteFilesystem(
            $this->downloadUrl,
            $this->io,
            $this->composer->getConfig(),
            $rfs->getOptions(),
            $rfs->isTlsDisabled()
        );

        // Override the previous remote filesystem with our new version
        $event->setRemoteFilesystem($customRfs);

        return;
    }
}
