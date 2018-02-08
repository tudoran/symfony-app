<?php

namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\Customer;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use StampsBundle\StampsClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{

    /**
     * Route for inserting new products
     *
     * @static string  $routeInsert
     */
    static $routeInsert = 'back_office_customers_save';

    /**
     * Route for updating existing products
     *
     * @static string   $routeUpdate
     */
    static $routeUpdate = 'back_office_customers_update';


    /**
     * Controller customer information
     *
     * @var Customer     $customer
     */
    private $customer;

    /**
     * Template Data
     *
     * @var Array       $templateData
     */
    private $templateData;

    /**
     * Template post action
     *
     * @var string      $postTo
     */
    private $postTo;

    /**
     * Response
     *
     * @var Response    $response
     */
    private $response;
    /**
     * @var
     */
    private $request;
    /**
     * @var ParsePDOException
     */
    private $exceptionHelper;


    /**
     * User Controller constructor.
     */
    public function __construct(){


    }

    /**
     * Show create form
     *
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $this->request = $request;
        return $this->render('BackOfficeBundle:Customers:register.html.twig', $this->getTemplateData());
    }

    /**
     * Collect return parameters
     * Id in url determines the post back location
     * Eval If there is a product object that needs to be displayed in the form
     * If so, display it
     * If not, get the request data and display it
     * @param int $id
     * @return array
     */
    public function getTemplateData($id = null){


        $this->postTo = (is_numeric($id)) ?
            self::$routeUpdate : self::$routeInsert;

        $this->templateData = (!empty($this->customer)) ? (array)$this->customer : $this->request->request->all();

        /**
         * Start transforming the object from Entity to array
         */
        foreach((array)$this->templateData as $key => $value){
            $name = explode('\\', $key);
            $customerKey = preg_replace( "/[^a-z0-9 ]/i", "_", $name[count($name)-1]);
            $customerKey = preg_replace('/\B([A-Z])/', '_$1', $customerKey);
            $customerKey = strtolower($customerKey);
            $this->templateData[$customerKey] = $value;
        }

        $this->templateData['post'] = $this->generateUrl($this->postTo,
            (is_null($id)) ? ['id' => null] : ['id' => $id]);

        return $this->templateData;
    }

    /**
     * Process create customer
     * @param Request $request
     * @return mixed
     */
    public function saveAction(Request $request){

        try {

            $this->request  = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            try {

                /**
                 * Flash validation messages
                 */

                if (empty($this->request->get('customer_first_name')))
                    $this->request->getSession()->getFlashBag()->add('error', 'Customer First Name is required');

                if (empty($this->request->get('customer_last_name')))
                    $this->request->getSession()->getFlashBag()->add('error', 'Customer Last Name  is required');

                if (empty($this->request->get('customer_email')))
                    $this->request->getSession()->getFlashBag()->add('error', 'Customer Email is required');


                /**
                 * Redirect if errors
                 */
                if ($this->request->getSession()->getFlashBag()->has('error')) {
                    return $this->render('BackOfficeBundle:Customers:register.html.twig',
                        $this->getTemplateData($this->request->get('customer_id')));
                }

                /**
                 * Attempt to save
                 * Parse and display database error
                 * On success reset the form and show confirmation message
                 */

                $customer = (empty($this->request->get('customer_id'))) ? new Customer :
                    $this->em()->find('BackOfficeBundle:Customer', $this->request->get('customer_id'));

                $customer->setFirstName($this->request->get('customer_first_name'));
                $customer->setLastName($this->request->get('customer_last_name'));
                $customer->setEmail($this->request->get('customer_email'));
                $customer->setActive(1);
                $customer->setModifiedAt(new \DateTime());
                $customer->setModifiedBy($this->getLoggedUser());

                $this->em()->persist($customer);
                $this->em()->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'Customer information has been successfully saved.');

                return $this->redirectToRoute('back_office_customers_show',
                    ['id' => $customer->getId(), 'status' => Response::HTTP_OK , 'time' => $customer->getModifiedAt()->getTimestamp(), 'load' => 0]);


            } catch (UniqueConstraintViolationException $e) {

                $this->exceptionHelper->setMessage($e->getMessage());

                throw new \Exception($this->exceptionHelper->parse($e->getMessage()));
            }

        } catch(\Exception $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Customers:register.html.twig', $this->getTemplateData());
        }
    }

    /**
     * Get Entitiy Manager
     *
     * @return ORM\EntityManager
     */
    public function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Get logged user
     * @return \BackOfficeBundle\Entity\User|null|object
     */
    public function getLoggedUser(){

        return $this->em()->getRepository('BackOfficeBundle:User')->find($this->getUser()->getId());
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws CustomerException
     */
    public function showAction(Request $request, $id){

        try {

            $this->request  = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            $customer = $this->em()->find('BackOfficeBundle:Customer', $id);

            if (!$customer instanceof Customer)
                throw new CustomerException(sprintf('Customer %s not found', $id));

            $this->customer = $customer;

            return $this->render('BackOfficeBundle:Customers:register.html.twig', $this->getTemplateData($id));

        } catch(BackOfficeBaseException $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Customers:register.html.twig', $this->getTemplateData($id));
        }
    }

    /**
     * Show grid
     * @return Response
     */
    public function gridAction(){

        return $this->render('BackOfficeBundle:Customers:grid.html.twig');
    }

    /**
     * Show grid
     *
     * @param   Request     $request
     * @return Response
     */
    public function postsAction(Request $request){

        $this->request = $request;

        return $this->render('BackOfficeBundle:Customers:grid.posts.html.twig', ['id' => $this->request->get('id')]);
    }

    public function validateDestinationAction(Request $request){

        try {

            $this->request  = $request;
            $this->response = new Response;
            $client         = new StampsClient('0a922775-3187-4b7d-99d9-0ff672b6c282','ZeetoSam-001', 'postage1');

            $destination = [
                'street_address' => $this->request->get('street_address'),
                'city' => $this->request->get('city'),
                'state' =>  $this->request->get('state'),
                'zip_code' => $this->request->get('zip_code')
            ];

            $this->request = $request;
            $this->response = new JsonResponse;

            $customer = new Customer;

            if($customer->validateDestination($client, $destination))
                return $this->response->create('UPS valid address', Response::HTTP_OK);

            throw new \Exception('UPS invalid address');

        }catch(\Exception $e){

            return $this->response->create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
