<?php

/**
 * ProfileController class
 *
 *
 */
namespace App\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Profile controller
 *
 *
 */
class ProfileController extends Controller
{
    /**
     * Edit profile action
     *
     * @throws AccessDeniedException
     * @return Response
     */
    public function editAction()
    {
        $user = $this->getUser();

        $form = $this->get('sonata.user.profile.form');
        $formHandler = $this->container->get('sonata.user.profile.form.handler');
        $process = $formHandler->process($user);
        if ($process) {
            $this->get('fos_user.user_manager')->updateUser($user);
            $this->get('session')->getFlashBag()->add('sonata_flash_success', 'flash_edit_success');

            return new RedirectResponse($this->generateUrl('app_backend_profile'));
        }

        return $this->render(
            'AppBackendBundle:Profile:edit_profile.html.twig',
            array(
                'admin_pool' => $this->get('sonata.admin.pool'),
                'base_template' => $this->container->parameters['sonata.admin.configuration.templates']['layout'],
                'form' => $form->createView(),
            )
        );
    }
}
