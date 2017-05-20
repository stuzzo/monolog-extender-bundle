<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\MonologBundle\Tests\DependencyInjection;

use Stuzzo\Bundle\MonologExtenderBundle\DependencyInjection\MonologExtenderExtension;
use Stuzzo\Bundle\MonologExtenderBundle\MonologExtenderBundle;
use Symfony\Bundle\MonologBundle\DependencyInjection\MonologExtension;
use Symfony\Bundle\MonologBundle\DependencyInjection\Compiler\LoggerChannelPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class MonologExtensionTest extends DependencyInjectionTest
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
		
		$container->getCompilerPassConfig()->setOptimizationPasses(array());
		$container->getCompilerPassConfig()->setRemovingPasses(array());
		$container->addCompilerPass(new LoggerChannelPass());
		
		$loader = new MonologExtenderExtension();
		$loader->load($config, $container);
		$container->compile();
		
		return $container;
	}
}
