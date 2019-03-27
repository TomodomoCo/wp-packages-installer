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
        // Get the URL
        $url = $this->config['url'];

        // Run a replacement with the auth values
        return $this->replace($this->auth, $url);
    }
}
