<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration\Parser;

use Indigo\Supervisor\Configuration;
use Indigo\Supervisor\Exception\ParsingFailed;

/**
 * Parse configuration from string
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Text extends Base
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Configuration $configuration = null)
    {
        if (is_null($configuration)) {
            $configuration = new Configuration;
        }

        // Suppress error to handle it
        if (!$ini = @parse_ini_string($this->text, true, INI_SCANNER_RAW)) {
            throw new ParsingFailed('Given data cannot be parsed as INI string');
        }

        $sections = $this->parseArray($ini);
        $configuration->addSections($sections);

        return $configuration;
    }
}
