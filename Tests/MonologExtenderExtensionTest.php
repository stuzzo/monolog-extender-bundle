<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stuzzo\Bundle\MonologExtenderBundle\Tests;

use Stuzzo\Bundle\MonologExtenderBundle\DependencyInjection\MonologExtenderExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MonologExtenderExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithDefault()
    {
        $container = $this->getContainer();

        $this->assertTrue($container->hasDefinition('stuzzo.logger.processor.web'));
        $this->assertTrue($container->hasDefinition('stuzzo.logger.stream.formatter'));
        $this->assertTrue($container->hasDefinition('stuzzo.logger.html.formatter'));
        $this->assertTrue($container->hasDefinition('stuzzo.logger.slack.formatter'));
        $this->assertTrue($container->hasDefinition('security.token_storage'));
    }
	
	protected function getContainer(array $config = array(), array $thirdPartyDefinitions = array())
	{
		$container = new ContainerBuilder();
		foreach ($thirdPartyDefinitions as $id => $definition) {
			$container->setDefinition($id, $definition);
		}
		
		$loader = new MonologExtenderExtension();
		$loader->load($config, $container);
		$container->compile();
		
		return $container;
	}
}
