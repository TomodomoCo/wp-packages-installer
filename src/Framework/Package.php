<?php

namespace Tomodomo\Packages\Installer\Framework;

use Composer\Package\PackageInterface as ComposerPackageInterface;
use Stringy\Stringy as S;
use Tomodomo\Packages\Installer\Installers\InstallerInterface;

class Package
{
    /**
     * Instantiate the package
     *
     * @return void
     */
    public function __construct(ComposerPackageInterface $package)
    {
        $this->package = $package;

        return;
    }

    /**
     * Get the installer config from the repository package.
     *
     * @return array
     */
    private function getInstallerConfig() : array
    {
        // Get the package "extra" details and pull out the config.
        $extra  = $this->package->getExtra();
        $config = $extra['wp-packages-installer-config'] ?? [];

        return $config;
    }

    /**
     * Get a class path for a given installation method.
     *
     * @return string
     */
    private function getClassPathForInstaller(string $method) : string
    {
        if (empty($method)) {
            throw new \Exception('No installation method was provided.');
        }

        // Transform the method into a fully qualified class path
        return S::create($method)
            ->upperCamelize()
            ->prepend('\\Framework\\')
            ->prepend(__NAMESPACE__);
    }

    /**
     * Get the installer class for the package.
     *
     * @return InstallerInterface
     */
    public function getInstaller() : InstallerInterface
    {
        $config = $this->getInstallerConfig();

        // Get the "method" via the extra config
        $classPath = $this->getClassPathForInstaller($config['method'] ?? '');

        // You must always have an installation method!
        if (!class_exists($classPath)) {
            throw new \Exception("The method could not be mapped to an Installer class.");
        }

        // Set up the method class
        $installer = new $classPath($this->auth, $this->config);

        return $installer;
    }

    /**
     * Get the download URL for the package.
     *
     * @return string
     */
    public function getDownloadUrl() : string
    {
        return $this->getInstaller()->getDownloadUrl();
    }
}
