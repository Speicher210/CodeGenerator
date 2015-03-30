<?php

namespace Wingu\OctopusCore\CodeGenerator\Exceptions;

/**
 * Exception thrown if an error which can only be found on runtime occurs in the code generator.
 */
class RuntimeException extends \RuntimeException implements Exception
{
}