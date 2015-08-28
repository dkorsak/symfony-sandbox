<?php

/**
 * ArticleAdmin class.
 */
namespace App\DemoBundle\Admin;

use App\BackendBundle\Admin\BaseAdmin;
use App\DemoBundle\Entity\Article;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Admin class for managing articles.
 */
class ArticleAdmin extends BaseAdmin
{
    /**
     * Route pattern.
     *
     * @var string
     */
    protected $baseRoutePattern = 'articles';

    /**
     * Route name.
     *
     * @var string
     */
    protected $baseRouteName = 'article';

    /**
     * Datagrid params.
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'publishDate',
    );

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getUniqid()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $articleCategoryParams = array(
            'property' => 'name',
            'empty_value' => $this->getEmptySelectValue(),
            'label' => 'Category',
            'attr' => array('data-placeholder' => $this->getEmptySelectValue()),
        );
        $tagsParams = array(
            'required' => false, 'expanded' => true, 'property' => 'name', 'by_reference' => false, 'multiple' => true,
        );

        $translation = $this->trans('If checked, article will be visible');
        $publishParams = array('required' => false, 'help' => $translation);

        $imagePath = $this->getSubject()->getFullImagePath();
        $uploadImageParams = array(
            'required' => false,
            'image_filter' => 'article_thumb',
            'image_path' => $imagePath,
            'label' => false,
        );

        $formMapper
            ->with('General', array('class' => 'col-md-6'))
                ->add('articleCategory', 'sonata_type_model', $articleCategoryParams)
                ->add('tags', 'sonata_type_model', $tagsParams)
                ->add('title')
                ->add('publishDate', 'app_backend_form_jquery_date_type', array('label' => 'Publish date'))
                ->add('publish', null, $publishParams)
            ->end()
            ->with('Image', array('class' => 'col-md-6'))
                ->add('uploadedImage', 'liip_imagine_image', $uploadImageParams)
            ->end()
            ->with('Content', array('class' => 'col-md-12'))
                ->add('body', 'app_backend_form_ckeditor_type', array('label' => false))
            ->end();
    }

    /**
     * {@inheritdoc}
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $articleCategoryParams = array(
            'sortable' => true, 'label' => 'Category', 'template' => 'SonataAdminBundle::CRUD:base_list_field.html.twig',
        );

        $publishDateParams = array(
            'label' => 'Publish date', 'template' => 'AppBackendBundle:CRUD:list_date.html.twig',
        );

        $listMapper
            ->addIdentifier('title')
            ->add('articleCategory.name')
            ->add('publishDate', null, $publishDateParams)
            ->add('publish', null, array('template' => 'AppDemoBundle:Article:list_status.html.twig'))
            ->add('_action', 'actions', array('actions' => $this->getActions(true)));
    }

    /**
     * {@inheritdoc}
     *
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $publishParams = array(
            'field_options' => array(
                 'catalogue' => 'article',
                 'choices' => array(BooleanType::TYPE_NO => 'Disabled', BooleanType::TYPE_YES => 'Published'),
            ),
        );
        $articleCategortParams = array(
            'label' => 'Category',
            'field_options' => array('empty_value' => $this->getEmptySelectValue()),
        );
        $filter
            ->add('title')
            ->add('articleCategory', null, $articleCategortParams)
            ->add('publishDate', 'jquery_date_range_filter', array('label' => 'Publish date'))
            ->add('publish', null, $publishParams);
    }

    /**
     * {@inheritdoc}
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $publishDateParams = array(
            'label' => 'Publish date', 'template' => 'AppBackendBundle:CRUD:show_date.html.twig',
        );
        $imageParams = array('label' => 'Uploaded Image', 'template' => 'AppDemoBundle:Article:show_image.html.twig');

        $showMapper
            ->with('General')
                ->add('title')
                ->add('articleCategory.name', null, array('label' => 'Category'))
                ->add('tags', null, array('template' => 'AppDemoBundle:ArticleTag:show_orm_many_to_many.html.twig'))
                ->add('publishDate', null, $publishDateParams)
                ->add('image', null, $imageParams)
            ->end()
            ->with('Body')
                ->add('body', null, array('template' => 'AppDemoBundle:Article:show_article_body.html.twig'))
            ->end();
    }
}
