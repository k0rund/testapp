<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Plans;
use app\models\PlansProperties;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ImportController extends Controller
{
    private $plans_id = array();
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = '')
    { 
        echo "Запуск импорта данных из xml файлов \n";
        try {
            $pathToPlansXML = "xml/plans.xml";
            $xml = simplexml_load_file($pathToPlansXML);
            $this->saveData("saveDataPlans", $xml);
            $pathToPlansXML = "xml/plan_properties.xml";
            $xml = simplexml_load_file($pathToPlansXML);
            $this->saveData("saveDataPlansProperties", $xml);
            
            echo "Окончание импорта данных из xml файлов \n";
            echo "Пиковое потребление памяти (функция memory_get_peak_usage()) : ". round(memory_get_peak_usage()/(1024*1024),2)."MB" . " \n";
            //echo "Использовано памяти скриптом (функция memory_get_usage()) : ". round(memory_get_usage()/(1024*1024),2)."MB" . " \n";
            return 0;
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
            return 1; 
        }
        
    }
    /**
     * @desc Вставка данных
     * @param String $nameFunction
     * @param Object $xml
     */
    private function saveData($nameFunction, $xml) {
        foreach ($xml->result->ROWSET->ROW as $item) {
            if (!empty($item->ACTIVE_TO)) {
                $date = new \DateTime();
                $now = $date->getTimestamp();
                $date = new \DateTime($item->ACTIVE_TO);
                $active = $date->getTimestamp();

                if ($now <= $active) {
                    $this->$nameFunction($item);
                }
            } else {
                $this->$nameFunction($item);
            }
        }
    }

    /**
     * @desc Вставка данных в таблицу plans
     * @param Object $item
     */
    private function saveDataPlans($item) {
        $model = new Plans();
        $model->plan_id = $item->PLAN_ID;
        $el =  $item->PLAN_ID;
        array_push($this->plans_id, (int)(json_decode(json_encode($el),TRUE)[0]));
        $model->plan_name = $item->PLAN_NAME;
        $model->plan_group_id = $item->PLAN_GROUP_ID;
        
        if (!empty($item->ACTIVE_FROM)) {
            $date = new \DateTime($item->ACTIVE_FROM);
            $model->active_from = $date->format("Y-m-d H:i:s");
        }
        if (!empty($item->ACTIVE_TO)) {
            $date = new \DateTime($item->ACTIVE_TO);
            $model->active_to = $date->format("Y-m-d H:i:s");
        }
        if (!empty($item->COMPANY_ID)) {
            $model->company_id = $item->COMPANY_ID;
        }
        $model->save();
    }
    
    /**
     * @desc Вставка данных в таблицу plans_properties
     * @param Object $item
     */
    private function saveDataPlansProperties($item) {
        $val = $item->PLAN_ID;
        if (in_array($val, $this->plans_id)) {
            $model = new PlansProperties();
            $model->plan_id = $item->PLAN_ID;
            $model->property_id = $item->PROPERTY_ID;
            $model->property_type_id = $item->PROPERTY_TYPE_ID;

            if (!empty($item->ACTIVE_FROM)) {
                $date = new \DateTime($item->ACTIVE_FROM);
                $model->active_from = $date->format("Y-m-d H:i:s");
            }
            if (!empty($item->ACTIVE_TO)) {
                $date = new \DateTime($item->ACTIVE_TO);
                $model->active_to = $date->format("Y-m-d H:i:s");
            }
            if (!empty($item->PROP_VALUE)) {
                $model->prop_value = $item->PROP_VALUE;
            }
            $model->save();
        }
    }  
}

