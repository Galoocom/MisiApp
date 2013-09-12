<?php

namespace Sylius\Bundle\WebBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Galoo\Bundle\ShopBundle\Context\ShopContextInterface;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Shop menu builder.
 *
 */
class ShopMenuBuilder extends MenuBuilder
{
    protected $shopContext;
    
    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, TranslatorInterface $translator, ShopContextInterface $shopContext)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->shopContext = $shopContext;
    }    
    
    /**
     * Builds shop main menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav'
            )
        ));

        $menu->setCurrent($request->getRequestUri());

        $childOptions = array(
            'attributes'         => array('class' => 'dropdown'),
            'childrenAttributes' => array('class' => 'dropdown-menu'),
            'labelAttributes'    => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'href' => '#')
        );

        $menu->addChild('dashboard', array(
            'route' => 'sylius_shop_dashboard',
            'routeParameters' => array('subdomain' => $this->shopContext->getShop()->getName())
        ))->setLabel($this->translate('sylius.backend.menu.main.dashboard'));

        $this->addAssortmentMenu($menu, $childOptions, 'main');
        $this->addSalesMenu($menu, $childOptions, 'main');
        $this->addConfigurationMenu($menu, $childOptions, 'main');

        $menu->addChild('homepage', array(
            'route' => 'sylius_homepage'
        ))->setLabel($this->translate('sylius.backend.menu.main.homepage'));

        return $menu;
    }

    /**
     * Builds backend sidebar menu.
     *
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createSidebarMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav'
            )
        ));

        $menu->setCurrent($request->getRequestUri());

        $childOptions = array(
            'childrenAttributes' => array('class' => 'nav nav-list'),
            'labelAttributes'    => array('class' => 'nav-header')
        );

        $child = $menu->addChild('MISI', $childOptions);

        $child->addChild('dashboard', array(
            'route' => 'sylius_shop_dashboard',
            'routeParameters' => array('subdomain' => $this->shopContext->getShop()->getName()),
            'labelAttributes' => array('icon' => 'icon-dashboard'),
        ))->setLabel($this->translate('sylius.backend.menu.sidebar.dashboard'));

        $this->addAssortmentMenu($menu, $childOptions, 'sidebar');
        $this->addSalesMenu($menu, $childOptions, 'sidebar');
        $this->addConfigurationMenu($menu, $childOptions, 'sidebar');

        return $menu;
    }

    /**
     * Add assortment menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addAssortmentMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('assortment', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.assortment', $section)))
        ;

        $child->addChild('products', array(
            'route' => 'sylius_shop_product_index',
            'routeParameters' => array('subdomain' => $this->shopContext->getShop()->getName()),
            'labelAttributes' => array('icon' => 'icon-th-large'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.products', $section)));

        $child->addChild('stockables', array(
            'route' => 'sylius_shop_inventory_index',
            'routeParameters' => array('subdomain' => $this->shopContext->getShop()->getName()),
            'labelAttributes' => array('icon' => 'icon-signal'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.stockables', $section)));

    }

    /**
     * Add sales menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addSalesMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('sales', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.sales', $section)))
        ;

        $child->addChild('orders', array(
            'route' => 'sylius_shop_order_index',
            'labelAttributes' => array('icon' => 'icon-shopping-cart'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.orders', $section)));
        $child->addChild('new_order', array(
            'route' => 'sylius_shop_order_create',
            'labelAttributes' => array('icon' => 'icon-plus-sign'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.new_order', $section)));
        $child->addChild('payments', array(
            'route' => 'sylius_shop_payment_index',
            'labelAttributes' => array('icon' => 'icon-money'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payments', $section)));
    }

    /**
     * Add customers menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addCustomersMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('customer', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.customer', $section)))
        ;

        $child->addChild('users', array(
            'route' => 'sylius_backend_user_index',
            'labelAttributes' => array('icon' => 'icon-group'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.users', $section)));
    }

    /**
     * Add configuration menu.
     *
     * @param ItemInterface $menu
     * @param array         $childOptions
     */
    protected function addConfigurationMenu(ItemInterface $menu, array $childOptions, $section)
    {
        $child = $menu
            ->addChild('configuration', $childOptions)
            ->setLabel($this->translate(sprintf('sylius.backend.menu.%s.configuration', $section)))
        ;

        $child->addChild('general_settings', array(
            'route' => 'sylius_shop_general_settings',
            'labelAttributes' => array('icon' => 'icon-cog'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.general_settings', $section)));

        $child->addChild('payment_methods', array(
            'route' => 'sylius_shop_payment_method_index',
            'labelAttributes' => array('icon' => 'icon-credit-card'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.payment_methods', $section)));

        $child->addChild('taxation_settings', array(
            'route' => 'sylius_shop_taxation_settings',
            'labelAttributes' => array('icon' => 'icon-cog'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.taxation_settings', $section)));

        $child->addChild('tax_categories', array(
            'route' => 'sylius_backend_tax_category_index',
            'labelAttributes' => array('icon' => 'icon-folder-close-alt'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_categories', $section)));

        $child->addChild('tax_rates', array(
            'route' => 'sylius_backend_tax_rate_index',
            'labelAttributes' => array('icon' => 'icon-adjust'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.tax_rates', $section)));

        $child->addChild('shipping_categories', array(
            'route' => 'sylius_backend_shipping_category_index',
            'labelAttributes' => array('icon' => 'icon-folder-close-alt'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_categories', $section)));

        $child->addChild('shipping_methods', array(
            'route' => 'sylius_backend_shipping_method_index',
            'labelAttributes' => array('icon' => 'icon-truck'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipping_methods', $section)));

        $child->addChild('shipments', array(
            'route' => 'sylius_backend_shipment_index',
            'labelAttributes' => array('icon' => 'icon-plane'),
        ))->setLabel($this->translate(sprintf('sylius.backend.menu.%s.shipments', $section)));
    }
}
