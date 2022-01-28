<?php

namespace Drupal\example_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserController.
 */
class UserController extends ControllerBase {

  /**
   * Getusers.
   *
   * @return string
   *   Return Hello string.
   */
  public function getUsers() {
    $header = [
      'nombre' => $this->t('Nombre'),
      'identificacion' => $this->t('Identificacion'),
      'fecha_nacimiento' => $this->t('Fecha nacimiento'),
      'cargo' => $this->t('Cargo'),
      'estado' => $this->t('Estado'),
    ];
    $database = \Drupal::database();
    $query = $database->select('example_users', 'u');
    $query->fields('u', ['nombre', 'identificacion', 'fecha_nacimiento', 'cargo', 'estado']);
    $result = $query->execute();
    $records = $result->fetchAll();
    $rows = [];
    foreach ($records as $record) {
      $record->fecha_nacimiento = date('d/m/Y', $record->fecha_nacimiento);
      $rows[] = (array) $record;
    }
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#suffix' => '<a href="/example-module/form">' . $this->t('Nuevo usuario') . '</a>',
    ];
  }

}
