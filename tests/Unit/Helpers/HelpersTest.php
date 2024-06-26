<?php

use Sunhill\Basic\Tests\SunhillTestCase;

uses(SunhillTestCase::class);

test('makeStdClass() works as expected', function()
{
   $result = makeStdclass(['keyA'=>'valueA','keyB'=>'valueB']);
   expect($result->keyA)->toBe('valueA');
   expect($result->keyB)->toBe('valueB');   
});