<?php

/**
 * @file
 * Contains \Drupal\token\Tests\TokenFieldTest.
 */
namespace Drupal\token\Tests;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\node\Entity\NodeType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\node\Entity\Node;
use Drupal\Component\Utility\Unicode;

/**
 * Tests file tokens.
 *
 * @group token
 */
class TokenFieldTest extends TokenKernelTestBase {
  /**
   * @var FieldDefinitionInterface
   */
  protected $float;
  /**
   * @var FieldDefinitionInterface;
   */
  protected $integer;
  /**
   * @varFieldDefinitionInterface
   */
  protected $text;
  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('path', 'token', 'token_test', 'node', 'user', 'views', 'field', 'text', 'system');

  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    $node_type = NodeType::create(['type' => 'page', 'name' => t('Page')]);
    $node_type->save();
  }
  /**
   * Test field tokens.
   */
  function testFieldTokens() {
    $values = array(
      'type' => 'Page',
      'name' => 'Titel',
    );

    FieldStorageConfig::create([
      'field_name' => 'field_float',
      'entity_type' => 'node',
      'type' => 'float',
    ])->save();

    $float_field = FieldConfig::create(array(
      'field_name' => 'field_float',
      'entity_type' => 'node',
      'label' => 'Field test float',
      'bundle' => 'token_field_test',
      'settings' => array(),
    ));

    FieldStorageConfig::create([
      'field_name' => 'field_integer',
      'entity_type' => 'node',
      'type' => 'integer',
    ])->save();

    $integer_field = FieldConfig::create(array(
      'field_name' => 'field_integer',
      'entity_type' => 'node',
      'label' => 'Field test integer',
      'bundle' => 'token_field_test',
      'settings' => array(),
    ));

    FieldStorageConfig::create([
      'field_name' => 'field_text',
      'entity_type' => 'node',
      'type' => 'text',
    ])->save();

    $text_field = FieldConfig::create(array(
      'field_name' => 'field_text',
      'entity_type' => 'node',
      'label' => 'Field test text',
      'bundle' => 'token_field_test',
      'settings' => array(),
    ));

    $this->float = $float_field;
    $this->integer = $integer_field;
    $this->text = $text_field;

    $page = Node::create(['type' => 'page', 'float' => $this->float, 'integer' => $this->integer]);
    $page->save();

    \Drupal::token()->generate('node', ['node' => $page], ['[field_float]' => '1.23', '[field_integer]' => '123', '[field_text]' => 'OneTwoThree']);

    $tokens = array(
      '[field_float]' => 'test',
      '[field_integer]' => 'test',
      '[field_text]' => 'test',
    );
    $this->assertTokens('node', array('node' => $page), $tokens);

//    $text = '[field_float]';

//    $tokens = array(
//      '[field_float]' => $this->float->get('float_field'),
//      '[field_integer]' => $this->integer->get('integer_field'),
//      '[field_text]' => $this->text->get('text_field'),
//    );
  }

}
