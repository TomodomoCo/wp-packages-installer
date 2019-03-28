<?php

namespace Tomodomo\Packages\Installer\Installers;

use Tomodomo\Packages\Installer\Framework\HttpClient;

/**
 * An installer which POSTs to an endpoint and returns
 * serialized data containing the download URL. Used
 * primarily for Gravity Forms.
 */
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
        $request  = HttpClient::request();
        $response = $request->post($url);

		// Extract and unserialize the response
		$body = unserialize($response->getBody());

        return $body;
    }
}
