<?php

namespace BackOfficeBundle\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request as Request;

class SandboxController extends BackOfficeBaseController
{

    /**
     * @var $data
     */
    private $data;

    /**
     * @var Client
     */
    private $client;

    private $errors;

    /**
     *
     * SandboxController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setup();
    }

    /**
     * Bootstrap form data
     */
    public function setup()
    {

        $this->data = new \stdClass;
        $this->data->first_name = null;
        $this->data->last_name = null;
        $this->data->person_id = null;
        $this->data->post_id = null;
        $this->data->traffic_source_id = null;
        $this->data->customer_name = null;
        $this->data->email = null;
        $this->data->city = null;
        $this->data->state = null;
        $this->data->phone = null;
        $this->data->zip_code = null;
        $this->data->formPost = null;
        $this->data->street_address = null;
        $this->data->utm = null;
        $this->data->ipv4 = null;
        $this->data->has_clean_product = null;
        $this->data->has_clean_email = null;
        $this->data->has_clean_street_address = null;
        $this->data->has_clean_traffic_source = null;
        $this->data->has_clean_utm = null;
        $this->data->has_clean_ipv4 = null;
        $this->data->errors = null;
    }

    /**
     *
     * @param \Request|Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function IndexAction(Request $request)
    {
        try {

            $this->request = $request;

            $this->getClient();

            $this->data->formPost = $this->getForm();

            return $this->renderForm();

        } catch (\Exception $e) {


            return $this->render('BackOfficeBundle:Sandbox:index.html.twig', ['errors' => $e->getMessage()]);
        }
    }

    /**
     * @return mixed
     */
    public function getPersonNames()
    {

        return $this->em()->getRepository('BackOfficeBundle:Customer')->findBy([], ['id' => 'DESC'], 10, 0);
    }

    /**
     * Form builder
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {

        return
            $this->createFormBuilder($this->data)
                ->add('first_name', TextType::class,
                    ['data' => $this->getFirstName()])
                ->add('last_name', TextType::class,
                    ['data' => $this->getLastName()])
                ->add('email', EmailType::class,
                    ['data' => $this->getEmail()])
                ->add('street_address', TextType::class,
                    ['data' => $this->getStreetAddress()])
                ->add('city', TextType::class,
                    ['data' => $this->getCity()])
                ->add('state', TextType::class,
                    ['data' => $this->getState()])
                ->add('zip_code', TextType::class,
                    ['data' => $this->getZipCode()])
                ->add('post_id', ChoiceType::class,
                    ['label' => 'Choose a post', 'data' => $this->getPost(), 'choices' =>
                        $this->formatProductPost($this->getPublishedProducts())])
                ->add('traffic_source_id', ChoiceType::class,
                    ['label' => 'Choose a traffic source', 'data' => $this->getTrafficSource(), 'choices' => $this->getTrafficSources()])
                ->add('save', SubmitType::class, ['label' => 'Generate Token'])
                ->setAction($this->generateUrl('back_office_sandbox_save'))
                ->setMethod('post')
                ->getForm();
    }

    public function getClient(){


        $this->client  = new Client(
            ['base_uri' => $this->container->getParameter('ima_api_validator_site')]);
    }
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {

        $this->errors = [];
        $this->request = $request;

        $this->getClient();

        $post = $this->getParameter('ima_api_validator_site_token');


        try {
            $response = $this->client->request('POST', $post, ['form_params' => $this->request->get('form')]);

            $contents = json_decode($response->getBody()->getContents());

            $redirect =  $this->container->getParameter('ima_api_authorize_site'). '?' . http_build_query(['token' => $contents->token]);

            return $this->redirect($redirect);

        }catch(RequestException $e){

            $response = json_decode($e->getResponse()->getBody()->getContents());

            $this->errors[] = property_exists($response, 'stack') ? $response->stack : [$e->getMessage()];

            $this->parseForm($this->request->get('form'));

            $this->data->formPost = $this->getForm();

            return $this->renderForm();
        }
    }

    /**
     * Format choices
     *
     * @param array|null $choices
     * @param array|null $output
     *
     * @return array
     */
    public function formatChoices(array $choices = null, array $output = null)
    {

        foreach ($choices as $choice)
            $output[ucwords($this->getDisplayValue($choice))] = $choice->id;

        return $output;
    }

    public function formatTrafficSourceChoices(array $choices = null, array $output = null)
    {

        foreach ($choices as $choice)
            $output[$choice->getName()] = $choice->getId();

        return $output;
    }

    public function formatProductPost(array $choices = null, array $output = null){

        foreach ($choices as $choice) :
            $output[$this->container->getParameter('ima_api_authorize_site') . '?' . http_build_query(['p' => $choice->link])] = $choice->link;

        endforeach;

        return $output;

    }
    /**
     * @param \Serializable $choice
     * @return string
     * @throws \Exception
     */
    public function getDisplayValue($choice)
    {

        return $choice->link;
    }

    /**
     * @return mixed
     */
    public function getFirstName(){

        return $this->data->first_name = $this->data->first_name ?: '';
    }

    /**
     * @return mixed
     */
    public function getLastName(){

        return $this->data->first_name = $this->data->last_name ?: '';
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {

        return $this->data->email = $this->data->email ?: '';
    }

    /**
     * @return mixed
     */
    public function getStreetAddress()
    {

        return $this->data->street_address = $this->data->street_address ?: '';
    }

    /**
     * @return mixed
     */
    public function getCity()
    {

        return $this->data->city = $this->data->city ?: '';
    }

    /**
     * @return mixed
     */
    public function getState()
    {

        return $this->data->state = $this->data->state ?: '';
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {

        return $this->data->zip_code = $this->data->zip_code ?: '';
    }

    /**
     * @return int
     */
    public function getPost()
    {

        return $this->data->post_id = is_null($this->data->post_id) ? 0 : $this->data->post_id;
    }

    /**
     * Get list of published products
     *
     * @return array
     */
    public function getPublishedProducts()
    {

        $request = $this->client->request('GET','product/post');

        return json_decode($request->getBody()->getContents());
    }

    /**
     * @return int
     */
    public function getTrafficSource()
    {

        return $this->data->traffic_source_id = is_null($this->data->traffic_source_id) ? 0 : $this->data->traffic_source_id;
    }

    public function getTrafficSources(){

        return $this->formatTrafficSourceChoices($this->em()->getRepository('BackOfficeBundle:TrafficSource')->findBy(['active' => 1]));
    }
    /**
     * @return string
     */
    public function getUTM()
    {

        return $this->data->utm = is_null($this->data->utm) ? $this->formatUTM(new \DateTime('now', new \DateTimeZone('UTC'))) : $this->data->utm;
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public function formatUTM(\DateTime $dateTime)
    {

        return $dateTime->format('Y-m-dTH:i:s');

    }

    /**
     * @return string
     */
    public function getIPv4()
    {

        return $this->data->ipv4 = is_null($this->data->ipv4) ? $this->request->getClientIp() : $this->data->ipv4;
    }

    /**
     * Render form
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderForm()
    {

        return $this->render('BackOfficeBundle:Sandbox:index.html.twig', [
            'form' => $this->data->formPost->createView(),
            'errors' => $this->errors
        ]);
    }

    /**
     * @param array|null $data
     * @return bool
     * @throws \Exception
     */
    public function parseForm(array $data = null)
    {

        if (is_null($data))
            throw new \Exception('Form data has not been received');

        foreach ($data as $key => $value)
            $this->data->{$key} = $value;

        return true;
    }

}
