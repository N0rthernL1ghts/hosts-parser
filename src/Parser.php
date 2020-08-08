<?php

declare(strict_types=1);

namespace NorthernLights\HostsFileParser;

use NorthernLights\HostsFileParser\Exception\HostsFileException;
use NorthernLights\HostsFileParser\Exception\ParserException;

/**
 * Hosts file parser
 *
 * Class Parser
 * @package NorthernLights\HostsFileParser
 */
class Parser
{
    public const DELIMITER = ' ';
    public const STRICT_SYNTAX = true;

    /** @var HostsFile */
    protected $hostsFile;

    /** @var Host[]|null */
    protected $hosts;

    /** @var bool */
    protected $strictSyntax;

    /**
     * HostsFile constructor.
     * @param HostsFile $filename
     * @param bool $strictSyntax
     *
     * @throws HostsFileException
     */
    public function __construct(HostsFile $hostsFile, bool $strictSyntax = false)
    {
        if ($hostsFile->getSplFileObject()->getSize() === 0) {
            throw new HostsFileException('Hosts file is empty');
        }

        $this->hostsFile = $hostsFile;
        $this->strictSyntax = $strictSyntax;
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

        /**
         * Traverse lines one by one
         * @var string $row
         */
        foreach ($splFile as $row) {
            $row = $this->removeExtraWhitespaces($row);

            if (!$this->isTokenParsable($row)) {
                continue;
            }

            $hosts[] = $this->parseEntry($row);
        }

        return $this->hosts = $hosts;
    }

    /**
     * Trim redundant whitespaces
     *
     * @param string $input
     * @param string $replacement
     * @return string
     */
    private function removeExtraWhitespaces(string $input, string $replacement = self::DELIMITER): string
    {
        return trim(
            preg_replace('/\s+/', $replacement, $input)
        );
    }

    /**
     * If first character is #, then this is a comment
     *
     * @param string $token
     * @return bool
     */
    private function isComment(string $token): bool
    {
        return ($token[0] ?? '') === '#';
    }

    /**
     * Number of tokens (delimiters) in file
     * This is faster than explode->count check
     *
     * @param string $input
     * @return int
     */
    private function countTokens(string $input): int
    {
        return substr_count($input, self::DELIMITER);
    }

    /**
     * Check if token can be parsed
     *
     * @param string $token
     * @return bool
     *
     * @throws ParserException
     */
    private function isTokenParsable(string $token): bool
    {
          // Sanitizer will strip down all whitespaces to one, which is enough to skip empty lines here
        if (($token === '') || ($token === ' ') || $this->isComment($token)) {
            return false;
        }

        if ($this->countTokens($token) < 1) {
            if ($this->strictSyntax) {
                throw new ParserException('Syntax error at line ' . $this->hostsFile->getSplFileObject()->key());
            }

            return false;
        }

        return true;
    }

    /**
     * Parse entry and extract host and domains
     *
     * @param string $token
     * @return Host
     */
    private function parseEntry(string $token): Host
    {
        $tokens = explode(self::DELIMITER, $token);
        $currentLine = ($this->hostsFile->getSplFileObject()->key() + 1);

        return new Host(
            array_shift($tokens),
            $tokens,
            $currentLine
        );
    }
}
