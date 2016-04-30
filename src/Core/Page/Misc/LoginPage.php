<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Misc;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\PasswordControl;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Form;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Helper\Password;
use SetBased\Abc\Page\Page;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for logging on the website.
 */
class LoginPage extends Page
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The login form shown on this page.
   *
   * @var Form
   */
  protected $form;

  /**
   * If set the URI to which the user agent must redirected after a successful login.
   *
   * @var string
   */
  private $redirect;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->redirect = self::getCgiUrl('redirect');

    if (isset($_SERVER['ABC_ENV']) && $_SERVER['ABC_ENV']=='dev')
    {
      $this->enableW3cValidator();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param string|null $uri A URI to which the user agent must be redirected after a successful login.
   *
   * @return string
   */
  public static function getUrl($uri = null)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_MISC_LOGIN, 'pag');
    $url .= self::putCgiVar('redirect', $uri);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoPage()
  {
    // Buffer for actual contents.
    ob_start();

    $this->showPageContent();

    $contents = ob_get_contents();
    ob_end_clean();

    // Buffer for header.
    ob_start();

    $this->showPageHeader();

    echo '<div id="container">';
    echo $contents;
    echo '</div>';

    $this->showPageTrailer();

    // Write the HTML code of this page to the file system for (asynchronous) validation.
    if ($this->w3cValidate)
    {
      $contents = ob_get_contents();
      file_put_contents($this->w3cPathName, $contents);
    }

    $this->setPageSize(ob_get_length());
    ob_end_flush();
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function showPageContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $abc = Abc::getInstance();

    $this->form = new CoreForm('', false);

    // Input for user name.
    $input = new TextControl('usr_name');
    $input->setAttrMaxLength(C::LEN_USR_NAME);
    $this->form->addFormControl($input, 'Naam', true);

    // Input for password.
    $input = new PasswordControl('usr_password');
    $input->setAttrSize(C::LEN_USR_NAME);
    $input->setAttrMaxLength(C::LEN_PASSWORD);
    $this->form->addFormControl($input, 'Wachtwoord', true);

    if ($abc->getDomain())
    {
      // Show domain.
      $input = new SpanControl('dummy');
      $input->setInnerText(strtolower($abc->getDomain()));
      $this->form->addFormControl($input, 'Company');

      // Constant for domain.
      $input = new ConstantControl('cmp_abbr');
      $input->setValue($abc->getDomain());
      $this->form->addFormControl($input);
    }
    else
    {
      // Input for domain.
      $input = new TextControl('cmp_abbr');
      $input->setAttrMaxLength(C::LEN_CMP_ABBR);
      $this->form->addFormControl($input, 'Company', true);
    }

    $this->form->addSubmitButton(C::WRD_ID_BUTTON_LOGIN, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the form shown on this page.
   */
  private function echoForm()
  {
    echo $this->form->generate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $login_succeeded = $this->login();

    if (!$login_succeeded) $this->echoForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if login is successful. Otherwise returns false.
   *
   * @return bool
   */
  private function login()
  {
    $abc = Abc::getInstance();

    $values = $this->form->getValues();

    // Phase 1: Validate the user is allowed to login (except for password validation).
    $response1 = Abc::$DL->sessionLogin1($abc->getSesId(), $values['usr_name'], $values['cmp_abbr']);
    $lgr_id    = $response1['lgr_id'];

    if ($lgr_id==C::LGR_ID_GRANTED)
    {
      // Phase 2: So far, user is allowed to login. Last validation: verify password.
      $match = Password::passwordVerify($values['usr_password'], $response1['usr_password_hash']);
      if ($match!==true) $lgr_id = C::LGR_ID_WRONG_PASSWORD;
    }

    // Phase 3: Log the login attempt and set session.
    $response3 = Abc::$DL->sessionLogin3($abc->getSesId(),
                                         $response1['cmp_id'],
                                         $response1['usr_id'],
                                         $lgr_id,
                                         $values['usr_name'],
                                         $values['cmp_abbr'],
                                         $_SERVER['REMOTE_ADDR']);

    if ($response3['lgr_id']==C::LGR_ID_GRANTED)
    {
      // The user has logged on successfully.

      // First verify that the hash is sill up to date.
      if (Password::passwordNeedsRehash($response1['usr_password_hash']))
      {
        $hash = Password::passwordHash($values['usr_password']);
        Abc::$DL->userUpdatePasswordHash($this->cmpId, $this->usrId, $hash);
      }

      $domain_redirect = false;
      if (!$abc->getDomain())
      {
        // Redirect browser to $values['cmp_abbr'].onzerelaties.net
        $parts = explode('.', $_SERVER['SERVER_NAME']);
        if (count($parts)==3 && $parts[0]=='www' && $_SERVER['HTTPS']=='on')
        {
          // Unset session and CSRF cookies.
          setcookie('ses_session_token', false, false, '/', $abc->getCanonicalServerName(), true, true);
          setcookie('ses_csrf_token', false, false, '/', $abc->getCanonicalServerName(), true, false);
          /*
          // Get tokens for cross domain redirect.
          $tokens = Abc::$DL->sessionGetRedirectTokens( $this->myRequestBus->getSesId() );

          // Set a (temporary) cookie that is valid at SLD.
          setcookie( 'cdr_token1', $tokens['cdr_token1'], false, '/', $parts[1].'.'.$parts[2], true, true );

          // Set token to be used in JavaScript.
          $values    = $this->form->getValues();
          $host_name = mb_strtolower( $values['cmp_abbr'] ).'.'.$parts[1].'.'.$parts[2];
          $url       = 'https://'.$host_name.DomainRedirectPage::getUrl( $this->redirect );

          $this->appendJavaScriptLine( 'PageMiscLogin.ourCdrToken2="'.$tokens['cdr_token2'].'";' ); // xxx escaping
          $this->appendJavaScriptLine( 'PageMiscLogin.ourCdrUrl="'.$url.'";' ); // xxx escaping

          $domain_redirect = true;
          */
        }
      }

      if (!$domain_redirect)
      {
        // Set the secure token.
        if ($_SERVER['HTTPS']=='on')
        {
          setcookie('ses_session_token',
                    $response3['ses_session_token'],
                    false,
                    '/',
                    $abc->getCanonicalServerName(),
                    true,
                    true);
          setcookie('ses_csrf_token',
                    $response3['ses_csrf_token'],
                    false,
                    '/',
                    $abc->getCanonicalServerName(),
                    true,
                    false);
        }

        HttpHeader::redirectSeeOther(($this->redirect) ? $this->redirect : '/');
      }

      return true;
    }
    else
    {
      // The user has not logged on successfully.
      return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageHeader()
  {
    echo '<!DOCTYPE html>';
    echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl" dir="ltr">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>';

    // echo "<title>".SET_Html::txt2Html( Babel::getWord( C::WRD_ID_USER_LOGIN, $this->myLanId ) )."</title>";
    foreach ($this->cssSources as $css_source)
    {
      echo '<link rel="stylesheet" media="screen" type="text/css" href="', $css_source, '"/>';
    }
    if ($this->css)
    {
      echo '<style type="text/css" media="all">', $this->css, '</style>';
    }

    echo '</head><body>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  private function showPageTrailer()
  {
    echo '</body></html>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
