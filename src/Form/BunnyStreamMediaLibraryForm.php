<?php

namespace Drupal\bunny_stream\Form;

use Drupal\bunny_stream\BunnyStreamSourceInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\media_library\Form\AddFormBase;

/**
 * Creates a form to create media entities from Bunny stream ID's.
 */
class BunnyStreamMediaLibraryForm extends AddFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return $this->getBaseFormId() . '_bunny_stream';
  }

  /**
   * {@inheritdoc}
   */
  protected function getMediaType(FormStateInterface $form_state) {
    if ($this->mediaType) {
      return $this->mediaType;
    }

    $media_type = parent::getMediaType($form_state);
    if (!$media_type->getSource() instanceof BunnyStreamSourceInterface) {
      throw new \InvalidArgumentException('Can only add media types which use an Bunny Stream source plugin.');
    }
    return $media_type;
  }

  /**
   * {@inheritdoc}
   */
  protected function buildInputElement(array $form, FormStateInterface $form_state) {

    // Add a container to group the input elements for styling purposes.
    $form['container'] = [
      '#type' => 'container',
    ];

    $form['container']['id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add @type via UUID', [
        '@type' => $this->getMediaType($form_state)->label(),
      ]),
      '#description' => $this->t("Allowed add Bunny Stream video using UUID's."),
      '#required' => TRUE,
    ];

    $form['container']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#button_type' => 'primary',
      '#submit' => ['::addButtonSubmit'],
      // @todo Move validation in https://www.drupal.org/node/2988215
      '#ajax' => [
        'callback' => '::updateFormCallback',
        'wrapper' => 'media-library-wrapper',
        // Add a fixed URL to post the form since AJAX forms are automatically
        // posted to <current> instead of $form['#action'].
        // @todo Remove when https://www.drupal.org/project/drupal/issues/2504115
        //   is fixed.
        'url' => Url::fromRoute('media_library.ui'),
        'options' => [
          'query' => $this->getMediaLibraryState($form_state)->all() + [
            FormBuilderInterface::AJAX_FORM_REQUEST => TRUE,
          ],
        ],
      ],
    ];
    return $form;
  }

  /**
   * Submit handler for the add button.
   *
   * @param array $form
   *   The form render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function addButtonSubmit(array $form, FormStateInterface $form_state) {
    $this->processInputValues([$form_state->getValue('id')], $form, $form_state);
  }

}
