<?php namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\Accounting;
use BackOfficeBundle\Entity\Actions;
use BackOfficeBundle\Entity\Customer;
use BackOfficeBundle\Entity\CustomerShippingAddress;
use BackOfficeBundle\Entity\Product;
use BackOfficeBundle\Entity\ProductHistory;
use BackOfficeBundle\Entity\User;
use BackOfficeBundle\Entity\UserRole;
use BackOfficeBundle\Exceptions\BackOfficeBaseException;
use BackOfficeBundle\Helper\BaseHelper;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BackOfficeBaseController extends Controller
{
    /**
     * Serialize under this format all dependent class
     */
    const DEFAULT_SERIALIZER = 'json';
    /**
     * Extended Class Controller Name
     *
     * @var
     */
    public $entity;
    /**
     * Route for inserting new products
     *
     * @var string $routeInsert
     */
    public $routeInsert;
    /**
     * Route for updating existing products
     *
     * @var string $routeUpdate
     */
    public $routeUpdate;
    /**
     * Controller grid path
     *
     * @var string $gridPath
     */
    public $gridPath;
    /**
     * Template Data
     *
     * @var $templateData
     */
    public $templateData;
    /**
     * Template post action
     *
     * @var string $postTo
     */
    public $postTo;
    /**
     * Response
     *
     * @var Response $response
     */
    public $response;
    /**
     * @var Request $request
     */
    public $request;
    /**
     * @var ValidatorInterface
     */
    public $validator;

    /**
     * Timer
     * @var mixed
     */
    public $timer;

    /**
     * Logger
     * @var
     */
    public $logger;

    public function __construct()
    {

        /**
         * Initialize Request
         */
        $this->request = Request::createFromGlobals();

        $this->timer    = microtime(1);
    }

    /**
     * Redirect to route
     *
     * @param int $code
     * @param array|null $opt
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function _redirectToRoute($code = 0, array $opt = []){

        return $this->redirectToRoute($this->gridPath, array_merge($opt,
            ['_status' => $code, '_load' => BaseHelper::peakUsage(), '_micro' => BaseHelper::microTimer($this->timer)]));

    }

    /**
     * Get logged user
     * @return \BackOfficeBundle\Entity\User|null|object
     */
    public function getLoggedUser()
    {

        return $this->em()->find('BackOfficeBundle:User', $this->getUser()->getId());
    }

    /**
     * Get user by id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getUserById($id)
    {

        return $this->em()->find('BackOfficeBundle:User', $id);
    }

    /**
     * Get Entitiy Manager
     *
     * @return ORM\em()
     */
    public function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     *  Create resource not authorized error page
     *
     * @param \Exception|null $e
     *
     * @return Response
     */
    public function createNotAuthorizedResource(\Exception $e = null)
    {

        return $this->render('BackOfficeBundle:Default:dashboard_layout.html.twig', array_merge($this->getTemplateData(), ['error' => $e->getMessage()]));
    }

    /**
     * Create product movement history
     *
     * @param Product|null $product
     * @param int $isDeliver
     * @param int $qty
     * @param string $reference
     * @return ProductHistory
     *
     * @throws \Exception
     */
    public function createProductHistory(Product $product = null, $isDeliver = 0, $qty = 0 , $reference = ''){

        if(is_null($product))
            throw new \Exception('Product is required to generate a log record');


        $productHistory = new ProductHistory;
        $productHistory->setProduct($product);
        $productHistory->setProductTotal($product->getTotal());
        $productHistory->setItems($qty);
        $productHistory->setDeliver($isDeliver);
        $productHistory->setProductOperationDescription($reference);
        $productHistory->setModifiedAt(new \DateTime);
        $productHistory->setModifiedBy($this->getLoggedUser());

        $this->em()->persist($productHistory);
        $this->em()->flush();

        return $productHistory;
    }


    /**
     * Delete resource
     *
     * @param string $traversableClassName
     * @param string|null $lookup
     * @param array $opt
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function _delete($traversableClassName = '', $lookup = '', $opt = []){

        try {

            if(empty($this->request->get('_archive_selected_elements')))
                throw new BackOfficeBaseException('missing_data');

            $traversable = new $traversableClassName;
            $toArchive = explode(',', $this->request->get('_archive_selected_elements'));

            try {

                for($j=0; $j < count($toArchive); $j++):

                    $id = $toArchive[$j];

                    $obj = $this->{$lookup}($id);

                    if (!$obj instanceof $traversable)
                        throw new BackOfficeBaseException(sprintf($traversableClassName .  ' %s not found', $id));

                    if($obj instanceof User)
                        $obj->eraseCredentials();

                    $obj->setActive(0);
                    $obj->setDeletedAt(new \DateTime);
                    $obj->setDeletedBy($this->getLoggedUser());

                    $this->em()->persist($obj);

                endfor;

                $this->em()->flush();

                return $this->_redirectToRoute(Response::HTTP_OK, $opt);

            }catch(DriverException $e){

                return$this->_redirectToRoute(Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getPrevious()->getMessage()]);
            }

        }catch(BackOfficeBaseException $e){

            return $this->_redirectToRoute(Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get user allowed actions
     *
     * @return array    $allowActions
     */
    public function getAllowedActions()
    {
        $allowActions = [];
        $isAllowedTo = $this->em()->getRepository('BackOfficeBundle:UserActions')->findBy(['user' => $this->getUser()->getId()]);

        foreach ($isAllowedTo as $allow)
            $allowActions[] = $allow->getAction()->getName();

        return $allowActions;
    }

    /**
     * Check create users access
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function hasAdministratorAccess(){

        if(!in_array('create', $this->getAllowedActions()))
            throw new \Exception('You are not authorized to access this resource');

        return true;
    }

    /**
     * Check write access
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function hasWriteAccess(){

        if(!in_array('write', $this->getAllowedActions()))
            throw new \Exception('You are not authorized to access this resource');

        return true;
    }

    /**
     * Swap duplicated entry error message
     *
     * @param string $message
     *
     * @throws \Exception
     */
    public function hasDuplicatedEntry($message = ''){

        if(preg_match('/'. BaseHelper::PDO_DUPLICATED_ENTRY.'/', $message))
            throw new \Exception('Oops! That vendor already exists.');

        throw new \Exception($message);
    }
    /**
     * Get Action By Id
     *
     * @param integer|null $id
     *
     * @return Actions
     */
    public function getActionById($id = null)
    {

        return $this->em()->find('BackOfficeBundle:Actions', $id);
    }

    /**
     * Get Accounting report By Id
     *
     * @param   $id
     *
     * @return  Accounting
     */
    public function getAccountingReportById($id)
    {

        return $this->em()->find('BackOfficeBundle:Accounting', $id);
    }

    /**
     * Get Product By Id
     *
     * @param   $id
     *
     * @return  Product
     */
    public function getProductById($id)
    {

        return $this->em()->find('BackOfficeBundle:Product', $id);
    }

    /**
     * Get product post by id
     *
     * @param $id
     * @return mixed
     */
    public function getProductPostById($id){

        return $this->em()->find('BackOfficeBundle:Post', $id);
    }

    /**
     * Get product purchase order by id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getProductPurchaseOrderById($id){

        return $this->em()->find('BackOfficeBundle:ProductPurchaseOrder', $id);
    }

    /**
     * Get user role by id
     *
     * @param $id
     *
     * @return UserRole
     */
    public function getUserRoleById($id)
    {

        return $this->em()->find('BackOfficeBundle:UserRole', $id);
    }

    /**
     * Get customer by id
     *
     * @param $id
     * @return mixed
     */
    public function getCustomerById($id){

        return $this->em()->find('BackOfficeBundle:Customer', $id);
    }

    /**
     * Get customer by email
     *
     * @param $email
     * @return mixed
     */
    public function getCustomerByEmail($email){

        return $this->em()->getRepository('BackOfficeBundle:Customer')->findOneBy(['email' => $email]);
    }

    /**
     * Get customer by address information
     *
     * @param string $streetAddress
     * @param string $city
     * @param string $state
     * @param string $zipCode
     * @return Customer|null
     * @throws \Exception
     */
    public function getCustomerByAddress($streetAddress = '', $city = '', $state = '', $zipCode = ''){

        $map = ['Street Address', 'City', 'State', 'Zip Code'];

        foreach(func_get_args() as $key=>$value){

            if(empty($value)){
                throw new \Exception(sprintf("%s cannot be empty" , $map[$key]));
            }
        }

        $customerAddress = $this->em()->getRepository('BackOfficeBundle:CustomerShippingAddress')
            ->findOneBy(['streetAddress' => $streetAddress, 'city' => $city, 'state' => $state, 'zipCode' => $zipCode]);

        if(!$customerAddress instanceof CustomerShippingAddress)
            return null;

        return $customerAddress->getCustomer();
    }
    /**
     * Get Carrier by id
     *
     * @param $id
     * @return mixed
     */
    public function getCarrierById($id)
    {

        return $this->em()->find('BackOfficeBundle:Carrier', $id);
    }

    /**
     *
     * Get Product Purchase status by Id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getProductPurchaseStatusById($id)
    {

        return $this->em()->find('BackOfficeBundle:ProductPurchaseStatus', $id);
    }

    /**
     * Get product purchase status by name
     * @param $name
     *
     * @return mixed
     */
    public function getProductPurchaseStatusByName($name){

        return $this->em()->getRepository('BackOfficeBundle:ProductPurchaseStatus')->findOneBy(['name' => $name]);
    }

    /**
     *
     * Get shipment by id
     *
     * @param $id
     * @return mixed
     */
    public function getShipmentById($id)
    {

        return $this->em()->find('BackOfficeBundle:Shipment', $id);
    }

    /**
     *
     * Get pending shipments
     *
     * @return mixed
     */
    public function getPendingShipments()
    {

        return $this->em()->getRepository('BackOfficeBundle:PendingShipment')->findBy(['active' => 1]);
    }

    /**
     * Get Vendors
     *
     * @return array|\BackOfficeBundle\Entity\Vendor[]
     */
    public function getVendors(){

        return $this->getDoctrine()->getRepository('BackOfficeBundle:Vendor')->findAll();
    }

    /**
     * Get Vendor by id
     *
     * @param   int     $id
     * @return  array|\BackOfficeBundle\Entity\Vendor[]
     */
    public function getVendorById($id){

        return $this->getDoctrine()->getRepository('BackOfficeBundle:Vendor')->find($id);
    }

    /**
     * Get open purchase orders (stock) count for a product
     * @param Product|null $product
     *
     * @return mixed
     */
    public function productHasOpenOrders(Product $product = null){

        $orders = $this->em()->createQueryBuilder()
            ->select('COUNT(o.id)')
            ->from('BackOfficeBundle:ProductPurchaseOrder', 'o')
            ->where('o.product = :product')
            ->andWhere('o.productPurchaseStatus = :new')
            ->andWhere('o.active = 1')
            ->setParameter(':product', $product)
            ->setParameter(':new', $this->getProductPurchaseStatusById(1))
            ->getQuery();

        return $orders->getSingleScalarResult();
    }

    /**
     * Parse template data
     *
     * @param int|null $id
     *
     * @return array|null
     */
    public function getTemplateData($id = null){

        return BaseHelper::getTemplateData($this, $this->get('router'), $id);
    }

    /**
     * Sanitize
     *
     * @param string|null $input
     *
     * @return string|null
     */
    public function sanitize($input = null){

        return BaseHelper::sanitize($input);
    }
}