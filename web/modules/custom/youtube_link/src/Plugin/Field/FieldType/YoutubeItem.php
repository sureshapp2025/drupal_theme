<?php

/**
 * @file
 * Contains the YoutubeItem field type plugin for the youtube_link module.
 *
 * Provides a custom field type for storing YouTube video information.
 */

namespace Drupal\youtube_link\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'youtube_link' field type.
 *
 * @FieldType(
 *   id = "youtube_link",
 *   label = @Translation("Embed Youtube video"),
 *   module = "youtube_link",
 *   description = @Translation("Output video from Youtube."),
 *   default_widget = "youtube_link",
 *   default_formatter = "youtube_link_thumbnail"
 * )
 */
class YoutubeItem extends FieldItemBase {
    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
        return array(
          'columns' => array(
            'value' => array(
              'type' => 'text',
              'size' => 'tiny',
              'not null' => false,
            ),
          ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() {
        $value = $this->get('value')->getValue();
        return $value === null || $value === '';
    }

  /**
   * {@inheritdoc}
   */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
        $properties = [];
        $properties['value'] = DataDefinition::create('string')
          ->setLabel(t('Youtube video URL'));

        return $properties;
    }
}
