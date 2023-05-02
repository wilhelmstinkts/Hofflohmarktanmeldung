<?php
namespace Ort;

class Koordinaten {
    public float $breite;
    public float $laenge;
    public function __construct(float $breite, float $laenge)
    {
        $this->breite = $breite;
        $this->laenge = $laenge;
    } 
}