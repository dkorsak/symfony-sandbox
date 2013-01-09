<?php

namespace App\DemoBundle\Admin;

use App\DemoBundle\Entity\Article;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;

class ArticleAdmin extends Admin
{
    /**
     * @var CacheManager $imageCacheManager
     */
    protected $imageCacheManager;

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'publishDate'
    );

    /**
     * @param CacheManager $imageCacheManager
     */
    public function setImageCacheManager(CacheManager $imageCacheManager)
    {
        $this->imageCacheManager = $imageCacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        $this->deleteAssets($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postRemove($object)
    {
        $this->deleteAssets($object);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('articleCategory', 'sonata_type_model', array('property' => 'name', 'empty_value' => 'Please select', 'label' => 'Category'))
                ->add('tags', 'sonata_type_model', array('required' => false, 'expanded' => true, 'property' => 'name', 'by_reference' => false, 'multiple' => true))
                ->add('title')
                ->add('publishDate', 'app_backend_form_jquery_date_type', array('label' => 'Publish date'))
                ->add('publish', null, array('required' => false, 'help' => $this->trans('If checked, article will be visible')))
                ->add('body', 'app_backend_form_ckeditor_type')
                ->add('uploadedImage', 'liip_imagine_image', array('required' => false, 'image_filter' => 'article_thumb', 'image_path' => $this->getSubject()->getFullImagePath()))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('articleCategory.name', null, array('sortable' => true, 'label' => 'Category', 'template' => 'SonataAdminBundle::CRUD:base_list_field.html.twig'))
            ->add('publishDate', null, array('label' => 'Publish date', 'template' => 'AppBackendBundle:CRUD:list_date.html.twig'))
            ->add('publish', null, array("template" => "AppDemoBundle:Article:list_status.html.twig"))
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
            ->add('publishDate', 'doctrine_orm_date', array('label' => 'Publish date'))
            ->add('publish', null, array('field_options' => array('catalogue' => 'article', 'choices' => array(BooleanType::TYPE_NO => 'Disabled', BooleanType::TYPE_YES => 'Published'))));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('title')
                ->add('articleCategory.name', null, array('label' => 'Category'))
                ->add('tags', null, array('template' => 'AppDemoBundle:ArticleTag:show_orm_many_to_many.html.twig'))
                ->add('publishDate', null, array('label' => 'Publish date', 'template' => 'AppBackendBundle:CRUD:show_date.html.twig'))
                ->add('image', null, array('label' => 'Uploaded Image', 'template' => 'AppDemoBundle:Article:show_image.html.twig'))
            ->end()
            ->with('Body')
                ->add('body', null, array('template' => 'AppDemoBundle:Article:show_article_body.html.twig'))
            ->end();
    }

    /**
     * Delete all object assets from cache
     * 
     * @param Article $object
     */
    private function deleteAssets(Article $object)
    {
        if ($object->getFullImagePath() != "") {
            $this->imageCacheManager->remove($object->getFullImagePath(), 'article_thumb');
        }
    }
}