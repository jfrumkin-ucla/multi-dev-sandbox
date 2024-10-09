<?php

namespace Drupal\siteden_paragraphs_convert\Commands;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\Query\QueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A Drush commandfile.
 */
class SitedenParagraphsConvertCommands extends DrushCommands {

  use StringTranslationTrait;

  /**
   * An instance of the entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * Logger service.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  private LoggerChannelFactoryInterface $loggerChannelFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactoryInterface $loggerChannelFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerChannelFactory = $loggerChannelFactory;
    parent::__construct();
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
   * Run the SiteDen Paragraphs node conversion.
   */
  #[CLI\Command(name: 'siteden_paragraphs_convert:nodes', aliases: ['sdpcn'])]
  public function simulation(): void {
    $this->loggerChannelFactory->get('siteden_paragraphs_convert')
      ->info('Update nodes batch operations start');

    try {
      $entity_storage = $this->entityTypeManager->getStorage('node');
      $content_types = $entity_storage->getQuery()->orConditionGroup()
        ->condition('type', 'sf_page')
        ->condition('type', 'sf_landing')
        ->condition('type', 'sf_article')
        ->condition('type', 'sf_event');
      $nids = $entity_storage->getQuery()->accessCheck(FALSE)->condition($content_types)
        ->execute();
    }
    catch (\Exception $e) {
      $this->output()->writeln($e);
      $this->loggerChannelFactory->get('siteden_paragraphs_convert')
        ->warning('Error found @e', ['@e' => $e]);
    }

    $this->logger()->info('Converting @num nodes...', ['@num' => count($nids)]);

    $operations = [];
    $numOperations = 0;
    if (!empty($nids)) {
      foreach ($nids as $nid) {
        $operations[] = [
          '\Drupal\siteden_paragraphs_convert\ConversionService::convertNode',
          [
            $nid,
            $this->t('Updating node @nid', ['@nid' => $nid]),
          ],
        ];
        $numOperations++;
      }
    }
    else {
      $this->logger()
        ->warning('No nodes of this type @type', ['@type' => $type]);
    }

    $batch = [
      'title' => $this->t('Updating @num node(s)', ['@num' => $numOperations]),
      'operations' => $operations,
      'finished' => '\Drupal\siteden_paragraphs_convert\ConversionService::convertNodesFinished',
    ];
    batch_set($batch);
    drush_backend_batch_process();

    $this->loggerChannelFactory->get('siteden_paragraphs_convert')
      ->info('Update batch operations end.');
    $this->logger()->success(dt('Layout Paragraphs conversion complete.'));

  }

}
