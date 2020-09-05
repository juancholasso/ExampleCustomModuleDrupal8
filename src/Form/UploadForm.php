<?php

namespace Drupal\module_juan\Form;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

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

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $errors = "";
        $file = $this->getRequest()->files->get('files', [])['file'];
        if (($gestor = fopen($file, "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                if($this->query("SELECT * FROM module_juan_name WHERE name = '".$datos[0]."'") == null){
                    $errors = $errors.$datos[0].", ";
                }else{
                    $resQuery = $this->insert(array('name' => $datos[0]), "module_juan_name");
                }
                $fila++;
            }
            fclose($gestor);
        }
        $this->messenger()->addError($this->t('Los siguientes nombres no fueron registrados: @errors', ['@errors' => $errors]));
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
    public function getEditableConfigNames() {
        return [
        ];
    }
}