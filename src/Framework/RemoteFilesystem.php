<?php

namespace Tomodomo\Packages\Installer\Framework;

use Composer\Config;
use Composer\IO\IOInterface;

class RemoteFilesystem extends \Composer\Util\RemoteFilesystem
{
    /**
     * Override the original file URL
     *
     * @var string
     */
    protected $fileUrl;

     /**
      * Constructor.
      *
      * @param string      $fileUrl    The url that should be used instead of fileurl
      * @param IOInterface $io         The IO instance
      * @param Config      $config     The config
      * @param array       $options    The options
      * @param bool        $disableTls
      *
      * @return void
      */
    public function __construct(
        $fileUrl,
        IOInterface $io,
        Config $config = null,
        array $options = [],
        $disableTls = false
    )
    {
        $this->fileUrl = $fileUrl;

        parent::__construct(
            $io,
            $config,
            $options,
            $disableTls
        );

        return;
    }

    /**
     * Copy the remote file to local.
     *
     * @param string $originUrl The origin URL
     * @param string $fileUrl   The file URL (ignored)
     * @param string $fileName  the local filename
     * @param bool   $progress  Display the progression
     * @param array  $options   Additional context options
     *
     * @return bool true
     */
    public function copy(
        $originUrl,
        $fileUrl,
        $fileName,
        $progress = true,
        $options = []
    )
    {
        return parent::copy(
            $originUrl,
            $this->fileUrl,
            $fileName,
            $progress,
            $options
        );
    }
}
