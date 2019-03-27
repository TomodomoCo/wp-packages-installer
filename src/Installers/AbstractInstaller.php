<?php

namespace Tomodomo\Packages\Installer\Framework;

abstract AbstractInstaller
{
    /**
     * Instantiate the installation method.
     *
     * @param array $auth   The authentication data for the package
     * @param array $config The configuration data for the package
     *
     * @return void
     */
    public function __construct(array $auth, array $config)
    {
        $method->auth   = $auth;
        $method->config = $config;

        return;
    }

    /**
     * Helper to replace template strings.
     *
     * @param array  $map     The array of key/value string replacements.
     * @param string $subject The string to do the replacement on.
     *
     * @return array
     */
    public static function replace(array $map, string $subject) : string
    {
        // Loop over the values
        foreach ($map as $from => $to) {
            // We can only handle strings!
            if (!is_string($from) || !is_string($to)) {
                continue;
            }

            // Build a bracketed token
            $from = '{{' . $from . '}}';

            // Run the replacement
            $subject = str_replace($from, $to, $subject);
        }

        return $subject;
    }
}
