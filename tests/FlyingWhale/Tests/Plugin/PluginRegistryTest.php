<?php
/*
 * Copyright 2013 Imre Toth <tothimre at gmail> Licensed under the Apache
 * License, Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */

namespace FlyingWhale\Tests\Plugin;

use FlyingWhale\Plugin\PluginRegistry;
use \Mockery as m;

class PluginRegistryTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testAddPlugin()
    {
        $pluginRegistry = new PluginRegistry();

        $plugin1Name = 'plugin_1';
        $plugin1 = m::mock('plugin', array('Init' => null, 'getName' => $plugin1Name, 'isMultipleInstanced' => false));

        $plugin2Name = 'plugin_2';
        $plugin2 = m::mock('plugin', array('Init' => null, 'getName' => $plugin2Name, 'isMultipleInstanced' => false));

        $pluginRegistry->addPlugin($plugin1);
        $this->assertEquals($plugin1, $pluginRegistry->getPlugin($plugin1Name));

        $pluginRegistry->addPlugin($plugin2);
        $this->assertEquals($plugin2, $pluginRegistry->getPlugin($plugin2Name));
    }

    public function testAddPluginException()
    {
        $pluginRegistry = new PluginRegistry();

        $pluginName = 'plugin_1';
        $plugin = m::mock('plugin', array('Init' => null, 'getName' => $pluginName, 'isMultipleInstanced' => false));
        $sameTypePlugin = m::mock('plugin', array('Init' => null, 'getName' => $pluginName, 'isMultipleInstanced' => false));

        $pluginRegistry->addPlugin($plugin);

        $this->setExpectedException('FlyingWhale\Plugin\Exceptions\MultipleSameTypePluginRegistrationException');
        $pluginRegistry->addPlugin($sameTypePlugin);
    }

    /**
     * @dataProvider provider
     */
    public function testGetPlugin($pluginRegistry, $pluginName, $expectedPlugin)
    {
        $plugin = $pluginRegistry->getPlugin($pluginName);
        $this->assertEquals($expectedPlugin, $plugin);
    }

    /**
     * @dataProvider provider
     */
    public function testGetPluginException($pluginRegistry, $expectedPlugin)
    {
        $this->setExpectedException('FlyingWhale\Plugin\Exceptions\PluginIsNotRegisteredException');
        $plugin = $pluginRegistry->getPlugin('not_registered_plugin');
    }

    public function provider()
    {
        $pluginName = 'plugin';
        $plugin = m::mock('plugin', array('init' => null, 'getName' => $pluginName, 'isMultipleInstanced' => false));
        $pluginRegistry = new PluginRegistry();
        $pluginRegistry->addPlugin($plugin);

        return array(
            array($pluginRegistry, $pluginName, $plugin)
        );
    }
}
