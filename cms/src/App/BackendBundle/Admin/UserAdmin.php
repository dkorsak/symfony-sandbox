<?php

/**
 * UserAdmin class
 *
 *
 */
namespace App\BackendBundle\Admin;

use App\GeneralBundle\Utils\StringUtil;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use App\GeneralBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Sonata\CoreBundle\Form\Type\BooleanType;

/**
 * Admin class for managing users
 *
 */
class UserAdmin extends BaseAdmin
{
    /**
     * Route pattern
     *
     * @var string
     */
    protected $baseRoutePattern = 'users';

    /**
     * Route name
     *
     * @var string
     */
    protected $baseRouteName = 'user';

    /**
     * Default data grid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'email'
    );

    /**
     * Form options
     *
     * @var array
     */
    protected $formOptions = array(
        'validation_groups' => array('Admin user', Constraint::DEFAULT_GROUP)
    );

    /**
     * {@inheritdoc}
     *
     * @return UserInterface
     */
    public function getNewInstance()
    {
        $user = $this->getService('fos_user.user_manager')->createUser();
        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $user);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     *
     * @param UserInterface $user
     */
    public function prePersist($user)
    {
        $password = StringUtil::generateRandomPassword();
        $user->setPlainPassword($password);
        $this->getService('fos_user.user_manager')->updateUser($user);
        $this->getService('app_general.services.user_email_service')->sendNewAdminUserEmail($user, $password);
    }

    /**
     * {@inheritdoc}
     *
     * @param UserInterface $user
     */
    public function preRemove($object)
    {
        // logged user can not delete own account
        if ($this->getService('security.context')->getToken()->getUser()->getId() == $object->getId()) {
            throw new ModelManagerException("You can not delete own account");
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  string        $name
     * @param  UserInterface $user
     * @return boolean
     */
    public function isGranted($name, $object = null)
    {
        return $this->getService('security.context')->isGranted(User::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getUniqid()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with("General")
                ->add("name")
                ->add("email")
            ->end()
            ->with('Permissions')
                ->add('locked')
                ->add(
                    "enabled",
                    null,
                    array(
                        'label' => 'Status',
                        'template' => 'AppBackendBundle:CRUD:show_status.html.twig'
                    )
                )
                ->add("singleRoleName", null, array("label" => "Role"))
                ->add("groups", null, array('template' => 'AppBackendBundle:UserAdmin:show_groups.html.twig'))
            ->end();
    }

    /**
     * {@inheritdoc}
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $groupsParams = array("expanded" => false, "multiple" => true, "property" => "name", "required" => false);
        $roleParams = array(
            'label' => 'Role',
            'empty_value' => $this->getEmptySelectValue()
        );
        $formMapper
            ->with('General')
                ->add("firstname")
                ->add("lastname")
                ->add("email", null, array('attr' => array('autocomplete' => 'off')))
            ->end()
            ->with('Permissions')
                ->add("enabled", null, array("required" => false, "label" => "Active"))
                ->add("singleRole", 'app_backend_form_user_single_role_type', $roleParams)
                ->add("groups", null, $groupsParams)
            ->end();
    }

    /**
     * {@inheritdoc}
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $groupParams = array("label" => "Groups", "template" => "AppBackendBundle:UserAdmin:list_groups.html.twig");
        $enabledParams = array("label" => "Status");

        $listMapper
            ->addIdentifier("name")
            ->add("email")
            ->add("singleRoleName", null, array("label" => "Role"))
            ->add("groups", null, $groupParams)
            ->add("enabled", null, $enabledParams)
            ->add('lastLogin', null, array('label' => 'Last login'))
            ->add('_action', 'actions', array('actions' => $this->getActions(true)));
    }

    /**
     * {@inheritdoc}
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $rolesParams = array(
            'field_type' => 'app_backend_form_user_single_role_type',
            'label' => 'Role',
            'empty_value' => $this->getEmptySelectValue(),
            'field_options' => array(
                'choices' => User::$userRoles,
            )
        );
        $enabledParams = array(
            'label' => 'Status',
            'field_options' => array(
                'choices' => array(
                    '' => '',
                    BooleanType::TYPE_NO => $this->trans('Inactive', array(), null),
                    BooleanType::TYPE_YES => $this->trans('Active', array(), null)
                )
            )
        );
        $datagridMapper
            ->add(
                "name",
                "doctrine_orm_callback",
                array(
                    'callback' =>
                        function ($queryBuilder, $alias, $field, $value) {
                            if (!$value || $value['value'] == "") {
                                return;
                            }
                            $queryBuilder->orWhere($alias.'.firstname LIKE :name');
                            $queryBuilder->orWhere($alias.'.lastname LIKE :name');
                            $queryBuilder->setParameter('name', '%'.$value['value'].'%');

                            return true;
                        }
                    )
            )
            ->add("enabled", null, $enabledParams)
            ->add('roles', null, $rolesParams)
            ->add("email");
    }
}
