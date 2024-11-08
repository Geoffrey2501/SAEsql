<?php

namespace iutnc\NRV\render;

class ListeSoireeRenderer implements Renderer
{
    private array $soirees;

    public function __construct(array $soirees)
    {
        $this->soirees = $soirees;
    }
    public function render(int $selector=1): string
    {
        $html = "<div style='text-align: center; padding: 10px 0;'>";
        foreach($this->soirees as $soiree) {
            $renderer = new SoireeRenderer($soiree);
            $html .= $renderer->renderLong();
        }
        $html .= "</div>";
        return $html;
    }
}