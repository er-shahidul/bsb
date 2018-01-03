<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Datatables\UserDatatable;
use UserBundle\Entity\User;
use UserBundle\Form\UserForm;
use UserBundle\Form\UserUpdatePasswordForm;

/**
 * User Controller.
 *
 */
class UserController extends UserBaseController
{
    /**
     * @Route("/users", name="users_home", options={"expose"=true})
     * @Template()
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(UserDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
            $qb->andWhere($qb->expr()->neq('groups.name', ':superAdminGroup'));
            $qb->setParameter('superAdminGroup', 'Super Administrator');

            $qb->addOrderBy('user.id', 'ASC');
            if ($this->isOfficeAdmin() && $this->getOffice()) {
                $qb->andWhere($qb->expr()->eq('user.office', $this->getOffice()->getId()));
            }

        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@User/User/index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/user/create", name="user_create")
     * @Template("UserBundle:User:new.html.twig")
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        if (!$this->isGranted('ROLE_ADMIN')) {
            $user->setOffice($this->getOffice());
        }

        $form = $this->createForm(UserForm::class, $user, ['attr' => ['renderOffice' => $this->isGranted('ROLE_ADMIN')]]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setEnabled(true);
                $this->getDoctrine()->getRepository('UserBundle:User')->create($user);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('User Created Successfully!')
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'mode' => 'create',
            'user' => $user
        );
    }

    /**
     * @Route("/user/update/{id}", name="user_update", options={"expose"=true})
     * @Template("UserBundle:User:new.html.twig")
     * @param Request $request
     * @param User $user
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function updateAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted('access', $user);

        $service = $this->get('user.registration.form.type');
        $service->setLoginUser($this->getUser());
        $form = $this->createForm(UserForm::class, $user, ['attr' => ['renderOffice' => FALSE]]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->get('fos_user.user_manager')->updateUser($user);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'User Updated Successfully!'
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'mode' => 'edit',
            'user' => $user
        );
    }

    /**
     * @Route("/user/update/password/{id}", name="user_update_password", options={"expose"=true})
     * @Template("UserBundle:User:update.password.html.twig")
     * @param Request $request
     * @param User $user
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function updatePasswordAction(Request $request, User $user)
    {
        $form = $this->createForm(new UserUpdatePasswordForm(), $user);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setPassword($form->get('plainPassword')->getData());
                $user->setPlainPassword($form->get('plainPassword')->getData());

                $this->getDoctrine()->getRepository('UserBundle:User')->update($user);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    $this->get('translator')->trans('Password Successfully Change')
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/enabled/{id}", name="user_enabled", options={"expose"=true})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function userEnabledAction(User $user)
    {
        $enable = $this->isUserEnabled($user);
        $user->setEnabled($this->isUserEnabled($user));

        $this->getDoctrine()->getRepository('UserBundle:User')->update($user);

        $messageString = $enable ? $this->get('translator')->trans('User Successfully Enable') : $this->get('translator')->trans('User Successfully Disable');
        $this->get('session')->getFlashBag()->add(
            'success',
            $messageString
        );

        return $this->redirect($this->generateUrl('users_home'));
    }

    /**
     * @Route("/user/details/{id}", name="user_details", options={"expose"=true})
     * @Template()
     * @param User $user
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function detailsAction(User $user)
    {
        return $this->render('UserBundle:User:details.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", options={"expose"=true})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function deleteAction(User $user)
    {
        return $this->redirect($this->generateUrl('users_home'));
    }

    /**
     * @param User $user
     * @return int
     */
    protected function isUserEnabled(User $user)
    {
        if ($user->isEnabled()) {
            return false;
        } else {
            return true;
        }
    }
}