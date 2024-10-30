<?php

namespace BitCode\BITGFZC\Admin\GF;

use BitCode\BITGFZC\Integration\IntegrationHandler;
use BitCode\BITGFZC\Integration\Integrations;
use FluentForm\App\Modules\Form\FormFieldsParser;

final class Handler
{
    public function __construct()
    {
        //
    }

    public function get_a_form($data)
    {
        if (empty($data->formId) || ! class_exists('GFAPI')) {
            wp_send_json_error(__('Form doesn\'t exists', 'bitgfzc'));
        }
        $form = \GFAPI::get_form($data->formId);
        $fieldDetails = $form['fields'];
        if (empty($fieldDetails)) {
            wp_send_json_error(__('Form doesn\'t exists', 'bitgfzc'));
        }

        $fields = [];
        $inputTypes = ['color','checkbox','date','datetime-local','email','file','hidden','image','month','number','password','radio','range','tel','text','time','url','week'];
        foreach ($fieldDetails as  $id => $field) {
            if (isset($field->inputs) && is_array($field->inputs)) {
                $labelPrefix =  !empty($field->adminLabel) ? $field->adminLabel : (!empty($field->label) ? $field->label : $field->id);
                foreach ($field->inputs as $input) {
                    if (!isset($input['isHidden'])) {
                        $fields[] = [
                            'name' => $input['id'],
                            'type' => isset($input['inputType']) &&  in_array($input['inputType'], $inputTypes) ? $input['inputType'] : 'text',
                            'label' => "$labelPrefix - ". $input['label'],
                        ];   
                    }
                }
            } else {
                 $fields[] = [
                        'name' => $field->id,
                        'type' => in_array($field->type, $inputTypes) ? $field->type : 'text',
                        'label' => !empty($field->adminLabel) ? $field->adminLabel : (!empty($field->label) ? $field->label : $field->id),
                    ];
            }
        }
        if (empty($fields)) {
            wp_send_json_error(__('Form doesn\'t exists any field', 'bitgfzc'));
        }

        $responseData['fields'] = $fields;
        $integrationHandler = new IntegrationHandler($data->formId);
        $formIntegrations = $integrationHandler->getAllIntegration();
        if (!is_wp_error($formIntegrations)) {
            $integrations = [];
            foreach ($formIntegrations as $integrationkey => $integrationValue) {
                $integrationData = array(
                    'id' => $integrationValue->id,
                    'name' => $integrationValue->integration_name,
                    'type' => $integrationValue->integration_type,
                    'status' => $integrationValue->status,
                );
                $integrations[] = array_merge(
                    $integrationData,
                    is_string($integrationValue->integration_details) ?
                        (array) json_decode($integrationValue->integration_details) :
                        $integrationValue->integration_details
                );
            }
            $responseData['integrations'] = $integrations;
        }
        wp_send_json_success($responseData);
    }

    public static function gform_after_submission($entry, $form)
    {
        $form_id = $form['id'];
        if (!empty($form_id)) {
            Integrations::executeIntegrations($form_id, $entry);
        }
    }
}
