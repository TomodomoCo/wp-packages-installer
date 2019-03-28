<?php

namespace Tomodomo\Packages\Installer\Installers;

use Zttp\Zttp;

class PostSerialized extends AbstractInstaller implements InstallerInterface
{
    /**
     * Get the download URL.
     *
     * @return string
     */
    public function getDownloadUrl() : string
    {
        // Get the URL and run a replacement with the auth values
        $url = static::replace($this->auth, $this->config['endpoint']);

		// Send a request to the endpoint
		$response = Zttp::asFormParams()->post($url);

		// Extract and unserialize the response
		$body = unserialize($response->body());

        return $body;
    }
}
