<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Product controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class ProductController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        $product = parent::createNew();
        
        if (null !== $shop = $this->container->get('galoo.shop_context')->getShop()) {
            $product->setShop($shop);
        }

        return $product;
    }    
    
    /**
     * List products categorized under given taxon.
     *
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        $config = $this->getConfiguration();

        $taxon = $this->get('sylius.repository.taxon')
            ->findOneByPermalink($permalink);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist');
        }

        $paginator = $this
            ->getRepository()
            ->createByTaxonPaginator($taxon)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('SyliusWebBundle:Frontend/Product:indexByTaxon.html.twig', array(
            'taxon'    => $taxon,
            'products' => $paginator,
        ));
    }
    
    /**
     * List products under a given shop.
     *
     * @param Request $request
     * @param \Misi\Bundle\ShopBundle\Model\ShopInterface
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByShopAction(Request $request)
    {
        $config = $this->getConfiguration();

        $shop = $this->get('galoo.shop_context')->getShop();
        
        if (!isset($shop)) {
            throw new NotFoundHttpException('Requested shop does not exist');
        }

        $paginator = $this
            ->getRepository()
            ->createByShopPaginator($shop)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('indexByShop.html', array(
            'shop'     => $shop,
            'products' => $paginator,
        ));
        
    }
    
    /**
     * List products categorized under given taxon in a given shop.
     *
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByShopTaxonAction(Request $request, $permalink)
    {
        $config = $this->getConfiguration();

        $taxon = $this->get('sylius.repository.taxon')
            ->findOneByPermalink($permalink);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist');
        }
        
        $shop = $this->get('galoo.shop_context')->getShop();

        if (!isset($shop)) {
            throw new NotFoundHttpException('Requested shop does not exist');
        }
        

        $paginator = $this
            ->getRepository()
            ->createByShopTaxonPaginator($shop, $taxon)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('SyliusWebBundle:Shop/Product:indexByTaxon.html.twig', array(
            'taxon'    => $taxon,
            'products' => $paginator,
        ));
    }

    /**
     * Render product filter form.
     *
     * @param Request $request
     */
    public function filterFormAction(Request $request)
    {
        $form = $this->getFormFactory()->createNamed('criteria', 'sylius_product_filter');

        return $this->renderResponse('SyliusWebBundle:Backend/Product:filterForm.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function getFormFactory()
    {
        return $this->get('form.factory');
    }
}
