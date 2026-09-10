<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 30/01/2019
 * Time: 11:58
 */

namespace App\Services\Connections;

use Hotelinking\Exceptions\SchemaNotValidException;
use Hotelinking\Services\SchemaValidator;
use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Support\Facades\Log;

class ConnectionTest implements HLConnectionInterface
{
    private $schemaValidator;
    private $validation;
    private $eventsEmitted;
    private $validationErrors;

    /**
     * ConnectionTest constructor.
     */
    public function __construct()
    {
        $this->schemaValidator = app(SchemaValidator::class);
        $this->validation = true;
        $this->eventsEmitted = [];
        $this->validationErrors = [];
    }

    /**
     * @return bool
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @return string
     */
    public function getValidationErrors()
    {
        return json_encode($this->validationErrors, true);
    }

    /**
     * @return array
     */
    public function getEventsEmitted()
    {
        return $this->eventsEmitted;
    }

    /**
     * @param $payload
     * @param $subDomain
     * @param string $method
     * @return SchemaNotValidException
     */
    public function sendRequest($payload, $subDomain, $method = "POST", $headers = [])
    {
        if (!$subDomain) {
            $subDomain = config("services.aws.gateway.stream");
        }
        if ($subDomain == config("services.aws.gateway.stream")) {
            try {
                $this->validateRequest(data_get($payload, 'schema'), $payload);
                $this->eventsEmitted[] = data_get($payload, 'schema');
            } catch (SchemaNotValidException $e) {
                Log::error("Error sending request: SchemaNotValidException", [$e]);
                $this->validationErrors[] = $e->getMessage();
                $this->validation = false;
            } catch (\Throwable $e) {
                Log::error("Error sending request: Throwable", [$e]);
                $this->validationErrors[] = $e->getMessage();
                $this->validation = false;
            }
        }

        return true;
    }

    /**
     * @param $keyValue
     * @param $payload
     * @return SchemaNotValidException
     */
    private function validateRequest($keyValue, $payload)
    {
        if ($this->schemaValidator) {
            return  $this->schemaValidator->validate($keyValue, $payload);
        }
        return true;
    }
}
