<?php

namespace App\BackendBundle\Admin;

use App\GeneralBundle\Utils\StringUtil;
use Sonata\AdminBundle\Exception\ModelManagerException;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use App\GeneralBundle\Entity\User;
use App\GeneralBundle\Services\Mailer;
use Symfony\Component\Validator\Constraint;
use Sonata\AdminBundle\Form\Type\BooleanType;

/**
 * Admin class for managing users
 *
 */
class UserAdmin extends BaseAdmin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var string
     */
    protected $baseRoutePattern = 'users';

    /**
     * @var string
     */
    protected $baseRouteName = 'user';

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'email' // field name
    );

    /**
     * @var array
     */
    protected $formOptions = array(
        'validation_groups' => array('Admin user', Constraint::DEFAULT_GROUP)
    );

    /**
     * Set userManager
     *
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Get userManager
     *
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * Set mailer
     *
     * @param Mailer $mailer
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Get mailer
     *
     * @return Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->saveUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($user)
    {
        $this->saveUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove($object)
    {
        // logged user can not delete own account
        if ($this->getSecurityContext()->getToken()->getUser()->getId() == $object->getId()) {
            throw new ModelManagerException("You can not delete own account");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($name, $object = null)
    {
        return $this->getSecurityContext()->isGranted(User::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqid()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with("General")
                ->add("name")
                ->add("email")
            ->end()
            ->with('Permissions')
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
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $groupParams = array("label" => "Groups", "template" => "AppBackendBundle:UserAdmin:list_groups.html.twig");
        $enabledParams = array("label" => "Status", "template" => "AppBackendBundle:CRUD:list_status.html.twig");

        $listMapper
            ->addIdentifier("name")
            ->add("email")
            ->add("singleRoleName", null, array("label" => "Role"))
            ->add("groups", null, $groupParams)
            ->add("enabled", null, $enabledParams)
            ->add('lastLogin', null, array('label' => 'Last login'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'show' => array('template' => 'AppBackendBundle:CRUD:list__action_show.html.twig'),
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

    /**
     * Save user
     *
     * @param User $user
     */
    private function saveUser(User $user)
    {
        if (!$user->getId()) {
            $password = StringUtil::generateRandomPassword();
            $user->setPlainPassword($password);
        }
        $this->getUserManager()->updateUser($user);
        // send email with password
        if (isset($password)) {
            $template = "AppBackendBundle:Mail:create.account.html.twig";
            $body = $this->getTwig()->render($template, array("user" => $user, "password" => $password));
            $this->getMailer()->send($this->trans("Account was created"), $body, $user->getEmail());
        }
    }
}
