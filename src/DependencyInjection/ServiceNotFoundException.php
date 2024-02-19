<?php

namespace App\DependencyInjection;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ServiceNotFoundException extends Exception implements ContainerExceptionInterface
{

}