<?php
/**
 * @file
 * Contains \Drupal\stats\Form\statsForm.
 */

namespace Drupal\stats\Form;

use Drupal\Core\Form\ MailManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StatsForm extends FormBase {
	
	public function getFormId(){
		return 'stats_form';
	}


public function buildForm(array $form,FormStateInterface $form_state){

  if($_GET['num']){

    $id = $_GET['num'];
  }

  $result = get_by_query($id);
  
	$form['Username'] = array(
	'#type' => 'textfield',
	'#title' => t('Username'),
  '#required' => TRUE,
  '#default_value' => (isset($result[0]->username)) ? $result[0]->username:'',);

    $form['Email'] = array(
    '#type' => 'email',
    '#title' => t('Email ID'),
    //'#required' => TRUE,
    '#default_value' => (isset($result[0]->email)) ? $result[0]->email:'',);

    $form['Mobile'] = array(
    '#type' => 'tel',
    '#title' => t('Mobile'),
    '#required' => TRUE,
    '#default_value' => (isset($result[0]->mobile)) ? $result[0]->mobile:'',);

    $form['Gender'] = array(
    '#type' =>'select',
    '#title' => t('Gender'),
    '#required' => TRUE,
    '#options' => array(
    'female' => t('Female'),
    'male' => t('Male'),
  ),
    '#default_value' => (isset($result[0]->gender)) ? $result[0]->gender:'',
);
    $form['Question'] = array(
    '#type' =>'radios',
    '#title' => t('Have You Filled the fields'),
    '#required' => TRUE,
    '#options' => array(
    '0' => t('No'),
    '1' => t('Yes'),
    ),
    '#default_value' => (isset($result[0]->question)) ? $result[0]->question:'',);
    $form['action']['#type'] = 'actions';

    $form['action']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    $form['link'] = [
    '#title' => $this ->t('Delete Records'),
    '#type' => 'link',
    '#url' => \Drupal\Core\Url::fromRoute('stats.new_page'),
];


    return $form;
}


public function validateForm(array &$form, FormStateInterface $form_state){
   $values = $form_state->getvalues();
         if (strlen($form_state->getValue('Mobile')) < 10) {
        $form_state->setErrorByName('Mobile', $this->t('Mobile number is too short.'));
      }
      
}

public function submitForm(array &$form, FormStateInterface $form_state) {

  $mail = $form_state->getValue('Email');
  //$redirect = $form_state->setRedirect('stats.new_page');  //redirect on listing page after submit
 
 if (empty($mail)) {
   drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
 }
 else {
   drupal_set_message(t('Your message has been sent.'));
 }

//For Update Value in Form

  $id = $_GET['num'];

  if($id) {

  $query = \Drupal::database()->update('application_details');
  $query->fields(['username' => $form_state->getValue('Username') ,'email' =>$form_state->getValue('Email'),'mobile' => $form_state->getValue('Mobile'),'gender' => $form_state->getValue('Gender'),'question' => $form_state->getValue('Question'),]);
  $query->condition('id',$id);
  $result = $query->execute();

  }

  else {
   $time = REQUEST_TIME;
   $newDate = date("m-d-Y", $time);

      $query = \Drupal::database()->insert('application_details');
      $query->fields(['username','email','mobile','gender','question','changed',]);
      $query->values(array(
  		$form_state->getValue('Username'),  // FIELD_1 value.
  		$form_state->getValue('Email'),		
  		$form_state->getValue('Mobile'),
  		$form_state->getValue('Gender'),
  		$form_state->getValue('Question'),
      $newDate,
  		

  	));
  }
    if ($query->execute()) {
      $this->sendMail($form_state->getValue('Email'));
      drupal_set_message('Your application is being submitted!');
    }
     

  }	

  public function sendMail($email) {
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $mailManager = \Drupal::service('plugin.manager.mail');
    $params['message'] = 'This is body of Stats form';
    $mailManager->mail('stats', 'cusmail', $email, $langcode, $params, NULL, true);
  }
}