<?php namespace BackOfficeBundle\Controller;

use BackOfficeBundle\Entity\Post;
use BackOfficeBundle\Entity\Product;
use BackOfficeBundle\Exceptions\BackOfficeBaseException;
use BackOfficeBundle\Helper\BaseHelper;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package BackOfficeBundle\Controller
 */
final class ProductController extends BackOfficeBaseController
{
    public function __construct(){


        parent::__construct();

        $this->routeInsert  = 'back_office_products_save';

        $this->routeUpdate  = 'back_office_products_update';

        $this->gridPath     = 'back_office_products_grid';
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

            $this->hasWriteAccess();

            $this->request = $request;

            return $this->render('BackOfficeBundle:Products:register.html.twig', $this->getTemplateData());

        }catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     *
     * Process create product
     *
     * @param Request $request
     * @param int     $id
     * @return mixed
     */
    public function saveAction(Request $request, $id = 0){

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
                if (empty($this->request->get('product_name')) || strlen(trim($this->request->get('product_name'))) === 0)
                    $this->request->getSession()->getFlashBag()->add('error', 'Product Name is required');

                if (empty($this->request->get('product_vendor_id')))
                    $this->request->getSession()->getFlashBag()->add('error', 'Product Vendor is required');

                /**
                 * Redirect if errors
                 */
                if ($this->request->getSession()->getFlashBag()->has('error'))
                    return $this->render('BackOfficeBundle:Products:register.html.twig',
                        $this->getTemplateData($this->request->get('product_id')));


                /**
                 * Attempt to save
                 * Parse and display database error
                 * On success reset the form and show confirmation message
                 */
                $product = $this->em()->find('BackOfficeBundle:Product', $id) ?: new Product;

                $product->setName($this->sanitize($this->request->get('product_name')));
                $product->setDescription($this->sanitize($this->request->get('product_description')));
                $product->setVendor($this->getVendorById($this->request->get('product_vendor_id')));
                $product->setTotal(intval($this->request->get('product_total')));
                $product->setThreshold(intval($this->request->get('product_threshold')));
                $product->setSku($this->request->get('product_sku'));
                $product->setVendorLink($this->request->get('product_vendor_link'));
                $product->setWeight($this->request->get('product_weight'));
                $product->setWeightMeasure($this->request->get('product_weight_measure'));
                $product->setModifiedAt(new \DateTime());
                $product->setModifiedBy($this->getLoggedUser());

                $this->validator = $this->get('validator');
                $violations = $this->validator->validate($product);

                if($violations->count()){

                    foreach($violations as $violation)
                        $this->request->getSession()->getFlashBag()->add('error', $violation->getMessage());


                    return $this->render('BackOfficeBundle:Products:register.html.twig',
                        $this->getTemplateData($this->request->get('product_purchase_order_id')));
                }

                $this->em()->persist($product);
                $this->em()->flush();

                $this->request->getSession()->getFlashBag()->add('success', 'Product information has been successfully saved.');

                return $this->redirectToRoute('back_office_products',
                    ['status' => Response::HTTP_OK , 'time' => $product->getModifiedAt()->getTimestamp(), 'load' => BaseHelper::peakUsage()]);

            } catch (DriverException $e) {

                $this->hasDuplicatedEntry($e->getPrevious()->getMessage());
            }

        } catch(\Exception $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Products:register.html.twig',$this->getTemplateData());
        }
    }


    /**
     * Delete product post
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws BackOfficeBaseException
     */
    public function postDeleteAction(Request $request, $id)
    {
        try {

            $this->hasWriteAccess();

            $this->request = $request;

            $this->gridPath = 'back_office_product_posts';

            $productId = $id;

            if(empty($this->request->get('_archive_selected_elements')))
                throw new BackOfficeBaseException('missing_data');

            $toArchive = explode(',', $this->request->get('_archive_selected_elements'));

            try {

                foreach ($toArchive as $id):

                    $post = $this->getProductPostById($id);

                    if (!$post instanceof Post)
                        throw new BackOfficeBaseException(sprintf('Post %s not found', $id));

                    $post->setActive(0);
                    $post->setModifiedAt(new \DateTime);
                    $post->setModifiedBy($this->getLoggedUser());

                    $this->em()->persist($post);

                endforeach;

                $this->em()->flush();

                return $this->redirectToRoute($this->gridPath,
                    ['id' => $productId, '_status' => Response::HTTP_OK, '_load' => BaseHelper::peakUsage()]);

            }catch(DriverException $e){

                return $this->redirectToRoute($this->gridPath,
                    ['id' => $productId, '_status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'error' => $e->getPrevious()->getMessage()]);
            }

        }catch(BackOfficeBaseException $e){

            return $this->_redirectToRoute($this->gridPath,
                ['id' => $productId, '_status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'error' => $e->getMessage()]);

        }catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Delete product
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws BackOfficeBaseException
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

                    $product = $this->getProductById($id);

                    if (!$product instanceof Product)
                        throw new BackOfficeBaseException(sprintf('Vendor %s not found', $id));

                    $product->setActive(0);
                    $product->setModifiedAt(new \DateTime);
                    $product->setModifiedBy($this->getLoggedUser());

                    $this->em()->persist($product);

                endforeach;

                $this->em()->flush();

                return $this->_redirectToRoute(Response::HTTP_OK, ['_load' => BaseHelper::peakUsage()]);

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
     *
     * Display a product
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */
    public function showAction(Request $request, $id){

        try {

            $this->hasWriteAccess();

            $this->request  = $request;
            $this->response = new Response();
            $this->request->getSession()->getFlashBag()->clear();

            $product = $this->getProductById($id);

            if (!$product instanceof Product)
                throw new BackOfficeBaseException(sprintf('Product %s not found', $id));

            $this->entity = $product;

            return $this->render('BackOfficeBundle:Products:register.html.twig', $this->getTemplateData($id));

        } catch(BackOfficeBaseException $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Products:register.html.twig', $this->getTemplateData($id));

        } catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }
    }

    /**
     * Get logged user
     * @return \BackOfficeBundle\Entity\User|null|object
     */
    public function getLoggedUser(){

        return $this->em()->getRepository('BackOfficeBundle:User')->find($this->getUser()->getId());
    }

    /**
     * Show grid table
     * @return Response
     */
    public function gridAction(){

        return $this->render('BackOfficeBundle:Products:grid.html.twig', $this->getTemplateData());
    }

    /**
     * Show grid table
     *
     * @param Request $request
     *
     * @return Response
     */
    public function vendorAction(Request $request, $id){

        try {

            $this->request = $request;

            $this->entity = $this->getVendorById($id);

            return $this->render('BackOfficeBundle:Products:vendor.grid.html.twig', $this->getTemplateData());

        }catch(\Exception $e){

            $this->request->getSession()->getFlashBag()->add('error', $e->getMessage());

            return $this->render('BackOfficeBundle:Products:vendor.grid.html.twig', $this->getTemplateData());
        }
    }

    public function vendorDeleteAction(Request $request, $id){

        try {

            $this->hasWriteAccess();

            $this->request = $request;

            $this->gridPath = 'back_office_products_vendor_grid';

            return $this->_delete(Product::class, 'getProductById', ['id' => $id]);

        } catch(\Exception $e){

            return $this->createNotAuthorizedResource($e);
        }

    }

    public function PostsAction(Request $request, $id){

        try{

            $this->request = $request;

            $product = $this->getProductById($id);

            if(!$product instanceof Product)
                throw new BackOfficeBaseException(sprintf('Product %s not found', $id));

            $this->entity = $product;

            return $this->render('BackOfficeBundle:Posts:grid.html.twig', $this->getTemplateData($id));

        }catch(\Exception $e){

            return $this->render('BackOfficeBundle:Posts:grid.html.twig', array_merge($this->getTemplateData(), ['error' => $e->getMessage()]));
        }
    }

    /**
     * === IMA API products section  ===
     *
     * Following methods leverage share and receive data from dependent sites such as samples.com
    **/


    /**
     * API method to retrieve a full list of products
     *
     * @param   Request $request
     * @param   string  $term   (optional)
     * @param   int     $limit  (optional)
     * @return  Response
     */
    public function apiShowAction(Request $request, $term = '', $limit = 10){

        try {
            $this->request = $request;
            $this->response = new Response;

            $term = (!empty($this->request->get('term'))) ? $this->request->get('term') : $term;
            $limit = (!empty($this->request->get('limit'))) ? $this->request->get('limit') : $limit;

            $dql = $this->em()->createQueryBuilder()->select('p.id, p.name, p.total AS stock, p.threshold')
                ->from('BackOfficeBundle:Product', 'p')
                ->where('p.active  = 1');

            /**
             * Add conditional search term
             * Regular expression match
             */
            if(!empty($term)){

                $dql->andWhere('REGEXP(p.name, :regexp) = 1');
                $dql->setParameter('regexp', $term);
            }

            /**
             * Add limit to the result set
             * Prevent memory exhaust issue
             */
            $dql->setMaxResults($limit);

            $results  = $dql->getQuery()->getResult();

            return $this->response->create(
                BaseHelper::serialize($results), RESPONSE::HTTP_OK);

        } catch(\Exception $e){

            return $this->response->create(BaseHelper::serialize(['error' => $e->getMessage()]), RESPONSE::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
     * API method to store a new product post
     *
     * @param Request $request
     * @return Response
     */
    public function apiSaveProductPostAction(Request $request){

        try {

            $this->request  = $request;
            $this->response = new Response;

            if(empty($this->request->get('product_id')))
                throw new \Exception('Product id parameter has not been received. Expected product_id, none given');

            $product = $this->getProductById($this->request->get('product_id'));

            if(!$product instanceof Product)
                throw new \Exception(sprintf('Product id %s does not exist', $this->request->get('product_id')));

            /**
             * TODO: convert to abstract method
             */
            $user = $this->em()->getRepository('BackOfficeBundle:User')->find(1);

            $post = $this->em()
                ->getRepository('BackOfficeBundle:Post')
                ->findOneBy([
                    'product' => $product,
                    'link' => $this->request->get('wp_post_link')
                ]);

            $post = $post ?: new Post;

            $post->setLink($this->request->get('wp_post_link'));
            $post->setProduct($product);
            $post->setActive(1);
            $product->setPosts($post);
            $post->setModifiedAt(new \DateTime());
            $post->setModifiedBy($user);

            $this->em()->persist($post);
            $this->em()->flush();

            return $this->response->create(
                BaseHelper::serialize(
                    ['error' => false,
                        'message' => sprintf('%s has been successfully registered for product %s', $post->getLink(), $product->getName()),]),
                RESPONSE::HTTP_OK);

        } catch(\Exception $e){

            return $this->response->create(
                BaseHelper::serialize(
                    ['error' => true, 'message' => sprintf('An error has occurred while attempting to save a product post: %s',  $e->getMessage())]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}