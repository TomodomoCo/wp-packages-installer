<?php

namespace Tomodomo\Packages\Installer\Installers;

class Edd extends AbstractInstaller implements InstallerInterface
{
    /**
     * @return string
     */
    public function getDownloadUrl()
    {
		// Build the request body
        $requestBody = $this->getRequestData();

		// Build a new Guzzle client
		$http = new \GuzzleHttp\Client();

		// Send a request to the EDD endpoing
		$response = $http->request(
			'post',
			$this->config['url'],
			[
				'form_params' => $requestBody,
			],
		);

		// Extract the response
		$body = json_decode((string) $response->getBody(), true);

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
        $data = [
			'edd_action' => 'get_version',
            'license'    => $this->auth['licenseKey'],
            'url'        => $this->auth['authorizedUrl'],
		];

        $itemId   = $this->config['itemId'] ?? '';
        $itemName = $this->config['itemName'] ?? '';

        // An Item ID or Item Name are required
        if (empty($itemId) || empty($itemName)) {
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
