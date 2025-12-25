<?php
namespace Drupal\external_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


/**
 * Provides a resource to access external API data.
 *
 * @RestResource(
 *   id = "external_api_resource",
 *   label = @Translation("External API Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/external-api",
 *     "https://www.drupal.org/link-relations/create" = "/api/external-api"
 *   }
 * )
 */
class ExternalApiResource extends ResourceBase
{

 

  /**
   * Responds to GET requests.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the external API data.
   */
  /**
   * A current user instance which is logged in the session.
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $loggedUser;
  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $config
   *   A configuration array which contains the information about the plugin instance.
   * @param string $module_id
   *   The module_id for the plugin instance.
   * @param mixed $module_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A currently logged user instance.
   */
  public function __construct(
    array $config,
    $module_id,
    $module_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user
  ) {
    parent::__construct($config, $module_id, $module_definition, $serializer_formats, $logger);

    $this->loggedUser = $current_user;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $config, $module_id, $module_definition)
  {
    return new static(
      $config,
      $module_id,
      $module_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('sample_rest_resource'),
      $container->get('current_user')
    );
  }
  /**
   * Responds to GET request.
   * Returns a list of taxonomy terms.
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * Throws exception expected.
   */
  public function get()
  {
    // Implementing our custom REST Resource here.
    // Use currently logged user after passing authentication and validating the access of term list.
    if (!$this->loggedUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }
    $client = new Client($page_result = 1);
    $url = 'https://www.thegazette.co.uk/all-notices/notice/data.json';
    $all_notices = [];

    try {
      while (TRUE) {
        $response = $client->get($url, [
          'query' => ['page-result' => $page_result],
          'headers' => ['Accept' => 'application/json'],
        ]);

        if ($response->getStatusCode() === 200) {
          $data = Json::decode($response->getBody());
          if (empty($data['notices'])) {
            break;
          }
          $all_notices = array_merge($all_notices, $data['notices']);

          // Check if there are more pages.
          if (empty($data['has_more'])) {
            break;
          }

          $page_result++;
        }
      }
    } catch (RequestException $e) {
      \Drupal::logger('external_api_resource')->error($e->getMessage());
      return new ResourceResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // $response = new ResourceResponse($term_result);
    // $response->addCacheableDependency($term_result);
    // return $response;
  }
}
