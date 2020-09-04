<?php

namespace Drupal\module_juan\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class RegisterForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'register_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#attached']['library'][] = 'module_juan/module_juan-libraries';

        // Page title field.
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Nombre:'),
            '#default_value' => $this->t(''),
            '#description' => $this->t('Introduce tu nombre'),
            '#required' => TRUE,
            '#attributes' => array('' => 'required', 'maxlength' => '5'),
        ];
    
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Guardar'),
            '#ajax' => [
                'callback' => '::createRegister',
            ],
        ];

        $form['message'] = [
            '#type' => 'markup',
            '#markup' => '<div class="result_message"></div>'
        ];

        return $form;
    }

    public function createRegister(array $form, FormStateInterface $form_state) {
        if (strlen($form_state->getValue('name')) > 5) {
            $response = new AjaxResponse();
            $response->addCommand(
              new HtmlCommand(
                '.result_message',
                '<div class="my_top_message">La longitud máxima es de 5 carácteres</div>'),
            );
            return $response;
        }
        else{
            
            if($this->query("SELECT * FROM module_juan_name WHERE name = '".$form_state->getValue('name')."'") == null){
                $resQuery = $this->insert(array('name' => $form_state->getValue('name')), "module_juan_name");

                $response = new AjaxResponse();
                $response->addCommand(
                  new HtmlCommand(
                    '.result_message',
                    '<div class="my_top_message">' . t('El usuario se ha creado con el ID:  @id', ['@id' => ($resQuery)]) . ' Con</div>'),
                );
                return $response;
            }
            else{
                $response = new AjaxResponse();
                $response->addCommand(
                new HtmlCommand(
                    '.result_message',
                    '<div class="my_top_message">El nombre ya existe</div>'),
                );
                return $response;
            }

            
        }
        
    }

    public function insert(array $array, $dbName){
        $conn =  \Drupal\Core\Database\Database::getConnection() ;
        $result = $conn->insert($dbName)->fields($array)->execute();
        return $result;
    }

    public function query($query){
        $conn =  \Drupal\Core\Database\Database::getConnection() ;
        $myobj = $conn->query($query)->fetchObject();
        return $myobj;
    }


    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (strlen($form_state->getValue('name')) > 5) {
            $form_state->setErrorByName('name', $this->t('El nombre debe ser máximo de 5 caracteres'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames() {
        return [
        ];
    }
}