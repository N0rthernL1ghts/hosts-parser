<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser;

use NorthernLights\HostsFileParser\Exception\HostsFileException;
use SplFileObject;

/**
 * Hosts file
 *
 * Class HostsFile
 * @package NorthernLights\HostsFileParser
 */
class HostsFile
{

    /** @var string */
    protected $filename;

    /** @var SplFileObject */
    protected $splFile;

    /**
     * HostsFile constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->load();
    }

    /**
     * @return SplFileObject
     */
    public function getSplFileObject(): SplFileObject
    {
        return $this->splFile;
    }

    /**
     * HostsFile destructor.
     */
    public function __destruct()
    {
        // Make sure file is unlocked and destroy file handle (held by SplFileObject)
        $this->splFile->flock(LOCK_UN);
        $this->splFile = null;
    }


    /**
     * Load file by creating SplFileObject instance
     */
    private function load(): void
    {
        $splFile = new SplFileObject($this->filename, 'rb');
        $splFile->flock(LOCK_EX);
        $splFile->setFlags(SplFileObject::READ_AHEAD | SplFileObject::DROP_NEW_LINE);
        $this->splFile = $splFile;
    }
}
