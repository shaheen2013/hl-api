<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AutocheckinResourceTest extends TestCase
{
    public function testUseSendDocumentEmailInPrecheckConfigurationExists()
    {
        // Test that the configuration key exists in the default configuration
        $defaultConfig = include __DIR__ . '/../../config/autocheckin/default_configuration.php';

        $this->assertArrayHasKey('use_send_document_email_in_precheck', $defaultConfig);
        $this->assertTrue($defaultConfig['use_send_document_email_in_precheck']);
    }

    public function testUseSendDocumentEmailInPrecheckDefaultValue()
    {
        // Test that the default value is true
        $defaultConfig = include __DIR__ . '/../../config/autocheckin/default_configuration.php';

        $this->assertTrue($defaultConfig['use_send_document_email_in_precheck']);
    }
}
