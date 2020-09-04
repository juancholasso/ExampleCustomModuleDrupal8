<?php

namespace Drupal\module_juan\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class UploadForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'upload_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Page title field.
        $form['file'] = [
            '#type' => 'file',
            '#description' => $this->t('Introduce el archivo'),
            '#title' => t('File *'),
            '#upload_location' => 'public://my_files/',
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    // public function submitForm(array &$form, FormStateInterface $form_state) {
    //     $file = \Drupal::entityTypeManager()->getStorage('file')
    //     ->load($form_state->getValue('my_file')[0]); // Just FYI. The file id will be stored as an array
    //     // And you can access every field you need via standard method
    //     dpm($file->get('filename')->value);
    //     print_r($file);
    //     exit();
    // }

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
    public function getEditableConfigNames() {
        return [
        ];
    }
}