<?php 
namespace Core; 

$configFile = __DIR__ . '/../App/config/config.php'; 
if (file_exists($configFile)) {
    require_once $configFile; 
}

class MiniBlade { 
    protected array $sections = []; 
    protected ?string $layout = null; 
    protected ?string $currentSection = null; 
    protected string $viewsPath; 
    protected string $cachePath; 
    protected bool $useCache = true; 
    protected bool $debug = true; 
    protected array $sharedData = []; 

    public function __construct( 
        ?string $viewsPath = null, 
        ?string $cachePath = null, 
        bool $useCache = true, 
        bool $debug = true 
    ) { 
        $defaultViews = defined('RAIZ_PROYECTO') ? RAIZ_PROYECTO . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' : __DIR__ . '/../resources/views'; 
        $defaultCache = defined('RAIZ_PROYECTO') ? RAIZ_PROYECTO . DIRECTORY_SEPARATOR . 'cache' : __DIR__ . '/../cache'; 
        
        $this->viewsPath = rtrim($viewsPath ?? $defaultViews, '/\\') . DIRECTORY_SEPARATOR; 
        $this->cachePath = rtrim($cachePath ?? $defaultCache, '/\\') . DIRECTORY_SEPARATOR; 
        $this->useCache = $useCache; 
        $this->debug = $debug; 

        if (!is_dir($this->cachePath)) { 
            mkdir($this->cachePath, 0777, true); 
        } 
    } 

    public function share(string|array $key, mixed $value = null): void { 
        if (is_array($key)) { 
            $this->sharedData = array_merge($this->sharedData, $key); 
        } else { 
            $this->sharedData[$key] = $value; 
        } 
    } 

    public function render(string $viewName, array $data = []) { 
        $this->layout = null; 
        $this->sections = []; 
        
        $combinedData = array_merge($this->sharedData, $data); 
        $content = $this->renderView($viewName, $combinedData); 

        if ($this->layout) { 
            return $this->renderView($this->layout, $combinedData); 
        } 
        return $content; 
    } 

    protected function renderView(string $viewName, array $data) { 
        $path = $this->viewsPath . str_replace('.', '/', $viewName) . ".view.php"; 
        if (!file_exists($path)) { 
            return "<!-- Vista [$viewName] no encontrada en la ruta: $path -->"; 
        } 

        $cacheFile = $this->cachePath . md5($viewName) . '.php'; 

        if (!$this->useCache || !file_exists($cacheFile) || filemtime($path) > filemtime($cacheFile)) { 
            $compiledCode = $this->compile(file_get_contents($path)); 
            file_put_contents($cacheFile, $compiledCode); 

            if ($this->debug) { 
                $this->validateSyntax($cacheFile, $path); 
            } 
        } 

        extract($data, EXTR_SKIP); 
        ob_start(); 
        include($cacheFile); 
        return ob_get_clean(); 
    } 

    protected function compile(string $code): string { 
        $patterns = [ 
            // 1. Eliminar comentarios de bloque: {{-- comentario --}} 
            '/\{\{\-\-(.*?)\-\-\}\}/s' => '', 
            
            // 2. Bloques PHP nativos 
            '/@php(.*?)@endphp/s' => '<?php $1 ?>', 
            
            // 3. Impresión de variables con escape HTML automático (admite funciones u operaciones complejas)
            '/\{\{\s*(.*?)\s*\}\}/' => '<?php echo htmlspecialchars((string)($1), ENT_QUOTES, "UTF-8"); ?>', 
            
            // 4. Estructuras de control condicionales y bucles
            '/@if\s*\((.*)\)/' => '<?php if($1): ?>', 
            '/@elseif\s*\((.*)\)/' => '<?php elseif($1): ?>', 
            '/@else/' => '<?php else: ?>', 
            '/@endif/' => '<?php endif; ?>', 
            '/@foreach\s*\((.*)\)/' => '<?php foreach($1): ?>', 
            '/@endforeach/' => '<?php endforeach; ?>', 
            
            // 5. Inclusiones adaptadas (Soporta comillas simples o dobles y espacios opcionales)
            '/@include\s*\(\s*[\'"](.*?)[\'"]\s*\)/' => '<?php echo $this->renderView(\'$1\', get_defined_vars()); ?>', 
            '/@yield\s*\(\s*[\'"](.*?)[\'"]\s*\)/' => '<?php echo $this->sections[\'$1\'] ?? ""; ?>', 
            '/@extends\s*\(\s*[\'"](.*?)[\'"]\s*\)/' => '<?php $this->layout = \'$1\'; ?>', 
            '/@section\s*\(\s*[\'"](.*?)[\'"]\s*\)/' => '<?php ob_start(); $this->currentSection = \'$1\'; ?>', 
            '/@endsection/' => '<?php $this->sections[$this->currentSection] = ob_get_clean(); ?>', 
        ]; 

        return preg_replace(array_keys($patterns), array_values($patterns), $code); 
    } 

    private function validateSyntax(string $cacheFile, string $originalPath): void { 
        if (function_exists('php_check_syntax')) { 
            if (!php_check_syntax($cacheFile, $error)) { 
                $this->throwParseError($cacheFile, $originalPath, $error); 
            } 
            return; 
        } 

        try { 
            $code = file_get_contents($cacheFile); 
            $prevErrorLevel = error_reporting(0); 
            $tokens = token_get_all($code); 
            error_reporting($prevErrorLevel); 
        } catch (\Throwable $e) { 
            $this->throwParseError($cacheFile, $originalPath, $e->getMessage()); 
        } 
    } 

    private function throwParseError(string $cacheFile, string $originalPath, string $details): void { 
        if (file_exists($cacheFile)) { 
            unlink($cacheFile); 
        } 
        $friendlyError = str_replace($cacheFile, $originalPath, $details); 
        throw new \ParseError("Error de sintaxis en la vista compilada.\nDetalles:\n" . trim($friendlyError)); 
    } 

    public function clearCache() { 
        $files = glob($this->cachePath . '*.php'); 
        foreach ($files as $file) { 
            if (is_file($file)) unlink($file); 
        } 
        return count($files); 
    } 
}
