<?php

namespace IsaEken\Loops\Exceptions;

use Exception;

class NotWorkedException extends Exception
{
    public function __construct()
    {
        parent::__construct("The loop is not worked before. Please call 'run' method before using this.", 0, null);
    }
}
