<?php

namespace Misi\Bundle\UserBundle\Controller;

use Misi\Bundle\UserBundle\Repository\UserFeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Misi\Bundle\UserBundle\Model\UserFee;

/**
 * Account fees controller.
 *
 */
class FeeController extends Controller
{
    /**
     * List fees of the current user
     *
     * @return Response
     */
    public function indexAction()
    {
        $fees = $this
            ->getUserFeeRepository()
            ->findByUser($this->getUser(), array('updatedAt' => 'desc'));
        
        $total = $this
            ->getUserFeeRepository()
            ->getTotalOwedByUserAndStatus($this->getUser(), UserFee::STATUS_INIT);

        return $this->render(
            'MisiUserBundle:Fee:index.html.twig',
            array(
                'fees' => $fees,
                'total' => $total
            )
        );
    }

    /**
     * @return UserFeeRepository
     */
    private function getUserFeeRepository()
    {
        return $this->get('misi.repository.user_fee');
    }

}
