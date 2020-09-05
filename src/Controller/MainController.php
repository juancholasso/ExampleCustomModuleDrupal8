<?php
/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */
namespace Drupal\module_juan\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class MainController extends ControllerBase implements ContainerInjectionInterface{

   public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  public function query($query){
      $conn =  \Drupal\Core\Database\Database::getConnection() ;
      $myobj = $conn->query($query)->fetchAll();
      return $myobj;
  }
  
  public function content() {

    $list = $this->query("select * from module_juan_name");
    return array(
      '#theme' => 'list',
      '#template' => 'list',
      '#list' => $list
    );
  }

  public function logs() {

    $list = $this->query("select * from module_juan_log");
    return array(
      '#theme' => 'logs',
      '#template' => 'logs',
      '#list' => $list
    );
  }


  public function downloadCSV(){
    $list = $this->query("select * from module_juan_name");
  
    $output = fopen('php://output', 'w');

    fputcsv($output, array(t('ID'), t('Nombre')));

    foreach($list as $item){
      fputcsv($output, [$item->id, $item->name]);
    }

    rewind($handle);
    $csv_data = stream_get_contents($output);
    fclose($output);

    $response = new Response();
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="article-report.csv"');
    $response->setContent($csv_data);

    return $response;
  }

}