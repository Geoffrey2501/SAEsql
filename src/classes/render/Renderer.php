<?php
declare(strict_types=1);

namespace iutnc\NRV\render;

interface Renderer {
    const COMPACT = 1;
    const LONG = 2;

    public function render(int $mode): string;
}




