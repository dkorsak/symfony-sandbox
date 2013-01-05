<?php

namespace App\BackendBundle\Admin;

use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;
use App\GeneralBundle\Entity\User;
use App\GeneralBundle\Services\Mailer;

/**
 * Admin class for managing users
 * 
 */
class UserAdmin extends Admin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContent;

    /**
     * @var Mailer 
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $baseRoutePattern = 'users';

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC', // sort direction
        '_sort_by' => 'email' // field name
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
     * Set twig
     * 
     * @param \Twig_Environment $twig
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;    
    }

    /**
     * Get twig
     * 
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Set securityContext
     * 
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContent(SecurityContextInterface $securityContext)
    {
        $this->securityContent = $securityContext;
    }

    /**
     * Get securityContent
     * 
     * @return SecurityContextInterface
     */
    public function getSecurityContent()
    {
        return $this->securityContent;
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
        if ($this->securityContent->getToken()->getUser()->getId() == $object->getId()) {
            throw new ModelManagerException("You can not delete own account");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($name, $object = null)
    {
        return $this->securityContent->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchActions()
    {
        // disable delete batch action
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->with("General")
                ->add("name")
                ->add("email")
            ->end()
            ->with('Permissions')
                ->add("enabled", null, array("label" => "Status", 'template' => 'AppBackendBundle:CRUD:show_status.html.twig'))
                ->add("groups", null, array('template' => 'AppBackendBundle:UserAdmin:show_groups.html.twig'))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add("firstname")
                ->add("lastname")
                ->add("email")
            ->end()
            ->with('Permissions')
                ->add("enabled", null, array("required" => false, "label" => "Active"))
                ->add("groups", null, array("expanded" => false, "multiple" => true, "property" => "name"))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier("name")
            ->add("email")
            ->add("groups", null, array("label" => "Roles", "template" => "AppBackendBundle:UserAdmin:list_groups.html.twig"))
            ->add("enabled", null, array("label" => "Status", "template" => "AppBackendBundle:CRUD:list_status.html.twig"))
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
            ->add("enabled", null, array("label" => "Enabled"))
            ->add("email", null, array("label" => "Email"));
    }

    /**
     * Save user
     * 
     * @param User $user
     */
    private function saveUser(User $user) 
    {
        if (!$user->getId()) {
            $password = $this->generateRandomPassword();
            $user->setPlainPassword($password);
        }
        $user->setUsername($user->getEmail());
        $this->getUserManager()->updateUser($user);
        // send email with password
        if (isset($password)) {
            $body = $this->getTwig()->render("AppBackendBundle:Mail:create.account.html.twig", array("user" => $user, "password" => $password));
            $this->getMailer()->send($this->trans("Account was created"), $body, $user->getEmail());
        }
    }

    /**
     * Generate random user password
     * 
     * @return string
     */
    private function generateRandomPassword()
    {
        return substr(md5(time().rand(1, 10000)), 7, 8);
    }
}