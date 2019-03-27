<?php

namespace Tomodomo\Packages\Installer\Installers;

interface InstallerInterface
{
    /**
     * Authentication credentials for the package.
     *
     * @var array
     */
    private $auth;

    /**
     * Configuration details from the package repository.
     *
     * @var array
     */
    private $config;

    /**
     * Get the download URL for the plugin.
     *
     * @return string
     */
    public function getDownloadUrl() : string;
}
