<?php

/**
 * @namespace
 */
namespace CKMB\Bundle\ConfigKnpMenuBundle\Tests\Provider;

use CKMB\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider;
use CKMB\Bundle\PhumborBundle\Tests\DependencyInjection\JbConfigKnpMenuExtensionTest;
use Knp\Menu\MenuFactory;
use Knp\Menu\Integration\Symfony\RoutingExtension;

/**
 * Tests for CKMB\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider
 */
class ConfigurationMenuProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CKMB\Bundle\ConfigKnpMenuBundle\Provider\ConfigurationMenuProvider
     */
    protected $configurationProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $authorizationChecker;

    /**
     * Init Mock
     */
    public function setUp()
    {
        $urlGenerator = $this->createMock('Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface');
        $urlGenerator
          ->method('generate')
          ->will($this->returnValue('/my-page'));

        $this->authorizationChecker = $this->createMock(
            'Symfony\\Component\\Security\\Core\\Authorization\\AuthorizationCheckerInterface'
        );

        $menuFactory = new MenuFactory();
        $menuFactory->addExtension(new RoutingExtension($urlGenerator));

        $eventDispatcher = $this->createMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        $configuration = JbConfigKnpMenuExtensionTest::loadConfiguration();

        $this->configurationProvider = new ConfigurationMenuProvider(
            $menuFactory,
            $eventDispatcher,
            $this->authorizationChecker,
            $configuration
        );
    }

    /**
     * test get
     */
    public function testGet()
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('main');

        $this->assertCount(
            5,
            $menu->getChildren(),
            'Menu item number'
        );

        $this->assertEquals(
            '/first-item',
            $menu->getChild('first_item')->getUri(),
            'First item uri'
        );

        $this->assertEquals(
            '/my-page',
            $menu->getChild('third_item')->getUri(),
            'Third item uri'
        );

        $this->assertEquals(
            'Third Item Label',
            $menu->getChild('third_item')->getLabel(),
            'Third item label'
        );
        $this->assertEquals(
            array('test' => 'test2'),
            $menu->getChild('third_item')->getAttributes(),
            'Third item attributes'
        );
        $this->assertEquals(
            array('test' => 'test3'),
            $menu->getChild('third_item')->getLinkAttributes(),
            'Third item link attributes'
        );
        $this->assertEquals(
            array('test' => 'test4'),
            $menu->getChild('third_item')->getChildrenAttributes(),
            'Third item children attributes'
        );
        $this->assertFalse(
            $menu->getChild('third_item')->isDisplayed(),
            'Third item display'
        );
        $this->assertFalse(
            $menu->getChild('third_item')->getDisplayChildren(),
            'Third item display children'
        );

        $this->assertEquals(
            array(
              'key1' => 'value1',
              'key2' => 'value2',
              'routes' => array(
                array('route' => 'my_route', 'parameters' => array('test' => 'test1'))
              )
            ),
            $menu->getChild('third_item')->getExtras(),
            'Third item extras'
        );

        $position = 0;
        foreach ($menu->getChildren() as $key => $item) {
            if ($key == 'first_item') {
                $this->assertEquals(0, $position, 'First item postion');
            }
            $position++;
        }

        $this->assertCount(
            0,
            $menu->getChild('first_item')->getChildren(),
            'First item children count'
        );
        $this->assertCount(
            1,
            $menu->getChild('second_item')->getChildren(),
            'Second item children count'
        );
        $this->assertCount(
            0,
            $menu->getChild('third_item')->getChildren(),
            'Third item children count'
        );

        $this->assertEquals(
            'First Child',
            $menu->getChild('second_item')->getChild('second_item_first_child')->getLabel(),
            'Second item child label'
        );
    }

    /**
     * test get with multiple menu
     */
    public function testMultipleMenus()
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('second_menu');

        $this->assertEquals(
            'Item 1 Label',
            $menu->getChild('item1')->getLabel(),
            'Second menu item 1 label'
        );
        $this->assertEquals(
            'Item 2 Label',
            $menu->getChild('item2')->getLabel(),
            'Second menu item 2 label'
        );
    }

    /**
     * test with roles
     */
    public function testWithRolesNotGranted()
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(false);

        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertNull(
            $menu->getChild('item2'),
            'not menu because no rights'
        );
    }

    /**
     * test with roles
     */
    public function testWithRolesGranted()
    {
        $this->authorizationChecker
            ->method('isGranted')
            ->willReturn(true);

        $menu = $this->configurationProvider->get('menu_roles');

        $this->assertInstanceOf(
            'Knp\\Menu\\ItemInterface',
            $menu->getChild('item2'),
            'authenticated and rights'
        );
    }
}
