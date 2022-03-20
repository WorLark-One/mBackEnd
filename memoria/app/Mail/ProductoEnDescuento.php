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
    public $precio_actual_producto;
    public $precio_anterior_producto;
    public $subject = "Producto en descuento KMaule";
    public $pagina = "http://localhost:8080/";
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($producto, $precio_anterior_producto)
    {
        //
        $this->producto = $producto;
        $this->precio_actual_producto = number_format($producto->precio, 0, ',', '.');
        $this->precio_anterior_producto = number_format($precio_anterior_producto, 0, ',', '.');
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
