<?php

namespace ADB\Tests;

use ADB\ImmoSyncWhise\Plugin;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testRenderValidTemplate()
    {
        $context = [
            'variable1' => 'Hello',
            'variable2' => 'World',
        ];

        $expectedOutput = 'Hello World';

        $output = Plugin::render('test_template', $context);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testRenderInvalidTemplate()
    {
        $context = [
            'variable1' => 'Hello',
            'variable2' => 'World',
        ];

        // Assuming the template 'non_existent_template' does not exist.
        $output = Plugin::render('non_existent_template', $context);

        $this->assertNull($output);
    }
}
