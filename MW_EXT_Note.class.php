<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Note
 */
class MW_EXT_Note
{
  /**
   * Get note.
   *
   * @param $type
   *
   * @return array
   */
  private static function getNote($type): array
  {
    $note = MW_EXT_Kernel::getYAML(__DIR__ . '/store/' . $type . '.json');
    return $note ?? [] ?: [];
  }

  /**
   * Get note ID.
   *
   * @param $type
   *
   * @return string
   */
  private static function getID($type): string
  {
    $note = self::getNote($type) ? self::getNote($type) : '';
    return $note['id'] ?? '' ?: '';
  }

  /**
   * Get note icon.
   *
   * @param $type
   *
   * @return string
   */
  private static function getIcon($type): string
  {
    $note = self::getNote($type) ? self::getNote($type) : '';
    return $note['icon'] ?? '' ?: '';
  }

  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setHook('note', [__CLASS__, 'onRenderTag']);
  }

  /**
   * Render tag function.
   *
   * @param $input
   * @param array $args
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string|null
   */
  public static function onRenderTag($input, array $args, Parser $parser, PPFrame $frame): ?string
  {
    // Argument: type.
    $getType = MW_EXT_Kernel::outClear($args['type'] ?? '' ?: '');
    $outType = MW_EXT_Kernel::outNormalize($getType);

    // Check note type, set error category.
    if (!self::getNote($outType)) {
      $parser->addTrackingCategory('mw-note-error-category');

      return null;
    }

    // Get icon.
    $getIcon = self::getIcon($outType);
    $outIcon = $getIcon;

    // Get ID.
    $getID = self::getID($outType);
    $outID = $getID;

    // Get content.
    $getContent = trim($input);
    $outContent = $parser->recursiveTagParse($getContent, $frame);

    // Out HTML.
    $outHTML = '<div class="mw-note mw-note-' . $outID . ' navigation-not-searchable mw-box">';
    $outHTML .= '<div class="mw-note-body">';
    $outHTML .= '<div class="mw-note-icon"><div><i class="' . $outIcon . '"></i></div></div>';
    $outHTML .= '<div class="mw-note-content">' . "\n\r" . $outContent . "\n\r" . '</div>';
    $outHTML .= '</div></div>';

    // Out parser.
    return $outHTML;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.note.styles']);
  }
}
