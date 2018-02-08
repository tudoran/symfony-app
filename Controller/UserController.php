<?php

namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\User;
use BackOfficeBundle\Entity\UserActions;
use BackOfficeBundle\Exceptions\BackOfficeBaseException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Regex;

final class UserController extends BackOfficeBaseController
{

    /**
     * User Controller constructor.
     */
    public function __construct()
    {

        parent::__construct();

        $this->routeInsert = 'back_office_users_save';

        $this->routeUpdate = 'back_office_users_update';

        $this->gridPath = 'back_office_users_grid';
    }

    /**
     * Show create form
     *
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        try {

            $this->hasAdministratorAccess();

            $this->request = $request;

            return $this->render('BackOfficeBundle:Users:register.html.twig', $this->getTemplateData());

        } catch (\Exception $e) {

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Process create user
     * @param Request $request
     * @return mixed
     */
    public function saveAction(Request $request)
    {

        try {

            $this->request = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            try {

                /**
                 * Flash validation messages
                 */

                if (empty($this->request->get('user_first_name')))
                    $this->request->getSession()->getFlashBag()->add('error', 'User First Name is required');

                if (empty($this->request->get('user_last_name')))
                    $this->request->getSession()->getFlashBag()->add('error', 'User Last Name  is required');

                if (empty($this->request->get('user_email')))
                    $this->request->getSession()->getFlashBag()->add('error', 'User Email is required');

                if (empty($this->request->get('user_password')))
                    $this->request->getSession()->getFlashBag()->add('error', 'User Password is required');

                if (empty($this->request->get('retype_user_password')))
                    $this->request->getSession()->getFlashBag()->add('error', 'Retype User Password is required');

                if (empty($this->request->get('user_role_id')))
                    $this->request->getSession()->getFlashBag()->add('error', 'User Role is required');

                /**
                 * If there are not required errors
                 * Continue validating input formats
                 */
                if (!$this->request->getSession()->getFlashBag()->has('error')) {

                    $this->validator = $this->get('validator');

                    $fNameContainsErrors = $this->validator->validate($this->request->get('user_first_name'), new Regex(['pattern' => '/^[a-zA-Za0-9\s_-]+$/']));
                    $lNameContainsErrors = $this->validator->validate($this->request->get('user_last_name'), new Regex(['pattern' => '/^[a-zA-Za0-9\s_-]+$/']));
                    $emailContainsErrors = $this->validator->validate($this->request->get('user_email'), new Email());
                    $reTypePassword = $this->validator->validate($this->request->get('retype_user_password'), new IdenticalTo(['value' => $this->request->get('user_password')]));

                    if ($fNameContainsErrors->count()) {
                        $this->request->getSession()->getFlashBag()->add('error',
                            'First name is not valid :' . $fNameContainsErrors->get(0)->getInvalidValue());

                        $this->request->getSession()->getFlashBag()->add('error', 'First name name allowed characters are case insensitive: a-z, digits: 0-9, whitespace, dash and underscore.');
                    }

                    if ($lNameContainsErrors->count()) {
                        $this->request->getSession()->getFlashBag()->add('error',
                            'First name is not valid :' . $lNameContainsErrors->get(0)->getInvalidValue());

                        $this->request->getSession()->getFlashBag()->add('error', 'Last name name allowed characters are case insensitive: a-z, digits: 0-9, whitespace, dash and underscore.');
                    }

                    if ($emailContainsErrors->count()) {
                        $this->request->getSession()->getFlashBag()->add('error',
                            'User Email is not valid: ' . $emailContainsErrors->get(0)->getInvalidValue());

                        $this->request->getSession()->getFlashBag()->add('error', 'If an email is specified it should be a valid email address.');
                    }

                    if ($reTypePassword->count()) {

                        $this->request->getSession()->getFlashBag()->add('error',
                            'Passwords does not match');
                    }

                }

                /**
                 * Redirect if errors
                 */
                if ($this->request->getSession()->getFlashBag()->has('error')) {
                    return $this->render('BackOfficeBundle:Users:register.html.twig',
                        $this->getTemplateData($this->request->get('user_id')));
                }

                /**
                 * Attempt to save
                 * Parse and display database error
                 * On success reset the form and show confirmation message
                 */

                $user = (empty($this->request->get('user_id'))) ? new User($this->get('doctrine.orm.entity_manager'), new ClassMetadata(User::class)) :
                    $this->em()->find('BackOfficeBundle:User', $this->request->get('user_id'));

                $user->setFirstName($this->request->get('user_first_name'));
                $user->setLastName($this->request->get('user_last_name'));
                $user->setUsername($this->request->get('user_email'));
                $user->setEmail($this->request->get('user_email'));
                $user->setPassword(password_hash($this->request->get('user_password'), PASSWORD_DEFAULT));
                $user->setActive(1);
                $user->setUserRole($this->getUserRoleById($this->request->get('user_role_id')));
                $user->setModifiedAt(new \DateTime());
                $user->setModifiedBy($this->getLoggedUser());

                $this->em()->persist($user);

                /**
                 * Allowed actions across system
                 */
                if ((int)$this->request->get('user_role_id') === 1) // IMA super admin
                    $this->persistUserActions($user, range(1, 6));
                if ((int)$this->request->get('user_role_id') === 2) // IMA admin
                    $this->persistUserActions($user, range(1, 3));
                if ((int)$this->request->get('user_role_id') === 3) // IMA read only
                    $this->persistUserActions($user, range(1, 2));


                $this->em()->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'User information has been successfully saved.');

                return $this->redirectToRoute('back_office_users_show',
                    ['id' => $user->getId(), 'status' => Response::HTTP_OK, 'time' => $user->getModifiedAt()->getTimestamp(), 'load' => 0]);


            } catch (DriverException $e) {

                throw new \Exception($e->getPrevious()->getMessage());
            }

        } catch (\Exception $e) {

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Users:register.html.twig', $this->getTemplateData());
        }
    }

    public function persistUserActions(User $user = null, array $actions = null)
    {

        try {

            foreach ($actions as $action):
                $userActions = new UserActions;
                $userActions->setUser($user);
                $userActions->setAction($this->getActionById($action));
                $userActions->setActive(1);
                $userActions->setModifiedBy($this->getLoggedUser());
                $userActions->setModifiedAt(new \DateTime());
                $this->em()->persist($userActions);
            endforeach;

        } catch (DriverException $e) {

            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showAction(Request $request, $id)
    {

        try {

            $this->hasAdministratorAccess();

            $this->request = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            $user = $this->em()->find('BackOfficeBundle:User', $id);

            if (!$user instanceof User)
                throw new BackOfficeBaseException(sprintf('User %s not found', $id));

            if (1 === intval($id))
                throw new BackOfficeBaseException('Security Context Firewall: super admin user is not editable within this context.');

            $this->entity = $user;

            return $this->render('BackOfficeBundle:Users:register.html.twig', $this->getTemplateData($id));

        } catch (BackOfficeBaseException $e) {


            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Users:register.html.twig', $this->getTemplateData($id));
        } catch (\Exception $e) {

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Show grid
     *
     * @return Response
     */
    public function gridAction()
    {

        try {

            $this->hasAdministratorAccess();

            return $this->render('BackOfficeBundle:Users:grid.html.twig', $this->getTemplateData());

        } catch (\Exception $e) {

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Show grid
     *
     * @param   Request $request
     * @return Response
     */
    public function postsAction(Request $request)
    {

        $this->request = $request;

        return $this->render('BackOfficeBundle:Users:grid.posts.html.twig', ['id' => $this->request->get('id')]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws BackOfficeBaseException
     * @throws \Exception
     */
    public function deleteAction(Request $request)
    {

        try {

            $this->hasWriteAccess();

            $this->request = $request;

            $em = $this->get('doctrine.orm.entity_manager');
            $this->gridPath = 'back_office_users_grid';


            if (empty($this->request->get('_archive_selected_elements')))
                throw new BackOfficeBaseException('missing_data');

            $toArchive = explode(',', $this->request->get('_archive_selected_elements'));

            for ($j = 0; $j < count($toArchive); $j++) {

                $users = $this->get('back_office.repository.user');

                $user = $users->find($toArchive[$j]);

                $user->setActive(0);
                $em->persist($user);
                $em->flush();
            }

            return $this->redirectToRoute($this->gridPath, ['_status' => Response::HTTP_OK]);

        } catch (\Exception $e) {

            return $this->createNotAuthorizedResource($e);
        }

    }
}
