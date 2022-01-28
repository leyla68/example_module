<?php

namespace Drupal\example_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Class formulario.
 */
class formulario extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Contenedor donde se muestra el mensaje al usuario
    $form['result'] = [
      '#type' => 'markup',
      '#markup' => '<div class="wrapper_result"></div>',
    ];

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#description' => $this->t('Ingresar el nombre de la persona'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      // Hace la llamada al Callback que realiza las validaciones al campo
      '#ajax' =>[
        'callback' => '::nombreValidateCallback',
        'effect' => 'fade',
        'event' => 'change',
      ],
    ];
    $form['identificacion'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identificacion'),
      '#description' => $this->t('Ingresar el numero de identificacion'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      // Hace la llamada al Callback que realiza las validaciones al campo
      '#ajax' => [
        'callback' => '::identificacionValidateCallback',
        'effect' => 'fade',
        'event' => 'change',
      ],
    ];
    $form['fecha_nacimiento'] = [
      '#type' => 'date',
      '#title' => $this->t('Fecha nacimiento'),
      '#description' => $this->t('Ingresar la fecha de nacimiento'),
      '#weight' => '0',
    ];
    $form['cargo'] = [
      '#type' => 'select',
      '#title' => $this->t('Cargo'),
      '#description' => $this->t('Seleccione el cargo'),
      '#options' => ['Administrador' => $this->t('Administrador'), 'Webmaster' => $this->t('Webmaster'), 'Desarrollador' => $this->t('Desarrollador')],
      '#size' => 1,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
      '#ajax' => [
        'callback' => '::submitForm',
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Valida campos
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function nombreValidateCallback(array &$form, FormStateInterface $form_state) {
    // Las variables se asisgan por defecto para un valor correcto
    $text = $this->t('Perfecto');
    $color = "green";
    // Se valida que el campo del nombre no este vacio
    if (empty($form_state->getValue('nombre'))) {
      $color = "red";
      $text = $this->t('Es requerido');
    }
    // Se valida que el campo del nombre sea alfanumérico
    if (!empty($form_state->getValue('nombre')) && !ctype_alnum($form_state->getValue('nombre'))) {
      $color = "red";
      $text = $this->t('No se aceptan caracteres especiales');
    }

    // AjaxResponse class instance
    $response = new AjaxResponse();
    // Agregar el mensaje al campo descriptivo del programa
    $response->addCommand(new HtmlCommand('#edit-nombre--description', $text));
    // Agrega el color al campo descriptivo del programa
    $response->addCommand(new InvokeCommand('#edit-nombre--description', 'css', ['color', $color]));
    // Devolver la respuesta
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function identificacionValidateCallback(array &$form, FormStateInterface $form_state) {
    // Las variables se asignan por defecto para un valor correcto
    $text = $this->t('Perfecto');
    $color = "green";
    // Se valida que el campo de la identificaicon no este vacio
    if (empty($form_state->getValue('identificacion'))) {
      $color = "red";
      $text = $this->t('El campo es requerido');
    }
    // Se valida que el campo de la identificacion sea numérico
    if (!empty($form_state->getValue('identificacion')) && !is_numeric($form_state->getValue('identificacion'))) {
      $color = "red";
      $text = $this->t('Solo se aceptan números');
    }
    
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#edit-identificacion--description', $text));
    $response->addCommand(new InvokeCommand('#edit-identificacion--description', 'css', ['color', $color]));
    return $response;
  }

    /**
   * {@inheritdoc}
   */
  public function validateFields(array &$form, FormStateInterface $form_state) {
    $valid = TRUE;
    // Validate el campo Nombre
    if (empty(trim($form_state->getValue('nombre')))) {
      $valid = FALSE;
      \Drupal::messenger()->addMessage($this->t('El campo <b>Nombre</b> es obligatorio'), 'error', TRUE);
    }
   
    // Valida el campo de Identificación
    if (empty(trim($form_state->getValue('identificacion')))) {
      $valid = FALSE;
      \Drupal::messenger()->addMessage($this->t('El campo <b>Identificacion</b> es obligatorio'), 'error', TRUE);
    }

    // Valida el campo fecha de nacimiento
    if (empty(trim($form_state->getValue('fecha_nacimiento')))) {
      $valid = FALSE;
      \Drupal::messenger()->addMessage($this->t('El campo <b>Fecha nacimiento</b> es obligtorio'), 'error', TRUE);
    }
    return $valid;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $messages = drupal_get_messages();
    if ($this->validateFields($form, $form_state)===TRUE) {
      $fields = [
      'nombre' => $form_state->getValue('nombre'),
      'identificacion' => $form_state->getValue('identificacion'),
      'fecha_nacimiento' => $form_state->getValue('fecha_nacimiento'),
      'cargo' => $form_state->getValue('cargo'),      
      'estado' => ($form_state->getValue('cargo') == 'Administrador') ? 1 : 0,
    ];

    try {
      $db_connection = \Drupal::database();
      $result = $db_connection->insert('example_users')
        ->fields($fields)
        ->execute();
      if ($result) {
        \Drupal::messenger()->addMessage($this->t('Usuario @name ha sido creado correctamente', ['@name' => ($form_state->getValue('nombre'))]), 'status', TRUE);
      }
      else {
        \Drupal::messenger()->addMessage($this->t('Opssss Problemas con la base de datos'), 'error', TRUE);
      }
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addMessage($this->t('A problem report in the watchdog.'), 'error', TRUE);
      \Drupal::logger('example_module')->error($e->getMessage());
    }

  } else {
  \Drupal::messenger()->addMessage($this->t('Revise los campos, al menos uno no es Correcto'), 'error', TRUE);
}

$message = [
  '#theme' => 'status_messages',
  '#message_list' => $messages,
];

$messages = \Drupal::service('renderer')->render($message);
$response = new AjaxResponse();
$response->addCommand(new HtmlCommand('.wrapper_result', $messages));
return $response;
}
}
