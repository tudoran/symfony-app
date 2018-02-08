<?php namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\Vendor;
use BackOfficeBundle\Exceptions\BackOfficeBaseException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

final class VendorController extends BackOfficeBaseController
{

    /**
     * VendorController constructor.
     */
    public function __construct(){

        parent::__construct();

        $this->routeInsert  = 'back_office_vendors_save';

        $this->routeUpdate  = 'back_office_vendors_update';

        $this->gridPath     = 'back_office_vendors_grid';
    }

    /**
     * Show create form
     *
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        try{

            $this->request = $request;

            $data = $this->getTemplateData();

            if(!$data['user_can_write'])
                throw new \Exception('You are not authorized to access this resource');

            return $this->render('BackOfficeBundle:Vendors:register.html.twig', $data);

        }catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Process create vendor
     * @param Request $request
     * @return mixed
     */
    public function saveAction(Request $request){

        try {

            $this->hasWriteAccess();

            $this->request  = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            try {
                /**
                 * Flash validation messages
                 * Use empty for checking existence of values
                 * Use asserts for input formats
                 */
                if (empty($this->request->get('vendor_name')) || strlen(trim($this->request->get('vendor_name'))) === 0)
                    $this->request->getSession()->getFlashBag()->add('error', 'Vendor name is required');

                if (empty($this->request->get('vendor_url'))  || strlen(trim($this->request->get('vendor_url'))) === 0)
                    $this->request->getSession()->getFlashBag()->add('error', 'Vendor Website URL is required');


                /**
                 * If there are not required errors
                 * Continue validating input formats
                 */
                if(!$this->request->getSession()->getFlashBag()->has('error')) {

                    $this->validator = $this->get('validator');

                    $nameContainsErrors = $this->validator->validate($this->request->get('vendor_name'), new Regex(['pattern' => '/^[a-zA-Za0-9\s_-]+$/']));
                    $urlContainsErrors = $this->validator->validate($this->request->get('vendor_url'), new Url());
                    $emailContainsErrors = $this->validator->validate($this->request->get('vendor_email'), new Email());

                    if ($nameContainsErrors->count()) {
                        $this->request->getSession()->getFlashBag()->add('error',
                            'Vendor name is not valid :' . $nameContainsErrors->get(0)->getInvalidValue());

                        $this->request->getSession()->getFlashBag()->add('error', 'Vendor name allowed characters are case insensitive: a-z, digits: 0-9, whitespace, dash and underscore.');
                    }

                    if ($urlContainsErrors->count()) {
                        $this->request->getSession()->getFlashBag()->add('error',
                            'Vendor URL is not valid: ' . $urlContainsErrors->get(0)->getInvalidValue());

                        $this->request->getSession()->getFlashBag()->add('error', 'URL must start with http:// or https://');
                    }

                    if (!empty($this->request->get('vendor_email'))) {

                        if ($emailContainsErrors->count()) {
                            $this->request->getSession()->getFlashBag()->add('error',
                                'Vendor Email is not valid: ' . $emailContainsErrors->get(0)->getInvalidValue());

                            $this->request->getSession()->getFlashBag()->add('error', 'If an email is specified it should be a valid email address.');
                        }

                    }
                }

                /**
                 * Redirect if errors
                 */
                if ($this->request->getSession()->getFlashBag()->has('error')) {
                    return $this->render('BackOfficeBundle:Vendors:register.html.twig',
                        $this->getTemplateData($this->request->get('vendor_id')));
                }

                /**
                 * Attempt to save
                 * Parse and display database error
                 * On success reset the form and show confirmation message
                 */

                $vendor = (empty($this->request->get('vendor_id'))) ? new Vendor :
                    $this->em()->find('BackOfficeBundle:Vendor', $this->request->get('vendor_id'));

                /**
                 * Sanitize
                 * Before saving all strings
                 * Trim and replace multiple whitespaces by only one space
                 */

                $vendor->setName($this->sanitize($this->request->get('vendor_name')));
                $vendor->setEmail($this->sanitize($this->request->get('vendor_email')));
                $vendor->setUrl($this->sanitize($this->request->get('vendor_url')));
                $vendor->setModifiedAt(new \DateTime());
                $vendor->setModifiedBy($this->getLoggedUser());

                $this->em()->persist($vendor);
                $this->em()->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'Vendor information has been successfully saved.');

                $this->gridPath = 'back_office_vendors';
                return $this->_redirectToRoute(Response::HTTP_OK);

            } catch (DriverException $e) {

                $this->hasDuplicatedEntry($e->getPrevious()->getMessage());

            }

        } catch(\Exception $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Vendors:register.html.twig', $this->getTemplateData());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showAction(Request $request, $id){

        try {

            $this->hasWriteAccess();

            $this->request  = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            $vendor = $this->em()->find('BackOfficeBundle:Vendor', $id);

            if (!$vendor instanceof Vendor)
                throw new BackOfficeBaseException(sprintf('Vendor %s not found', $id));

            $this->entity = $vendor;

            return $this->render('BackOfficeBundle:Vendors:register.html.twig', $this->getTemplateData($id));

        }catch(BackOfficeBaseException $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Vendors:register.html.twig', $this->getTemplateData($id));

        }catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Archive vendors
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAction(Request $request)
    {
        try {

            $this->hasWriteAccess();

            $this->request = $request;

            if(empty($this->request->get('_archive_selected_elements')))
                throw new BackOfficeBaseException('missing_data');

            $toArchive = explode(',', $this->request->get('_archive_selected_elements'));

            try {

                foreach ($toArchive as $id):

                    $vendor = $this->getVendorById($id);

                    if (!$vendor instanceof Vendor)
                        throw new BackOfficeBaseException(sprintf('Vendor %s not found', $id));

                    $vendor->setActive('0');

                    $this->em()->persist($vendor);

                endforeach;

                $this->em()->flush();

                return $this->_redirectToRoute(Response::HTTP_OK);

            }catch(DriverException $e){

                return $this->_redirectToRoute(Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getPrevious()->getMessage()]);
            }

        }catch(BackOfficeBaseException $e){

            return $this->_redirectToRoute(Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getMessage()]);

        }catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Show grid
     * @return Response
     */
    public function gridAction(){

        return $this->render('BackOfficeBundle:Vendors:grid.html.twig', $this->getTemplateData());
    }

    /**
     * Show grid
     *
     * @param   Request     $request
     * @return Response
     */
    public function postsAction(Request $request){

        $this->request = $request;

        return $this->render('BackOfficeBundle:Vendors:grid.posts.html.twig', ['id' => $this->request->get('id')]);
    }
}
