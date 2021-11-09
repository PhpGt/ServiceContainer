<?php
namespace Gt\ServiceContainer;

use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;

class Injector {
	public function __construct(
		private Container $container
	) {
	}

	/**
	 * @param object|null $instance The instance of the object containing
	 * the method to invoke.
	 * @param string $functionName The method name to invoke.
	 * @param array<string, mixed> $extraArgs An associative array where the
	 * keys will match the method parameters by *name*, for passing values
	 * of PHP's inbuilt types like scalar values.
	 * @return mixed The return value of the invoked method.
	 */
	public function invoke(
		?object $instance,
		string|callable $functionName,
		array $extraArgs = []
	):mixed {
		$arguments = [];

		if($instance) {
			$refClass = new ReflectionClass($instance);
			$refFunction = $refClass->getMethod($functionName);
		}
		else {
			$refFunction = new ReflectionFunction($functionName);
		}

		foreach($refFunction->getParameters() as $refParam) {
			/** @var ReflectionNamedType|null $refType */
			$refType = $refParam->getType();
			if(is_null($refType)
			|| $refType->isBuiltin()) {
				array_push(
					$arguments,
					$extraArgs[$refParam->getName()]
				);
			}
			else {
				array_push(
					$arguments,
					$this->container->get($refType->getName())
				);
			}
		}

		if($instance) {
			array_unshift($arguments, $instance);
		}

		return $refFunction->invoke(...$arguments);
	}
}
