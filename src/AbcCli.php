<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc;

//----------------------------------------------------------------------------------------------------------------------
/**
 * The main helper class for the ABC Abc.
 */
abstract class AbcCli extends Abc
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the domain (a.k.a. company) of the requestor.
   *
   * @return int
   */
  public function getCmpId()
  {
    return $this->sessionInfo['cmp_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the code of the preferred language of the requestor.
   *
   * @return string
   */
  public function getLanCode()
  {
    return $this->sessionInfo['lan_code'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the preferred language of the requestor.
   *
   * @return int
   */
  public function getLanId()
  {
    return $this->sessionInfo['lan_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the ID of the session.
   *
   * @return int
   */
  public function getSesId()
  {
    return $this->sessionInfo['ses_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the session info.
   *
   * @return array
   */
  public function getSessionInfo()
  {
    return $this->sessionInfo;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the user ID of the requestor.
   *
   * @return int
   */
  public function getUsrId()
  {
    return $this->sessionInfo['usr_id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if the requestor is anonymous. Returns false otherwise.
   *
   * @return bool
   */
  public function isAnonymous()
  {
    return (!empty($this->sessionInfo['usr_anonymous']));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
