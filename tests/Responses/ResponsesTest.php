<?php namespace Neomerx\Tests\JsonApi\Responses;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Mockery;
use \Mockery\MockInterface;
use \Neomerx\Tests\JsonApi\BaseTestCase;
use \Neomerx\JsonApi\Responses\Responses;
use \Neomerx\JsonApi\Parameters\Headers\MediaType;
use \Neomerx\JsonApi\Parameters\SupportedExtensions;
use \Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use \Neomerx\JsonApi\Contracts\Responses\ResponsesInterface;
use \Neomerx\JsonApi\Contracts\Parameters\Headers\MediaTypeInterface;
use \Neomerx\JsonApi\Contracts\Parameters\SupportedExtensionsInterface;

/**
 * @package Neomerx\Tests\JsonApi
 */
class ResponsesTest extends BaseTestCase
{
    /**
     * @var MockInterface
     */
    private $mock;

    /**
     * @var ResponsesInterface
     */
    private $responses;

    /**
     * Set up tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mock      = Mockery::mock(Responses::class)->makePartial();
        $this->responses = $this->mock;
    }

    /**
     * Test get status code only response.
     */
    public function testGetCodeResponse1()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs([null, 123, ['Content-Type' => 'some/type']])->andReturn('something');

        $mediaType = new MediaType('some', 'type');
        $this->assertEquals('something', $this->responses->getResponse(123, $mediaType));
    }

    /**
     * Test get status code only response.
     */
    public function testGetCodeResponse2()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs([null, 123, ['Content-Type' => 'some/type;ext="ext1"']])->andReturn('something');

        $mediaType = new MediaType('some', 'type', [MediaTypeInterface::PARAM_EXT => 'ext1']);
        $this->assertEquals('something', $this->responses->getResponse(123, $mediaType));
    }

    /**
     * Test get status code only response.
     */
    public function testGetCodeResponse3()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs([null, 123, ['Content-Type' => 'some/type;ext="ext1",supported-ext="sup-ext1"']])
            ->andReturn('something');

        $mediaType = new MediaType('some', 'type', [MediaTypeInterface::PARAM_EXT => 'ext1']);
        $mockSupportedExt = Mockery::mock(SupportedExtensionsInterface::class);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $mockSupportedExt->shouldReceive('getExtensions')->once()->withNoArgs()->andReturn('sup-ext1');

        /** @var SupportedExtensionsInterface $mockSupportedExt */
        $this->assertEquals('something', $this->responses->getResponse(123, $mediaType, null, $mockSupportedExt));
    }

    /**
     * Test get status code only response.
     */
    public function testGetCodeResponse4()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs([null, 123, ['Content-Type' => 'some/type;supported-ext="sup-ext1"']])
            ->andReturn('something');

        $mediaType = new MediaType('some', 'type');
        $mockSupportedExt = Mockery::mock(SupportedExtensionsInterface::class);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $mockSupportedExt->shouldReceive('getExtensions')->once()->withNoArgs()->andReturn('sup-ext1');

        /** @var SupportedExtensionsInterface $mockSupportedExt */
        $this->assertEquals('something', $this->responses->getResponse(123, $mediaType, null, $mockSupportedExt));
    }

    /**
     * Test get status code only response.
     */
    public function testGetCodeResponse5()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs([null, 123, ['Content-Type' => 'some/type']])
            ->andReturn('something');

        $mediaType = new MediaType('some', 'type');
        $supportedExt = new SupportedExtensions();

        $this->assertEquals('something', $this->responses->getResponse(123, $mediaType, null, $supportedExt));
    }

    /**
     * Test get response with content.
     */
    public function testGetCreatedResponse()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs(['content', 201, ['Location' => '/resource/123', 'Content-Type' => 'some/type']])
            ->andReturn('something');

        $mediaType = new MediaType('some', 'type');

        /** @var EncoderInterface $encoder */
        $this->assertEquals('something', $this->responses->getCreatedResponse(
            '/resource/123',
            $mediaType,
            'content'
        ));
    }

    /**
     * Test get response.
     */
    public function testGetResponse()
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $this->mock->shouldReceive('createResponse')->once()
            ->withArgs(['content', 456, ['Content-Type' => 'some/type']])
            ->andReturn('something');

        $mediaType = new MediaType('some', 'type');

        /** @var EncoderInterface $encoder */
        $this->assertEquals('something', $this->responses->getResponse(456, $mediaType, 'content'));
    }
}
