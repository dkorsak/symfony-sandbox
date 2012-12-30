<?php

namespace App\DemoBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;

class ArticleAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('articleCategory', 'sonata_type_model', array('property' => 'name', 'empty_value' => 'Please select', 'label' => 'Category'))
                ->add('title')
                ->add('publishDate', 'genemu_jquerydate', array('label' => 'Publish date', 'culture' => 'pl'))
                ->add('publish', null, array('required' => false, 'help' => 'If checked, article will be visible'))
                ->add('body', 'ckeditor')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('articleCategory', 'trans', array('label' => 'Category', 'template' => 'SonataAdminBundle::CRUD:base_list_field.html.twig'))
            ->add('publishDate', null, array('label' => 'Publish date', 'template' => 'AppBackendBundle:CRUD:list_date.html.twig'))
            ->add('publish', null, array("label" => "Status", "template" => "AppDemoBundle:Article:list_status.html.twig"))
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'Actions',
                    'actions' => array(
                        'view' => array('template' => 'AppBackendBundle:CRUD:list__action_view.html.twig'),
                        'edit' => array('template' => 'AppBackendBundle:CRUD:list__action_edit.html.twig'),
                        'delete' => array('template' => 'AppBackendBundle:CRUD:list__action_delete.html.twig'),
                    )
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('articleCategory', null, array('label' => 'Category'))
            ->add('publish');
    }
}