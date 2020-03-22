<?php

    namespace src\Core;

    use League\Plates\Engine;

    class View {
        /** @var Engine $engine */
        private $engine;

        public function __construct(string $path = CONF_VIEW_PATH, string $ext = CONF_VIEW_EXT)
        {
            $this->engine = Engine::create($path, $ext);
        }

        public function path(string $name, string $path): View {
            $this->engine->addFolder($name, $path);
            return $this;
        }

        public function render(string $templateName, array $data): string {
            return $this->engine->render($templateName, $data);
        }

        public function engine(): Engine {
            return $this->engine();
        }
    }