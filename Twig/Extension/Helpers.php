<?php namespace  BackOfficeBundle\Twig\Extension;

use BackOfficeBundle\Entity\Product;
use BackOfficeBundle\Entity\Vendor;
use Doctrine\ORM\EntityManager;

class Helpers extends \Twig_Extension
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Helpers constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('user_roles', [$this, 'user_roles']),
            new \Twig_SimpleFunction('carriers', [$this, 'carriers']),
            new \Twig_SimpleFunction('vendors', [$this, 'vendors']),
            new \Twig_SimpleFunction('vendor_products', [$this, 'vendor_products']),
            new \Twig_SimpleFunction('vendor_posts', [$this, 'vendor_posts']),
            new \Twig_SimpleFunction('products', [$this, 'products']),
            new \Twig_SimpleFunction('product_order_status', [$this, 'product_order_status']),
            new \Twig_SimpleFunction('posts', [$this, 'posts']),
            new \Twig_SimpleFunction('orders', [$this, 'orders']),
            new \Twig_SimpleFunction('users', [$this, 'users']),
            new \Twig_SimpleFunction('customers', [$this, 'customers']),
            new \Twig_SimpleFunction('shipments', [$this, 'shipments']),
            new \Twig_SimpleFunction('shipped', [$this, 'shipped']),
            new \Twig_SimpleFunction('strpad', [$this, 'strpad']),
            new \Twig_SimpleFunction('csv_escape', [$this, 'csv_escape']),
        );
    }

    /**
     *
     * Get list of user roles
     *
     * @return array|\BackOfficeBundle\Entity\UserRole[]
     */
    public function user_roles(){
        return $this->entityManager->getRepository('BackOfficeBundle:UserRole')->findBy(['active' => 1]);
    }

    /**
     *
     * Get list of carriers
     * 
     * @return array|\BackOfficeBundle\Entity\Carrier[]
     */
    public function carriers(){
        return $this->entityManager->getRepository('BackOfficeBundle:Carrier')->findAll();
    }

    /**
     * @return array|\BackOfficeBundle\Entity\User[]
     */
    public function users()
    {
        return $this->entityManager->getRepository('BackOfficeBundle:User')->findBy(['active' => 1]);
    }

    /**
     * @return array|\BackOfficeBundle\Entity\Customer[]
     */
    public function customers()
    {
        return $this->entityManager->getRepository('BackOfficeBundle:Customer')->findAll();
    }

    /**
     * @return array|\BackOfficeBundle\Entity\Vendor[]
     */
    public function vendors()
    {
        return $this->entityManager->getRepository('BackOfficeBundle:Vendor')->findBy(['active' => 1]);
    }

    /**
     * @param $vendor
     * @return array|\BackOfficeBundle\Entity\Product[]
     */
    public function vendor_products($vendor){
        return $this->entityManager->getRepository('BackOfficeBundle:Product')->findBy(['vendor' => $vendor, 'active' => 1]);
    }

    /**
     * @param $vendor
     * @return array|\BackOfficeBundle\Entity\ProductPost[]
     */
    public function vendor_posts($vendor){
        return $this->entityManager->getRepository('BackOfficeBundle:ProductPost')->findBy(['vendor_id' => $vendor]);
    }

    /**
     * @return array|\BackOfficeBundle\Entity\Product[]
     */
    public function products(){
        return $this->entityManager->getRepository('BackOfficeBundle:Product')->findBy(['active' => 1]);
    }

    /**
     * @return array|\BackOfficeBundle\Entity\ProductPurchaseOrder[]
     */
    public function product_order_status(){
        return $this->entityManager->getRepository('BackOfficeBundle:ProductPurchaseStatus')->findAll();
    }

    /**
     * @param $product
     * @return array|\BackOfficeBundle\Entity\Post[]
     */
    public function posts($product){
        return $this->entityManager->getRepository('BackOfficeBundle:Post')->findBy(['product' => $product, 'active' => 1]);
    }

    /**
     * Mutator id string
     * @param $input
     * @return string
     */
    public function strpad($input){
        return (empty($input)) ? '########' : str_pad($input,8,'0', STR_PAD_LEFT);
    }

    /**
     * CSV-escape string
     * @param  string
     * @return string
     */
    public function csv_escape($value) {
        $shouldBeQuoted = FALSE;
        if (strpos($value, '"') !== FALSE) {
            $shouldBeQuoted = TRUE;
            $value = str_replace('"', '""', $value);
        }
        if (strpos($value, ',') !== FALSE) {
            $shouldBeQuoted = TRUE;
        }
        if ($shouldBeQuoted) {
            $value = '"' . $value . '"';
        }
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'reflection';
    }
}