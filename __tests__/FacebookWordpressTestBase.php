<?php
/*
 * Copyright (C) 2017-present, Facebook, Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace FacebookPixelPlugin\Tests;

use \WP_Mock\Tools\TestCase;

abstract class FacebookWordpressTestBase extends TestCase {
  public function setUp() {
    \WP_Mock::setUp();
    $GLOBALS['wp_version'] = '1.0';
    \Mockery::getConfiguration()->setConstantsMap([
      'FacebookPixelPlugin\Core\FacebookPixel' => [
        'FB_INTEGRATION_TRACKING_KEY' => 'fb_integration_tracking',
      ],
    ]);

    $_SERVER['HTTPS'] = 'on';
    $_SERVER['HTTP_HOST'] = 'www.pikachu.com';
    $_SERVER['REQUEST_URI'] = '/index.php';
  }

  public function tearDown() {
    $this->addToAssertionCount(
      \Mockery::getContainer()->mockery_getExpectationCount());
    unset($GLOBALS['wp_version']);
    \WP_Mock::tearDown();
  }

  // mock Wordpress Core Function
  protected function mockIsAdmin($is_admin) {
    $this->mocked_fbpixel = \Mockery::mock
      ('alias:FacebookPixelPlugin\Core\FacebookPluginUtils');
    $this->mocked_fbpixel->shouldReceive('isAdmin')
      ->andReturn($is_admin);
  }

  protected function mockFacebookWordpressOptions($options = array()){
    $this->mocked_options = \Mockery::mock(
      'alias:FacebookPixelPlugin\Core\FacebookWordpressOptions');
    if(array_key_exists('use_s2s', $options)){
      $this->mocked_options->shouldReceive('getUseS2S')->andReturn($options['use_s2s']);
    }
    else{
      $this->mocked_options->shouldReceive('getUseS2S')->andReturn(false);
    }
    if(array_key_exists('use_pii', $options)){
      $this->mocked_options->shouldReceive('getUsePii')->andReturn($options['use_pii']);
    }
    else{
      $this->mocked_options->shouldReceive('getUsePii')->andReturn(true);
    }
    if(array_key_exists('agent_string', $options)){
      $this->mocked_options->shouldReceive('getAgentString')->andReturn($options['agent_string']);
    }
    else{
      $this->mocked_options ->shouldReceive('getAgentString')
                            ->andReturn('wordpress');
    }
    if(array_key_exists('pixel_id', $options)){
      $this->mocked_options->shouldReceive('getPixelId')->andReturn($options['pixel_id']);
    }
    else{
      $this->mocked_options->shouldReceive('getPixelId')->andReturn('1234');
    }
  }
}
