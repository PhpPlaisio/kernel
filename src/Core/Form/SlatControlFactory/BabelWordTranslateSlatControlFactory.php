<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\SlatControlFactory;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Form\Control\SlatControl;
use SetBased\Abc\Form\Control\SlatControlFactory;
use SetBased\Abc\Form\SlatJoint\TableColumnSlatJoint;
use SetBased\Abc\Form\SlatJoint\TextSlatJoint;
use SetBased\Abc\Obfuscator\Obfuscator;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Slat control factory for creating slat controls for translating words.
 */
class BabelWordTranslateSlatControlFactory extends SlatControlFactory
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The obfuscator for word IDs.
   * 
   * @var Obfuscator
   */
  private $myWrdIdObfuscator;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $theLanId       The ID of the reference language.
   * @param int $theTargetLanId The ID of the target language.
   */
  public function __construct($theLanId, $theTargetLanId)
  {
    $ref_language = Abc::$DL->languageGetName($theLanId, $theLanId);
    $act_language = Abc::$DL->LanguageGetName($theTargetLanId, $theLanId);

    // Create slat joint for table column with word ID.
    $table_column = new NumericTableColumn('ID', 'wrd_id');
    $this->addSlatJoint('wrd_id', new TableColumnSlatJoint($table_column));

    // Create slat joint for table column with the word in the reference language.
    $table_column = new TextTableColumn($ref_language, 'ref_wdt_text');
    $this->addSlatJoint('ref_wdt_text', new TableColumnSlatJoint($table_column));

    // Create slat joint with text form control for the word in the target language.
    $table_column = new TextSlatJoint($act_language);
    $this->addSlatJoint('act_wdt_text', $table_column);

    $this->myWrdIdObfuscator = Abc::getObfuscator('wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function createRow($theLouverControl, $theData)
  {
    /** @var SlatControl $row */
    $row = $theLouverControl->addFormControl(new SlatControl($theData['wrd_id']));
    $row->setObfuscator($this->myWrdIdObfuscator);

    $control = $this->createFormControl($row, 'wrd_id');
    print_r($control);
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'ref_wdt_text');
    $control->setValue($theData);

    $control = $this->createFormControl($row, 'act_wdt_text');
    $control->setValue($theData['act_wdt_text']);
    $control->setAttrSize(C::LEN_WDT_TEXT);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
