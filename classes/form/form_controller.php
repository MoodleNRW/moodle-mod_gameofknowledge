<?php

namespace mod_gameofknowledge\form;

use \mod_gameofknowledge\gameofknowledge;

defined('MOODLE_INTERNAL') || die();

abstract class form_controller {
    /** @var gameofknowledge */
    protected $gameofknowledge;

    /** @var array */
    protected $formdata;

    /** @var \stdClass */
    protected $moreargs;

    /** @var \moodleform */
    protected $mform;

    /** @var array */
    protected $customdata;

    /** @var bool Form was submitted, validated and data was processed successfully. */
    private $formsubmittedsuccess = false;

    /** @var string display a message instead of rendering the form */
    protected $message = '';

    public function __construct(gameofknowledge $gameofknowledge, array $formdata, \stdClass $moreargs) {
        $this->gameofknowledge = $gameofknowledge;
        $this->formdata = $formdata;
        $this->moreargs = $moreargs;

        $this->build_customdata();
        $this->check_capability();
        $this->construct_mform();

        if ($data = $this->mform->get_data()) {
            // We have validated data.
            $this->formsubmittedsuccess = $this->handle_submit($data);
        } else {
            $this->handle_display();
        }
    }

    protected function construct_mform() {
        $formclass = '\\mod_gameofknowledge\\form\\' . static::$formname;
        $this->mform = new $formclass(null, $this->customdata, 'post', '', null, true, $this->formdata);
    }

    /**
     * Render form (HTML).
     *
     * @return string
     */
    public function render() {
        if (!empty($this->message)) {
            return $this->message;
        }

        return $this->mform->render();
    }

    /**
     * Get a message that should be sent to the client.
     *
     * @return string
     */
    public function get_message() {
       return $this->message;
    }

    /**
     * Form was submitted, validated and data was processed successfully.
     *
     * @return bool
     */
    public function success() : bool {
        return $this->formsubmittedsuccess;
    }

    /**
     * Customdata sent to form.
     */
    abstract protected function build_customdata();

    /**
     * Handle a successful form submission.
     *
     * Called when the submitted form data was validated.
     *
     * @param \stdClass $data validated data from form
     * @return bool
     */
    abstract protected function handle_submit(\stdClass $data) : bool;

    /**
     * First display of the form.
     *
     * Or the form is submitted but the data doesn't validate and the form is redisplayed.
     *
     * This is the place where to set the default (existing) data with
     * $this->mform->set_data.
     */
    abstract protected function handle_display();

    /**
     * Check that user is allowed to access this form.
     */
    abstract protected function check_capability();

    /**
     * Get the specific controller for a form.
     */
    static public function get_controller(string $formname, gameofknowledge $gameofknowledge, array $formdata, \stdClass $moreargs)
        : form_controller {

        switch ($formname) {
            case 'room_edit':
                return new room_edit_controller($gameofknowledge, $formdata, $moreargs);

            default:
                throw new \moodle_exception('unknown_form', 'gameofknowledge');
        }
    }
}
