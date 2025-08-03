<?php

namespace Drupal\common\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Properties Block' block.
 *
 * @Block(
 *   id = "properties_block",
 *   admin_label = @Translation("Properties Block"),
 *   category = @Translation("Properties"),
 * )
 */
class PropertiesBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The entity type manager.
   * 
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a CommonBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager)
  {
    $this->entityTypeManager = $entityTypeManager;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->accessCheck(FALSE)
      ->condition('status', 1)
      ->condition('type', 'best_deal');
    $nids = $query->execute();
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    // Prepare the output.
    $items = [];
    foreach ($nodes as $node) {
      $taxonomy_terms = $node->get('villa_types')->entity;
      $items[] = [
        'body' => $node->get('body')->value,
        // 'field_best_deal_type' => $taxonomy_terms->getName(),
        'field_floor_number' => $node->get('field_floor_number')->value,
        'field_number_of_rooms' => $node->get('field_number_of_rooms')->value,
        'field_parking_available' => $node->get('field_parking_available')->value,
        'field_payment_process' => $node->get('field_payment_process')->value,
        'field_total_flat_space' => $node->get('field_total_flat_space')->value,
        'field_total_flat_space' => $node->get('field_total_flat_space')->value,
        'field_best_deal_image' => $node->get('field_best_deal_image')->entity ? $node->get('field_best_deal_image')->entity->get('field_media_image')->entity->get('uri')->value : '',
        // 'field_address' => $node->get('field_address')->value,
        // 'field_number_of_both_room' => $node->get('field_number_of_both_room')->value,
        // 'field_proice' => $node->get('field_proice')->value,
      ];
    }
    return [
      '#theme' => 'block__list_properties',
      '#items' => $items,
      '#cache' => [
        'max-age' => 1,
      ],
    ];
  }
}
