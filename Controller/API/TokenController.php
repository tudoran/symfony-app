<?php

namespace BackOfficeBundle\Controller\API;

use BackOfficeBundle\Controller\BackOfficeBaseController;
use BackOfficeBundle\Entity\PostClick;
use BackOfficeBundle\Entity\Token;
use BackOfficeBundle\Helper\BaseHelper;
use Firebase\JWT\JWT;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StampsBundle\StampsClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

/**
 * Class TokenController
 * @package BackOfficeBundle\Controller\API
 */
class TokenController extends BackOfficeBaseController
{

    public $errors;

    public $content;

    public $formatContent;

    public $customer;

    public $version;

    /**
     * @Route("/api/{version}/token/")
     * @Method("POST")
     * @param Request $request
     * @param $version
     * @return Response
     */
    public function postToken(Request $request , $version = 'v.1.0')
    {
        try {

            $this->request = $request;
            $this->version = $version;
            $this->response = new JsonResponse;


            if(empty($this->request->get('post_id'))){

                $this->errors[] = $postIdError = 'post_id must be provided';

                throw new \Exception($postIdError);
            }

            $products = $this->get('back_office.repository.product_repository');
            $products->findOneByPostOrFail($this->request->get('post_id'));

            if($this->getParameter('ima_api_should_check_destination')) {
                // $stampsClient = new StampsClient();
                // $stampsClient->setIntegrationId($this->getParameter('ima_api_stamps_id'));
                // $stampsClient->setUserId($this->getParameter('ima_api_stamps_user'));
                // $stampsClient->setPassword($this->getParameter('ima_api_stamps_password'));

                $usps_key = $this->getParameter('ima_api_usps_key');
                $this->content = $this->getCustomer($request)->getDestination();
            } else {
                $this->content = $this->getCustomer($request);
            }

            // one user can redeem only one product in a month
            $email = $this->request->get('email');
            $street_address = $this->request->get('street_address');
            $prev_token = $this->get('back_office.repository.token')
                ->findOneBy(
                  array(
                    'email' => $email,
                    'streetAddress' => $street_address,
                    'active' => 0
                  ),
                  array('id' => 'DESC')
                );
            if ($prev_token) {
              $firstDayOfThisMonth = new DateTime();
              $firstDayOfThisMonth->setDate(date('Y'), date('n'), 1);
              $firstDayOfThisMonth->setTime(0, 0, 0);
              if ($prev_token->getModifiedAt() >= $firstDayOfThisMonth) {
                // this user has already redeemed a product this month so should not be allowed to redeem again in the same month
                $this->content = ['token' => '', 'error' => 'This user has already redeemed a product this month.'];
                return $this->response->setStatusCode(Response::HTTP_FORBIDDEN)->setContent(BaseHelper::serialize($this->content));
              }
            }

            $ip = $this->request->get('client_ip');
            if (!$ip) {
                $ip = $this->request->getClientIp();
            }

            $tokens  = $this->get('back_office.repository.token');
            $token   = $tokens->save($this->request, $ip);

            // update token with pk
            $token->setToken(JWT::encode(['id' => $token->getId(), 'post_id' => $token->getPostId()], $this->getParameter('jwt_verification_signature')));
            $this->em()->flush();

            $this->content = ['token' => $token->getToken()];

            return $this->response->setStatusCode(Response::HTTP_OK)->setContent(BaseHelper::serialize($this->content));

        }catch(\Exception $e) {

            $this->content = ['type' => $e->getMessage(), 'error' => true, 'line' => $e->getLine(), 'file' => $e->getFile(), 'stack' => $this->errors];

            return $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->setContent(BaseHelper::serialize($this->content));
        }
//        finally {
//
//            $this->formatContent = BaseHelper::serialize($this->content);
//
//            return $this->response->setContent($this->formatContent);
//        }
    }

    /**
     * @Route("/api/{version}/token/{token}/{payload}/", defaults={"token" = "", "payload" = "" })
     * @Method("GET")
     * @param Request $request
     * @param $version
     * @param $token
     * @param $payload
     * @return Response
     */
    public function showToken(Request $request, $version = 'v.1.0', $token = '', $payload = ''){

        try{

            $this->version = $version;
            $this->request  = $request;
            $this->response = new JsonResponse;
            $tokens = $this->get('back_office.repository.token');

            if(!($this->request->get('verify') === 't' || $this->request->get('dump') === 't')){

                $this->errors[] = $errorActionToken = 'Unable to process your request. Expected one of GET{verify,dump}';

                throw new \Exception($errorActionToken);
            }

            $decoded = $this->decodeToken($token, $this->getParameter('jwt_verification_signature'));
            $token   = $tokens->find($decoded->id);

            if(!$token instanceof Token) {

                $this->errors[] = $errorFoundToken = 'Token not found';

                throw new \Exception($errorFoundToken);
            }

            if(!empty($token->getConfirmedBy())){
// echo $token->getConfirmedBy();
                if($token->getConfirmedBy() != $payload) {

                    $this->errors[] = $errorFoundToken = 'Token should not be shared';

                    throw new \Exception($errorFoundToken);
                }
            }

            if(!(bool)$token->getActive()) {

                $this->errors [] = $errorActiveToken = 'Token has been redeemed and is no longer valid';

                throw new \Exception($errorActiveToken);
            }

            if($this->request->get('dump') === 't'){


                $data = (array)json_decode($token->getFormData());

                $decoded = [];

                $decoded['product'] = [];
                $decoded['product']['id'] = $data['post_id'];
                $decoded['customerShippingAddress']['customer'] = [];
                $decoded['customerShippingAddress']['customer']['first_name'] = $data['first_name'];
                $decoded['customerShippingAddress']['customer']['last_name'] = $data['last_name'];
                $decoded['customerShippingAddress']['street_address'] =
                    (isset($data['street_address'])) ? $data['street_address'] : '';

                $decoded['customerShippingAddress']['city'] = $data['city'];
                $decoded['customerShippingAddress']['state'] = $data['state'];
                $decoded['customerShippingAddress']['zip_code'] =
                    (isset($data['zip_code'])) ? $data['zip_code'] : '';
                $decoded['customerShippingAddress']['customer']['email'] = $data['email'];

                $decoded['payload'] = $payload; // = time();

                $this->content = $decoded;

                // confirm and do not share
                if(empty($token->getConfirmedBy()))
			      $token->setConfirmedBy($payload);

	              $this->em()->persist($token);
                  $this->em()->flush();
                } else {
                  $this->content = ['token' => $token->getToken(), 'valid' => true, 'message' => 'Token has been successfully validated'];
                }

            $this->response->setStatusCode(Response::HTTP_OK);

        }catch(\Exception $e){

            $this->content = ['error' => true, 'line' => $e->getLine(), 'file' => $e->getFile(), 'type' => $e->getMessage(), 'stack' => $this->errors];

            $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        } finally {

            $this->formatContent = BaseHelper::serialize($this->content);

            return $this->response->setContent($this->formatContent);
        }

    }

    /**
     * Post click stats
     *
     * @Route("/api/{version}/post/{post}/", defaults={"post" = "0"})
     * @Method("POST")
     * @param Request $request
     * @param $version
     * @param $post
     * @return Response
     */
    public function postClick(Request $request, $version, $post)
    {
        try{

            $this->request = $request;
            $this->version = $version;
            $this->response = new Response;

            $post = $this->getProductPostById($post);

            if(!$post)
                throw new \Exception('Post does not exist or has been deleted');

            $postClick = $this->em()->getRepository('BackOfficeBundle:PostClick')->findOneBy(['post'=>$post->getId()]);

            if(!$postClick instanceof PostClick){
                $postClick = new PostClick;
                $postClick->setModifiedAt(new \DateTime());
                $postClick->setTotal(1);
                $postClick->setPost($post);
            } else{

                $postClick->setTotal($postClick->getTotal()+1);
            }

            $this->em()->persist($postClick);
            $this->em()->flush();

            $this->content = ['post' => $postClick->getPost()->getId(), 'total' => $postClick->getTotal()];

        }catch(\Exception $e){

            $this->content = ['error' => true, 'line' => $e->getLine(), 'file' => $e->getFile(), 'type' => $e->getMessage(), 'stack' => $this->errors];

            $this->response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        }finally{

            $this->formatContent = BaseHelper::serialize($this->content);

            return $this->response->setContent($this->formatContent);
        }
    }

    /**
     * Decode token
     *
     * @param string $token
     * @param string $signature
     * @param array $algo
     * @return object
     * @throws \Exception
     */
    public function decodeToken($token = '', $signature = '', $algo = ['HS256']){

        try {

            return JWT::decode($token, $signature, $algo);

        } catch(\Exception $e){

            $this->errors[] = $e->getMessage();

            throw new \Exception('JSON web token decode error:');
        }
    }

    /**
     * Create a customer instance for token
     *
     * @param Request $request
     * @return \BackOfficeBundle\Entity\Customer
     */
    public function getCustomer(Request $request)
    {

        $this->request = $request;
        $customers = $this->get('back_office.repository.customer');

        $customer = $customers->create();
        $customer->setStreetAddress($request->get('street_address'));
        $customer->setCity($request->get('city'));
        $customer->setState($request->get('state'));
        $customer->setZipCode($request->get('zip_code'));

        return $customer;
    }

}
