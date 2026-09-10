<?php

namespace Tests\Unit;

use App\Types\AutocheckinDataType;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class AutocheckinDataTypeTest extends TestCase
{
    public function testUseSendDocumentEmailInPrecheckFieldExists()
    {
        // Simple test to verify the field is handled in the DataType
        $request = new Request([
            'use_send_document_email_in_precheck' => true,
            'product_id' => 21
        ]);

        $dataType = new AutocheckinDataType($request);

        // Test that the property exists and can be accessed
        $this->assertTrue(property_exists($dataType, 'useSendDocumentEmailInPrecheck'));
    }

    public function testUseSendDocumentEmailInPrecheckBooleanHandling()
    {
        // Test boolean true
        $request = new Request([
            'use_send_document_email_in_precheck' => true,
            'product_id' => 21
        ]);
        $dataType = new AutocheckinDataType($request);
        $this->assertTrue($dataType->useSendDocumentEmailInPrecheck);

        // Test boolean false
        $request = new Request([
            'use_send_document_email_in_precheck' => false,
            'product_id' => 21
        ]);
        $dataType = new AutocheckinDataType($request);
        $this->assertFalse($dataType->useSendDocumentEmailInPrecheck);
    }
}
