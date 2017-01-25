<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/01
 */
namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\User\UserProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends Controller
{

    /**
     * @Route("/secured/my/profile/{slug}" , name="user_profile" , options={"expose"=true})
     * @ParamConverter("user", class="AppBundle\Entity\User")
     * @param Request $request
     * @Method("GET")
     *
     *
     * NOTE: The $post controller argument is automatically injected by Symfony
     * after performing a database query looking for a Post with the 'slug'
     * value given in the route.
     * See http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     * @return Response
     */
    public function profileAction(Request $request, User $user)
    {
        $account = null;

        $form = $this->createForm(UserProfileType::class,$user);

        if ($user->getUserGroup()->getTitle() == 'Admin') {
            $account = 'admin';
        }

        return $this->render('user/profile.html.twig', array(
            'page_header' => ucfirst($user->getFirstName() . "'s profile"),
            'breadcrumb' => 'Profile',
            'action' => 'user_profile',
            'user' => $user,
            'account' => $account,
            'form' => $form->createView()
        ));
    }
}
