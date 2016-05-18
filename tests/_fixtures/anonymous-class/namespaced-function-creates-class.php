<?php

namespace Foo\Bar;

function test()
{
    return new \stdClass();
}

class test {}

function test2()
{
    return new class() {};
}