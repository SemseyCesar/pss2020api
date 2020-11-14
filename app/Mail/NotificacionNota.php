<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionNota extends Mailable
{
    use Queueable, SerializesModels;
    public $alumno_materia;
    public $materia;
    public $alumno;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($alumno_materia, $materia, $alumno)
    {
        $this->alumno_materia = $alumno_materia;
        $this->alumno = $alumno;
        $this->materia = $materia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('notificacion');
    }
}
