<?php
/**
 * @file
 * Contains \Drupal\test\Plugin\Block\XaiBlock.
 */
namespace Drupal\stats\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\stats\Controller\NewController;

/**
 * Provides a 'test' block.
 *
 * @Block(
 *   id = "stats",
 *   admin_label = @Translation("Statsblock"),
 *   category = @Translation("Custom Test block example")
 * )
 */

class Test extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\stats\Form\StatsForm');
 //    $statistics = new NewController;
	//  $value = $statistics->myPage();
	// kint($value);
    return $form;

    
}


}