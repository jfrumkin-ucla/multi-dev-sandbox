<?php

namespace Drupal\siteden_paragraphs_convert;

/**
 * Conversion service for converting nodes to Layout Paragraphs.
 */
class ConversionService {

  /**
   * Batch process callback.
   *
   * @param int $nid
   *   Id of the node.
   * @param string $operation_details
   *   Details of the operation.
   * @param object $context
   *   Context for operations.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\TypedData\Exception\ReadOnlyException
   */
  public static function convertNode(int $nid, string $operation_details, object &$context): void {
    $context['results'][] = _siteden_paragraphs_convert_node($nid);
    // Optional message displayed under the progressbar.
    $context['message'] = t('@details',
      ['@id' => $nid, '@details' => $operation_details]
    );
  }

  /**
   * Batch finished callback.
   *
   * @param bool $success
   *   Success of the operation.
   * @param array $results
   *   Array of results for post processing.
   * @param array $operations
   *   Array of operations.
   */
  public static function convertNodesFinished(bool $success, array $results, array $operations) {
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One node processed. 🦄 🌈', '@count nodes processed. 🦄 🌈'
      );
    }
    else {
      $message = t('An error occurred while converting nodes to Layout Paragraphs. Review logs for details.');
    }
    \Drupal::messenger()->addMessage($message);
  }

}
