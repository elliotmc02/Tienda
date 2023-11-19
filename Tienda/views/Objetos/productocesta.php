<?php
class ProductoCesta
{
    public $idProducto;
    public $cantidad;

    function __construct($idProducto, $cantidad)
    {
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
    }
}
