<?php

namespace App\Includes;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\StandardDebugBar;

final class DebugBar
{
    private static ?DebugBar $instance = null;
    private $debugbar;
    private $renderer;

    private function __construct()
    {
        $this->debugbar = new StandardDebugBar();
        $this->renderer = $this->debugbar->getJavascriptRenderer();
    }

    // Singleton (un seul DebugBar pour toute l’app)
    public static function getInstance(): DebugBar
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Envoi d’un log (INFO, WARNING, ERROR…)
    public function log(string $message, string $level = 'info'): void
    {
        $this->debugbar['messages']->addMessage("[$level] $message");
    }

    // Timer
    public function startTimer(string $name, string $label = ''): void
    {
        $this->debugbar['time']->startMeasure($name, $label);
    }

    public function stopTimer(string $name): void
    {
        $this->debugbar['time']->stopMeasure($name);
    }

    // Ajoute la DebugBar dans ta page HTML
    public function renderHead(): string
    {
        return $this->renderer->renderHead();
    }

    public function renderBody(): string
    {
        return $this->renderer->render();
    }

    public function panel(string $name, array $data): void
    {
        // Un collector par nom → un onglet dédié
        $collectorName = 'panel_' . $name;
        if (!$this->debugbar->hasCollector($collectorName)) {
            $this->debugbar->addCollector(new ConfigCollector($data, $name));
        } else {
            /** @var ConfigCollector $c */
            $c = $this->debugbar->getCollector($collectorName);
            $c->setData($data);
        }
    }
}
