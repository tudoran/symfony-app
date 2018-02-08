<?php

namespace BackOfficeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BackOfficeBundle extends Bundle
{

    /**
     * BackOfficeBundle constructor.
     */
    public function __construct()
    {
        ini_set('auto_detect_line_endings', 1);
    }
}
