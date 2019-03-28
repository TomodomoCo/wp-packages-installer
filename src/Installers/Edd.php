<?php

namespace Tomodomo\Packages\Installer\Installers;

use Zttp\Zttp;

class Edd extends AbstractInstaller implements InstallerInterface
{
    /**
     * Get the EDD download URL.
     *
     * @return string
     */
    public function getDownloadUrl() : string
    {
		// Build the request body
        $requestBody = $this->getRequestData();

		// Send a request to the EDD endpoint
		$response = Zttp::asFormParams()->post(
			$this->config['endpoint'],
			$requestBody
		);

		// Extract the response
		$body = $response->json();

        var_dump($body);die;

        // If there is an error message, throw it here
        if ($body['msg'] ?? false) {
            throw new \Exception($body['msg']);
        }

        return $this->getDownloadLinkFromBody($body);
    }

    /**
     * Get the download link from a response body.
     *
     * @param array $body The response body to parse
     *
     * @return string
     */
    public function getDownloadLinkFromBody(array $body) : string
    {
		// If we can't get a link, stop here
		if (($body['download_link'] ?? false) === false) {
			throw new \Exception('No download link was found.');
		}

		// Return the link
		return $body['download_link'];
    }

    /**
     * Build the request data parameters.
     *
     * @return array
     */
    public function getRequestData() : array
    {
        // Fetch some auth data and confirm it's set.
        $licenseKey    = $this->auth['licenseKey'] ?? '';
        $authorizedUrl = $this->auth['authorizedUrl'] ?? '';

        if (empty($licenseKey) || empty($authorizedUrl)) {
            throw new \Exception('You must provide a `licenseKey` and `authorizedUrl`.');
        }

        $data = [
			'edd_action' => 'get_version',
            'license'    => $this->auth['licenseKey'],
            'url'        => $this->auth['authorizedUrl'],
		];

        $itemId   = $this->config['itemId'] ?? '';
        $itemName = $this->config['itemName'] ?? '';

        // An Item ID or Item Name are required
        if (empty($itemId) && empty($itemName)) {
            throw new \Exception('No item ID or item name were available.');
        }

        if (!empty($itemId)) {
			$data['item_id'] = $itemId;
        }

        if (!empty($itemName)) {
			$data['item_name'] = $itemName;
        }

        // Return the full data
        return $data;
    }
}
