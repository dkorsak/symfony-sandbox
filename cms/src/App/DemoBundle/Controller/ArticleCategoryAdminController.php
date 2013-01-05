<?php

namespace App\DemoBundle\Controller;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;

class ArticleCategoryAdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function deleteAction($id)
    {
        try {
            return parent::deleteAction($id);
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Cannot delete category. Category is used.'));
        }
        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    /**
     * {@inheritdoc}
     */
    public function batchActionDelete(ProxyQueryInterface $query)
    {
        try {
            return parent::batchActionDelete($query);
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Cannot delete category. Category is used.'));
        }
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}