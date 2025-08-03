<?php
namespace Drupal\google_map_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Google Map' Block.
 *
 * @Block(
 *   id = "google_map_block",
 *   admin_label = @Translation("Google Map Block"),
 *   category = @Translation("Custom"),
 * )
 */

// #[Block(
//   id: "google_map_block",
//   admin_label: new TranslatableMarkup("Google Map Block"),
//   category: new TranslatableMarkup("Custom")
// )]

class GoogleMapBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $api_key = 'YOUR_GOOGLE_MAPS_API_KEY';
    $location = 'INDIA, TN';

    $build = [
      '#markup' => '<div id="google-map" style="width: 100%; height: 400px;"></div>',
      '#attached' => [
        'library' => [
          'google_map_block/google_map_library',
        ],
        'drupalSettings' => [
          'google_map_block' => [
            'apiKey' => $api_key,
            'location' => $location,
          ],
        ],
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    // Optional: Add form elements for block configuration.
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Optional: Handle form submission.
  }
}
