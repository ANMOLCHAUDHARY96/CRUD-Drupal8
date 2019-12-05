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

  global $base_url;

  $result = get_details();
    //kint($result);
    if ($result == NULL) {

      $output = 'No Record Found';
    } else {
      
      $output = '<table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Gender</th><th>Have You answer the question</th><th colspan=2>Operations</th></tr><thead><tbody>';
      foreach ($result as $key => $value) {
        $id = $value->id;
        $username = $value->username;
        $email = $value->email;
        $mobile  = $value->mobile;
        $gender = $value->gender;
        $question = $value->question;
        $current_path = \Drupal::service('path.current')->getPath();
        //print($current_path);die;
        $output .= "<tr><td>".$id."</td><td>".$username."</td><td>".$email."</td><td>".$mobile."</td><td>".$gender."</td><td>".$question."</td><td>"."<a href ='" . $base_url . '/delete/' .$id . "'>delete</a>"."</td><td>"."<a href ='" . $base_url . '/stats/myform?num=' .$id . "'>edit</a>"."</td></tr>";
      }

    $output .= '</tbody></table>';

    }


    
    $build = [
      '#markup' => $output,
    ];
    return $build;
    
  }

  public function deletePage($id) {

  global $base_url;
    $query = \Drupal::database()->delete('application_details');
    $query->condition('id',$id);
    $query->execute();
    return new TrustedRedirectResponse($base_url.'/mypage');
    
  }

}


