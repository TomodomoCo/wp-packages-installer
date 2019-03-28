<?php

namespace Tomodomo\Packages\Installer\Installers;

interface InstallerInterface
{
    /**
     * Get the download URL for the plugin.
     *
     * @return string
     */
    public function getDownloadUrl() : string;
}
