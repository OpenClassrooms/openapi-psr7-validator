<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Tests\FromCommunity;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Utils;
use OpenClassrooms\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use OpenClassrooms\OpenAPIValidation\PSR7\ServerRequestValidator;
use OpenClassrooms\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\TestCase;

use function implode;

final class JsonContainerTypeValidationTest extends TestCase
{
    /**
     * @return mixed[][]
     */
    public function validBodyProvider(): array
    {
        return [
            'empty array' => ['/arrays', '[]'],
            'empty object' => ['/objects', '{}'],
            'nested empty array' => ['/nested-arrays', '{"value":[]}'],
            'nested empty object' => ['/nested-objects', '{"value":{}}'],
            'object enum' => ['/object-enums', '{"kind":"x"}'],
        ];
    }

    /**
     * @dataProvider validBodyProvider
     */
    public function testItPreservesJsonContainerTypes(string $path, string $body): void
    {
        $this->validator()->validate($this->request($path, $body));

        $this->addToAssertionCount(1);
    }

    /**
     * @return mixed[][]
     */
    public function invalidBodyProvider(): array
    {
        return [
            'object is not an array' => ['/arrays', '{}'],
            'array is not an object' => ['/objects', '[]'],
            'nested object is not an array' => ['/nested-arrays', '{"value":{}}'],
            'nested array is not an object' => ['/nested-objects', '{"value":[]}'],
        ];
    }

    /**
     * @dataProvider invalidBodyProvider
     */
    public function testItRejectsTheWrongJsonContainerType(string $path, string $body): void
    {
        $this->expectException(InvalidBody::class);

        $this->validator()->validate($this->request($path, $body));
    }

    /**
     * @return mixed[][]
     */
    public function validMultipartBodyProvider(): array
    {
        return [
            'empty array' => ['/multipart-arrays', '[]'],
            'empty object' => ['/multipart-objects', '{}'],
        ];
    }

    /**
     * @dataProvider validMultipartBodyProvider
     */
    public function testItPreservesJsonContainerTypesInMultipartBodies(string $path, string $body): void
    {
        $this->validator()->validate($this->multipartRequest($path, $body));

        $this->addToAssertionCount(1);
    }

    /**
     * @return mixed[][]
     */
    public function invalidMultipartBodyProvider(): array
    {
        return [
            'object is not an array' => ['/multipart-arrays', '{}'],
            'array is not an object' => ['/multipart-objects', '[]'],
        ];
    }

    /**
     * @dataProvider invalidMultipartBodyProvider
     */
    public function testItRejectsTheWrongJsonContainerTypeInMultipartBodies(string $path, string $body): void
    {
        $this->expectException(InvalidBody::class);

        $this->validator()->validate($this->multipartRequest($path, $body));
    }

    private function validator(): ServerRequestValidator
    {
        return (new ValidatorBuilder())->fromYaml($this->schema())->getServerRequestValidator();
    }

    private function request(string $path, string $body): ServerRequest
    {
        return (new ServerRequest('post', 'http://localhost' . $path))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(Utils::streamFor($body));
    }

    private function multipartRequest(string $path, string $body): ServerRequest
    {
        $boundary      = 'json-container-boundary';
        $multipartBody = implode("\r\n", [
            '--' . $boundary,
            'Content-Disposition: form-data; name="value"',
            'Content-Type: application/json',
            '',
            $body,
            '--' . $boundary . '--',
            '',
        ]);

        return (new ServerRequest('post', 'http://localhost' . $path))
            ->withHeader('Content-Type', 'multipart/form-data; boundary=' . $boundary)
            ->withBody(Utils::streamFor($multipartBody));
    }

    private function schema(): string
    {
        return <<<'YAML'
openapi: 3.0.0
info:
  title: JSON container validation
  version: '1.0'
paths:
  /arrays:
    post:
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: array
              items:
                type: object
      responses:
        '204':
          description: No content
  /objects:
    post:
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
      responses:
        '204':
          description: No content
  /nested-arrays:
    post:
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [value]
              properties:
                value:
                  type: array
                  items:
                    type: string
      responses:
        '204':
          description: No content
  /nested-objects:
    post:
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              required: [value]
              properties:
                value:
                  type: object
      responses:
        '204':
          description: No content
  /object-enums:
    post:
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              enum:
                - kind: x
      responses:
        '204':
          description: No content
  /multipart-arrays:
    post:
      requestBody:
        required: true
        content:
          multipart/form-data:
            schema:
              type: object
              required: [value]
              properties:
                value:
                  type: array
                  items:
                    type: string
      responses:
        '204':
          description: No content
  /multipart-objects:
    post:
      requestBody:
        required: true
        content:
          multipart/form-data:
            schema:
              type: object
              required: [value]
              properties:
                value:
                  type: object
      responses:
        '204':
          description: No content
YAML;
    }
}
