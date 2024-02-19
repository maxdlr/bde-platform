<?php

namespace App\DependencyInjection;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ServiceExistsException extends Exception implements ContainerExceptionInterface
{

}