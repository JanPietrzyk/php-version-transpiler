<?php

$foo = ['user' => 'bar'];
$username = $foo['user'] ?? 'nobody';
$username = isset($foo['user']) ? $foo['user'] : 'nobody';