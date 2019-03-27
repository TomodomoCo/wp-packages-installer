<?php

namespace Tomodomo\Packages\Installer\Installers;

class PostSerialized extends AbstractInstaller implements InstallerInterface
{
    /**
     * Get the download URL.
     *
     * @return string
     */
    public function getDownloadUrl() : string
    {
        // Get the URL
        $url = $this->replace($this->auth, $this->config['url']);

		// Build a new Guzzle client
		$http = new \GuzzleHttp\Client();

		// Send a request to the endpoint
		$response = $http->request('post', $url);

		// Extract and unserialize the response
		$body = unserialize((string) $response->getBody());

        return $body;
    }
}
