<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Form\Control;

use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A pseudo form control for generating (pseudo) form controls in a table format.
 */
class LouverControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The data on which the table row form controls must be created.
   *
   * @var array[]
   */
  protected $myData;

  /**
   * Form control for the footer of the table.
   *
   * @var control
   */
  protected $myFooterControl;

  /**
   * Object for creating table row form controls.
   *
   * @var SlatControlFactory
   */
  protected $myRowFactory;

  /**
   * The data for initializing teh template row(s).
   *
   * @var array
   */
  private $myTemplateData;

  /**
   * The key of the key in the template row.
   *
   * @var string
   */
  private $myTemplateKey;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of displaying the form controls of this complex form control in a table.
   *
   * @return string
   */
  public function generate()
  {
    if (!empty($this->myTemplateData))
    {
      $this->setAttrData('slat-name', $this->mySubmitName);

      // If required add template row to this louver control. This row will be used by JS for adding dynamically
      // additional rows to the louver control.
      $this->myTemplateData[$this->myTemplateKey] = 0;
      $row                                        = $this->myRowFactory->createRow($this, $this->myTemplateData);
      $row->setAttrClass('slat_template');
      $row->setAttrStyle('visibility: collapse');
      $row->prepare($this->mySubmitName);
    }

    $ret = $this->myPrefix;

    $ret .= Html::generateTag('div', $this->attributes);
    $ret .= '<table>';

    // Generate HTML code for the column classes.
    $ret .= '<colgroup>';
    $ret .= $this->myRowFactory->getColumnGroup();
    $ret .= '</colgroup>';

    $ret .= '<thead>';
    $ret .= $this->getHtmlHeader();
    $ret .= '</thead>';

    if ($this->myFooterControl)
    {
      $ret .= '<tfoot>';
      $ret .= '<tr>';
      $ret .= '<td colspan="'.$this->myRowFactory->getNumberOfColumns().'">';
      $ret .= $this->myFooterControl->generate();
      $ret .= '</td>';
      $ret .= '<td class="error"></td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '<tbody>';
    $ret .= $this->getHtmlBody();
    $ret .= '</tbody>';

    $ret .= '</table>';
    $ret .= '</div>';

    $ret .= $this->myPostfix;

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function loadSubmittedValuesBase(&$theSubmittedValue, &$theWhiteListValue, &$theChangedInputs)
  {
    $submit_name = ($this->myObfuscator) ? $this->myObfuscator->encode($this->myName) : $this->myName;

    if (!empty($this->myTemplateData))
    {
      $children         = $this->myControls;
      $this->myControls = [];
      foreach ($theSubmittedValue[$submit_name] as $key => $row)
      {
        if (is_numeric($key) && $key<0)
        {
          $this->myTemplateData[$this->myTemplateKey] = $key;
          $row                                        = $this->myRowFactory->createRow($this, $this->myTemplateData);
          $row->prepare($this->mySubmitName);
        }
      }

      $this->myControls = array_merge($this->myControls, $children);
    }

    parent::loadSubmittedValuesBase($theSubmittedValue, $theWhiteListValue, $theChangedInputs);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Populates this table form control with table row form controls (based on the data set with setData).
   */
  public function populate()
  {
    foreach ($this->myData as $data)
    {
      $this->myRowFactory->createRow($this, $data);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the data for which this table form control must be generated.
   *
   * @param array[] $theData
   */
  public function setData($theData)
  {
    $this->myData = $theData;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the footer form control of this table form control.
   *
   * @param Control $theControl
   */
  public function setFooterControl($theControl)
  {
    $this->myFooterControl = $this->addFormControl($theControl);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the row factory for this table form control.
   *
   * @param SlatControlFactory $theRowFactory
   */
  public function setRowFactory($theRowFactory)
  {
    $this->myRowFactory = $theRowFactory;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the template data and key of the key for dynamically adding additional rows to form.
   *
   * @param array  $theData The date for initializing template row(s).
   * @param string $theKey  The key of the key in the template row.
   */
  public function setTemplate($theData, $theKey)
  {
    $this->myTemplateData = $theData;
    $this->myTemplateKey  = $theKey;
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the tbody element of this table form control.
   *
   * @return string
   */
  protected function getHtmlBody()
  {
    $ret = '';
    $i   = 0;
    foreach ($this->myControls as $control)
    {
      if ($control!==$this->myFooterControl)
      {
        // Add class for zebra theme.
        $control->setAttrClass(($i % 2==0) ? 'even' : 'odd');

        // Generate the table row.
        $ret .= $control->generate();

        $i++;
      }
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the thead element (e.g. column headers and filters) of this table form control.
   *
   * @return string
   */
  protected function getHtmlHeader()
  {
    return $this->myRowFactory->getHtmlHeader();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
