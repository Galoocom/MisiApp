<?php

namespace Misi\Bundle\WebBundle\Controller\Frontend\Shop;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Shop homepage controller.
 */
class HomepageController extends Controller
{
    /**
     * Store front page.
     *
     * @return Response
     */
    public function indexAction()
    {
        $shop = $this->get('galoo.shop_context')->getShop();

        if (!isset($shop)) {
            throw new NotFoundHttpException('Requested shop does not exist');
        }
        
        return $this->render('MisiWebBundle:Frontend/Shop/Homepage:index.html.twig', array(
            'shop' => $shop,
        ));
    }
}
