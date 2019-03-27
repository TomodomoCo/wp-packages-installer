<?php

namespace Tomodomo\Packages\Installer\Installers;

class Get extends AbstractInstaller implements InstallerInterface
{
    /**
     * Get the download URL.
     *
     * @return string
     */
    public function getDownloadUrl() : string
    {
        // Get the URL and run a replacement with the auth values
        return static::replace($this->auth, $this->config['url']);
    }
}
