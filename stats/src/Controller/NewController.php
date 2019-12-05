<?php
namespace Drupal\stats\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Routing\TrustedRedirectResponse;
/**
 * Provides route responses for the Example module.
 */
class NewController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function myPage() {

    $record = get_details();
    if ($record == NULL) {
      
      $record = 'No Record Found';
    }

    $build = [
      '#markup' => $record,
    ];
    return $build;
    
  }

  public function deletePage($id) {

    $query = \Drupal::database()->delete('application_details');
    $query->condition('id',$id);
    $query->execute();
    return new TrustedRedirectResponse('http://localhost/drupal8/mypage');
    
  }

}


