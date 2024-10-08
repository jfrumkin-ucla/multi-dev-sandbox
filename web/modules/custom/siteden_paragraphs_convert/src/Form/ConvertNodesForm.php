<?php

namespace Drupal\siteden_paragraphs_convert\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Convert nodes class.
 */
class ConvertNodesForm extends FormBase {

  /**
   * An instance of the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'siteden_paragraphs_convert_nodes';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#markup' => $this->t('Use this form to convert SiteDen nodes to use Layout Paragraphs.'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Convert nodes'),
      '#submit' => [[$this, 'submitForm']],
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(&$form, FormStateInterface $form_state) {
    // Fetch the node types we want to convert.
    $entity_storage = $this->entityTypeManager->getStorage('node');
    $content_types = $entity_storage->getQuery()->orConditionGroup()
      ->condition('type', 'sf_page')
      ->condition('type', 'sf_landing')
      ->condition('type', 'sf_article')
      ->condition('type', 'sf_event');
    $nids = $entity_storage->getQuery()->accessCheck(FALSE)->condition($content_types)
      ->execute();

    $batch = [
      'title' => $this->t('Converting nodes'),
      'init_message' => $this->t('Starting conversion…'),
      'progress_message' => $this->t('Processed @current of @total.'),
      'error_message' => $this->t('An error occurred during processing'),
      'operations' => [],
      'finished' => 'siteden_paragraphs_convert_nodes_finished',
    ];
    foreach ($nids as $nid) {
      $batch['operations'][] = [
        'siteden_paragraphs_convert_nodes',
        [$nid],
      ];
    }
    batch_set($batch);
  }

}
