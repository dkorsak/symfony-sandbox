<?php

/**
 * ArticleTagAdmin class
 *
 *
 */
namespace App\DemoBundle\Admin;

use App\BackendBundle\Admin\BaseAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Admin class for managing article tags
 *
 *
 */
class ArticleTagAdmin extends BaseAdmin
{
    /**
     * Route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'article-tags';

    /**
     * Route name
     *
     * @var string
     */
    protected $baseRouteName = 'article_tag';

    /**
     * Datagrid params
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getUniqid()
    {
        return 'article_tag';
    }

    /**
     * {@inheritdoc}
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
            ->end();
    }

    /**
     * {@inheritdoc}
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('slug')
            ->add('_action', 'actions', array('actions' => $this->getActions()));
    }

    /**
     * {@inheritdoc}
     *
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name');
    }
}
