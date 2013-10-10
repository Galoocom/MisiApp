<?php

namespace Misi\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\CoreBundle\Model\UserInterface;

/**
 * Favorite controller.
 */
class FavoriteShopController extends ResourceController
{
    public function indexByUserAction(Request $request)
    {
        $config = $this->getConfiguration();
        
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $paginator = $this
            ->get('misi.repository.favorite_shop')
            ->createByUserPaginator($user)
        ;
        
        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse($config->getTemplate('index.html'), array(
            'shops' => $paginator
        ));
        
    }    
}