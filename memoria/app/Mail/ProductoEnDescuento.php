<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductoEnDescuento extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $producto;
    public $subject = "Producto en descuento KMaule";
    public $pagina = "http://localhost:8080/";
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($producto)
    {
        //
        $this->producto = $producto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.producto-descuento');
    }
}
