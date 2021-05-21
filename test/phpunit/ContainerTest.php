<?php
namespace Gt\ServiceContainer\Test;

use Gt\ServiceContainer\Container;
use Gt\ServiceContainer\ServiceNotFoundException;
use Gt\ServiceContainer\Test\Example\Greeter;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase {
	public function testSetGet():void {
		$greeter = new Greeter();
		$sut = new Container();
		$sut->set(Greeter::class, $greeter);
		self::assertSame($greeter, $sut->get(Greeter::class));
	}

	public function testGet_unknown():void {
		$sut = new Container();
		self::expectException(ServiceNotFoundException::class);
		$sut->get(Greeter::class);
	}
}
