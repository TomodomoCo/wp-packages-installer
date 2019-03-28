<?php

namespace Tomodomo\Packages\Installer\Installers;

/**
 * An installer type where the URL can be generated
 * without needing to make additional HTTP requests;
 * applies a token search/replace on the URL.
 */
class Url extends AbstractInstaller implements InstallerInterface
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
