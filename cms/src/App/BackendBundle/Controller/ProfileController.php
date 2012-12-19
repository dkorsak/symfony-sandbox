<?php

namespace App\BackendBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->get('sonata.user.profile.form');
        $formHandler = $this->container->get('sonata.user.profile.form.handler');
        $process = $formHandler->process($user);
        if ($process) {
            // update username, needs to be the same as email
            $user->setUsername($user->getEmail());
            $this->get('fos_user.user_manager')->updateUser($user);
            $this->get('session')->setFlash('sonata_flash_success', 'flash_edit_success');
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