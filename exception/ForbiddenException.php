<?php

namespace callmeliow\phpmvc\exception;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = "You don't have permisson to access this page";
    protected $code = 403;
}
