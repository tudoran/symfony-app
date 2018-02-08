<?php

namespace BackOfficeBundle\Helper;


use BackOfficeBundle\Controller\BackOfficeBaseController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class BaseHelper
{

    const PDO_DUPLICATED_ENTRY = 1062;

    const DEFAULT_SERIALIZER   = 'json';
    /**
     * BaseHelper constructor.
     * Not implemented by default
     */
    public function __construct()
    {

    }

    /**
     * Memory peak usage for this script
     * @return int
     */
    public static function peakUsage()
    {

        return memory_get_peak_usage(1);
    }

    /**
     * Get micro time difference
     *
     * @param int $dtStartAt
     *
     * @return float
     */
    public static function microTimer($dtStartAt = 0){

        return sprintf("%.4f", (float)microtime(1) - $dtStartAt);
    }

    /**
     * Serializer
     *
     * @param $data
     *
     * @param string $format
     * @return mixed|string
     */
    public static function serialize($data, $format = self::DEFAULT_SERIALIZER)
    {

        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();

        return
            $serializer->serialize($data, $format);
    }

    /**
     *
     * Trim and replace multiple whitespaces
     * @param $value
     *
     * @return mixed
     */
    public static function sanitize($value)
    {

        return preg_replace('/\s+/', ' ', trim($value));
    }


    /**
     * Parse template data
     *
     * @param BackOfficeBaseController $backOfficeBaseController
     * @param Router    $router
     * @param null $id
     *
     * @return array    $templateData
     */
    public static function getTemplateData(BackOfficeBaseController $backOfficeBaseController, Router $router, $id = null)
    {

        $backOfficeBaseController->postTo = (is_numeric($id)) ?
            $backOfficeBaseController->routeUpdate : $backOfficeBaseController->routeInsert;

        $backOfficeBaseController->templateData = (!empty($backOfficeBaseController->entity)) ? (array)$backOfficeBaseController->entity : $backOfficeBaseController->request->request->all();
        $auxTemplateData = $backOfficeBaseController->templateData;
        $backOfficeBaseController->templateData = null;


        /**
         * Start transforming the object from Entity to array
         */
        foreach ($auxTemplateData as $key => $value) {
            $name = explode('\\', $key);
            $key = preg_replace("/[^a-z0-9 ]/i", "_", $name[count($name) - 1]);
            $key = preg_replace('/\B([A-Z])/', '_$1', $key);
            $key = strtolower($key);
            $backOfficeBaseController->templateData[$key] = $value;
        }

        $auxTemplateData = null;

        $backOfficeBaseController->templateData['post'] = (is_numeric($id)) ? $router->generate($backOfficeBaseController->postTo, ['id' => $id]) :
            $router->generate($backOfficeBaseController->postTo);

        /**
         * Include authorized actions
         */
        $allowedList = $backOfficeBaseController->getAllowedActions();
        $backOfficeBaseController->templateData['user_can_read'] = in_array('read', $allowedList);
        $backOfficeBaseController->templateData['user_can_write'] = in_array('write', $allowedList);
        $backOfficeBaseController->templateData['user_can_administer'] = in_array('create', $allowedList);

        $reflectionClass = new \ReflectionClass(get_class($backOfficeBaseController));
        $backOfficeBaseController->templateData['reflection_controller_name'] = preg_replace('/Controller/', '', $reflectionClass->getShortName());

        return $backOfficeBaseController->templateData;
    }

}