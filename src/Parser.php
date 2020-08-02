<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser;

use NorthernLights\HostsFileParser\Exception\HostsFileException;

/**
 * Hosts file parser
 *
 * Class Parser
 * @package NorthernLights\HostsFileParser
 */
class Parser
{
    public const DELIMITER = ' ';

    /** @var HostsFile */
    protected $hostsFile;

    /** @var Host[]|null */
    protected $hosts;

    /**
     * HostsFile constructor.
     * @param HostsFile $filename
     *
     * @throws HostsFileException
     */
    public function __construct(HostsFile $hostsFile)
    {
        if ($hostsFile->getSplFileObject()->getSize() === 0) {
            throw new HostsFileException('Hosts file is empty');
        }

        $this->hostsFile = $hostsFile;
    }

    /**
     * Parse
     *
     * @return Host[]
     */
    public function parse(): array
    {
       // There is no point of re-parsing as HostsFile is immutable
        if ($this->hosts !== null) {
            return $this->hosts;
        }

        $splFile = $this->hostsFile->getSplFileObject();
        $hosts = [];

        /** @var string $row */
        foreach ($splFile as $row) {
            $row = trim(preg_replace('/\s+/', self::DELIMITER, $row));

            if ($row === '') {
                // Skip empty entries. Lines structured of multiple whitespaces are not skipped by SplFileObject
                continue;
            }

            if (($row[0] ?? '') === '#') {
                # Skip comment
                continue;
            }

            $dlmCount = substr_count($row, self::DELIMITER); // This is faster than explode->count check
            if ($dlmCount < 1) {
                // Skip unprocessable line.
                continue;
            }

            list($ip, $host) = explode(self::DELIMITER, $row, 2);

            // If there is more than one host per entry, then we need to handle it properly
            if ($dlmCount > 1) {
                $hosts[] = new Host($ip, explode(self::DELIMITER, $host), $splFile->key());

                continue;
            }

            $hosts[] = new Host($ip, [$host], $splFile->key());
        }

        return $this->hosts = $hosts;
    }
}
